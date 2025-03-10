<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Automationsequence;


class AutomationSequenceSeederNoshowed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sequences = [
            [
                'dayinterval'       => '1',
                'status_id'         => '13',
                'text_template'     => "Hi {{REPLACE_FIRST_NAME}}, we noticed you missed your consultation at {{REPLACE_PRACTICE}}. No worries! Let’s find a new time for you to meet {{REPLACE_DOCTOR}}. When’s good for you? 📅",
                'email_subject'     => 'Let’s Reschedule Your Smile Consultation',
                'email_template'    => "Hi {{REPLACE_FIRST_NAME}},  We noticed you weren’t able to make it to your consultation, and we hope everything is okay. At {{REPLACE_PRACTICE}}, we understand that life can be unpredictable and schedules change. {{REPLACE_DOCTOR}} and our team are here to accommodate you and ensure your journey to a perfect smile is seamless and stress-free.<br/><br/>

                Would you like to reschedule your appointment? We’re eager to discuss the transformative benefits of dental implants with you and explore how they can enhance your life.<br/><br/>
                
                Let us know a time that works for you, {{REPLACE_PHONE}}<br/><br/>
                
                The {{REPLACE_PRACTICE}} Team",
            ],            
            [
                'dayinterval'       => '2',
                'status_id'         => '13',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '3',
                'status_id'         => '13',
                'text_template' => "Missed your appointment, {{REPLACE_FIRST_NAME}}? Let’s reschedule so you don’t miss out on learning about life-changing dental implants. {{REPLACE_DOCTOR}} is ready when you are! 🦷✨",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '4',
                'status_id'         => '13',
                'text_template'     => "Hold",
                'email_subject'     => 'Reschedule Your Appointment with {{REPLACE_DOCTOR}}',
                'email_template'    => "Hi {{REPLACE_FIRST_NAME}},  Your dream smile is just one consultation away. If you missed your appointment with {{REPLACE_DOCTOR}}, don’t worry—we’re ready to find a new time that fits your schedule.<br/><br/>

                Dental implants can significantly improve your quality of life, offering a durable, natural-looking solution for missing teeth. Plus, our flexible financing options make your new smile more accessible than ever.<br/><br/>
                
                Let’s pick up where we left off, call us to reschedule {{REPLACE_PHONE}}<br/><br/>
                
                The {{REPLACE_PRACTICE}} Team",
            ],            
            [
                'dayinterval'       => '5',
                'status_id'         => '13',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '6',
                'status_id'         => '13',
                'text_template' => "Life gets busy, we understand! If you need to reschedule your dental implant consultation with {{REPLACE_DOCTOR}}, just let us know. Your new smile awaits! 😊",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '7',
                'status_id'         => '13',
                'text_template'     => "Hold",
                'email_subject'     => 'Hold',
                'email_template'    => "Hold",
            ],            
            [
                'dayinterval'       => '8',
                'status_id'         => '13',
                'text_template' => "Hold",
                'email_subject' => 'Flexible Rescheduling for Your Dental Implant Consultation',
                'email_template' => "Hi {{REPLACE_FIRST_NAME}},  We understand that things don’t always go as planned, which is why we’re here to offer our support and flexibility. If you missed your consultation with {{REPLACE_DOCTOR}}, we’d love to help you reschedule at a time that’s more convenient for you.<br/><br/>

                Explore the benefits of dental implants and learn about our state-of-the-art treatment options in a friendly, no-pressure environment. Your health and comfort are our top priorities.<br/><br/>
                
                Looking forward to welcoming you, {{REPLACE_PHONE}}<br/><br/>
                
                The {{REPLACE_PRACTICE}} Team",
            ],
            [
                'dayinterval'       => '9',
                'status_id'         => '13',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],            
            [
                'dayinterval'       => '10',
                'status_id'         => '13',
                'text_template'     => "Hi {{REPLACE_FIRST_NAME}}, we hope everything’s okay. Ready to reschedule your smile consultation with {{REPLACE_DOCTOR}} at {{REPLACE_PRACTICE}}? Your dream smile is just an appointment away.",
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],        
            [
                'dayinterval'       => '11',
                'status_id'         => '13',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '12',
                'status_id'         => '13',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '13',
                'status_id'         => '13',
                'text_template' => "Hold",
                'email_subject' => 'Your Consultation with {{REPLACE_DOCTOR}} – Easy Rescheduling',
                'email_template' => "Hi {{REPLACE_FIRST_NAME}},  It seems your consultation at {{REPLACE_PRACTICE}} didn’t go as planned. We’re reaching out to remind you that a beautiful, functional smile is within reach, and we’re here to make the process as smooth as possible.
                <br/><br/>
                {{REPLACE_DOCTOR}} looks forward to discussing how dental implants can transform your smile. Plus, our flexible financing options make it easy to invest in your health and confidence.
                <br/><br/>
                Ready to reschedule? We’re just a call away, {{REPLACE_PHONE}}
                <br/><br/>
                The {{REPLACE_PRACTICE}} Team",
            ],            
        ];

        foreach ($sequences as $sequenceData) {
            AutomationSequence::create($sequenceData);
        }
    }
}
