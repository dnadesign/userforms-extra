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

	/**
	* Render with a special template
	* so we can add the limitations as text
	*/
	public function afterUpdateFormField(&$field)
	{
		$field->setFieldHolderTemplate('UserFormsFileField_holder');
		$field->setDescription($this->owner->getLimitations());

		$fieldID = $field->getName();
		$extensions = implode('|', $this->owner->getAllowedExtensionArray());

		$field->setAttribute('data-rule-extensions', $extensions);

		if ($extensions) {
			Requirements::customScript(<<<JS
                (function($) {
                    $(document).ready(function() {
                		$("#{$fieldID}").rules( "add", {
						  extensions: "{$extensions}",
						  messages: {
						    extensions: "Please add file with a valid extension ({$extensions})",
						  }
						});
                    });
                })(jQuery);
JS
, '{$fieldID}');
	}
	}



	/**
	* Return a sentence explaining what the file extensions and size limitations are.
	*
	* @return String | null
	*/
	public function getLimitations()
	{
		$limitations = null;

		$extensions = $this->owner->getAllowedExtensionArray();
		if (!empty($extensions )) {
			$limitations = sprintf('Only files with extensions <span class="file-extensions">%s</span> can be uploaded.', implode(' | ', $extensions));
		}

		if ($this->owner->MaxFileSizeMB) {
			$limitations = ($limitations) ? $limitations.'&nbsp;' : '';
			$limitations .= sprintf('File must not exceed %sMB.', $this->owner->MaxFileSizeMB);
		}

		if ($limitations) {
			return DBField::create_field('HTMLText', $limitations);
		}

		return null;
	}
}