<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\FormField;
use SilverStripe\UserForms\Model\EditableFormField;

/**
 * @extends Extension<EditableFormField>
 */
class EditableFormFieldInitialVisibility extends Extension
{

    /**
    * Make sure that if a field is subject to a rule
    * When the page reloads and data is populated, the intial state is the correct one
    * as javascript does not initialise the states
    */
    protected function afterUpdateFormField(FormField &$field): void
    {
        // If field is supposed to be hidden by default
        if ($this->getOwner()->ShowOnLoad == false) {
            $rules = $this->getOwner()->DisplayRules();

            foreach ($rules as $rule) {
                $conditionField = $rule->ConditionField();
                if (!$conditionField->exists()) {
                    continue;
                }
                $conditionFieldName = $conditionField->Name;

                // Can only get the data from the session
                // Has we may get redirected at this stage and the request will be empty
                $request = Injector::inst()->get(HTTPRequest::class);
                $session = $request->getSession();
                $value = $session->get('FormInfo.BetterUserForm_Form.data.'.$conditionFieldName);

                // If field has a rules that would reveal it
                if ($rule->Display == 'Show' && $value) {
                    $operator = null;
                    $operator = match ($rule->ConditionOption) {
                        'HasValue' => '==',
                        'ValueNot' => '!=',
                        default => null,
                    };

                    // Check if we can eval the condition
                    // Currently works with "Equals" and "Not Equals"
                    // TODO: eval other operator, like less than etc...
                    if ($operator) {
                        $condition = sprintf('return ("%s" %s "%s") ? true : false;', $rule->FieldValue, $operator, $value);
                        $conditionIsMet = eval($condition);
                        // If the condition is met
                        // Remove the class that hides the field
                        if ($conditionIsMet === true) {
                            $field->removeExtraClass('hide');
                            break;
                        }
                    }
                }
            }
        }
    }
}
