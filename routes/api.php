
<?php

use App\Http\Controllers\Api\V1\CRTX\AuthController;
use App\Http\Controllers\Api\V1\CRTX\ClinicController;
use App\Http\Controllers\Api\V1\CRTX\DefaultController;
use App\Http\Controllers\Api\V1\CRTX\LeadsController;
use App\Http\Controllers\Api\V1\CRTX\NotesController;
use App\Http\Controllers\Api\V1\CRTX\PatientController;
use App\Http\Controllers\Api\V1\CRTX\ChatController;
use App\Http\Controllers\Api\V1\CRTX\ReportController;
use App\Http\Controllers\Api\V1\CRTX\StatusController;
use App\Http\Controllers\Api\V1\CRTX\ProfileController;
use App\Http\Controllers\Api\V1\CRTX\DashboardController;
use App\Http\Controllers\Api\V1\CRTX\SupportController;
use App\Http\Controllers\Api\V1\CRTX\EmailController;
use App\Http\Controllers\Api\V1\CRTX\TemplateController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Api\V1\CRTX\AppointmentController;
use App\Http\Controllers\Api\V1\CRTX\TagController;
use App\Http\Controllers\Api\V1\CRTX\SikkaWebhookController;
use App\Http\Controllers\Api\V1\CRTX\NotificationsController;
use App\Http\Controllers\Api\V1\CRTX\ZohoAnalyticsController;
use App\Http\Controllers\Api\V1\CRTX\AssistantFileController;
use App\Http\Controllers\Api\V1\CRTX\ElasticEmailController;
use App\Http\Controllers\Api\V1\CRTX\EmailConfigController;
use App\Http\Controllers\AppointmentServiceController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HelpAssistantController;
use App\Http\Controllers\OutboundCallingWebhookController;
use App\Http\Controllers\ScrapedDataController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\VapiClinicController;
use App\Http\Controllers\WherebyController;
use App\Models\ScrapedData;
use Dompdf\Dompdf;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\DomCrawler\Crawler;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::post('users/media', 'UsersApiController@storeMedia')->name('users.storeMedia');
    Route::apiResource('users', 'UsersApiController');

    // Task Status
    Route::apiResource('task-statuses', 'TaskStatusApiController');

    // Task Tag
    Route::apiResource('task-tags', 'TaskTagApiController');

    // Task
    Route::post('tasks/media', 'TaskApiController@storeMedia')->name('tasks.storeMedia');
    Route::apiResource('tasks', 'TaskApiController');

    // Crm Status
    Route::apiResource('crm-statuses', 'CrmStatusApiController');

    // Crm Customer
    Route::post('crm-customers/media', 'CrmCustomerApiController@storeMedia')->name('crm-customers.storeMedia');
    Route::apiResource('crm-customers', 'CrmCustomerApiController');

    // Crm Note
    Route::apiResource('crm-notes', 'CrmNoteApiController');

    // Crm Document
    Route::post('crm-documents/media', 'CrmDocumentApiController@storeMedia')->name('crm-documents.storeMedia');
    Route::apiResource('crm-documents', 'CrmDocumentApiController');

    // Sources
    Route::apiResource('sources', 'SourcesApiController', ['except' => ['show']]);

    // Clinic
    Route::post('clinics/media', 'ClinicApiController@storeMedia')->name('clinics.storeMedia');
    Route::apiResource('clinics', 'ClinicApiController');
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('clinic/store', [ClinicController::class, 'store']);
    Route::post('clinic/registrationEmail', [ClinicController::class, 'registrationEmail']);
    Route::post('clinic/ai-setup-email', [ClinicController::class, 'aiSetupEmail']);
    Route::get('activate/{token}', [ClinicController::class, 'activeToken'])->name('activate');
    Route::post('clinic/resend-token', [ClinicController::class, 'resendToken'])->name('resendToken');
    Route::post('store/staff', [ClinicController::class, 'addStaff']);
    Route::post('delete/staff', [ClinicController::class, 'deleteStaff']);
    Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::post('resetPassword', [AuthController::class, 'resetPassword']);
    Route::get('generateZohoAnalyticsToken', [ZohoAnalyticsController::class, 'generateAccessToken']);
    Route::get('exportZohoAnalyticsBulkData/{type}', [ZohoAnalyticsController::class, 'regenerateAccessToken']);
