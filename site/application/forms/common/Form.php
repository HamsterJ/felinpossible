<?php
/**
 * Formulaire générique avec gestion des subForms.
 * @author Benjamin
 *
 */
abstract class FP_Form_common_Form extends Zend_Form
{
	/**
	 * Construction du formulaire.
	 *
	 * @param  mixed $options
	 * @return void
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);

		$this->setMethod('post');
		$this->setAttrib('class', 'formOrange');
		$translate = new Zend_Translate('array', FP_Util_Constantes::$LISTE_MESSAGES_ERREUR_FORM, 'fr');
		$this->setTranslator($translate);
	}


	/**
	 * Prepare a sub form for display
	 *
	 * @param  string|Zend_Form_SubForm $spec
	 * @return Zend_Form_SubForm
	 */
	public function prepareSubForm($spec)
	{
		if (is_string($spec)) {
			$subForm = $this->{$spec};
		} elseif ($spec instanceof Zend_Form_SubForm) {
			$subForm = $spec;
		} else {
			throw new Exception('Invalid argument passed to ' .
			__FUNCTION__ . '()');
		}
		$this->setSubFormDecorators($subForm)
		->addSubmitButton($subForm)
		->addSubFormActions($subForm);
		return $subForm;
	}

	/**
	 * Add form decorators to an individual sub form
	 *
	 * @param  Zend_Form_SubForm $subForm
	 * @return My_Form_Registration
	 */
	public function setSubFormDecorators(Zend_Form_SubForm $subForm)
	{
		$subForm->setDecorators(array(
            'FormElements',
		array('HtmlTag', array('tag' => 'dl',
                                   'class' => 'zend_form')),
            'Form',
		));
		return $this;
	}

	/**
	 * Add a submit button to an individual sub form
	 *
	 * @param  Zend_Form_SubForm $subForm
	 * @return My_Form_Registration
	 */
	public function addSubmitButton(Zend_Form_SubForm $subForm)
	{
		$subForm->addElement(new Zend_Form_Element_Submit(
            'save',
		array(
                'label'    => 'Sauver et continuer',
                'required' => false,
                'ignore'   => true,
		        'order' => 100
		)
		));
		return $this;
	}

	/**
	 * Add action and method to sub form
	 *
	 * @param  Zend_Form_SubForm $subForm
	 * @return My_Form_Registration
	 */
	public function addSubFormActions(Zend_Form_SubForm $subForm)
	{
		$subForm->setAttrib('class', 'formOrange');
		$subForm->setMethod('post');
		return $this;
	}

	/**
	 * Return form in html format.
	 * @return string
	 */
	public function toHtml() {
		$result = "";

		foreach ($this->getSubForms() as $subForm) {
			$result = $result."<table border='1'><caption>".$subForm->getDescription()."</caption>";
			$result = $result."<tr><th>Question</th><th>Réponse</th></tr>";
			foreach ($subForm->getElements() as $element) {
				if (!$element->getIgnore() && !($element instanceof Zend_Form_Element_Hidden)) {
					if ($element instanceof Zend_Form_Element_Multi) {
						$result = $result."<tr><td>".$element->getLabel()."</td><td>".$element->getMultiOption($element->getValue())."</td></tr>";
					} else {
						$result = $result."<tr><td>".$element->getLabel()."</td><td>".$element->getValue()."</td></tr>";
					}
				}
			}
			$result = $result."</table><br>";
		}
		return $result;
	}

	/**
	 * Return form in text format.
	 * @return string
	 */
	public function toText() {
		$result = "";

		foreach ($this->getSubForms() as $subForm) {
			$result = $result.$subForm->getDescription()."\r\n";
			foreach ($subForm->getElements() as $element) {
				if (!$element->getIgnore() && !($element instanceof Zend_Form_Element_Hidden)) {
					if ($element instanceof Zend_Form_Element_Multi) {
						$result = $result.$element->getLabel()." : ".$element->getMultiOption($element->getValue())."\r\n";
					} else  {
						$result = $result.$element->getLabel()." : ".$element->getValue()."\r\n";
					}
				}
			}
			$result = $result."\r\n";
		}
		return $result;
	}

}

