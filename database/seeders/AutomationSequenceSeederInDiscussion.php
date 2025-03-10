<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Automationsequence;


class AutomationSequenceSeederInDiscussion extends Seeder
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
                'status_id'         => '5',
                'text_template'     => "Hold",
                'email_subject'     => 'Hold',
                'email_template'    => "Hold",
            ],
            [
                'dayinterval'       => '2',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '3',
                'status_id'         => '5',
                'text_template' => "Hi {{REPLACE_FIRST_NAME}}! Still thinking about dental implants? {{REPLACE_DOCTOR}} at {{REPLACE_PRACTICE}} is here to answer your questions and ease your concerns. Let’s chat! {{REPLACE_PHONE}}",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '4',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '5',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => "Enhance Your Smile and Life with {{REPLACE_DOCTOR}}",
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}}, We understand that choosing dental implants is a significant decision. Remember, opting for dental implants with {{REPLACE_DOCTOR}} at {{REPLACE_PRACTICE}} means investing in your smile\'s future and overall well-being.
                <br/><br/>
                    {{REPLACE_DOCTOR}}’ expertise and our advanced technology ensure a seamless, comfortable experience with results that speak for themselves. Don’t let uncertainty hold you back from the smile you deserve.
                    <br/><br/>  
                    Questions? We\'re here to help every step of the way.Schedule your consultation here: <a href="{{Link}}"></a>
                    <br/><br/>
                    With Smiles,
                    <br/><br/>
                    {{REPLACE_SIGNATURE}}
                    <br/><br/>
                    <a href="{{unsubscribe}}">Unsubscribe</a>',
            ],
            [
                'dayinterval'       => '6',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '7',
                'status_id'         => '5',
                'text_template' => "Hi {{REPLACE_FIRST_NAME}}! Make your smile dreams a reality with {{REPLACE_DOCTOR}}. Experience, care, and the smile you deserve await at {{REPLACE_PRACTICE}}. Ready to talk? {{REPLACE_PHONE}}",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '8',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '9',
                'status_id'         => '5',
                'text_template'     => "Hold",
                'email_subject'     => 'Discover the Long-Term Benefits of Dental Implants',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}}, Remember, dental implants are more than a cosmetic solution; they\'re a long-term investment in your quality of life. Enjoy your favorite foods, speak with ease, and smile confidently, all thanks to dental implants durable, natural-looking results.
                <br/><br/>
                With {{REPLACE_DOCTOR}}’s extensive experience and our state-of-the-art technology, your journey to a perfect smile is in the best hands.
                <br/><br/>
                Ready to take the next step? We’re here for you.
                
                {{REPLACE_SIGNATURE}} <br/><br/> <a href="{{unsubscribe}}">Unsubscribe</a>',
            ],            
            [
                'dayinterval'       => '10',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '11',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '12',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '13',
                'status_id'         => '5',
                'text_template' => "Hi {{REPLACE_FIRST_NAME}}! Dental implants = A lifetime of smiles. {{REPLACE_DOCTOR}} can show you how. Don’t miss out on the benefits. Discover more: {{REPLACE_PHONE}}",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '14',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '15',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '16',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '17',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '18',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '19',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '20',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '21',
                'status_id'         => '5',
                'text_template' => "Hi {{REPLACE_FIRST_NAME}}! Your perfect smile is just an appointment away. Don’t let doubts hold you back. {{REPLACE_DOCTOR}} and {{REPLACE_PRACTICE}} are here for you. Book now: {{REPLACE_PHONE}}",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '22',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '23',
                'status_id'         => '5',
                'text_template' => "Hold",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '24',
                'status_id'         => '5',
                'text_template'     => "Hold",
                'email_subject'     => 'Your New Smile Awaits at {{REPLACE_PRACTICE}}',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}}, We noticed you\'re still on the fence about dental implants. It\'s a big decision, but remember, {{REPLACE_DOCTOR}} and the team at {{REPLACE_PRACTICE}} are committed to your comfort and confidence.
                <br/><br/>
                With flexible scheduling and a supportive, informative process, we make it easy to take the first step towards the smile you’ve always wanted. Let’s make it happen together.
                <br/><br/>
                {{REPLACE_SIGNATURE}} <br/><br/> <a href="{unsubscribe}">Unsubscribe</a>',
            ],
            
            
        ];

        foreach ($sequences as $sequenceData) {
            AutomationSequence::create($sequenceData);
        }
    }
}
