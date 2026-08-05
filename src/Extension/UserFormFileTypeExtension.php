<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileField;
use SilverStripe\UserForms\Control\UserDefinedFormController;
use SilverStripe\UserForms\Form\UserForm;
use SilverStripe\UserForms\Model\EditableFormField\EditableFileField;
use SilverStripe\UserForms\Model\UserDefinedForm;

/**
 * @extends Extension<UserForm>
 */
class UserFormFileTypeExtension extends Extension
{
    protected function updateForm(): void
    {
        $controller = $this->getOwner()->controller;

        if ($controller && $controller instanceof UserDefinedFormController) {
            // Check if we have File Fields
            $userDefinedForm = $controller->data();

            if ($userDefinedForm instanceof UserDefinedForm) {
                $fileFields = $userDefinedForm->Fields()->filter('ClassName', EditableFileField::class);
                $fields = FieldList::create($this->getOwner()->Fields()->getDataFields());

                foreach ($fileFields as $field) {
                    if (!$field instanceof EditableFileField) {
                        continue;
                    }

                    $allowedExtensions = $field->getAllowedExtensionArray();
                    $fileField = $fields->fieldByName($field->getFormField()->getName());

                    if ($fileField instanceof FileField && $allowedExtensions) {
                        $validator = $fileField->getValidator();
                        $validator->setAllowedExtensions($allowedExtensions);
                    }
                }
            }
        }
    }
}
