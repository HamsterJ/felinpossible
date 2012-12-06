<?php
/**
 * Sous-formulaire générique.
 * @author Benjamin
 *
 */
abstract class FP_Form_common_SubForm extends Zend_Form_SubForm
{
    /**
    * Construction du subForm.
    *
    * @param  mixed $options
    * @return void
    */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $translate = new Zend_Translate('array', FP_Util_Constantes::$LISTE_MESSAGES_ERREUR_FORM, 'fr');
        $this->setTranslator($translate);
    }

    /**
     * Override to add custom error style class.
     */
    public function addElement($element, $name = null, $options = null)
    {
        
        if ($element instanceof Zend_Form_Element) {
          if ($element->getDecorator('Errors')) {
              $element->getDecorator('Errors')->setOption('class', 'alert alert-error');
          }
          if ($element->isRequired()) {
            $element->setLabel($element->getLabel().' *');
          }
        }
        parent::addElement($element, $name, $options);
        
        return $this;
    }
}

