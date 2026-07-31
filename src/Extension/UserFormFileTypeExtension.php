<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\UserForms\Control\UserDefinedFormController;
use SilverStripe\UserForms\Model\EditableFormField\EditableFileField;

class UserFormFileTypeExtension extends Extension
{
    protected function updateForm()
    {
        $controller = $this->getOwner()->controller;

        if ($controller && $controller instanceof UserDefinedFormController) {
            // Check if we have File Fields
            $userDefinedForm = $controller->data();

            if ($userDefinedForm) {
                $fileFields = $userDefinedForm->Fields()->filter('ClassName', EditableFileField::class);
                $fields = FieldList::create($this->getOwner()->Fields()->dataFields());

                foreach ($fileFields as $field) {
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
