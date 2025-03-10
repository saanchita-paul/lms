<?php

namespace App\Console\Commands;

use App\Models\CrmCustomer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class UpdateCallRailDetails extends Command
{
    protected $signature = 'callrail:update-details';
    protected $description = 'Update CRM customers with CallRail form submission details';

    public function handle()
    {
        $client = new Client();
        $apiToken = env('CALLRAIL_API_TOKEN');
        $accountID = env('CALLRAIL_ACCOUNT_ID');

        $startDate = Carbon::create(2025, 2, 1)->startOfDay();
        $customers = CrmCustomer::where('phone_form', 'Web Form')
            ->where('form_id', '!=', '')
            ->where('form_id', 'Not LIKE' ,'Updated:%')
            ->where('created_at', '>=', $startDate)
            ->get();

        foreach ($customers as $customer) {
            $formSubmissionId = $customer->form_id;

            if (!$formSubmissionId) {
                $this->error("Missing form submission ID for crm customer ID {$customer->id}");
                continue;
            }

            $url = "https://api.callrail.com/v3/a/{$accountID}/form_submissions/{$formSubmissionId}.json";

            try {
                $response = $client->put($url, [
                    'headers' => [
                        'Authorization' => "Token token={$apiToken}",
                        'Content-Type' => 'application/json',
                    ],
                ]);

                $responseBody = json_decode($response->getBody(), true);

                $formData = $responseBody['form_data'] ?? [];

                $updateData = [];

                $emailKey = $phoneKey = $firstNameKey = $lastNameKey = $dobKey = null;

                foreach ($formData as $k => $v) {
                    if (stripos($k, 'First Name') !== false ||
                        stripos($k, 'first-name') !== false ||
                        stripos($k, 'First') !== false ||
                        stripos($k, 'Field12') !== false ||
                        preg_match('/^first_\d+$/i', $k)) {
                        $firstNameKey = $v;
                    }
                    if (stripos($k, 'Last Name') !== false ||
                        stripos($k, 'last-name') !== false ||
                        stripos($k, 'Last') !== false ||
                        stripos($k, 'Field13') !== false ||
                        preg_match('/^last_\d+$/i', $k)) {
                        $lastNameKey = $v;
                    }
                    if (stripos($k, 'Email') !== false ||
                        stripos($k, 'Field14') !== false ||
                        preg_match('/^email_\d+$/i', $k)) {
                        $emailKey = $v;
                    }
                    if (stripos($k, 'Phone') !== false ||
                        stripos($k, 'Field15') !== false ||
                        stripos($k, 'phone_number') !== false ||
                        stripos($k, 'Phone Number') !== false ||
                        preg_match('/^phone_\d+$/i', $k))  {
                        $phoneKey = "+1" . preg_replace("/[^a-zA-Z0-9]/", "", str_replace('+1', '', $v));
                    }
                    if (stripos($k, 'dob') !== false ||
                        stripos($k, 'Date of Birth') !== false ||
                        preg_match('/^dob_\d+$/i', $k)) {
                        $dobKey = $v;
                    }
                }

                if ($firstNameKey && !$lastNameKey) {
                    $nameParts = explode(' ', trim($firstNameKey), 2);
                    $updateData['first_name'] = $nameParts[0] ?? '';
                    $updateData['last_name'] = $nameParts[1] ?? '';
                } else {
                    $updateData['first_name'] = $firstNameKey;
                    $updateData['last_name'] = $lastNameKey;
                }

                $updateData['email'] = $emailKey;
                $updateData['phone'] = $phoneKey;
                $updateData['dob'] = $dobKey;

                $updateData['form_data'] = "FormData:" . json_encode($responseBody['form_data']);
                $updateData['form_id'] = "Updated:" . $responseBody['id'] . ":" . now()->format('YmdHis');

                $sourcename = $responseBody['source'];
                $source = '';
                $customsourcename = '';

                if (stristr($sourcename, "Direct") !== false) {
                    $source = '1';
                } elseif (stristr($sourcename, "Facebook") !== false) {
                    $source = '3';
                } elseif (stristr($sourcename, "Google Ads") !== false) {
                    $source = '2';
                } elseif (stristr($sourcename, "Google Organic") !== false) {
                    $source = '4';
                } elseif (stristr($sourcename, "Yahoo") !== false) {
                    $source = '8';
                } elseif (stristr($sourcename, "Msn") !== false) {
                    $source = '9';
                } elseif (stristr($sourcename, "Bing Ads") !== false) {
                    $source = '23';
                } elseif (stristr($sourcename, "Bing") !== false) {
                    $source = '10';
                } elseif (stristr($sourcename, "Youtube") !== false) {
                    $source = '6';
                } elseif (stristr($sourcename, "TV") !== false) {
                    $source = '12';
                } elseif (stristr($sourcename, "Infomercial") !== false) {
                    $source = '13';
                } elseif (stristr($sourcename, "Radio") !== false) {
                    $source = '14';
                } elseif (stristr($sourcename, "Tiktok") !== false) {
                    $source = '15';
                } elseif (stristr($sourcename, "Instagram") !== false) {
                    $source = '24';
                } else {
                    $source = '11';
                    $customsourcename = $sourcename;
                }

                $updateData['source_id'] = $source;
                $updateData['source_other'] = $customsourcename;

                $originalData = json_decode($customer->callrail_details, true) ?? [];
                $originalData['form_data'] = $formData;

                $updateData['callrail_details'] = json_encode($originalData, JSON_PRETTY_PRINT);
                $customer->update($updateData);

                $this->info("Updated customer ID {$customer->id} successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to update customer ID {$customer->id}: " . $e->getMessage());
            }
        }

        return 0;
    }
}
