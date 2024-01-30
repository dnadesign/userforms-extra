<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Core\Extension;

class UserDefinedFormControllerAttachmentExtension extends Extension
{

    /**
    * Remove all attachments if AttachFilesToEmail is false
    * on a per recipient basis
    */
    public function updateCanAttachFileForRecipient(&$canAttachFileForRecipient, $recipient, $uploadFieldName, $file)
    {
        $canAttachFileForRecipient = filter_var($recipient->AttachFilesToEmail, FILTER_VALIDATE_BOOLEAN);
    }
}
