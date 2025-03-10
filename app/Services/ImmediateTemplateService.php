<?php 

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Clinic;
use App\Models\ManageTemplate;
use App\Services\TemplateService;
use App\Services\ImmediateTemplateService; // Assuming this exists as per your code

class ImmediateTemplateService
{
    public function getImmediateTemplate($clinic_id)
    {

        
        $autosequencData = DB::table('automationsequence')->where('dayinterval', 0)->select('*')->orderBy('id')->get();
        

        $records = [];
        $clinicdata = Clinic::find($clinic_id);
       
        $clinicValues = $clinicdata->getAttributes();
        $nurturing_display_name = '';
        $scheduling_link = '';

        foreach ($autosequencData as $value) {
            $manageData = $this->checkRecordExistsImmediate($clinic_id, 0);

            if($nurturing_display_name != ''){
                $value->text_template = str_replace('Grace',$nurturing_display_name,$value->text_template);
            }
            if($scheduling_link != ''){
                $value->text_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->text_template);
                $value->email_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->email_template);
            }
            $templateService = new TemplateService();
            $value = $templateService->replacePlaceholders($value, $clinicValues);            

            $record = [
                'id'          => $manageData['id'] ?? $value->id,
                'dayinterval' => 0,
                'status_id' => null,
                'text_template' => $manageData['text_template'] ?? $value->text_template,
                'email_subject' => $manageData['email_subject'] ?? $value->email_subject,
                'email_template' => $manageData['email_template'] ?? $value->email_template,
            ];
            $records[] = (object) $record;
        }

        return collect($records);
    }

    public function checkRecordExistsImmediate($clinic_id, $dayinterval)
    {
        $manageRecord = ManageTemplate::where('clinic_id', $clinic_id)
            ->where('dayinterval', $dayinterval)
            ->orderBy('id')
            ->first();

        $clinicdata = Clinic::find($clinic_id);
        $clinicValues = $clinicdata->getAttributes();

        $templateService = new TemplateService();
        $manageRecord = $templateService->replacePlaceholders($manageRecord, $clinicValues);


        if ($manageRecord) {
            return [
                'id'          =>  $manageRecord->id,
                'dayinterval' => $manageRecord->dayinterval,
                'status_id'   => $manageRecord->status_id,
                'text_template' => $manageRecord->text_template,
                'email_subject' => $manageRecord->email_subject,
                'email_template' => $manageRecord->email_template,
            ];
        }

        // If no record is found, you can return null or an empty array as needed.
        return [];
    }
}



?>