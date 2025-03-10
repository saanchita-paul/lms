<?php

namespace App\Services;

use App\Models\Clinic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ElasticEmailService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.elasticemail.com/v4';

    public function __construct()
    {
        $this->apiKey = env('ELASTIC_EMAIL_API_KEY');
    }

    public function createAccountWithSMTP(array $data)
    {
        try {
            $clinic = Clinic::find($data['clinic_id']);

//             Step 1: Create Subaccount
            Log::info('Creating subaccount', ['email' => $data['email']]);
            $subaccount = $this->createSubAccount($data);

            $CreateSMTP = $this->createSMTP($data['email']);

//             Step 2: Wait for account creation to propagate
            sleep(3);

//             Step 3: Create API key using the main security endpoint
            Log::info('Creating API key', ['email' => $data['email']]);
            $apiKey = $this->createMainApiKey($data['email']);

            //Store create SMTP to Clinic
            $clinic->smtpUsername = $data['email'];
            $clinic->smtpServer = 'smtp.elasticemail.com';
            $clinic->smtpPort = '2525';
            $clinic->smtpPassword = $CreateSMTP['Token'] ?? null;
            $clinic->smtpMailer = 'custom';
            $clinic->elastic_email_api_key = $apiKey['Token'] ?? null;
            $clinic->save();

            return [
                'status' => 'success',
                'message' => 'Account created successfully',
                'account' => $subaccount,
                'smtp' => [
                    'host' => 'smtp.elasticemail.com',
                    'port' => 2525,
                    'username' => $data['email'],
                    'encryption' => 'tls'
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Account Creation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception($e->getMessage());
        }
    }

    protected function createSubAccount(array $data)
    {
        $payload = [
            'email' => $data['email'],
            'password' => $data['password'],
            'sendActivation' => false,
        ];

        $response = Http::withHeaders([
            'X-ElasticEmail-ApiKey' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/subaccounts", $payload);

        Log::info('Subaccount creation response', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);

        if (!$response->successful()) {
            $errorMessage = $response->json();
            throw new \Exception('Failed to create subaccount: ' . json_encode($errorMessage));
        }

//        Delete default SMTP
        $responseDelete = Http::withHeaders([
            'X-ElasticEmail-ApiKey' => env('ELASTIC_EMAIL_API_KEY'),
            'Content-Type' => 'application/json'
        ])->delete("{$this->baseUrl}/security/smtp/{$data['email']}", [
            'subaccount' => $data['email']
        ]);

        if (!$responseDelete->successful()) {
            $errorMessageDelete = $responseDelete->json();
            throw new \Exception('Failed to delete SMTP: ' . json_encode($errorMessageDelete));
        }

        return $response->json();
    }

    protected function createSMTP(string $email)
    {
        // Wait for account propagation
        sleep(5);
        Log::info('Deleting default SMTP for subaccount', ['email' => $email]);

        $payload = [
            'subaccount' => $email,
        ];
        $response = Http::withHeaders([
            'X-ElasticEmail-ApiKey' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->get("{$this->baseUrl}/security/smtp", $payload);
        $smtp_list = $response->json();


        $responseSmtpDelete = Http::withHeaders([
            'X-ElasticEmail-ApiKey' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->delete("{$this->baseUrl}/security/smtp/{$smtp_list[0]['Name']}", $payload);

        if (!$responseSmtpDelete->successful()) {
            $errorMessageDelete = $responseSmtpDelete->json();
            throw new \Exception('Failed to delete SMTP: ' . json_encode($errorMessageDelete));
        }

        Log::info('Creating SMTP for subaccount', ['email' => $email]);

        $payload = [
            'Name' => $email, // Required: must be valid email
            'Expires' => null,
            'RestrictAccessToIPRange' => [],
            'Subaccount' => $email // Subaccount email
        ];

        $response = Http::withHeaders([
            'X-ElasticEmail-ApiKey' => env('ELASTIC_EMAIL_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/security/smtp", $payload);


        Log::info('SMTP creation raw response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            return $response->json();
        }
    }

    protected function createMainApiKey(string $email)
    {
        // Wait for account propagation
        sleep(5);

        Log::info('Creating API key for subaccount', ['email' => $email]);


        $responseDeleteApi = Http::withHeaders([
            'X-ElasticEmail-ApiKey' => env('ELASTIC_EMAIL_API_KEY'),
            'Content-Type' => 'application/json'
        ])->delete("{$this->baseUrl}/security/apikeys/Default", [
            'subaccount' => $email
        ]);

        if (!$responseDeleteApi->successful()) {
            $errorApiDelete = $responseDeleteApi->json();
            throw new \Exception('Failed to delete API Keys: ' . json_encode($errorApiDelete));
        }
        $payload = [
            "Name" => "SMTP_" . Str::random(8), // Required: Name of the API key
            "AccessLevel" => ["SendSmtp", "SendHttp"], // Required: Access levels, matching enums in the documentation
            "Expires" => null,
            "RestrictAccessToIPRange" => [], // Optional: List of IPs allowed to use this key (empty means unrestricted)
            "Subaccount" => $email
        ];

        Log::info('Sending API key creation request', [
            'url' => "{$this->baseUrl}/security/apikeys",
            'payload' => $payload
        ]);

        $response = Http::withHeaders([
            'X-ElasticEmail-ApiKey' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/security/apikeys", $payload);

        Log::info('API key creation raw response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        $result = $response->json();
        Log::info('API key created successfully', ['result' => $result]);

        return $result;
    }
}
