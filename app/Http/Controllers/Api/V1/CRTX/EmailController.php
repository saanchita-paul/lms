<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Services\AwsEmailService;
use App\Http\Services\MailSlurpService;
use App\Models\Clinic;
use App\Models\EmailSent;
use App\Models\ReceivedEmails;
use App\Models\User;
use App\Traits\ExceptionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use Illuminate\Support\Collection;
use eXorus\PhpMimeMailParser\Parser;


class EmailController extends Controller
{
    use ExceptionLog;

    private MailSlurpService $mailSlurpService;
    private $mailslurpBaseUrl = 'https://api.mailslurp.com';

    public function __construct()
    {
        $this->mailSlurpService = new MailSlurpService();
        $this->awsEmailService = new AwsEmailService();

    }

    public function emailThread(Request $request)
    {
        $inboxId = $request->get('inbox');
        $emailId = $request->get('email_id');
        $user = [];
        $mergedEmails = [];
        try {
            if($inboxId){
                $clinic = Clinic::where('inbox_id', $inboxId)
                    ->leftJoin('clinic_user', 'clinics.id', '=', 'clinic_user.clinic_id')
                    ->first();
                if($clinic){
                    $user  = User::select('id','name','email')->find($clinic->user_id);
                }
                $limit = 10;
                $emails = $this->awsEmailService->fetchEmails($limit,$emailId,1,$inboxId);

                foreach ($emails[0] as $receivedEmail){
                    $mergedEmails[] = $receivedEmail;
                }

                $collection = new Collection($mergedEmails);
                $sortedCollection = $collection->sortBy('created_at',0,1);
                $sortedArray = $sortedCollection->values()->all();

                $mergedEmailsBody = [];
                foreach ($sortedArray as $key => $sa){
                    if(isset($sa['body'])){
                        $mergedEmailsBody[$key]['data'] = $sa;
                        if($sa['body_excerpt'] === false || $sa['body_excerpt'] === NULL){
                            $mergedEmailsBody[$key]['body'] = $sa['body'];
                        }else{
                            $mergedEmailsBody[$key]['body'] = $sa['body_excerpt'];
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Inbox Data',
                    'data' => [
                        'inbox' =>$inboxId,
                        'user'=>$user,
                        'email_list'=>$mergedEmailsBody,
                    ]
                ], 200);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'No Inbox Data Found',
                    'data' => []
                ], 200);
            }
        }catch (\Exception $ex){
            return response()->json([
                'success' => true,
                'message' => 'No Inbox Data Found',
            ], 200);
        }
    }

    public function sentEmailThread(Request $request)
    {
        $inboxId = $request->get('inbox');
        $emailId = $request->get('email_id');
        $user = [];
        try {
            if($inboxId){
                $mergedEmails = [];
                $clinic = Clinic::where('inbox_id', $inboxId)
                    ->leftJoin('clinic_user', 'clinics.id', '=', 'clinic_user.clinic_id')
                    ->first();
                if($clinic){
                    $user  = User::select('id','name','email')->find($clinic->user_id);
                }
                $loggedInUserEmail = Auth::user()->email;
                $sentEmails = EmailSent::select('bcc', 'body', 'body_excerpt', 'cc', 'created_at', 'from', 'subject', 'to')
                    ->where('to', $emailId)
                    ->where('from', $clinic->email_id)
                    ->get();

                $collection = new Collection($sentEmails);
                $sortedCollection = $collection->sortBy('created_at',0,1)->map(function ($group) {
                    $count = $group->count();
                    $dataMails = $group;
                    $dataMails['to'] = [$group['to']];
                    $dataMails['createdAt'] = $group['created_at'];
                    return $dataMails;
                });
                $sortedArray = $sortedCollection->values()->all();

                $mergedEmailsBody = [];
                foreach ($sortedArray as $key => $sa){
                        $mergedEmailsBody[$key]['data'] = $sa;
                        $mergedEmailsBody[$key]['body'] = $sa['body'];
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Inbox Data',
                    'data' => [
                        'inbox' =>$inboxId,
                        'user'=>$user,
                        'email_list'=>$mergedEmailsBody,
                    ]
                ], 200);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'No Inbox Data Found',
                    'data' => []
                ], 200);
            }
        }catch (\Exception $ex){
            return response()->json([
                'success' => true,
                'message' => 'No Inbox Data Found',
            ], 200);
        }
    }

    private function extractEmailContent($emailContent)
    {
        $parser = new Parser();
        $email_list = [];
        $emailContentx = $parser->setText($emailContent);

        $date = $parser->getHeader('date');
        $parts = explode(',', $date);
        $datePart = $parts[1];
        $cleanedDate = explode(' +', $datePart)[0];
        $cleanedDate = trim($cleanedDate);
        $carbonDate = Carbon::createFromFormat('d M Y H:i:s', $cleanedDate);
        $date = $carbonDate->toDateTimeString();

        $email_list['created_at'] = $date;
        $email_list['createdAt'] = $date;
        $email_list['from'] = $parser->getHeader('from');
        $email_list['subject'] = $parser->getHeader('subject');
        $email_list['to'][] = $parser->getHeader('to');
        $email_list['body'] = $parser->getMessageBody();
        $email_list['body_excerpt'] = $parser->getMessageBody('html');
        $email_list['bcc'] = [];
        $email_list['cc'] = [];
        $email_list['read'] = false;

        return $email_list;
    }

    public function receivedEmailList(Request $request)
    {
        $inboxId = $request->get('inbox');
        $page = $request->get('page');
        $size = $request->get('size');
        $searchKeyword = $request->get('search_keyword');
        $unread = $request->get('unread');
        $user = [];
        try {
            if($inboxId){
                $mergedEmails = [];
                $clinic = Clinic::where('inbox_id', $inboxId)
                    ->leftJoin('clinic_user', 'clinics.id', '=', 'clinic_user.clinic_id')
                    ->first();
                if($clinic){
                    $user  = User::select('id','name','email')->find($clinic->user_id);
                }
                $limit = 50;
                $emails = $this->awsEmailService->fetchEmails($limit,"",$page,$inboxId);
                $collection = new Collection($emails[0]);
                $sortedEmails = $collection->sortByDesc('created_at')->values();

                $sortedGroups = $sortedEmails->groupBy('from')->map(function ($group) {
                    $count = $group->count();
                    $dataMails = $group->sortBy('created_at')->last();
                    $response_data = ['count'=>$count, 'dataEmails'=>$dataMails];
                    return $response_data;
                })->values();

                $mergedEmailsBody = [];
                foreach ($sortedGroups as $key => $sg){
                    $mergedEmailsBody[$key]['data'] = $sg;
                    $mergedEmailsBody[$key]['body'] = $sg['dataEmails']['body_excerpt'];

                    //enter data in received_emails table
                    $createdAt = $sg['dataEmails']['created_at'];
                    $subject = str_replace([' '],'',$sg['dataEmails']['subject']);
                    $fromEmail = preg_match('/<(.+?)>/', $sg['dataEmails']['from'], $matches) ? $matches[1] : $sg['dataEmails']['from'];
                    $received_emails = ReceivedEmails::where(['clinic_id'=>$clinic->id,'email_token'=>$fromEmail,'email_created_date'=>$createdAt])->first();

                    if($received_emails){
                        $mergedEmailsBody[$key]['read'] = $received_emails->email_read;
                    }
                    if (is_null($received_emails)) {
                        ReceivedEmails::where(['clinic_id'=>$clinic->id,'email_token'=>$fromEmail])->delete();

                        ReceivedEmails::create([
                            'clinic_id'=>$clinic->id,
                            'email_token' => $fromEmail,
                            'email_created_date' => $createdAt,
                            'email_read' => false
                        ]);
                    }
                }
                $total_email_count = count($mergedEmailsBody);
                $unreadCount = ReceivedEmails::where(['email_read'=>false,'clinic_id'=>$clinic->id])->count();

                return response()->json([
                    'success' => true,
                    'message' => 'Inbox Data',
                    'data' => [
                        'inbox' =>'4534534534',
                        'emailAddress'=>$clinic->email_id,
                        'user'=>$user,
                        'received_email_list'=>$mergedEmailsBody,
                        'total_email_count'=>$total_email_count,
                        'pageable'=>true,
                        'total'=>'17',
                        'total_pages'=>'2',
                        'total_elements'=>'17',
                        'last'=>$emails[1],
                        'size'=>'2',
                        'number'=>'2',
                        'sort'=>'asc',
                        'number_of_elements'=>$total_email_count,
                        'first'=>'1',
                        'empty'=>'0',
                        'unreadCount'=>$unreadCount,
                    ]
                ], 200);
                }else{
                    return response()->json([
                        'success' => true,
                        'message' => 'No Inbox Data Found',
                        'data' => []
                    ], 200);
                }
            }catch (\Exception $ex){
                return response()->json([
                    'success' => true,
                    'message' => 'No Inbox Data Found',
                ], 200);
            }
    }

    public function sentEmailList(Request $request)
    {
        $inboxId = $request->get('inbox');
        $page = $request->get('page');
        $size = $request->get('size');
        $searchKeyword = $request->get('search_keyword');
        $user = [];
        try {
            if($inboxId){
                $mergedEmails = [];
                $clinic = Clinic::where('inbox_id', $inboxId)
                    ->leftJoin('clinic_user', 'clinics.id', '=', 'clinic_user.clinic_id')
                    ->first();
                if($clinic){
                    $user  = User::select('id','name','email')->find($clinic->user_id);
                }
                $loggedInUserEmail = $clinic->email_id;
                $sentEmails = EmailSent::select('bcc', 'body', 'body_excerpt', 'cc', 'created_at', 'from', 'subject', 'to')
                            ->where('from', $loggedInUserEmail)
                            ->orderBy('created_at','desc')
                            ->paginate(50);
                $sentEmailItems = $sentEmails->items();
                $last = !$sentEmails->hasMorePages();
                $totalPages = $sentEmails->lastPage();

                $collection = new Collection($sentEmailItems);
                $sortedGroups = $collection->groupBy('to')->map(function ($group) {
                    $count = $group->count();
                    $dataMails = $group->sortBy('created_at')->last();
                    $dataMails['to'] = [$dataMails['to']];
                    $dataMails['createdAt'] = $dataMails['created_at'];
                    return ['count'=>$count, 'dataEmails'=>$dataMails];
                })->values();


                $newCollection = new Collection($sortedGroups);
                $sortedCollection = $newCollection->sortBy(function ($item) {
                    return $item['dataEmails']['created_at'];
                });

                $mergedEmailsBody = [];
                foreach ($sortedCollection as $key => $sg){
                    $mergedEmailsBody[$key]['data'] = $sg;
                    $mergedEmailsBody[$key]['body'] = $sg['dataEmails']['body'];
                }
                $total_email_count = count($mergedEmailsBody);
                $mergedEmailsBody = array_reverse($mergedEmailsBody);

                return response()->json([
                    'success' => true,
                    'message' => 'Inbox Data',
                    'data' => [
                        'inbox' =>$inboxId,
                        'user'=>$user,
                        'received_email_list'=>$mergedEmailsBody,
                        'total_email_count'=>$total_email_count,
                        'pageable'=>true,
                        'total'=>count($sentEmails),
                        'total_pages'=>$totalPages,
                        'total_elements'=>count($sentEmails),
                        'last'=>$last,
                        'size'=>50,
                        'number'=>50,
                        'sort'=>'asc',
                        'number_of_elements'=>count($sentEmails),
                        'first'=>$sentEmails->currentPage(),
                        'empty'=>false,
                    ]
                ], 200);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'No Inbox Data Found',
                    'data' => []
                ], 200);
            }
        }catch (\Exception $ex){
            return response()->json([
                'success' => true,
                'message' => 'No Inbox Data Found',
            ], 200);
        }
    }

    public function showSendEmail(Request $request)
    {
        $emailId = $request->get('email_id');

        try {
            if($emailId){
                return response()->json([
                    'success' => true,
                    'message' => 'Inbox Data',
                    'data' => $emailId
                ], 200);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'No Data Found',
                ], 200);
            }
         }catch (\Exception $ex){
            return response()->json([
                'success' => true,
                'message' => 'No EmailId Data Found',
                ], 200);
        }

    }

    public function showSentEmail(Request $request)
    {
        $emailId = $request->get('email_id');
        try {
            if($emailId){
                return response()->json([
                    'success' => true,
                    'message' => 'Inbox Data',
                    'data' => $emailId
                ], 200);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'No Data Found',
                ], 200);
            }
        }catch (\Exception $ex){
            return response()->json([
                'success' => true,
                'message' => 'No EmailId Data Found',
            ], 200);
        }
    }

    public function sendEmail(Request $request)
    {
        $inbox_id = $request->get('inbox_id');
        try {
            $clinic = Clinic::where('inbox_id', $inbox_id)->first();

            if (!$clinic) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clinic not found.',
                ], 404);
            }

            $input = $request->all();
            $user = Auth::user();

            // Ensure required fields are provided
            if (empty($input['to']) || empty($input['subject']) || empty($input['body'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Required email fields (to, subject, body) are missing.',
                ], 422);
            }

            // Configure SMTP settings dynamically if available
            if ($clinic->smtpServer && $clinic->smtpUsername && $clinic->smtpPassword) {
                config([
                    'mail.mailers.smtp.host' => $clinic->smtpServer,
                    'mail.mailers.smtp.port' => $clinic->smtpPort,
                    'mail.mailers.smtp.encryption' => 'tls',
                    'mail.mailers.smtp.username' => $clinic->smtpUsername,
                    'mail.mailers.smtp.password' => $clinic->smtpPassword,
                ]);
            }

            Mail::purge();

            // Send the email
            Mail::send([], [], function (Message $message) use ($input, $clinic) {
                // Handling multiple 'to' addresses
                if (!empty($input['to'])) {
                    $toEmails = array_map('trim', explode(',', $input['to']));
                    foreach ($toEmails as $toEmail) {
                        $message->to($toEmail);
                    }
                }

                $message->from($clinic->email_id)
                    ->subject($input['subject'])
                    ->setBody($input['body'], 'text/html');

                // Adding multiple CC addresses if provided
                if (!empty($input['cc'])) {
                    $ccEmails = array_map('trim', explode(',', $input['cc']));
                    foreach ($ccEmails as $ccEmail) {
                        $message->cc($ccEmail);
                    }
                }

                // Adding multiple BCC addresses if provided
                if (!empty($input['bcc'])) {
                    $bccEmails = array_map('trim', explode(',', $input['bcc']));
                    foreach ($bccEmails as $bccEmail) {
                        $message->bcc($bccEmail);
                    }
                }
            });

            // Enter sent email details into the EmailSent table
            EmailSent::create([
                'user_id' => $user->id,
                'clinic_id' => $clinic->id,
                'from' => $clinic->email_id,
                'subject' => $input['subject'],
                'to' => is_array($input['to']) ? implode(',', $input['to']) : $input['to'],
                'bcc' => $input['bcc'] ?? null,
                'cc' => $input['cc'] ?? null,
                'body' => $input['body'],
                'body_excerpt' => $input['body_excerpt'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully.',
                'data' => [
                    'to' => $input['to'],
                    'bcc' => $input['bcc'],
                    'cc' => $input['cc'],
                ]
            ], 200);

        } catch (\Throwable $e) {
            // General error handling for any exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the email.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function readReceivedEmail(Request $request)
    {
        try {
            //update data in received_emails table
            $createdAt = str_replace(['-',':',' '],'',$request->get('created_at'));
            $subject = str_replace([' '],'',$request->get('subject'));
            $fromEmail = preg_match('/<(.+?)>/', $request->get('from_email'), $matches) ? $matches[1] : $request->get('from_email');

            $received_emails = ReceivedEmails::where(['email_token'=>$fromEmail,'clinic_id'=>$request->get('clinic_id')])->first();
            if ($received_emails) {
                $received_emails->email_read = true;
                $received_emails->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Received Emails Read updated !',
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'No Emails Found !',
            ], 200);
        }catch (\Exception $ex){
            return response()->json([
                'success' => true,
                'message' => 'No Data Found',
                'data' => $ex
            ], 200);
        }
    }

    public function replyEmail(Request $request){
        $email_id = $request->get('email_id');
        try {
                $input = $request->all();
                try {
                    $reply_to_email_options = [
                        'bcc' => $input['bcc'],
                        'cc' => $input['cc'],
                        'subject' => $input['subject'],
                        'body' => $input['body'],
                        'reply_to' => $input['to'],
                        'isHTML' => false, // Set to true if using HTML content
                        'thread_Id' => 'thread1',
                        'use_inbox_name' => 'poolthread1',
                        'headers' => [
                            'X-Custom-Header' => 'Custom-Value',
                        ],
                        'sendStrategy' => 'SINGLE_MESSAGE',
                    ];
                    // Reply to an existing email thread using MailSlurp's API

                    return response()->json([
                        'success' => true,
                        'message' => 'Email Triggered but not sent',
                        'data' => [
                            'to' => $input['to'],
                            'bcc' => $input['bcc'],
                            'cc' => $input['cc'],
                        ]
                    ], 200);
                } catch (\Throwable $th) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Error',
                        'data' => $th->getMessage()
                    ], 200);
                }
        }catch (\Exception $ex){
            return response()->json([
                'success' => true,
                'message' => 'No Data Found',
                'data' => $ex
            ], 200);
        }
    }

}
