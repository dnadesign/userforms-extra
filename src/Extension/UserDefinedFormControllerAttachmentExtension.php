<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Assets\File;
use SilverStripe\Core\Extension;
use SilverStripe\UserForms\Control\UserDefinedFormController;
use SilverStripe\UserForms\Model\Recipient\EmailRecipient;

/**
 * @extends Extension<UserDefinedFormController>
 */
class UserDefinedFormControllerAttachmentExtension extends Extension
{

    /**
    * Remove all attachments if AttachFilesToEmail is false
    * on a per recipient basis
    */
    protected function updateCanAttachFileForRecipient(
        bool &$canAttachFileForRecipient,
        EmailRecipient $recipient,
        string $uploadFieldName,
        File $file
    ): void {
        $canAttachFileForRecipient = filter_var($recipient->AttachFilesToEmail, FILTER_VALIDATE_BOOLEAN);
    }
}
