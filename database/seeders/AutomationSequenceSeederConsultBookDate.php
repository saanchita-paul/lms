<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Automationsequence;


class AutomationSequenceSeederConsultBookDate extends Seeder
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
                'status_id'         => '12',
                'text_template'     => "Excited for your consultation with {{REPLACE_DOCTOR}} at {{REPLACE_PRACTICE}}, {{REPLACE_FIRST_NAME}}! We're ready to guide you through the journey to a perfect smile. 😊",
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],            
            [
                'dayinterval'       => '2',
                'status_id'         => '12',
                'text_template' => "Hold",
                'email_subject' => 'Get Ready for Your Smile Transformation Journey!',
                'email_template' => "Hi {{REPLACE_FIRST_NAME}},  We're thrilled you've scheduled your consultation with {{REPLACE_DOCTOR}} at {{REPLACE_PRACTICE}}! This first step is crucial in exploring how dental implants can revolutionize not just your smile but your overall quality of life.
                <br/><br/>
                With her extensive experience and patient-focused care, {{REPLACE_DOCTOR}} is excited to meet you and discuss how we can achieve the smile you've always dreamed of. Remember, we offer state-of-the-art solutions and personalized care tailored to your needs.
                <br/><br/>
                Looking forward to your visit.
                <br/><br/>
                The {{REPLACE_PRACTICE}} Team",
            ],
            [
                'dayinterval'       => '3',
                'status_id'         => '12',
                'text_template' => "{{REPLACE_DOCTOR}} combines expertise & compassion to transform smiles with dental implants. Your dream smile awaits at {{REPLACE_PRACTICE}}! See you soon.",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '4',
                'status_id'         => '12',
                'text_template'     => "Hold",
                'email_subject'     => 'Discover the Difference with {{REPLACE_DOCTOR}}',
                'email_template'    => "Hi {{REPLACE_FIRST_NAME}},  As you prepare for your consultation, know that choosing {{REPLACE_DOCTOR}} for your dental implants means selecting expertise, compassion, and a track record of transformative smiles.
                <br/><br/>
                {{REPLACE_PRACTICE}} prides itself on utilizing the latest in dental technology, ensuring your treatment is effective and comfortable. {{REPLACE_DOCTOR}} and our team are committed to making your dental goals a reality.
                <br/><br/>
                See you soon,
                <br/><br/>
                The {{REPLACE_PRACTICE}} Team",
            ],            
            [
                'dayinterval'       => '5',
                'status_id'         => '12',
                'text_template' => "Thinking about dental implants? They're a game-changer for your smile and health. Learn about the benefits and our financing options at your consultation. 🦷💬",
                'email_subject' => 'Hold',
                'email_template' => "Hold",
            ],
            [
                'dayinterval'       => '6',
                'status_id'         => '12',
                'text_template' => "Hold",
                'email_subject' => 'The Lasting Benefits of Choosing Dental Implants',
                'email_template' => "Hi {{REPLACE_FIRST_NAME}},  Dental implants are more than a dental procedure; they're a life-changing investment in your health, function, and self-confidence. As you look forward to your consultation, remember that dental implants offer a permanent solution for a vibrant smile and improved oral health.
                <br/><br/>
                We're here to provide all the information you need, including flexible financing options, to make this important decision as easy as possible.
                <br/><br/>
                Warm regards,
                <br/><br/>
                The {{REPLACE_PRACTICE}} Team",
            ],
            [
                'dayinterval'       => '7',
                'status_id'         => '12',
                'text_template'     => "Your new smile is within reach, {{REPLACE_FIRST_NAME}}! Ask us about flexible financing options during your consultation. A brighter future smiles ahead. 🌞",
                'email_subject'     => 'Hold',
                'email_template'    => "Hold",
            ],            
            [
                'dayinterval'       => '8',
                'status_id'         => '12',
                'text_template' => "Hold",
                'email_subject' => 'Flexible Financing for Your New Smile',
                'email_template' => "Hi {{REPLACE_FIRST_NAME}},  Your dream smile should never be out of reach. That's why {{REPLACE_PRACTICE}} offers a variety of financing options to fit your budget. During your consultation, we'll explore these options, ensuring you can proceed with your dental implants without financial stress.
                <br/><br/>
                {{REPLACE_DOCTOR}} and our team believe everyone deserves a smile they're proud to show off. Let's make it happen together.
                <br/><br/>
                Best wishes,
                <br/><br/>
                The {{REPLACE_PRACTICE}} Team",
            ],            
        ];

        foreach ($sequences as $sequenceData) {
            AutomationSequence::create($sequenceData);
        }
    }
}
