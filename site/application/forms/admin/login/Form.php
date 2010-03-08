<?php
/**
 * Formulaire de login à l'administration.
 * @author Benjamin
 *
 */
class FP_Form_admin_login_Form extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        $this->setAttrib('class', 'formOrange');
        $this->setName("login");
        
        // Ajout lastname
        $this->addElement('text', 'login', array(
            'label'      => 'Login',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));
        
        // Ajout firstname
        $this->addElement('password', 'password', array(
            'label'      => 'Mot de passe',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Connexion',
        ));
    }
    
}