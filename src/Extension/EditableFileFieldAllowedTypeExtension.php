<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormField;
use SilverStripe\UserForms\Model\EditableFormField\EditableFileField;

/**
 * @extends Extension<EditableFileField>
 */
class EditableFileFieldAllowedTypeExtension extends Extension
{
    private static array $db = [
        'AllowedExtensions' => 'Varchar(255)'
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $allowedExtensions = TextField::create('AllowedExtensions');
        $allowedExtensions->setRightTitle('Comma seperated list of file extensions eg csv,pdf,jpg');

        $fields->addFieldToTab('Root.Main', $allowedExtensions);
    }

    /**
    * Return the list of extensions
    * stripping whitepsaces and .
    */
    public function getAllowedExtensionArray(): ?array
    {
        $extensions = preg_replace('/[\s+|.+]/', '', strtolower($this->owner->AllowedExtensions ?? ''));
        if (!$extensions) {
            return null;
        }

        return explode(',', $extensions);
    }

    /**
    * Render with a special template
    * so we can add the limitations as text
    */
    public function afterUpdateFormField(FormField &$field): void
    {
        $field->setFieldHolderTemplate('Forms/EditableFileField_holder');
        $field->setDescription($this->owner->getLimitations());
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
        if (!empty($extensions)) {
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
