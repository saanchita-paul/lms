<?php

namespace App\Http\Controllers;

use App\Models\ScrapedData;
use App\Services\Assistant\CreateVectorStoreFileService;
use App\Services\Assistant\RetrieveAssistantWithVectorIdService;
use App\Services\Assistant\UploadFileService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Symfony\Component\DomCrawler\Crawler;
use Dompdf\Dompdf;
use App\Models\Clinic;
use Illuminate\Http\UploadedFile;

class ScrapedDataController extends Controller
{
    public function submitUrl(Request $request)
    {

        // Retrieve clinic data
        $clinic = Clinic::find($request->clinicId);

        
        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }
        $apiKey = $clinic->chat_api_key;
        

        $client = new Client();
        $url = $request->get('url');

        $response = $client->request('GET', $url);
        $htmlContent = $response->getBody()->getContents();

        

        $crawler = new Crawler($htmlContent);

        $content = $crawler->filter('body')->each(function (Crawler $node) {
            $textContent = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $node->html());
            $textContent = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/i', '', $textContent);
            return strip_tags($textContent);
        });

        $fullContent = implode("\n", $content);
        $cleanedContent = preg_replace("/\n+/", "\n", $fullContent);
        $cleanedContent = trim($cleanedContent);

        $links = $crawler->filter('a')->each(function (Crawler $node) {
            $href = $node->attr('href');
            $linkText = $node->text();
            $href = preg_replace('/\s+/', '', $href);
            return "{$linkText}: {$href}";
        });

        $formattedLinks = implode("\n", $links);
        $finalContent = $cleanedContent . "\n\nLinks Found:\n" . $formattedLinks;

        $finalContent = preg_replace('/([.!?])(?=\s*[A-Z])/', '$1 ', $finalContent);

        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? 'unknown_host';
        $path = $parsedUrl['path'] ?? '';
        $sanitizedPath = str_replace('/', '_', ltrim($path, '/'));
        $currentDateTime = now()->format('m-d-Y_H-i-s');
        $fileName = "{$host}_{$sanitizedPath}.{$currentDateTime}.pdf";

        $clinicId = $request->input('clinicId');
        
        $pdfDirectory = storage_path("app/public/crtxagent/{$clinicId}/");
        $pdfPath = $pdfDirectory . $fileName;

        

        if (!File::exists($pdfDirectory)) {
            File::makeDirectory($pdfDirectory, 0755, true);
        }

        $headerTitle = $host . ($sanitizedPath ? " / $sanitizedPath" : "");

        $dompdf = new Dompdf();
        $html = "<h1>Scraped Content from {$headerTitle}</h1><p>" . nl2br(e($finalContent)) . "</p>";
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        file_put_contents($pdfPath, $pdfOutput);

        
        $scrapedData = new ScrapedData();
        $scrapedData->clinic_id = $clinicId;
        $scrapedData->url = $url;
        $scrapedData->file_path = $pdfPath;
        $scrapedData->save();
        
        // $request->files->set(
        //     'file',
        //     new \Illuminate\Http\UploadedFile($pdfPath, $fileName, 'application/pdf', null, true)
        // );

        if (file_exists($pdfPath)) {

            
            // Create a new UploadedFile instance
            $uploadedFile = new UploadedFile(
                $pdfPath,     // Full path to the file
                $fileName,    // Original file name
                'application/pdf', // Mime type
                null,         // Error code (null by default)
                true          // Test mode (true for files outside of $_FILES)
            );

           
            $request = Request::create('/', 'POST', [
                'assistant_id' => $request->assistant_id, // Set assistant_id
                'clinicId' => $request->clinicId, // Set clinicId
                'url' => $request->url // Set url
            ], [], ['file' => $uploadedFile]);
            //$request->files->set('file', $uploadedFile);
            $file = $request->file('file');
            

            
           
        }
        
       
        $uploadService = new UploadFileService();
        $fileId = $uploadService->uploadFile($request);
       
        $vectorStoreService = new RetrieveAssistantWithVectorIdService();
        $vectorStoreId = $vectorStoreService->getAssistant($request->input('assistant_id'),$apiKey);
        
        $createVectorStoreFileService = new CreateVectorStoreFileService();
        $createVectorStoreFileService->createVectorStoreFile($fileId, $vectorStoreId,$apiKey);

        return response()->json(['message' => 'URL submitted and PDF generated successfully.']);
    }


    public function listUrls(Request $request)
    {
        $clinicId = $request->clinic_id;
        $scrapedData = ScrapedData::where('clinic_id', $clinicId)->orderBy('id', 'desc')->get();
        return response()->json($scrapedData);
    }

    public function downloadFile($id)
    {
        $scrapedData = ScrapedData::find($id);
        if ($scrapedData && File::exists($scrapedData->file_path)) {
            return response()->download($scrapedData->file_path, basename($scrapedData->file_path));
        }

        return response()->json(['error' => 'File not found.'], 404);
    }

    public function deleteUrls(Request $request)
    {
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $scrapedData = ScrapedData::find($id);
            if ($scrapedData && File::exists($scrapedData->file_path)) {
                File::delete($scrapedData->file_path);
                $scrapedData->delete();
            }
        }

        return response()->json(['message' => 'Selected URLs and their files were deleted successfully.']);
    }
}
