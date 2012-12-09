<?php
/**
 * Controller pour gérer les fiches des vétérinaires.
 * @author Benjamin
 *
 */
class VetoController extends FP_Controller_SubFormController {

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getFormClassName()
	 */
	protected function getFormClassName() {
		return 'FP_Form_veto_Form';
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getNamespace()
	 */
	protected function getNamespace() {
		return 'VetoController';
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getHeaderPath()
	 */
	protected function getHeaderPath() {
		return "veto/headeradm.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getFilterPath()
	 */
	protected function getFilterPath() {
		return "veto/filterveto.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getViewTitle()
	 */
	protected function getViewTitle() {
		return "Fiches des vétérinaires";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getEmailSubject()
	 */
	protected function getEmailSubject() {
		return FP_Util_Constantes::MAIL_ADOPTER_SUJET;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getStyleClass()
	 */
	protected function getStyleClass() {
		return "ficheVeto";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getService()
	 * @return FP_Service_AdoptantServices
	 */
	protected function getService() {
		return FP_Service_VetoServices::getInstance();
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#exportAction()
	 */
	public function exportAction() {
		if ($this->checkIsLogged()) {
			$workbook = FP_Service_ExportServices::getInstance()->buildExcelAllVetos();
			exit;
		}
	}

	/**
	 * Page d'accueil des fiches de vétérinaires.
	 */
	public function indexAction() {
		if ($this->checkIsLogged()) {
			$this->initGridParam();
		}
	}

	/**
	 * Initialisation du tableau des vétérinaires.
	 */
	protected function initGridParam() {
		parent::initGridParam();
		$this->view->defaultSort = 3;
	}
}

