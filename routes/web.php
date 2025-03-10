<?php

//Route::redirect('/', '/login')->name('login');
use App\Http\Controllers\Admin\CrmChatController;
use App\Http\Controllers\FacebookMessagesController;
use App\Http\Controllers\AutomationRuleController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\WherebyController;
use App\Http\Controllers\LicenseController;


Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/importcallrailrecording','ImportcallrailController@importcallrailrecording')->name('importcallrailrecording');
Route::get('/calls', 'CallrailController@getSingleCall')->name('getSingleCall');
//Route::get('/getSingleCall', 'CallrailController@getSingleCall')->name('getSingleCall');
Route::post('/callrail', 'CallrailController@savedata')->name('callrail');
Route::post('/callrailpostcall', 'CallrailController@callrailpostcall')->name('callrailpostcall');
Route::get('/callrailpending', 'CallrailController@pending');
Route::get('/callrailchecksource', 'CallrailController@checksource');
Route::post('/calendly', 'CallrailController@calendly')->name('calendly');

Route::post('/team-member', 'CallrailController@teammember');
Route::post('/fb-message', [FacebookMessagesController::class, 'handleFbMessageWebhook']);

Route::post('/whereby', [WherebyController::class, 'handleWherebyWebhook']);
Route::post('/save-thread-id', [ThreadController::class, 'handleThreadId']);

Route::get('/getNextHealthAppointmentTypes/{id}', 'AppointmentController@getNextHealthAppointmentTypes')->name('getNextHealthAppointmentTypes');
Route::get('/getServicesList', 'AppointmentController@getServicesList')->name('getServicesList');
Route::get('/getAvailableTimes/{id}/{date}', 'AppointmentController@getAvailableTimes')->name('getAvailableTimes');
Route::post('/getPractiseInfo', 'AppointmentController@getPractiseInfo')->name('getPractiseInfo');
Route::post('/createAppointment', 'AppointmentController@createAppointment')->name('createAppointment');
Route::post('/create-patient', 'AppointmentController@createPatient')->name('create-patient');
Route::get('/get-env-data', 'AppointmentController@get_env_data')->name('get_env_data');
Route::post('getAppointmentById', 'AppointmentController@getById');

Route::get('/validate-license', [LicenseController::class, 'validateKey']);


Route::get('/importscript', 'ImportController@importscript');

Route::get('/unsubcribe/{key}', 'UnsubscribeController@index');

