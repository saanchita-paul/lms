<?php
namespace App\Http\Services;

use Carbon\Carbon;
use eXorus\PhpMimeMailParser\Parser;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\GraphViz\Exception;

class AwsEmailService
{
    public function XfetchEmails($limit = 10)
    {
        $emails = [];
        $count = 0;


        // Get all files in the S3 bucket
        $files = Storage::disk('s3')->files();

        foreach ($files as $file) {
            if ($count >= $limit) {
                break;
            }
            $emailContent = Storage::disk('s3')->get($file);
            $emails[] = $emailContent;
            $count++;
        }

        return $emails;
    }

    public function fetchEmails($limit = 50, $fromEmailSubstring = null, $page = 1, $bucketName)
    {
        $email = [];
        $count = 0;
        $offset = ($page - 1) * $limit;
        $last = true;

        $config = config('filesystems.disks.s3');
        $config['bucket'] = $bucketName;

        // Get all files in the S3 bucket
        config(['filesystems.disks.s3.bucket' => $bucketName]);
        $files = Storage::disk('s3')->files();
        $filesCount = count($files);

        try{
            foreach ($files as $file) {
                if ($count >= $limit + $offset) {
                    break;
                }

                $emailContent = Storage::disk('s3')->get($file);

                $parser = new Parser();
                $parser->setText($emailContent);
                $fromEmail = $parser->getHeader('from');
                // Check if the email's "from" address matches the specified email
                if ($fromEmail) {
                    if ($fromEmailSubstring && strpos(strtolower($fromEmail), strtolower($fromEmailSubstring)) === false) {
                        continue;
                    }

                    $date = $parser->getHeader('date');

                    $parts = explode(',', $date);
                    if (isset($parts[1])) {
                        $datePart = $parts[1];
                        if ($datePart) {
                            $cleanedDate = explode(' +', $datePart);
                            if (isset($cleanedDate[1])) {
                                $cleanedDate = $cleanedDate[0];
                            } else {
                                $cleanedDate = explode(' -', $datePart)[0];
                            }
                            $cleanedDate = trim($cleanedDate);

                            $carbonDate = Carbon::createFromFormat('d M Y H:i:s', $cleanedDate);
                            $date = $carbonDate->toDateTimeString();
                        }
                    }
                    $email[] = [
                        'created_at' => $date,
                        'createdAt' => $date,
                        'from' => $parser->getHeader('from'),
                        'subject' => $parser->getHeader('subject'),
                        'to' => [$parser->getHeader('to')],
                        'body' => $parser->getMessageBody(),
                        'body_excerpt' => $parser->getMessageBody('html'),
                        'bcc' => [],
                        'cc' => [],
                        'read' => false,
                    ];
                    $count++;
                }
            }
            $emailArray =  array_slice($email, $offset, $limit);
            if($filesCount > ($offset+$limit)){
                $last = false;
            }
            return [$emailArray, $last];
        }catch (Exception $ex){
            return "Something went wrong";
        }
    }

    public function fetchEmailContent($bucketName, $fileName)
    {
        $config = config('filesystems.disks.s3');
        $config['bucket'] = $bucketName;
        $email = [];
        // Get file from the S3 bucket
        config(['filesystems.disks.s3.bucket' => $bucketName]);
        try{

            $emailContent = Storage::disk('s3')->get($fileName);
            $parser = new Parser();
            $parser->setText($emailContent);
            $date = $parser->getHeader('date');
            $parts = explode(',', $date);

            if (isset($parts[1])) {
                $datePart = $parts[1];
                if ($datePart) {
                    $cleanedDate = explode(' +', $datePart);
                    if (isset($cleanedDate[1])) {
                        $cleanedDate = $cleanedDate[0];
                    } else {
                        $cleanedDate = explode(' -', $datePart)[0];
                    }
                    $cleanedDate = trim($cleanedDate);

                    $carbonDate = Carbon::createFromFormat('d M Y H:i:s', $cleanedDate);
                    $date = $carbonDate->toDateTimeString();
                }

                $email = [
                    'created_at' => $date,
                    'createdAt' => $date,
                    'from' => $parser->getHeader('from'),
                    'subject' => $parser->getHeader('subject'),
                    'to' => [$parser->getHeader('to')],
                    'body' => $parser->getMessageBody(),
                    'body_excerpt' => $parser->getMessageBody('html'),
                    'bcc' => [],
                    'cc' => [],
                    'read' => false,
                ];
            }
            return $email;
        }catch (Exception $ex){
            return "Something went wrong";
        }
    }

}
