<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\ExceptionLog;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use Carbon\Carbon;
use Str;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    use ExceptionLog;

    public function login(Request $request)
    {
        try {
            $role = '';
            $validate = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            if ($validate->fails()) {
                $error = $this->errorMessages($validate);
                return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $error], 422);
            }
            $credentials = ['email'=>$request->email, 'password'=>$request->password];

            if (!Auth::attempt($credentials))
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid username and password'
                ],422);
            $user = $request->user();

            // Check if 2FA is enabled
            if ($user->two_factor_enabled) {
                // Create a temporary token for 2FA
                $tempToken = $user->createToken('2FA-Temp-Token')->plainTextToken;

                // Generate and send 2FA code
                $codeResponse = app(TwoFactorController::class)->generateCode($request);

                if (!$codeResponse->getData()->success) {
                    return $codeResponse;
                }

                // Get clinic data for storing in tempLoginData
                $clinicId = $user->managerClinics()->groupBy('id')->first();
                $clinicCount = $user->managerClinics()->count();
                $role = $user->roles()->first() ? $user->roles()->first()['title'] : '';
                $clinicDataArray = [
                    'id' => $clinicId->id,
                    'clinic_name' => $clinicId->clinic_name,
                    'inbox_id' => $clinicId->inbox_id,
                    'ai_complete' => $clinicId->ai_complete
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Please verify 2FA code',
                    'requires_2fa' => true,
                    'user_id' => $user->id,
                    'user' => $user,
                    'token' => $tempToken,
                    'two_factor_type' => $user->two_factor_type,
                    'clinic_data' => $clinicDataArray,
                    'clinicId' => $clinicId->id,
                    'role' => $role,
                    'settings' => $user->setting,
                    'multiple_clinic' => $clinicCount > 1
                ]);
            }

            // Regular login flow
            $loginLogArr = array(
                'last_login_date' => date('Y-m-d H:i:s')
            );
            User::updateRecord($loginLogArr,$user->id);

            $clinicId = $user->managerClinics()->groupBy('id')->first();
            $clinicCount = $user->managerClinics()->count();
            $clinicIds = $user->managerClinics()->pluck('id')->toArray();
            $clinicIds = implode(",",$clinicIds);
            $tokenResult = $user->createToken('Personal Access Token')->plainTextToken;
            $user_data = User::with('roles')->find($user->id);
            if($user_data->roles()->first()){
                $role = $user_data->roles()->first()['title'];
            }
            $clinicDataArray = ['id'=>$clinicId->id,'clinic_name'=>$clinicId->clinic_name,'inbox_id'=>$clinicId->inbox_id,'ai_complete'=>$clinicId->ai_complete];
            return response()->json([
                'success' => true,
                'requires_2fa' => false,
                'token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user_data,
                'role'=> $role,
                'clinic_data' => $clinicDataArray,
                'multiple_clinic'=>$clinicCount > 1,
                'clinicId' => $clinicId->id,
                'settings' => $user_data->setting,
                'whereby_link' => $clinicId->whereby_link,
                'assistant_id' => $clinicId->assistant_id,
            ]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex, 'login');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $user = User::where(['email'=>$request->email,'deleted_at'=>NULL])->count();
        if($user > 0){
            $request->validate(['email' => 'required|email']);

            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            return $response == Password::RESET_LINK_SENT
                ? response()->json(['success' => true, 'message' => 'Reset link sent to your email'], 200)
                : response()->json(['success' => false, 'message' => 'Unable to send reset link'], 200);
        }else{
            return response()->json(['success' => false, 'message' => 'Email not Exist'], 200);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required|email',
           'password' => 'required|min:8|confirmed',
           'password_confirmation' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $this->errorMessages($validator);
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $error], 200);
        }

        $user = User::where('email', $request->email)->first();

        $reset = DB::table('password_resets')->where(['email'=> $request->email])->first();

        if($reset && $user && Hash::check($request->token, $reset->token)){
            $user->password = Hash::make($request->password);
            $user->activation_token = null;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Password reset Successful!'], 200);
        }

        return response()->json(['success' => false, 'message' => 'The details you provided is not valid!'], 200);
    }

    protected function broker()
    {
        return Password::broker();
    }
}
