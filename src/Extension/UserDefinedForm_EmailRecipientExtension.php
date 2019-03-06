<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;

/**
* This extension disabled attaching by default files uploaded by user on a UserDefniedForm to an email.
* It adds a checkbox on each UserDefinedForm Email Recipient to allow attaching uploaded files
* Note: by default, files over 1MB will not be attached. @see UserDefniedForm:554
*/
class UserDefinedForm_EmailRecipientExtension extends DataExtension
{
    private static $db = [
        'AttachFilesToEmail' => 'Boolean'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        // Add Checkbox to allow Files to be attached to emails
        $emailFile = CheckboxField::create('AttachFilesToEmail');
        $fields->insertAfter($emailFile, 'SendPlain');
    }
}