//Route::post('/smswebhook/{key}', 'CrmChatController@pending')->name('smswebhook');
Route::post('/smswebhook/{key}', 'Admin\CrmChatController@pending')->name('sms.inbound');
Route::post('/verifysms', 'Admin\CrmChatController@verifysms');
Route::post('/verifysms/resend', 'Admin\CrmChatController@resendverifysms');
Route::post('/crtxaiwebhook/{key}', [CrmChatController::class, 'vapiwebhook']);

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    //dashboard
    Route::get('/dashboardData', 'HomeController@dashboard');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Task Status
    Route::delete('task-statuses/destroy', 'TaskStatusController@massDestroy')->name('task-statuses.massDestroy');
    Route::resource('task-statuses', 'TaskStatusController');

    // Task Tag
    Route::delete('task-tags/destroy', 'TaskTagController@massDestroy')->name('task-tags.massDestroy');
    Route::resource('task-tags', 'TaskTagController');

    // Task
    Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    Route::post('tasks/media', 'TaskController@storeMedia')->name('tasks.storeMedia');
    Route::post('tasks/ckmedia', 'TaskController@storeCKEditorImages')->name('tasks.storeCKEditorImages');
    Route::resource('tasks', 'TaskController');

    // Tasks Calendar
    Route::resource('tasks-calendars', 'TasksCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Crm Status
    Route::delete('crm-statuses/destroy', 'CrmStatusController@massDestroy')->name('crm-statuses.massDestroy');
    Route::resource('crm-statuses', 'CrmStatusController');

    // Crm Customer

    Route::delete('crm-customers/destroy', 'CrmCustomerController@massDestroy')->name('crm-customers.massDestroy');
    Route::post('crm-customers/media', 'CrmCustomerController@storeMedia')->name('crm-customers.storeMedia');
    Route::post('crm-customers/ckmedia', 'CrmCustomerController@storeCKEditorImages')->name('crm-customers.storeCKEditorImages');
    Route::resource('crm-customers', 'CrmCustomerController');
    Route::get('crm-customers_updated', 'CrmCustomerController@updatedlist');
    Route::post('crm-customers_move', 'CrmCustomerController@moveStage')->name('crm-customers.moveStage');
    Route::post('crm-customers/hassms', 'CrmCustomerController@hassms')->name('crm-customers.hassms');
    Route::post('crm-customers/settings', 'CrmCustomerController@settings')->name('crm-customers.settings');
    Route::get('crm-customers/restore/one/{id}', 'CrmCustomerController@restore')->name('crm-customers.restore');
    Route::post('crm-customers/sendemail', 'CrmCustomerController@sendemail')->name('crm-customers.sendemail');
    Route::post('crm-customers/sendcreditcardpdf', 'CrmCustomerController@sendcreditcardpdf')->name('crm-customers.sendcreditcardpdf');
    Route::post('crm-customers/markasspam', 'CrmCustomerController@markasspam')->name('crm-customers.markasspam');

    // Crm Note
    Route::delete('crm-notes/destroy', 'CrmNoteController@massDestroy')->name('crm-notes.massDestroy');
    Route::resource('crm-notes', 'CrmNoteController');


    // Crm Chat
    Route::delete('crm-chat/destroy', 'CrmChatController@massDestroy')->name('crm-chats.massDestroy');
    Route::resource('crm-chats', 'CrmChatController');
    Route::post('/inbox', 'CrmChatController@inbox');
    Route::post('/leftinbox', 'CrmChatController@leftinbox');

    // Crm Document
    Route::delete('crm-documents/destroy', 'CrmDocumentController@massDestroy')->name('crm-documents.massDestroy');
    Route::post('crm-documents/media', 'CrmDocumentController@storeMedia')->name('crm-documents.storeMedia');
    Route::post('crm-documents/ckmedia', 'CrmDocumentController@storeCKEditorImages')->name('crm-documents.storeCKEditorImages');
    Route::resource('crm-documents', 'CrmDocumentController');

    // Sources
    Route::delete('sources/destroy', 'SourcesController@massDestroy')->name('sources.massDestroy');
    Route::resource('sources', 'SourcesController', ['except' => ['show']]);

    // Clinic
    Route::delete('clinics/destroy', 'ClinicController@massDestroy')->name('clinics.massDestroy');
    Route::post('clinics/media', 'ClinicController@storeMedia')->name('clinics.storeMedia');
    Route::post('clinics/ckmedia', 'ClinicController@storeCKEditorImages')->name('clinics.storeCKEditorImages');
    Route::post('clinics/parse-csv-import', 'ClinicController@parseCsvImport')->name('clinics.parseCsvImport');
    Route::post('clinics/process-csv-import', 'ClinicController@processCsvImport')->name('clinics.processCsvImport');
    Route::resource('clinics', 'ClinicController');

    Route::post('/clinics/{cid}/edit', 'ClinicController@patientJourneyTemplate')->name('clinic.patientjourney');

    Route::post('/clinics/{id}', 'ClinicController@manageTemplate')->name('clinic.update');



    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    // Reports
    Route::resource('reports', 'ReportsController');
    Route::resource('userreports', 'UserReportsController');
    Route::resource('dataupload', 'DataUploadController');
    Route::post('dataupload/media', 'DataUploadController@storeMedia')->name('dataupload.storeMedia');

});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});

Route::group(['prefix'=> 'crtx', 'as' => 'crtx.', 'namespace' => 'CRTX'], function () {

    Route::get('/schedule-appointment/{id}/sa-step-1', function($id){
        $clinic = \App\Models\Clinic::where('id', $id)->first();
        $callrail_installation_script = $clinic->callrail_installation_script;
        $env = config('app.env');
        return view('crtx.index', compact('env','id', 'callrail_installation_script'));
    });


    Route::get('/account/start', function(){
        $env = config('app.env');
        return view('crtx.index', compact('env'));
    });

    Route::get('/account/setup', function(){
        $env = config('app.env');
        return view('crtx.index', compact('env'));
    })->name('account.setup');

    Route::get('/account/signin', function(){
        $env = config('app.env');
        return view('crtx.index', compact('env'));
    });

    Route::get('/account/reset-pw/{token}', function(){
        $env = config('app.env');
        return view('crtx.index', compact('env'));
    });

    Route::get('/patient-profile/{id}', function(){
        $env = config('app.env');
        return view('crtx.index', compact('env'));
    });

    Route::get('/save-voice-agent', function (\Illuminate\Http\Request $request) {
        if (!$request->user()->getIsAdminAttribute()) {
            abort(403, 'Unauthorized access.');
        }
        $env = config('app.env');
        return view('crtx.index', compact('env'));
    })->middleware('auth');


    Route::get('{any}', function(){
        return redirect('crtx/account/signin');
    })->where('any', '.*');

});

Route::get('/update-automation-rules', [AutomationRuleController::class, 'updateAutomationRules']);

//Route::redirect('/', '/crtx/account/signin')->name('crtx.index');
Route::get('/', function(){
    //return view('webpage');
    return Redirect::to('https://agent.mycrtx.com');
});
Route::get('/crtx-agent-training', function(){
    return view('crtx-agent-training');
});



if (App::environment('production') || App::environment('staging'))
{
    URL::forceScheme('https');
}

