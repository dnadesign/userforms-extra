<?php

class EditableFileField_AllowedTypeExtension extends DataExtension {

	private static $db = [
		'AllowedExtensions' => 'Varchar(255)'
	];

	public function updateCMSFields(FieldList $fields)
	{
		$allowedExtensions = TextField::create('AllowedExtensions');
		$allowedExtensions->setRightTitle('Comma seperated list of file extensions eg csv,pdf,jpg');

		$fields->addFieldToTab('Root.Main', $allowedExtensions);
	}

	/**
	* Return the list of extensions
	* stripping whitepsaces and .
	*
	* @return Array
	*/
	public function getAllowedExtensionArray()
	{
		$extensions = preg_replace('/[\s+|.+]/', '', strtolower($this->owner->AllowedExtensions));
		if (!$extensions) return null;

		return explode(',', $extensions);
	}
}