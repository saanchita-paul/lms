<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ExceptionLog;

class TemplateController extends Controller
{
	use ExceptionLog;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clinic_id = $request->input('clinic_id');
        $type = $request->input('type');

        // Retrieve templates based on client_id using Eloquent
        $templatesQuery = Template::query();

        if ($clinic_id) {
            $templatesQuery->where('clinic_id', $clinic_id);
        }

        if ($type) {
            $templatesQuery->where('type', $type);
        }


        $templates = $templatesQuery->get();

        return response()->json(['success' => true, 'templates' => $templates], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clinic_id' => 'required',
            'user_id' => 'required',
            'template_name' => 'required',
            'type' => 'required|in:email,text,appointment,reminder-email,reminder-text',
            'subject' => $request->input('type') == 'email'
                ? 'required_if:type,email'
                : ($request->input('type') == 'appointment'
                    ? 'required_if:type,appointment'
                    : ($request->input('type') == 'reminder-email'
                        ? 'required_if:type,email' : '')),
            'body' => 'required',
        ]);

        if ($validator->fails()) {
			$errors = $this->errorMessages($validator);
            return response()->json(['errors' => $errors], 200);
        }

        $templateData = [
            'clinic_id' => $request->input('clinic_id'),
            'user_id' => $request->input('user_id'),
            'template_name' => $request->input('template_name'),
            'body' => $request->input('body'),
            'type' => $request->input('type'),
        ];

        if (in_array($request->input('type'), ['email', 'appointment', 'reminder-email', 'reminder-text'])) {
            $templateData['subject'] = $request->input('subject');
        }

        $template = Template::create($templateData);

        return response()->json(['template' => $template,'success' => true, 'message' => 'Template created successfully'], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'clinic_id' => 'required',
            'user_id' => 'required',
            'template_name' => 'required',
            'type' => 'required|in:email,text,appointment,reminder-email,reminder-text',
            'subject' => $request->input('type') == 'email'
                ? 'required_if:type,email'
                : ($request->input('type') == 'appointment'
                    ? 'required_if:type,appointment'
                    :  ($request->input('type') == 'reminder-email'
                    ? 'required_if:type,email' : '')),
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $this->errorMessages($validator);
            return response()->json(['errors' => $errors], 200);
        }

        $template = Template::findOrFail($id);

        $updateData = [
            'clinic_id' => $request->input('clinic_id'),
            'user_id' => $request->input('user_id'),
            'template_name' => $request->input('template_name'),
            'body' => $request->input('body'),
            'type' => $request->input('type'),
        ];

        if ($request->input('type') === 'email' || $request->input('type') === 'reminder-email') {
            $updateData['subject'] = $request->input('subject');
        } elseif ($request->input('type') === 'appointment') {
            $updateData['subject'] = $request->input('subject'); // Retain the subject for appointments
        } else {
            unset($updateData['subject']); // Remove subject field if type is 'text'
        }

        $template->update($updateData);

        return response()->json(['template' => $template,'success' => true, 'message' => 'Template updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        $template->delete();

        return response()->json(['message' => 'Template deleted successfully','success' => true]);
    }
}
