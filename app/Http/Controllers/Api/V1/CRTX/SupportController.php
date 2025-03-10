<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ExceptionLog;
use Mail;
use Validator;
use Illuminate\Mail\Message;
use Exception;

class SupportController extends Controller
{
    use ExceptionLog;
    public function submitTicket(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'subject' => 'required',
            'description' => 'required',
            'support_file'=> 'nullable|max:50240'
       ]);

        if ($validate->fails()) {
            $error = $this->errorMessages($validate);
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
        }

        $emailContent = $request->description;

        try {

            $file = $request->support_file;

            $result = Mail::raw($emailContent, function (Message $message) use ($request, $file) {
                $message->to('helpme@microsite.com') //helpme@microsite.com
                ->from($request->email)
                ->subject($request->subject.' - '.$request->practice_name);
                if(!empty($file)){
                    $message->attach($file->getRealPath(), array(
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getMimeType())
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!'
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ], 200);

        }
    }
}