//    Route::get('/create-sikka-webhook', [SikkaWebhookController::class, 'createSikkaWebhook']);
    Route::post('/receieve_payload', [SikkaWebhookController::class, 'receievePayload']);
    Route::post('checkAppointmentAvailability', [\App\Http\Controllers\AppointmentController::class, 'checkAppointmentAvailability']);
    Route::post('bookAppointment', [\App\Http\Controllers\AppointmentController::class, 'bookAppointment']);
    Route::post('checkAppointmentAvailabilityVF', [\App\Http\Controllers\AppointmentController::class, 'checkAppointmentAvailabilityVoiceflow']);
    Route::post('bookAppointmentVF', [\App\Http\Controllers\AppointmentController::class, 'bookAppointmentVoiceflow']);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('clinic/allList',[ClinicController::class,'clinicAllList']);
        Route::post('clinic/list',[ClinicController::class,'clinicList']);
        Route::post('clinic/update',[ClinicController::class,'clinicUpdate']);
        Route::get('logout',[AuthController::class,'logout']);
        Route::get('getleads',[LeadsController::class,'getleads']);
        Route::post('delete-lead',[LeadsController::class,'deleteLeads']);
        Route::post('restore-lead',[LeadsController::class,'restoreLeads']);
        Route::post('get-deleted-leads-list',[LeadsController::class,'getdeletedLeadsList']);
        Route::post('getPatientProfile',[PatientController::class,'getPatientProfile']);
        Route::post('updatePatientProfile',[PatientController::class,'updatePatientProfile']);
        Route::get('search',[LeadsController::class,'search']);
        Route::post('lead/store',[LeadsController::class,'store']);
        Route::post('lead/movestage',[LeadsController::class,'moveStage']);
        Route::get('getStageCount',[LeadsController::class,'getStageCount']);
        Route::post('inboxchat',[ChatController::class,'index']);
        Route::get('getSources',[DefaultController::class,'getSources']);
        Route::post('addNotes',[NotesController::class,'addNotes']);
        Route::post('updateNotes',[NotesController::class,'updateNotes']);
        Route::post('deleteNotes',[NotesController::class,'deleteNotes']);
        Route::post('storeSms',[ChatController::class,'storeSms']);
        Route::get('leftinbox',[ChatController::class,'leftinbox']);
        Route::post('report',[ReportController::class,'report']);
        Route::post('getcontact',[ChatController::class,'getContact']);
        Route::get('consult',[LeadsController::class,'getconsult']);
        Route::get('getAllStatus',[StatusController::class,'getAllStatus']);
        Route::post('getmessagecount',[ChatController::class,'getMessageCount']);
        Route::post('updateprofile',[ProfileController::class,'update']);
        Route::post('changepassword', [ProfileController::class,'changePassword']);
        Route::post('updatesettings', [ProfileController::class,'updateSettings']);
        Route::get('report-export', [ReportController::class, 'downloadReport']);
        Route::post('email-statistics', [ReportController::class, 'getEmailStatistics']);
        Route::post('addquickconsultbook',[LeadsController::class,'addquickConsultbook']);
        Route::post('get_nurturing_template',[ClinicController::class,'getNurturingTemplate']);
        Route::post('managetemplate',[ClinicController::class,'manageTemplate']);
        Route::post('clinics/{id}/verify', [ClinicController::class, 'sendVerificationEmail']);
        Route::get('clinics/verify/{id}', [ClinicController::class, 'getClinic']);
        Route::put('/clinics/{clinicId}/auto-reply', [ClinicController::class, 'updateAutoReply']);
        Route::get('/clinics/{clinicId}/auto-reply', [ClinicController::class, 'getAutoReply']);

        //My Dashboard
        Route::post('my-dashboard',[DashboardController::class,'myDashboard']);
        Route::post('sales-summary',[DashboardController::class,'salesSummary']);
        Route::post('leads-by-sources',[DashboardController::class,'leadsBySources']);
        Route::post('dashboard-nurturing',[DashboardController::class,'dashboardNurturing']);

        //Email Controller
