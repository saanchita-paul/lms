<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Automationsequence;


class AutomationSequenceSeederCancellation extends Seeder
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
                'status_id'         => '14',
                'text_template'     => 'Hi {{REPLACE_FIRST_NAME}}! Noticed you canceled your appointment at {{REPLACE_PRACTICE}}. Life happens! When would you like to reschedule with {{REPLACE_DOCTOR}}? Your new smile awaits',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '2',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Reschedule Your Path to a Perfect Smile',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},  We saw that you had to cancel your recent appointment at {{REPLACE_PRACTICE}}. We completely understand that sometimes plans need to change. Your journey towards a healthier, brighter smile is important to us, and we\'re here to support you every step of the way.
                <br/><br/>
        Would you like to reschedule your consultation with {{REPLACE_DOCTOR}}? She\'s eager to explore how dental implants can enhance your life, offering the expertise and compassionate care you deserve.
        <br/><br/>
        Let us know a time that suits you, {{REPLACE_PHONE}}
        <br/><br/>
        The {{REPLACE_PRACTICE}} Team',
            ],
            [
                'dayinterval'       => '3',
                'status_id'         => '14',
                'text_template'     => 'Missed your chance to learn about dental implants with {{REPLACE_DOCTOR}}? No worries, {{REPLACE_FIRST_NAME}}. Let’s find a new time that works for you. Ready when you are! 🦷✨',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '4',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '5',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '6',
                'status_id'         => '14',
                'text_template'     => 'Need to reschedule your consultation, {{REPLACE_FIRST_NAME}}? {{REPLACE_PRACTICE}} makes it easy. Discover the benefits of dental implants at a time that suits you. 📅',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'        => '7',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Easy Rescheduling for Your Dental Consultation',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},  Canceling plans is often necessary, but we hope you won’t delay your dream smile journey for too long. {{REPLACE_DOCTOR}} and the team at {{REPLACE_PRACTICE}} are keen to show you how dental implants can not only transform your smile but also boost your confidence and quality of life.
                <br/><br/>
        We offer flexible scheduling and financing options to fit your needs and make this important decision as straightforward as possible.
        <br/><br/>
        Ready to find a new appointment time? {{REPLACE_PHONE}}
        <br/><br/>
        Best,
        <br/><br/>
        The {{REPLACE_PRACTICE}} Team',
            ],
            [
                'dayinterval'       => '8',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'        => '9',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '10',
                'status_id'         => '14',
                'text_template'     => 'Hi {{REPLACE_FIRST_NAME}}, we hope all is well. If you’re still interested in exploring dental implants, {{REPLACE_DOCTOR}} would love to meet you. Let’s reschedule your visit! 😊',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'        => '11',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '12',
                'status_id'         => '14',
                'text_template'     => 'Flexible Options for Your Consultation',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},  We understand your need to cancel your consultation and want to assure you that {{REPLACE_PRACTICE}} prioritizes your convenience and comfort. Let\'s work together to find a new time that fits into your schedule seamlessly.
                <br/><br/>
        {{REPLACE_DOCTOR}} specializes in dental implants that look and feel natural, improving your oral health and self-esteem. And with our accessible financing options, a beautiful smile is more achievable than you might think.
        <br/><br/>
        Looking forward to your visit, [phone[
            <br/><br/>
        The {{REPLACE_PRACTICE}} Team
        
        ',
            ],
            [
                'dayinterval'       => '13',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '14',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'        => '15',
                'status_id'         => '14',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'               => '16',
                'status_id'         => '14',
                'text_template'     => 'Still Interested in Transforming Your Smile?',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},  We noticed your appointment was canceled and wanted to remind you of the opportunity to enhance your smile and well-being with dental implants at {{REPLACE_PRACTICE}}. {{REPLACE_DOCTOR}} is here to guide you through your options in a supportive, pressure-free environment.
                <br/><br/>
        Remember, we’re here to make your path to a new smile as smooth as possible, with flexible scheduling and financing solutions tailored to your needs.
        <br/><br/>
        Let us help you reschedule at your convenience, {{REPLACE_PHONE}}
        <br/><br/>
        The {{REPLACE_PRACTICE}} Team',
            ],             
        ];

        foreach ($sequences as $sequenceData) {
            AutomationSequence::create($sequenceData);
        }
    }
}
