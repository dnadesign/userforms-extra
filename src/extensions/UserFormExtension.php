<?php

class UserForm_FileTypeExtension extends Extension {

	public function updateForm()
	{
		$controller = $this->owner->controller;
		
		if ($controller && $controller instanceof UserDefinedForm_Controller) {
			// Check if we have File Fields
			$userDefinedForm = $controller->data();
			if ($userDefinedForm) {
				$fileFields = $userDefinedForm->Fields()->filter('ClassName', 'EditableFileField');
				$fields = FieldList::create($this->owner->Fields()->dataFields());

				foreach($fileFields as $field) {
					$allowedExtensions = $field->getAllowedExtensionArray();
					$fileField = $fields->fieldByName($field->getFormField()->getName());
					if ($fileField && $allowedExtensions && !empty($allowedExtensions)) {
						$validator = $fileField->getValidator();
						$validator->setAllowedExtensions($allowedExtensions);
					}
				}				
			}			
		}
	}
}