//        Route::get('get-inboxes',[EmailController::class,'getInboxes']);
//        Route::get('check-inboxes',[EmailController::class,'checkInboxes']);
        Route::post('show-inbox',[EmailController::class,'showInbox']);
        Route::post('/sms', [ChatController::class, 'handleSMS']);
        Route::post('/email', [ChatController::class, 'handleEmail']);
        Route::post('show-send-email',[EmailController::class,'showSendEmail']);
        Route::post('show-sent-email',[EmailController::class,'showSentEmail']);
        Route::post('send-email',[EmailController::class,'sendEmail']);
        Route::post('read-received-email',[EmailController::class,'readReceivedEmail']);
        Route::post('create-inbox',[EmailController::class,'createInbox']);
        Route::post('search-email',[EmailController::class,'searchEmail']);
        Route::post('reply-email',[EmailController::class,'replyEmail']);
        Route::post('received-email-list',[EmailController::class,'receivedEmailList']);
        Route::post('sent-email-list',[EmailController::class,'sentEmailList']);
        Route::post('sent-email-thread',[EmailController::class,'sentEmailThread']);
        Route::post('email-thread',[EmailController::class,'emailThread']);

        Route::post('/templates/store', [TemplateController::class, 'store']);
        Route::post('/templates/update/{id}', [TemplateController::class, 'update']);
        Route::post('/templates/delete/{id}', [TemplateController::class, 'destroy']);
        Route::get('/templates', [TemplateController::class, 'index']);

        //Appointments
        Route::post('appointments/find', [AppointmentController::class, 'index']);
        Route::post('appointments/update', [AppointmentController::class, 'update']);
        Route::post('services', [AppointmentServiceController::class, 'create']);

        //Support Controller
        Route::post('submit-ticket', [SupportController::class, 'submitTicket']);

        Route::post('/upload-clinic-logo', [ClinicController::class, 'uploadLogo']);
        Route::post('/upload-image', [ClinicController::class, 'uploadImage']);

        Route::post('/update-toggles', [ClinicController::class, 'updateToggles']);

        Route::post('/get-automation-campaign',[ClinicController::class, 'getAutomationCampaign']);


        Route::post('get_automation_template',[ClinicController::class,'getAutomationTemplate']);
        Route::post('manageautomationTemplate',[ClinicController::class,'manageautomationTemplate']);

        //2fa
        Route::post('/2fa/enable', [TwoFactorController::class, 'enable2FA']);
        Route::post('/2fa/disable', [TwoFactorController::class, 'disable2FA']);
        Route::post('/2fa/generate', [TwoFactorController::class, 'generateCode']);
        Route::post('/2fa/verify', [TwoFactorController::class, 'verifyCode']);

        Route::post('get_immediate_template',[ClinicController::class,'getImmediateTemplate']);
        Route::post('manageimmediateTemplate',[ClinicController::class,'manageimmediateTemplate']);
        Route::post('toggleimmediateTemplateStatus',[ClinicController::class,'toggleimmediateTemplateStatus']);
        Route::get('getToggleValues', [ClinicController::class, 'getToggleValues']);


       // Route::get('/tags', [TagController::class, 'index']);
        Route::get('/tags', [TagController::class,'getTagsByClinicId']);
        Route::post('/tags', [TagController::class, 'store']);
        Route::put('/tags/{id}', [TagController::class, 'update']);
        Route::delete('/tags/{tagId}/{leadId}', [TagController::class, 'softDelete']);
        Route::post('/autocomplete', [TagController::class, 'fetchAutocompleteSuggestions']);

        //Notifications
        Route::post('/notifications/list', [NotificationsController::class, 'list']);
        Route::post('/notifications/read', [NotificationsController::class, 'read']);
        Route::post('/notifications/readAll', [NotificationsController::class, 'readAll']);
        Route::get('/notifications/count', [NotificationsController::class, 'count']);
        Route::post('/notifications/delete', [NotificationsController::class, 'delete']);
        Route::post('/notifications/deleteAll', [NotificationsController::class, 'deleteAll']);

        Route::get('/audit-log/differences/{subject_id}', [PatientController::class, 'getDifferences']);
        Route::get('/audit-logs/field-updates/{subjectId}', [PatientController::class, 'getFieldUpdates']);

        Route::post('/assistants/create', [AssistantFileController::class, 'createAssistant']);
        Route::get('/assistant/{id}', [AssistantFileController::class, 'getAssistantById']);
        Route::get('/help/assistant', [HelpAssistantController::class, 'getHelpAssistantById']);
        Route::post('/assistant/upload-file', [AssistantFileController::class, 'uploadFile']);
        Route::get('/assistant/{assistantId}/files', [AssistantFileController::class, 'getFiles']);
        Route::delete('/clinic/{clinicId}/delete-file/assistant/{assistantId}/files/{fileId}/{fileName}', [AssistantFileController::class, 'deleteFile']);
        Route::delete('/delete-all-files/clinic/{clinicId}/{assistantId}/files', [AssistantFileController::class, 'deleteAllVectorStoreFiles']);
        Route::get('/clinic/{clinicId}/assistant/{assistantId}/files/{fileId}/download', [AssistantFileController::class, 'downloadFile']);
        Route::post('/assistant/{assistantId}', [AssistantFileController::class, 'updateAssistant']);
        Route::get('/clinics/{clinicId}/instructions', [AssistantFileController::class, 'fetchInstructions']);

        Route::put('/clinics/{clinicId}/instructions', [AssistantFileController::class, 'updateEmailSmsInstructions']);
        Route::post('/submit-url', [ScrapedDataController::class, 'submitUrl']);
        Route::get('/list-urls', [ScrapedDataController::class, 'listUrls']);
        Route::get('/download-file/{id}', [ScrapedDataController::class, 'downloadFile']);
        Route::delete('/delete-urls', [ScrapedDataController::class, 'deleteUrls']);
        Route::post('/assistant/{assistantId}/ask-assistant', [ChatbotController::class, 'askAssistant']);
        Route::post('/help/assistant/ask-assistant', [HelpAssistantController::class, 'askHelpAssistant']);
        Route::get('/threads/{assistantId}', [ThreadController::class, 'listThreads']);
        Route::get('/thread/{threadId}', [ThreadController::class, 'getThreadHistory']);

        Route::post('/activate-video-consultation', [WherebyController::class, 'activateClinic']);
        Route::get('/clinic/{clinic_id}/activation', [WherebyController::class, 'getActivationState']);

        Route::post('/clinic/elastic-email/accounts', [ElasticEmailController::class, 'createAccountWithSMTP']);
        Route::post('/clinic/configure-aws-email', [EmailConfigController::class, 'configureEmailReceiving']);

        Route::post('/recent-leads', [DashboardController::class, 'getRecentLeads']);
        Route::post('/recent-leads-count', [DashboardController::class, 'getRecentLeadsCount']);

        Route::get('/voice_agent/clinic/{clinic_id}', [VapiClinicController::class, 'show']);
        Route::post('/voice_agent/clinic/{clinic_id}', [VapiClinicController::class, 'storeOrUpdate']);
    });

});

Route::post('smswebhook/{key}', [ChatController::class, 'pending'])->name('sms.crtxinbound');
Route::post('clinic/email-notification', [ClinicController::class, 'emailNotification']);
Route::post('outbound-calling-webhook', [OutboundCallingWebhookController::class, 'handleOutboundCallingWebhook']);

if (App::environment('production'))
{
    URL::forceScheme('https');
}



