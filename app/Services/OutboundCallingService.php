<?php


namespace App\Services;

use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\CrmNote;
use App\Models\Tag;
use App\Models\TagLeadMapping;
use Illuminate\Http\Request;

class OutboundCallingService
{
    public function handleOutboundCallingWebhook(Request $request)
    {
        $clinic = Clinic::where('vapi_assistant_id', $request->vapi_assistant_id)->first();

        if (!$clinic) {
            return [
                'status' => 404,
                'message' => 'Clinic not found',
            ];
        }

        $lead = CrmCustomer::where('phone', $request->phone)
                        ->where('phone_form', '=', 'Web Form')
                        ->where('clinic_id', $clinic->id)
                        ->get()
                        ->last();

        if (!$lead) {
            return [
                'status' => 404,
                'message' => 'Lead not found',
            ];
        }

        $notes = [
            "Outbound Call Status: " . $request->call_status,
            "Outbound Call Suppressed: " . $request->suppressed,
        ];

        foreach ($notes as $noteContent) {
            CrmNote::create([
                'note' => $noteContent,
                'user_id' => CrmNote::USER_ROLE_ADMIN,
                'customer_id' => $lead->id,
            ]);
        }


        $tags = [];
        if (isset($request->tags)) {
            if (is_string($request->tags)) {
                $tags = explode(',', $request->tags);
            } elseif (is_array($request->tags)) {
                $tags = $request->tags;
            }
        }

        foreach ($tags as $tagValue) {
            $tagValue = trim($tagValue);

            if (!empty($tagValue)) {

                $tag = Tag::firstOrCreate([
                    'tagName' => $tagValue,
                    'clinic_id' => $clinic->id,
                ]);

                TagLeadMapping::create([
                    'tag_id' => $tag->id,
                    'lead_id' => $lead->id,
                ]);
            }
        }

        return [
            'status' => 200,
            'data' => [
                'clinic_id' => $clinic->id,
                'lead_id' => $lead->id,
                'tags' => $tags,
            ],
            'message' => 'Outbound Calling Webhook processed successfully',
        ];
    }
}
