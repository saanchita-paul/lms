<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PatientCampaign;


class PatientJourneyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $crmStatuses = [
            [
                'id'         => 1,
                'dayinterval'       => '1',
                'text_template' => "Hi {{REPLACE_NAME}}, just wanted to let you know we're excited to meet you for your dental implant consultation. Our team is here to help you achieve a beautiful, healthy smile. If you have any questions, please don't hesitate to call us at {{REPLACE_PHONE}}. See you soon!",
                'email_subject' => 'Welcome to our dental implants consultation!',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    Thank you for booking a consultation with us for dental implants. We are excited to have the opportunity to meet you and discuss how we can help you improve your dental health.
                                    <br/><br/>
                                    In this email, we wanted to give you a brief introduction to our practice and what you can expect during your consultation. Our practice specializes in dental implants, and we have helped many patients achieve a beautiful, healthy smile. Our team of experts is committed to providing personalized, high-quality care to each of our patients.
                                    <br/><br/>
                                    During your consultation, we will assess your dental needs and discuss the best treatment options for you. We will also provide you with a detailed explanation of the dental implant procedure and answer any questions you may have.
                                    <br/><br/>
                                    We look forward to meeting you soon.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 2,
                'dayinterval'       => '2',
                'text_template' => "Hi {{REPLACE_NAME}}, just a reminder that your upcoming dental implant consultation will be with {{REPLACE_DOCTOR}}, our dental implant specialist. We've attached a video of {{REPLACE_DOCTOR}} discussing dental implants and what to expect during your consultation. Take a moment to watch it if you can. You're in great hands, and we can't wait to help you achieve a beautiful, healthy smile.",
                'email_subject' => 'Meet your dental implant specialist',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    We are pleased to introduce you to our dental implant specialist, {{REPLACE_DOCTOR}}. {{REPLACE_DOCTOR}} has extensive experience in dental implant surgery and has helped many patients achieve successful outcomes.
                                    <br/><br/>
                                    We believe that it's important for you to get to know {{REPLACE_DOCTOR}} before your consultation. As such, we've attached a video of {{REPLACE_DOCTOR}} discussing dental implants and what to expect during your consultation. If you have a chance, please take a moment to watch it.
                                    <br/><br/>
                                    During your consultation, {{REPLACE_DOCTOR}} will evaluate your dental needs and develop a personalized treatment plan for you. You can rest assured that you are in capable hands and will receive the highest level of care.
                                    <br/><br/>
                                    If you have any questions or concerns before your appointment, please do not hesitate to contact us.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 3,
                'dayinterval'       => '3',
                'text_template' => "Hi {{REPLACE_NAME}}, just a friendly reminder to bring a list of your medications and your insurance card to your upcoming dental implant consultation. We want to make sure you're comfortable and prepared for your appointment.",
                'email_subject' => 'Getting ready for your dental implant consultation',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    We hope you are looking forward to your dental implant consultation. To help you prepare for your appointment, we have put together some helpful tips:
                                    <br/><br/>
                                    Bring a list of any medications you are currently taking.
                                    <br/><br/>
                                    If you have dental insurance, please bring your insurance card with you.
                                    <br/><br/>
                                    Wear comfortable clothing and avoid wearing any jewelry or accessories that may get in the way during your examination.
                                    <br/><br/>
                                    If you have any questions or concerns before your appointment, please don't hesitate to contact us.
                                    We are excited to meet you and discuss how we can help you achieve a beautiful, healthy smile.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 4,
                'dayinterval'       => '4',
                'text_template' => "Hi {{REPLACE_NAME}}, just a quick reminder of what to expect during your upcoming dental implant consultation. Our team will review your medical and dental history, examine your mouth, teeth, and gums, and discuss the dental implant procedure with you. We'll also provide you with a personalized treatment plan and discuss the costs associated with your treatment. If you have any questions, please don't hesitate to call us at {{REPLACE_PHONE}}. We look forward to seeing you soon!",
                'email_subject' => 'What to expect during your dental implant consultation',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    We understand that you may have some questions about what will happen during your dental implant consultation. Here's what you can expect:
                                    <br/><br/>
                                    We will review your medical and dental history to ensure that dental implants are the right treatment option for you.
                                    <br/><br/>
                                    We will examine your mouth, teeth, and gums to assess your dental health.
                                    <br/><br/>
                                    We will discuss the dental implant procedure in detail and answer any questions you may have.
                                    <br/><br/>
                                    We will provide you with a personalized treatment plan and discuss the costs associated with your treatment.
                                    <br/><br/>
                                    We are committed to providing you with the highest level of care and ensuring that you are comfortable throughout your consultation.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 5,
                'dayinterval'       => '5',
                'text_template' => "Hi {{REPLACE_NAME}}, we wanted to share a testimonial from one of our dental implant patients: {{REPLACE_TESTIMONIAL}} We're excited to help you achieve a healthy, beautiful smile!",
                'email_subject' => 'Real stories from our dental implant patients',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    At our practice, we take great pride in providing personalized, high-quality care to each of our patients. But don't just take our word for it! Here's what one of our satisfied dental implant patients had to say:
                                    <br/><br/>
                                    {{REPLACE_TESTIMONIAL}}
                                    <br/><br/>
                                    We hope that hearing from our satisfied patients will help you feel more confident in your decision to choose our practice for your dental implant consultation.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 6,
                'dayinterval'       => '6',
                'text_template' => "Hi {{REPLACE_NAME}}, we use top-notch technology, including {{REPLACE_TECHNOLOGIS}}, to provide you with precise, efficient, and comfortable treatment during your dental implant procedure. See you soon!",
                'email_subject' => 'Top-notch technology for your dental implant consultation',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    At our practice, we believe in investing in the latest technology to provide our patients with the best possible care. That's why we use state-of-the-art equipment and techniques for all of our dental implant procedures.
                                    <br/><br/>
                                    Our technology includes {{REPLACE_TECHNOLOGY}}, which allows us to provide you with precise, efficient, and comfortable treatment. We are confident that our top-notch technology will help you achieve the best possible outcome for your dental implant procedure.
                                    <br/><br/>
                                    We look forward to showing you our technology during your consultation.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 7,
                'dayinterval'       => '7',
                'text_template' => "Hi {{REPLACE_NAME}}, our patients have left us {{REPLACE_PHONE}} positive reviews on Google! We invite you to check them out and see what they have to say about their experiences with us.",
                'email_subject' => 'See what our patients have to say on Google',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    We know that choosing the right dental practice for your needs can be challenging. That's why we encourage all of our patients to share their experiences on Google.
                                    <br/><br/>
                                    We are proud to have {{REPLACE_PHONE}} positive reviews on Google, which is a testament to our commitment to providing personalized, high-quality care to each of our patients.
                                    <br/><br/>
                                    If you haven't already, we invite you to read our reviews on Google to see what our patients have to say about their experiences with us. We are confident that you will feel confident in your decision to choose our practice for your dental implant consultation.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 8,
                'dayinterval'       => '8',
                'text_template' => "Hi {{REPLACE_NAME}}, dental implants can improve your dental health and help you achieve a beautiful, healthy smile. They look and feel like natural teeth, improve your speech and ability to eat, and are a long-term solution to missing teeth. See you soon!",
                'email_subject' => 'The many benefits of dental implants',
                'email_template' => "Dear {{REPLACE_NAME}},
                                    <br/><br/>
                                    Dental implants are an excellent option for patients who are looking to improve their dental health and achieve a beautiful, healthy smile. Here are some of the many benefits of dental implants:
                                    <br/><br/>
                                    They look and feel like natural teeth.
                                    <br/><br/>
                                    They can improve your speech and ability to eat.
                                    <br/><br/>
                                    They are a long-term solution to missing teeth.
                                    <br/><br/>
                                    They can improve your confidence and self-esteem.
                                    During your consultation, we will discuss these and other benefits of dental implants in more detail. We will also develop a personalized treatment plan to help you achieve the best possible outcome.
                                    <br/><br/>
                                    Best regards,
                                    {{REPLACE_SIGNATURE}}",
            ],
            [
                'id'         => 9,
                'dayinterval'       => '9',
                'text_template' => "Hi {{REPLACE_NAME}}, just a friendly reminder of your upcoming dental implant consultation with us on {{REPLACE_DATE}} at {{REPLACE_TIME}}. If you need to cancel or reschedule, please click the following link: [Cancellation/Rescheduling link]. If you have any questions or concerns, please call us at [insert phone number]. See you soon!",
                'email_subject' => 'Confirmation of your dental implant consultation',
                'email_template' => "Dear {{REPLACE_NAME}},
                        <br/><br/>
                        We're looking forward to seeing you at your upcoming dental implant consultation with us. Your appointment is scheduled for {{REPLACE_DATE}} at {{REPLACE_TIME}}, and we want to make sure you're all set.
                        <br/><br/>
                        If you need to make any changes to your appointment or if you cannot attend, please click the link below and follow the prompts to cancel or reschedule.
                        <br/><br/>
                        [Cancellation/Rescheduling link]
                        <br/><br/>
                        If you have any questions or concerns, please do not hesitate to contact us at {{REPLACE_PHONE}}.
                        <br/><br/>
                        We can't wait to help you achieve a beautiful, healthy smile.
                        <br/><br/>
                        Best regards,
                        {{REPLACE_SIGNATURE}}",
            ],
            
        ];

        PatientCampaign::insert($crmStatuses);
    }
}
