<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Core\Extension;

class UserDefinedFormController_AttachmentExtension extends Extension
{

    /**
    * Remove all attachments if AttachFilesToEmail is false
    * on a per recipient basis
    */
    public function updateEmail($email, $recipient, $emailData)
    {
        if (filter_var($recipient->AttachFilesToEmail, FILTER_VALIDATE_BOOLEAN) !== true) {
            $email->attachments = [];
        }
    }
}
