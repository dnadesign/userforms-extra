<?php

namespace DNADesign\UserFormExtras\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;

class EditableFormField_InitialVisibility extends Extension
{

    /**
    * Make sure that if a field is subject to a rule
    * When the page reloads and data is populated, the intial state is the correct one
    * as javascript does not initialise the states
    */
    public function afterUpdateFormField(&$field)
    {
        // If field is supposed to be hidden by default
        if ($this->owner->ShowOnLoad == false) {
            $rules = $this->owner->DisplayRules();

            foreach ($rules as $rule) {
                $conditionFieldName = $rule->ConditionField()->Name;
                
                // Applying fix provided @Ashpik https://github.com/dnadesign/userforms-extra/issues/2
                $request = Injector::inst()->get(HTTPRequest::class);
                $session = $request->getSession();
                $value = $session->get('FormInfo.BetterUserForm_Form.data.' . $conditionFieldName);
                
                // If field has a rules that would reveal it
                if ($rule->Display == 'Show' && $value) {
                    $operator = null;
                    switch ($rule->ConditionOption) {
                        case 'HasValue':
                            $operator = '==';
                            break;

                        case 'ValueNot':
                            $operator = '!=';
                            break;

                        default:
                            $operator = null;
                    }

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
