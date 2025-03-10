<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class TwoFactorController extends Controller
{
    public function generateCode(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return response()->json([
                'success' => false,
                'message' => '2FA is not enabled'
            ], 400);
        }

        // Generate a random 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $sent = false;
        $errors = [];

        // Send via SMS if type is 'sms' or 'both'
        if (in_array($user->two_factor_type, ['sms', 'both'])) {
            try {
                $twilioSid = env('TWILIO_SID');
                $twilioToken = env('TWILIO_AUTH_TOKEN');
                $twilioFrom = env('TWILIO_FROM');

                $twilio = new Client($twilioSid, $twilioToken);
                $twilio->messages->create(
                    $user->phone,
                    [
                        'from' => $twilioFrom,
                        'body' => "CRTX - Your verification code is: {$code}"
                    ]
                );
                $sent = true;
            } catch (\Exception $e) {
                $errors[] = 'SMS sending failed: ' . $e->getMessage();
            }
        }

        // Send via Email if type is 'email' or 'both'
        if (in_array($user->two_factor_type, ['email', 'both'])) {
            try {

                $htmlbody = view('emails.auth.two-factor', [
                    'code' => $code,
                    'name' => $user->name
                ])->render();

                $mail = new PHPMailer(true);
                $mail->Encoding = "base64";
                $mail->SMTPAuth = true;
                $mail->Host = env('ZEPTO_MAIL_HOST');
                $mail->Port = env('ZEPTO_MAIL_PORT');
                $mail->Username = env('ZEPTO_MAIL_USERNAME');
                $mail->Password = env('ZEPTO_MAIL_PASSWORD');
                $mail->SMTPSecure = env('ZEPTO_MAIL_ENCRYPTION');
                $mail->isSMTP();
                $mail->IsHTML(true);
                $mail->CharSet = "UTF-8";
                $mail->From = env('ZEPTO_MAIL_FROM');
                $mail->FromName = "CRTX"; // Add a from name
                $mail->addAddress($user->email, $user->name);
                $mail->Subject = "CRTX: Your Two-Factor Authentication Code";
                $mail->Body = $htmlbody;
                $mail->SMTPDebug = SMTP::DEBUG_SERVER; // More detailed debug level
                $mail->Debugoutput = function($str, $level) {
                    \Log::info("SMTP Debug: $str");
                };

                if (!$mail->Send()) {
                    \Log::error("Email Error: " . $mail->ErrorInfo);
                    $errors[] = 'Email sending failed: ' . $mail->ErrorInfo;
                } else {
                    $sent = true;
                }

            } catch (\Exception $e) {
                $errors[] = 'Email sending failed: ' . $e->getMessage();
            }
        }

        if (!$sent) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code',
                'errors' => $errors
            ], 500);
        }

        // Store the code temporarily in the session
        session(['2fa_code' => [
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10)->timestamp
        ]]);

        // Prepare success message based on delivery method
        $message = 'Verification code sent successfully via ';
        if ($user->two_factor_type === 'both') {
            $message .= 'SMS and Email';
        } else {
            $message .= ucfirst($user->two_factor_type);
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = $request->user();

        // Check if it's a recovery code
        if ($user->two_factor_recovery_codes) {
            $recoveryCodes = json_decode($user->two_factor_recovery_codes, true);
            $codeIndex = array_search($request->code, $recoveryCodes);

            if ($codeIndex !== false) {
                // Remove used recovery code
                unset($recoveryCodes[$codeIndex]);
                $user->two_factor_recovery_codes = json_encode(array_values($recoveryCodes));
                $user->two_factor_verified_at = Carbon::now();
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Recovery code accepted',
                    'remaining_recovery_codes' => count($recoveryCodes)
                ]);
            }
        }

        // Get stored code from session
        $storedCode = session('2fa_code');

        if (!$storedCode ||
            $storedCode['code'] !== $request->code ||
            Carbon::createFromTimestamp($storedCode['expires_at'])->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired code'
            ], 422);
        }

        // Clear the code from session
        session()->forget('2fa_code');

        // Update user verification status
        $user->two_factor_verified_at = Carbon::now();

        if (in_array($user->two_factor_type, ['sms', 'both'])) {
            $user->phone_verified = true;
            $user->phone_verified_at_2fa = Carbon::now();
        }

        if (in_array($user->two_factor_type, ['email', 'both'])) {
            $user->email_verified = true;
            $user->email_verified_at_2fa = Carbon::now();
        }

        $user->save();

        // Get fresh user data
        $user_data = User::find($user->id);
        $role = '';
        if ($user_data->roles()->first()) {
            $role = $user_data->roles()->first()['title'];
        }

        // Get clinic data
        $clinicId = $user->managerClinics()->groupBy('id')->first();
        $clinicCount = $user->managerClinics()->count();
        $clinicDataArray = [
            'id' => $clinicId->id,
            'clinic_name' => $clinicId->clinic_name,
            'inbox_id' => $clinicId->inbox_id,
            'ai_complete' => $clinicId->ai_complete
        ];

        // Create new token for authenticated session
        $tokenResult = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => '2FA verification successful',
            'token' => $tokenResult,
            'token_type' => 'Bearer',
            'user' => $user_data,
            'role' => $role,
            'clinic_data' => $clinicDataArray,
            'multiple_clinic' => $clinicCount > 1,
            'clinicId' => $clinicId->id
        ]);
    }
}
