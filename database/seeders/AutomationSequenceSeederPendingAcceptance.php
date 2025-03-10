<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Automationsequence;


class AutomationSequenceSeederPendingAcceptance extends Seeder
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
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '2',
                'status_id'         => '15',
                'text_template'     => 'Thank you for visiting {{REPLACE_PRACTICE}}, {{REPLACE_FIRST_NAME}}! Remember, {{REPLACE_DOCTOR}} and our team are here to guide you to your perfect smile. Please contact us with any questions about financing options to fit your budget.',
                'email_subject'     => 'Take the Next Step Towards Your Ideal Smile with {{REPLACE_DOCTOR}}',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},  Thank you for visiting {{REPLACE_PRACTICE}} for your consultation. We hope you left feeling informed and excited about the possibilities dental implants offer to enhance your smile and life.
                <br/><br/>
        {{REPLACE_DOCTOR}} and our team are dedicated to providing exceptional results and care that makes you feel valued and understood. Remember, investing in dental implants with us is investing in the highest standard of care and technology.
        <br/><br/>
        Considering your options? We\'re here to answer any further questions you may have, especially regarding our flexible financing options designed to make your decision easier.',
            ],
            [
                'dayinterval'       => '3',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '4',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '5',
                'status_id'         => '15',
                'text_template'     => 'Your New Smile Awaits - Let’s Make It Happen Together',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},  We understand that choosing dental implants is a big decision. If you’re still contemplating, remember that a brighter, healthier smile can significantly enhance your quality of life and confidence.
                <br/><br/>
        {{REPLACE_DOCTOR}} and the {{REPLACE_PRACTICE}} team are here to support you every step of the way, with flexible financing options to ease any concerns. Let us help you achieve the smile you\'ve always wanted.
        <br/><br/>
        Ready to proceed or have questions? We’re just a call or click away.',
            ],
            [
                'dayinterval'       => '6',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '7',
                'status_id'         => '15',
                'text_template'     => 'Hi {{REPLACE_FIRST_NAME}}! Still considering your smile options? {{REPLACE_PRACTICE}} offers unmatched expertise and technology, plus flexible financing to make your decision easier. Let’s reconnect!',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '8',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '9',
                'status_id'         => '15',
                'text_template'     => 'The {{REPLACE_PRACTICE}} Difference: Unmatched Care & Technology',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hi {{REPLACE_FIRST_NAME}},  As you consider your options for dental implants, remember what sets {{REPLACE_PRACTICE}} apart: {{REPLACE_DOCTOR}}\'s extensive experience, our state-of-the-art technology, and a patient-centric approach that ensures your comfort and satisfaction.
                <br/><br/>
        We understand that the decision involves both financial and emotional considerations. That’s why we offer financing options to help make your dream smile a reality without the stress.
        <br/><br/>
        If you have questions about the treatment plan that fits your needs and budget, reach out to us!',
            ],
            [
                'dayinterval'       => '10',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '11',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '12',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '13',
                'status_id'         => '15',
                'text_template'     => 'Hold',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
            [
                'dayinterval'       => '14',
                'status_id'         => '15',
                'text_template'     => 'Ready to take the next step towards your ideal smile? {{REPLACE_PRACTICE}}\'s team is here to help by answering any remaining questions. Reach out to us anytime.',
                'email_subject'     => 'Hold',
                'email_template'    => 'Hold',
            ],
               
        ];

        foreach ($sequences as $sequenceData) {
            AutomationSequence::create($sequenceData);
        }
    }
}
