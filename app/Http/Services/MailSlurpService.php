<?php

namespace App\Http\Services;

use MailSlurp\Apis\EmailControllerApi;
use MailSlurp\Configuration;
use MailSlurp\Apis\InboxControllerApi;
use MailSlurp\Apis\SentEmailsControllerApi;
use MailSlurp\Models\SendEmailOptions;
class MailSlurpService
{
    private Configuration $config;

    private InboxControllerApi $inboxController;

    private EmailControllerApi $emailController;

    private SentEmailsControllerApi $sentEmailsController;

    public function __construct()
    {
        
        $this->config = Configuration::getDefaultConfiguration()->setApiKey('x-api-key', config('mailslurp.api_key'));

        $this->inboxController = new InboxControllerApi(null, $this->config);

        $this->emailController = new EmailControllerApi(null, $this->config);

        $this->sentEmailsController = new SentEmailsControllerApi(null, $this->config);
        
    }

    public function getInboxes()
    {
        return $this->inboxController->getAllInboxes();
    }

    public function createInbox($name): string
    {
        return $this->inboxController->createInbox($name, null, $name);
    }

    public function getInbox($inbox)
    {
        return $this->inboxController->getInbox($inbox);
    }
    public function getRawEmailContents($email_id)
    {
        return $this->emailController->getRawEmailJson($email_id);
    }

    public function getInboxEmails($inbox)
    {
        return $this->inboxController->getEmails($inbox);
    }

    public function getInboxEmailsPaginated($inbox, $page, $size, $sort='',$unread_only='',$search_filter)
    {
        return $this->emailController->getEmailsPaginated($inbox, $page, $size, $sort, $unread_only, $search_filter);
    }

    public function getSentEmails($inbox)
    {
        return $this->inboxController->getInboxSentEmails($inbox);
    }

    public function getEmail($email)
    {
        return $this->emailController->getEmail($email);
    }

    public function getSentEmailContent($email)
    {
        return $this->sentEmailsController->getSentEmail($email);
    }

    public function getSentEmailRawContent($email)
    {
        return $this->sentEmailsController->getRawSentEmailContents($email);
    }

    public function sendEmail($inbox, $data)
    {
        $to = $data['to'];
        $subject = $data['subject'];
        $body = $data['body'];
        $cc = $data['cc'] ?? '';
        $bcc = $data['bcc'] ?? '';

        $sendOptions = new SendEmailOptions();
        $sendOptions->setTo([$to]);
        if($bcc){
            $sendOptions->setBcc([$bcc]);
        }
        if($cc){
            $sendOptions->setCc([$cc]);
        }
        $sendOptions->setSubject($subject);
        $sendOptions->setBody($body);
        $sendOptions->setIsHTML(true);

        return $this->inboxController->sendEmail($inbox, $sendOptions);
    }

    public function getInboxEmailsSearch($inbox, $page, $size, $sort, $unread_only, $search = '')
    {
        return $this->emailController->getEmailsPaginated(
            $inbox,
            $page,
            $size,
            $sort,
            $unread_only,
            $search
        );
    }

    public function getSentEmailsSearch($inbox, $search = '')
    {
        return $this->inboxController->getInboxSentEmails(
            $inbox,
            0,
            100,
            null,
            $search
        );
    }

    public function replyToEmail($inbox, $reply_to_email_options = '')
    {
        return $this->emailController->replyToEmail(
            $inbox,
            $reply_to_email_options
        );
    }
}
