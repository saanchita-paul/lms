<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyClinicRequest;
use App\Http\Requests\StoreClinicRequest;
use App\Http\Requests\UpdateClinicRequest;
use App\Http\Requests\templateClinicRequest;
use App\Models\Clinic;
use App\Models\User;
use App\Models\ManageTemplate;
use App\Models\PatientCampaign;
use App\Models\ManagePatientJourneyTemplate;
use App\Notifications\DnsVerificationNotification;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ClinicController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('clinic_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userid = $request->user()->id;

        $isadmin =  $request->user()->getIsAdminAttribute();
        if ($request->ajax()) {
            if(!$isadmin){
                $query = Clinic::whereHas('managers', function ($query ) use($userid) {
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->get();
            }else{
                $query = Clinic::with(['managers'])->select(sprintf('%s.*', (new Clinic)->table));
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'clinic_show';
                $editGate      = 'clinic_edit';
                $deleteGate    = 'clinic_delete';
                $crudRoutePart = 'clinics';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? Clinic::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('clinic_name', function ($row) {
                return $row->clinic_name ? $row->clinic_name : "";
            });
            $table->editColumn('clinic_legal_name', function ($row) {
                return $row->clinic_legal_name ? $row->clinic_legal_name : "";
            });
            $table->editColumn('dr_name', function ($row) {
                return $row->dr_name ? $row->dr_name : "";
            });
            $table->editColumn('address', function ($row) {
                return $row->address ? $row->address : "";
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : "";
            });
            $table->editColumn('office_number', function ($row) {
                return $row->office_number ? $row->office_number : "";
            });
            $table->editColumn('hotline_phone_number', function ($row) {
                return $row->hotline_phone_number ? $row->hotline_phone_number : "";
            });
            $table->editColumn('website', function ($row) {
                return $row->website ? $row->website : "";
            });
            $table->editColumn('lead_center', function ($row) {
                return $row->lead_center ? Clinic::LEAD_CENTER_SELECT[$row->lead_center] : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Clinic::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('manager', function ($row) {
                $labels = [];

                foreach ($row->managers as $manager) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $manager->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'manager']);

            return $table->make(true);
        }

        $users = User::get();

        return view('admin.clinics.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('clinic_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $managers = User::all()->pluck('name', 'id');

        return view('admin.clinics.create', compact('managers'));
    }

    public function store(StoreClinicRequest $request)
    {
        $clinic = Clinic::create($request->all());
        $clinic->managers()->sync($request->input('managers', []));

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $clinic->id]);
        }

        return redirect()->route('admin.clinics.edit', $clinic->id)->with('success', 'Changes saved successfully!');
        //return redirect()->route('admin.clinics.index');
    }

    public function edit(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic->load('managers');
        $userid = Auth()->user()->id;
        $user = Auth()->user()->roles;
        $userrole = $user[0]['title'];
        $isadmin =  Auth()->user()->getIsAdminAttribute();

        //$managers = User::all()->pluck('name', 'id');
        //$managers = User::with('roles')->get()->map->only('name', 'id', 'roles.*.title');

        $users = User::with('roles')->get();
        $bio = $users->map->only('id','name','roles.title');
        $managers=  User::with('roles')->get();

       $autosequencData = DB::table('automationsequence')->select('*')->get();
        $records = array();

        foreach ($autosequencData as $key => $value) {
            $manageData = $this->checkRecordExists($clinic->id,$value->dayinterval);

            if($manageData != null)
            {
              $record = array(
                    'dayinterval' => $manageData['dayinterval'],
                    'text_template' => $manageData['text_template'],
                    'email_subject' => $manageData['email_subject'],
                    'email_template' => $manageData['email_template'],
                );
            }
            else
            {
               $record = array(
                    'dayinterval' => $value->dayinterval,
                    'text_template' => $value->text_template,
                    'email_subject' => $value->email_subject,
                    'email_template' => $value->email_template,
                );
            }
            $records[] = (object) $record;
        }

        $template = collect($records);

        $patientData = DB::table('patient_journey_campaign')->select('*')->get();

        $records = array();

        foreach ($patientData as $key => $value) {

            $manageData = $this->patientJourneyTemplateExists($clinic->id,$value->dayinterval);



            if($manageData != null)
            {
              $record = array(
                    'dayinterval' => $manageData['dayinterval'],
                    'text_template' => $manageData['text_template'],
                    'email_subject' => $manageData['email_subject'],
                    'email_template' => $manageData['email_template'],
                );
            }
            else
            {
               $record = array(
                    'dayinterval' => $value->dayinterval,
                    'text_template' => $value->text_template,
                    'email_subject' => $value->email_subject,
                    'email_template' => $value->email_template,
                );
            }
            $records[] = (object) $record;
        }

        $patientcampaign = collect($records);

        if(!$isadmin &&  $userrole == "Onboarding"){
            $clinic = $clinic->whereHas('managers', function ($query ) use($userid) {
             return   $query->where('user_id', '=', $userid );
            })->first();
        }else{
            $clinic->load('managers');
        }

        if($clinic){
            return view('admin.clinics.edit', compact('managers', 'clinic','template','patientcampaign'));
        }else{
            return redirect()->route('admin.clinics.index');
        }


    }

    public function update(UpdateClinicRequest $request, Clinic $clinic)
    {
        $user = auth()->user()->roles;
        $userrole = $user[0]['title'];
        $userid = Auth()->user()->id;
        $manager = $clinic->managers->pluck('id')->toArray();

        if(!empty($request->multiple_localtion_details)){
            $request['multiple_localtion_details'] = implode('|*|', $request->multiple_localtion_details);
        }else{
            $request['multiple_localtion_details'] = '';
        }

        if(!empty($request->practice_specialty)){
            $request['practice_specialty'] = implode(',', $request->practice_specialty);
        }else{
            $request['practice_specialty'] = '';
        }
        if(!empty($request->primary_services)){
            $request['primary_services'] = implode(',', $request->primary_services);
        }else{
            $request['primary_services'] = '';
        }
        if(!empty($request->primary_selling)){
            $request['primary_selling'] = implode(',', $request->primary_selling);
        }else{
            $request['primary_selling'] = '';
        }
        if(!empty($request->technology)){
            $request['technology'] = implode(',', $request->technology);
        }else{
            $request['technology'] = '';
        }
        if(!empty($request->available_treatment)){
            $request['available_treatment'] = implode(',', $request->available_treatment);
        }else{
            $request['available_treatment'] = '';
        }
        if(!empty($request->financing_options)){
            $request['financing_options'] = implode(',', $request->financing_options);
        }else{
            $request['financing_options'] = '';
        }
        if(($userrole == 'Onboarding' || $userrole == 'Manager' ) && $request->status != "Active"){
            $request['status'] = 'On-boarding';
        }
        if($userrole == 'Manager' && $request->status == "Active"){
            $request['status'] = 'Active';
        }

        if($userrole == 'Onboarding' && !in_array($userid, $manager) ){
            echo "You are not allow to perform this update.";exit;
        }else{
            $clinic->update($request->all());
            if(!empty($request->input('managers'))){
                $clinic->managers()->sync($request->input('managers', []));
            }

            return redirect()->route('admin.clinics.edit', $clinic->id)->with('success', 'Changes saved successfully!');
        }

    }

    public function show(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic->load('managers');
        $userid = Auth()->user()->id;

        $isadmin =  Auth()->user()->getIsAdminAttribute();

        if(!$isadmin){
            $clinic = $clinic->whereHas('managers', function ($query ) use($userid) {
                                                                     return   $query->where('user_id', '=', $userid );
                                                                    })->first();
        }else{
            $clinic->load('managers');
        }
        if($clinic){
            return view('admin.clinics.show', compact('clinic'));
        }else{
            return redirect()->route('admin.clinics.index');
        }


    }

    public function destroy(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic->delete();

        return back();
    }

    public function massDestroy(MassDestroyClinicRequest $request)
    {
        Clinic::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('clinic_create') && Gate::denies('clinic_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Clinic();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function manageTemplate(Request $request)
    {

        DB::table('manage_clinic_template')
            ->where('clinic_id', $request->clinicid)
            ->where('dayinterval', $request->dayinterval)
            ->update([
                'updated_at' => null,
                'deleted_at' => null
            ]);

        DB::table('manage_clinic_template')
             ->where('clinic_id', $request->clinicid)
            ->where('dayinterval', $request->dayinterval)
            ->delete();

        $clinicId = $request->clinicid;
        $dayinterval = $request->dayinterval;
        $text_template = $request->text_template;
        $email_subject = $request->email_subject;
        $email_template = $request->email_template;

        if($request->text_template == 'undefined' || $request->text_template == null )
        {
            $text_template = 'hold';
        }
        if($request->email_subject == 'undefined' || $request->email_subject == null)
        {
            $email_subject = 'hold';
        }
        if($request->email_template == 'undefined' || $request->email_template == null)
        {
            $email_template = 'hold';
        }


        ManageTemplate::where('clinic_id', $clinicId)->where('dayinterval', $dayinterval)->updateOrInsert(
            [
                'clinic_id'     => $clinicId,
                'dayinterval'   => $dayinterval,
                'text_template' => $text_template,
                'email_subject' => $email_subject,
                'email_template' => $email_template
            ]
        );

        return response()->json(
            [
                'success' => true,
                'message' => 'Template update successfully'
            ]
        );

    }

    public  function checkRecordExists($clinicid, $dayinterval) {

    $manageRecord = ManageTemplate::where('clinic_id', $clinicid)->where('dayinterval', $dayinterval)->get();

        if($manageRecord->isNotEmpty())
        {
            $record = array(
                'dayinterval' => $manageRecord[0]['dayinterval'],
                'text_template' => $manageRecord[0]['text_template'],
                'email_subject' => $manageRecord[0]['email_subject'],
                'email_template' => $manageRecord[0]['email_template'],
            );
            return $record;
        }

    }

    public function patientJourneyTemplate(Request $request)
    {
        DB::table('manage_patient_journey_template')
            ->where('clinic_id', $request->clinicid)
            ->where('dayinterval', $request->dayinterval)
            ->update([
                'updated_at' => null,
                'deleted_at' => null
            ]);

        DB::table('manage_patient_journey_template')
             ->where('clinic_id', $request->clinicid)
            ->where('dayinterval', $request->dayinterval)
            ->delete();

        $clinicId = $request->clinicid;
        $dayinterval = $request->dayinterval;
        $text_template = $request->text_template;
        $email_subject = $request->email_subject;
        $email_template = $request->email_template;

        if($request->text_template == 'undefined' || $request->text_template == null )
        {
            $text_template = 'hold';
        }
        if($request->email_subject == 'undefined' || $request->email_subject == null)
        {
            $email_subject = 'hold';
        }
        if($request->email_template == 'undefined' || $request->email_template == null)
        {
            $email_template = 'hold';
        }


        ManagePatientJourneyTemplate::where('clinic_id', $clinicId)->where('dayinterval', $dayinterval)->updateOrInsert(
            [
                'clinic_id'     => $clinicId,
                'dayinterval'   => $dayinterval,
                'text_template' => $text_template,
                'email_subject' => $email_subject,
                'email_template' => $email_template
            ]
        );

        return response()->json(
            [
                'success' => true,
                'message' => 'Patient Journey Template update successfully'
            ]
        );

    }

    public  function patientJourneyTemplateExists($clinicid, $dayinterval) {

    $manageRecord = ManagePatientJourneyTemplate::where('clinic_id', $clinicid)->where('dayinterval', $dayinterval)->get();

        if($manageRecord->isNotEmpty())
        {
            $record = array(
                'dayinterval' => $manageRecord[0]['dayinterval'],
                'text_template' => $manageRecord[0]['text_template'],
                'email_subject' => $manageRecord[0]['email_subject'],
                'email_template' => $manageRecord[0]['email_template'],
            );
            return $record;
        }

    }
}
