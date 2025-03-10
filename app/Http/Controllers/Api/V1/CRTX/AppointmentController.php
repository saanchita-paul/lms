<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentAvailability;
use App\Models\AppointmentReminder;
use App\Models\AppointmentUnavailability;
use App\Models\Clinic;
use App\Models\FixedAppointmentAvailability;
use App\Models\RepeatingAppointmentAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ExceptionLog;

class AppointmentController extends Controller
{
    use ExceptionLog;

    public function index(Request $request)
    {
        DB::enableQueryLog();

        $clinic = Clinic::find($request->clinic_id);

        $appointment_availability = $clinic->appointment_availability();

        if(empty($appointment_availability->count())){
            return response()->json([
                'success' => true,
                'message' => 'No Appointment data found for this clinic!',
            ], 200);
        }else{
            $arr = [
                'success' => true,
                'data' => $appointment_availability->with(['fixed_appointment_availabilities'=>function($query){
                $query->whereDate('date', '>=', now());
            }, 'repeating_appointment_availability', 'appointments'=>function($query){
                    $query->whereDate('date', '>=', now());
                }, 'appointment_unavailabilities'=>function($query){
                    $query->whereDate('date', '>=', now());
                }, 'appointments.crm_customer', 'appointments.services', 'appointment_reminders'])->first(),
                'message' => 'Appointment data was successfully fetched!',
            ];

            return response()->json($arr, 200);
        }
    }

    public function update(Request $request)
    {
        $rules = [];

        if($request->section == 'detail'){
            $rules = [
                'patient_types.*' => 'required|string',
                'service_types.*' => 'required|integer',
                'calendar_type' => 'required|string',
            ];
        }else{
            $rules = [
                'duration' => 'required|numeric',
                'timezone' => 'required',
            ];
        }

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $error = $this->errorMessages($validate);
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
        }

        $clinic = Clinic::find($request->clinic_id);

        $appointment_availability = $clinic->appointment_availability;

        if(empty($appointment_availability)){
            $appointment_availability = new AppointmentAvailability();
        }

