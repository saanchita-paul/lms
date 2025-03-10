<?php

namespace App\Services;
use App\Models\Template;


class TemplateService
{
    public function replacePlaceholders($value, $clinicValues)
    {
        $phoneNumber = ($clinicValues['lead_center'] != "Yes") ? $clinicValues['office_number'] : $clinicValues['hotline_phone_number'];


        $replacements = [
            '{{REPLACE_DOCTOR}}' => $clinicValues['dr_name'],
            '{{REPLACE_PRACTICE}}' => $clinicValues['clinic_name'],
            '{{REPLACE_PHONE}}' => $phoneNumber,
            '{{REPLACE_LINK_1}}' => $clinicValues['link1'],
            '{{REPLACE_LINK_2}}' => $clinicValues['link2'],
            '{{REPLACE_LINK_3}}' => $clinicValues['link3'],
            '{{REPLACE_WEBSITE}}' => $clinicValues['microsite_website'],
            '{{REPLACE_SIGNATURE}}' => "The Dental Implant Team at " . $clinicValues['clinic_name'] . " " . $phoneNumber,
        ];

         // Check if $value is an object or an array and handle accordingly
        if (is_object($value)) {
            foreach ($replacements as $placeholder => $replacement) {
                if (isset($value->text_template)) {
                    $value->text_template = str_replace($placeholder, $replacement, $value->text_template);
                }
                if (isset($value->email_template)) {
                    $value->email_template = str_replace($placeholder, $replacement, $value->email_template);
                }
                if (isset($value->email_subject)) {
                    $value->email_subject = str_replace($placeholder, $replacement, $value->email_subject);
                }
            }
        } elseif (is_array($value)) {
            foreach ($replacements as $placeholder => $replacement) {
                if (isset($value['text_template'])) {
                    $value['text_template'] = str_replace($placeholder, $replacement, $value['text_template']);
                }
                if (isset($value['email_template'])) {
                    $value['email_template'] = str_replace($placeholder, $replacement, $value['email_template']);
                }
                if (isset($value['email_subject'])) {
                    $value['email_subject'] = str_replace($placeholder, $replacement, $value['email_subject']);
                }
            }
        }

        return $value;
    }

    public function getTemplateByClinicAndType(int $clinicId, string $type): ?Template
    {
        return Template::where('clinic_id', $clinicId)
            ->where('type', $type)
            ->first();
    }

    public function appointmentreplacePlaceholders(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $template = str_replace($placeholder, $value, $template);
        }
        return $template;
    }

}
