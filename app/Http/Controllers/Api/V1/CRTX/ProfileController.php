<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Setting;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use MediaUploadingTrait;

    public function update(Request $request)
    {
       $user = User::find($request->id);

       if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

       $user->name  = $request->input('name');
       $newEmail    = $request->input('email');
       $profilePic  = $request->input('profile_pic');
       $phone      = $request->input('phone');

        // Remove special characters from the phone number
        $cleanedPhoneNumber = preg_replace('/[^0-9]/', '', $phone);

        if(Str::length($cleanedPhoneNumber) == 11 && Str::startsWith($cleanedPhoneNumber, '1')){
            $cleanedPhoneNumber = '+' . $cleanedPhoneNumber;
        }else if(Str::length($cleanedPhoneNumber) == 10) {
            $cleanedPhoneNumber = '+1' . $cleanedPhoneNumber;
        }
       // Compare the new email with the current email
        if ($user->email !== $newEmail) {
            // Check if the new email is already in use
            $existingUser = User::where('email', $newEmail)->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already in use.'
                ]);
            }
        }

        // Update name and email
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'two_factor_enabled' => $request->two_factor_enabled,
            'two_factor_type' => $request->two_factor_type,
            'phone' => $cleanedPhoneNumber
        ]);

        // Update profile picture if provided
        if ($request->hasFile('profile_pic')) {
            // Delete existing profile picture if any
            $user->clearMediaCollection('profile_pic');

            // Add the new profile picture to the collection
            $user->addMediaFromRequest('profile_pic')->toMediaCollection('profile_pic');
        }

        // Fetch the updated user data
        $updatedUser = User::with('roles')->find($request->id);

        // Return a success response along with the updated user data
        return response()->json([
            'success' => true,
            'message' => 'User profile updated successfully.',
            'user' => $updatedUser
        ]);


    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.'
        ]);

    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        if(!empty($user)){

            $setting = Setting::updateOrCreate(['id' => !empty($user->setting)? $user->setting->id : null], [
                'user_id' => $user->id,
                'follow_up_email_notification' => $request->followUpEmailNotification,
                'follow_up_text_notification' => $request->followUpTextNotification,
                'follow_up_browser_notification' => $request->followUpBrowserNotification,
                'lead_reconnecting_email_notification' => $request->leadReconnectingEmailNotification,
                'lead_reconnecting_text_notification' => $request->leadReconnectingTextNotification,
                'lead_reconnecting_browser_notification' => $request->leadReconnectingBrowserNotification,
                'appointment_email_notification' => $request->appointmentEmailNotification,
                'appointment_text_notification' => $request->appointmentTextNotification,
                'appointment_browser_notification' => $request->appointmentBrowserNotification,
                'daily_summary_email_notification' => $request->dailySummaryEmailNotification,
                'weekly_summary_email_notification' => $request->weeklySummaryEmailNotification,
                'do_not_disturb' => $request->doNotDisturb,
                'whereby_email_notification' => $request->wherebyEmailNotification,
                'whereby_text_notification' => $request->wherebyTextNotification,
                'whereby_browser_notification' => $request->wherebyBrowserNotification,
            ]);

            return response()->json([
                'success' => true,
                'settings' => $setting,
                'message' => 'Notification settings updated successfully.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'There was an error updating your settings.'
        ], 200);
    }
}
