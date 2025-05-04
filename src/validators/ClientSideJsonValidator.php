<?php

namespace hrzg\widget\validators;

use yii\validators\Validator;

class ClientSideJsonValidator extends Validator
{
    public function validateValue($value)
    {
        return [];
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        return <<<JS
            const editor = window.jsonEditors[0]
            
            if (!editor) {
              return
            }
            
            const errors = editor.validate()
            

            // collect error messages for this validator
            errors.forEach(function (error) {
              const invalidEditor = editor.getEditor(error.path)
              const field = invalidEditor.schema.title || error.path
              const message = field + ': ' + error.message
              messages.push(message)
            })
            
            // coerce json-editor to display own inline error messages
            Object.values(editor.editors).forEach(function (editor) {
              editor.is_dirty = true
            })
            
            editor.root.showValidationErrors(errors)
                        
            // remove the 'has-error' class from editor container. Using * selector because
            // the class name can change from model to mode but the attribute in this case
            // is always "default_properties_json"
            const targetNode = document.querySelector('[class*="default_properties_json"]')

            const observer = new MutationObserver((mutations) => {
              mutations.forEach(() => {
                if (targetNode.classList.contains('has-error')) {
                  targetNode.classList.remove('has-error')
                  observer.disconnect()
                }
              })
            })
            
            observer.observe(targetNode, { attributes: true, attributeFilter: ['class'] })
        JS;
    }
}