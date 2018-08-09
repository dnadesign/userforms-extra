<?php

/**
* This extension disabled attaching by default files uploaded by user on a UserDefniedForm to an email.
* It adds a checkbox on each UserDefinedForm Email Recipient to allow attaching uploaded files
* Note: by default, files over 1MB will not be attached. @see UserDefniedForm:554
*/
class UserDefinedForm_EmailRecipientExtension extends DataExtension {

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

class UserDefinedFormController_AttachmentExtension extends Extension {

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