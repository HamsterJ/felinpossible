<?php
/**
 * Classe pour les éléments de type Text.
 * @author Benjamin
 *
 */
class FP_Form_common_ElementText extends Zend_Form_Element_Text {
	/**
	 * Supprime tous les messages de l'objet
	 * @return FP_Form_common_ElementText
	 */
	public function clearAllMessages()
	{
		$this->_messages = array();
		$this->_isErrorForced = false;
		$this->clearErrorMessages();
		return $this;
	}
}