        if($request->section == 'detail'){

            if ($clinic) {
                $clinic->emails_for_scheduling = $request->emails_for_scheduling;
                $clinic->save();
            }

            $appointment_availability->clinic_id = $request->clinic_id;
            $appointment_availability->patient_types = implode(',', $request->patient_types);
            $appointment_availability->service_types = implode(',', $request->service_types);
            $appointment_availability->calendar_type = $request->calendar_type;
            $appointment_availability->save();
        }else{
            $appointment_availability->clinic_id = $request->clinic_id;
            $appointment_availability->duration = $request->duration;
            $appointment_availability->repeat_type = $request->repeat_type;
            $appointment_availability->timezone = $request->timezone;
            $appointment_availability->save();

            if($request->repeat_type == 'Repeat weekly'){
                $repeating_appointment_availability = $appointment_availability->repeating_appointment_availability;

                if(empty($repeating_appointment_availability)){
                    $repeating_appointment_availability = new RepeatingAppointmentAvailability();
                }

                $repeating_appointment_availability->appointment_availability_id = $appointment_availability->id;

                $mon_arr = [];
                if(!empty($request->availabilities['mon_start']))
                foreach ($request->availabilities['mon_start'] as $key=>$mon){
                    $mon_arr[$key] = $request->availabilities['mon_start'][$key] . '-' . $request->availabilities['mon_end'][$key];
                }

                $tue_arr = [];
                if(!empty($request->availabilities['tue_start']))
                foreach ($request->availabilities['tue_start'] as $key=>$tue){
                    $tue_arr[$key] = $request->availabilities['tue_start'][$key] . '-' . $request->availabilities['tue_end'][$key];
                }

                $wed_arr = [];
                if(!empty($request->availabilities['wed_start']))
                foreach ($request->availabilities['wed_start'] as $key=>$wed){
                    $wed_arr[$key] = $request->availabilities['wed_start'][$key] . '-' . $request->availabilities['wed_end'][$key];
                }

                $thu_arr = [];
                if(!empty($request->availabilities['thu_start']))
                foreach ($request->availabilities['thu_start'] as $key=>$thu){
                    $thu_arr[$key] = $request->availabilities['thu_start'][$key] . '-' . $request->availabilities['thu_end'][$key];
                }

                $fri_arr = [];
                if(!empty($request->availabilities['fri_start']))
                foreach ($request->availabilities['fri_start'] as $key=>$fri){
                    $fri_arr[$key] = $request->availabilities['fri_start'][$key] . '-' . $request->availabilities['fri_end'][$key];
                }

                $sat_arr = [];
                if(!empty($request->availabilities['sat_start']))
                foreach ($request->availabilities['sat_start'] as $key=>$sat){
                    $sat_arr[$key] = $request->availabilities['sat_start'][$key] . '-' . $request->availabilities['sat_end'][$key];
                }

                $sun_arr = [];
                if(!empty($request->availabilities['sun_start']))
                foreach ($request->availabilities['sun_start'] as $key=>$sun){
                    $sun_arr[$key] = $request->availabilities['sun_start'][$key] . '-' . $request->availabilities['sun_end'][$key];
                }

                $repeating_appointment_availability->mon = implode(',', $mon_arr);
                $repeating_appointment_availability->tue = implode(',', $tue_arr);
                $repeating_appointment_availability->wed = implode(',', $wed_arr);
                $repeating_appointment_availability->thu = implode(',', $thu_arr);
                $repeating_appointment_availability->fri = implode(',', $fri_arr);
                $repeating_appointment_availability->sat = implode(',', $sat_arr);
                $repeating_appointment_availability->sun = implode(',', $sun_arr);
                $repeating_appointment_availability->save();

                $existing_unavailabilities = $appointment_availability->appointment_unavailabilities()->pluck('id')->toArray();

                foreach ($request->unavailable_appointment_dates as $key => $obj) {

                    if ($obj['id'] != 0 && in_array($obj['id'], $existing_unavailabilities)) {
                        //  Update
                        $appointment_unavailability = AppointmentUnavailability::find($obj['id']);

                        //  Remove the existing availability from existing array
                        $existing_unavailabilities = array_diff($existing_unavailabilities, [$obj['id']]);
                    } else {
                        //  Add
                        $appointment_unavailability = new AppointmentUnavailability();
                    }

                    $appointment_unavailability->appointment_availability_id = $appointment_availability->id;

                    $appointment_unavailability->date = Carbon::createFromFormat('m/d/Y', $obj['date'])->format('Y-m-d');

                    $appointment_unavailability->save();
                }

                foreach ($existing_unavailabilities as $obj){
                    // Delete existing unavailabilities
                    AppointmentUnavailability::find($obj)->delete();
                }

            }else{

                $existing_availabilities = $appointment_availability->fixed_appointment_availabilities()->pluck('id')->toArray();

                foreach ($request->fixed_appointment_dates as $key => $obj){

                    if($obj['id']!=0 && in_array($obj['id'], $existing_availabilities)){
                        //  Update

                        $fixed_appointment_availability = FixedAppointmentAvailability::find($obj['id']);

                        //  Remove the existing availability from existing array
                        $existing_availabilities = array_diff($existing_availabilities, [$obj['id']]);
                    }else{
                        //  Add

                        $fixed_appointment_availability = new FixedAppointmentAvailability();
                    }

                    $fixed_appointment_availability->appointment_availability_id = $appointment_availability->id;

                    $fixed_appointment_availability->date = Carbon::createFromFormat('m/d/Y', $obj['date'])->format('Y-m-d');

                    $times_arr = [];
                    foreach ($obj['start_times'] as $index=>$time){
                        $times_arr[$index] = $time . '-' . $obj['end_times'][$index];
                    }

                    $fixed_appointment_availability->times = implode(',', $times_arr);

                    $fixed_appointment_availability->save();
                }

                foreach ($existing_availabilities as $obj){
                    // Delete existing availabilities
                    FixedAppointmentAvailability::find($obj)->delete();
                }
            }

            $reminders = array_column($request->reminders, 'id');

            $existing_reminders = AppointmentReminder::where('appointment_availability_id', $appointment_availability->id)->pluck('id')->toArray();

            foreach ($existing_reminders as $reminder){
                if(!in_array($reminder, $reminders)){
                    AppointmentReminder::find($reminder)->delete();
                }
            }

            foreach ($request->reminders as $reminder) {
                AppointmentReminder::updateOrCreate(['id' => $reminder['id']], [
                    'appointment_availability_id' => $appointment_availability->id,
                    'type' => $reminder['type'],
                    'interval' => $reminder['interval'],
                    'unit' => $reminder['unit'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment Schedule successfully updated!',
        ], 200);

    }
}
