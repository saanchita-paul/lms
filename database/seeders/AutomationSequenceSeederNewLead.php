<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Automationsequence;


class AutomationSequenceSeederNewLead extends Seeder
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
                'status_id'         => '1',
                'text_template'     => "Hey, {{REPLACE_FIRST_NAME}}, this is Grace from {{REPLACE_PRACTICE}}! We received your inquiry for dental implants. When is a good time to discuss your smile?",
                'email_subject'     => 'Transform Your Smile with {{REPLACE_DOCTOR}}  at {{REPLACE_PRACTICE}}',
                'email_template'    => 'Hi There, {{REPLACE_FIRST_NAME}}, Let\'s hop on the phone and discuss your dental implant options. {{REPLACE_DOCTOR}} at {{REPLACE_PRACTICE}} can\'t wait to meet with you! Please call {{REPLACE_PHONE}} to schedule your consultation. Our team of Dental Implant Experts is available right now to get you on our schedule. At the end of your consultation with our doctors, you will completely understand the next steps and costs of getting your dream smile. Don\'t hesitate to call {{REPLACE_PHONE}}  today!
                <br/><br/>
                If you\'re ready to transform your smile today! Schedule your consultation here: <a href="{{Link}}"></a>
                <br/><br/>
                With Smiles,
                <br/><br/>
                {{REPLACE_SIGNATURE}}
                <br/><br/>
                <a href="{{unsubscribe}}">Unsubscribe</a>',
            ],            
            [
                'dayinterval'       => '2',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '3',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '4',
                'status_id'         => '1',
                'text_template'     => "{{REPLACE_FIRST_NAME}}, hey there! It's Grace again from {{REPLACE_PRACTICE}}, are you free today to have a quick chat about dental implant options? We specialize in smile transformations, check out this video! {{REPLACE_LINK_1}} 
            
                Don't hesitate to schedule your consultation today: https://calendly.com/implanthotline/15min",
                'email_subject'     => 'Did you forget to book your Dental Implant Consultation?',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},
                <br/><br/>
                We are reaching out to get you on the schedule for a dental implant consultation. We know that it\'s not an easy decision to pick a dentist.
                We want you to feel comfortable that you are in the right hands.
                <br/><br/>
                Allow us the honor of getting to know you and help you get the smile of your dreams. Schedule your consultation here: <a href="https://calendly.com/implanthotline/15min">https://calendly.com/implanthotline/15min</a> 
                <br/><br/>
                We are standing by,
                <br/><br/>
                {{REPLACE_SIGNATURE}}
                <br/><br/>
                <a href="{unsubscribe}">Unsubscribe</a>',
            ],            
            [
                'dayinterval'       => '5',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '6',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '7',
                'status_id'         => '1',
                'text_template'     => "Hi there, {{REPLACE_FIRST_NAME}} - can you imagine eating steak again? Or even biting into an apple without pain? Let me know if you're still interested in dental implants.",
                'email_subject'     => 'Still interested?',
                'email_template'    => "Hi {{REPLACE_FIRST_NAME}}! This is the {{REPLACE_PRACTICE}} office. If you’re still interested in transforming your smile, please reach out so we can help you! Our number is: {{REPLACE_PHONE}} or just reply to this email!
                <br/><br/>
                If you're ready to make a change, book your consultation here: <a href='https://calendly.com/implanthotline/15min'>https://calendly.com/implanthotline/15min</a>
                <br/><br/>
                {{REPLACE_SIGNATURE}}
                <br/><br/>
                <a href='{unsubscribe}'>Unsubscribe</a>",
            ],            
            [
                'dayinterval'       => '8',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '9',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],            
            [
                'dayinterval'       => '10',
                'status_id'         => '1',
                'text_template'     => "Hold",
                'email_subject'     => 'An Apple never tasted so good',
                'email_template'    => 'Did you know that Dental implants can restore your teeth back to their natural function? 
                <br/><br/>
                When is the last time you\'ve taken a bite out of a crisp, tasty apple? You are just one consultation away from changing your life, forever. 
                <br/><br/>
                Let\'s get you on the schedule! {{REPLACE_DOCTOR}} at {{REPLACE_PRACTICE}} is ready to help you take a bite out of life. Reply to this email or Call {{REPLACE_PHONE}}.  
                <br/><br/>
                Make the Call.  Change your Smile.  Change your Life.
                <br/><br/>
                Let\'s smile together,
                <br/><br/>
                {{REPLACE_SIGNATURE}}
                <br/><br/>
                <a href="{{unsubscribe}}">Unsubscribe</a>',
            ],        
            [
                'dayinterval'       => '11',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '12',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '13',
                'status_id'         => '1',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '14',
                'status_id'         => '1',
                'text_template'     => "Hold",
                'email_subject'     => '{{REPLACE_DOCTOR}} will see you now!',
                'email_template'    => 'Dear {{REPLACE_FIRST_NAME}}
                <br/><br/>
                My name is {{REPLACE_DOCTOR}}, I\'ve been placing implants now for dozens of years and I have helped thousands of patients achieve the smile they\'ve always wanted. 
                <br/><br/>
                Are you interested in a consultation with me? What\'s holding you back from scheduling a no-risk consultation? 
                <br/><br/>
                I am passionate about dentistry and smile restoration, I will hold your hand through the entire process and help to put your fears at ease.  
                <br/><br/>
                If you\'re interested, then give us a call and let\'s set up some time to chat during your one hour consultation with me. 
                <br/><br/>
                Check out the testimonial below from one of my favorite patients. . . .and then I want to see you next!
                <br/><br/>
                {{REPLACE_LINK_3}}
                <br/><br/>
                {{REPLACE_SIGNATURE}}
                <br/><br/>
                <a href="{unsubscribe}">Unsubscribe</a>',
            ],
        ];

        foreach ($sequences as $sequenceData) {
            AutomationSequence::create($sequenceData);
        }
    }
}
