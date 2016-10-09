<?php
/**
 * Controller pour les Demande de fiches de soins.
 * @author mmo
 */

class DemandeFicheSoinsController extends FP_Controller_CommonController
{
	/**
	 * Retourne le service associé au controller.
	 * @return FP_Service_FicheSoinsServices
	 */
	private function getService() {
		return FP_Service_DemandeFicheSoinsServices::getInstance();
	}
		 
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
		return 'DemandeFicheSoinsController';
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getHeaderPath()
	 */
	protected function getHeaderPath() {
		return "demande-fiche-soins/headeradm.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getFilterPath()
	 */
	protected function getFilterPath() {
		return "demande-fiche-soins/filtersoins.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getViewTitle()
	 */
	protected function getViewTitle() {
		return "Demandes de fiches vétérinaires";
	}

        
        /**
	 * Suppression d'une demande de fiche de soins(partie admin).
	 */
	public function deleteAction() {
		if ($this->checkIsLogged()) {
			$id = $this->getRequest()->getParam("id");
			$this->getService()->deleteElement($id);
			exit;
		}
	}
        
        /**
	 * Initialisation des urls pour l'admin.
	 */
	private function initUrlForAdmin() {
		$action = $this->getRequest()->getParam('action');

		$this->view->urlListeJson = $this->view->url(array('action' => 'liste'));
		$this->view->urlAddItem = $this->view->url(array('action' => 'demanderFicheSoins', 'callback' => $action));
                $this->view->urlEditItem = $this->view->url(array('action' => 'traiterDemandeAdm', 'callback' => $action));
		$this->view->urlTraiterFiche = $this->view->url(array('action' => 'traiterDemandeAdm', 'callback' => $action));
		$this->view->urlDeleteItem = $this->view->url(array('action' => 'delete'));
		
		$this->view->defaultSort = -2; //tri descendant sur la 2ème colonne

		$this->view->headerPath = "demande-fiche-soins/headeradm.phtml";
		$this->view->class = "ficheSoins";
	}
        
        
	/**
	 * Retourne la liste des demandes de fiches de soins pour l'admin au format json.
	 */
	public function listeAction () {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			echo  $this->getService()->getJsonData($request->getParams());
			exit;
		}
	}
        
	/**
	* Affichage du formulaire de demande de fiche de soins (partie publique destinée aux FA)
	*/ 
        public function demanderfichesoinsAction() {            
                $form = new FP_Form_chat_DemanderFicheForm();
                $form->setAction($this->view->url(array('action' => 'saveDemandeFicheSoins')));
                $this->view->form = $form;
        } 
		
		
	/**
	* sauvegarder la demande de fiche de soins et envoyer le lien par mail sur la boite de l'asso pour traitement.
	*/ 
        public function savedemandefichesoinsAction() {
             $request = $this->getRequest();
             $form = new FP_Form_chat_DemanderFicheForm();
		
            if ($form->isValid($request->getPost())) {
               $f=$request->getPost();
            $fa = $this->getService()->getFAFromLogin($f['login']);
               
               if (sizeof($fa) > 0) // On connait la FA
                {
                   $this->getService()->saveDemandeFicheSoins($f);
                   $this->render('retour');
                   return;
                }
               else 
                {
                   $this->view->errorMessage = 'Nous ne trouvons pas ce login dans notre base des familles d\'accueil, veuillez le vérifier ou vous inscrire <a href="/fa/process">ici </a>' ;
                }
            }
             
             $this->view->form = $form;     
             $this->render('demander-fiche-soins');
         }   
         
         
	/**
	* Traiter une demande de soins : Depuis le token de demande recu dans l'URL, 
        * on récupère la demande et on pré-remplie le formulaire de génération de la fiche de soins
	*/  
         public function traiterdemandeAction() {
             
             $tok = (array_key_exists('token',$_REQUEST))?($_REQUEST['token']):'';   
             
             if ($tok) 
             {
                $demande = $this->getService()->getDemandeParToken($tok);//on récupère la demande en base
                
                if ($demande)
                {
                    // on prend la premiere demande correspondante (soit le dernier par ordre de date), au cas où...
                    $fiche = $this->getService()->getFormPrerempli($demande[0]);
                    $this->view->form = $fiche; 
                    $this->view->form->setAction($this->view->url(array('action' => 'genereFicheSoins')));
                    $this->view->demandeTraitee = $demande[0]->getFicheGeneree();
                    $this->view->envoiVeto = $demande[0]->getEnvoiVeto();
                    $this->render('traiter-fiche-soins');
                    return;
                }
                else // si le token recu n'existe pas ou plus, on tombe en erreur et on ne permet aucune génération de fiche de soins
                {
                    $this->render('fiche-soins-error');
                    return;
                }
            }
            else // si le token n'est pas bien passé dans l'URL, on tombe en erreur pour ne pas permettre la génréation de fiche par n'importe qui
            {
                $this->render('fiche-soins-error');
                return;
            }
         }
         
         /**
	 * Action pour ouvrir la page de génération de fiche de soins (depuis le tableau de l'admin).
	 */
	public function traiterdemandeadmAction() {
            if ($this->checkIsLogged()) {
                    
                $id = $this->getRequest()->getParam("id");

                if ($id) {
                    $data = $this->getService()->getDataDemandeSoins($id);
                }
                    
                //récupération d'un formulaire de génération de fiche de soins depuis la demande de la FA
                $form = $this->getService()->getFormPrerempli($data[0]);
                $form->setAction($this->view->url(array('action' => 'generefichesoins')));
                $this->view->form = $form;
                $this->view->demandeTraitee = $data[0]->getFicheGeneree();
                $this->view->envoiVeto = $data[0]->getEnvoiVeto();
            }
	}
         
        /**
	* Genere le PDF de fiche de soins (Au passage on flaggera la demande comme traitée)
	*/  
         public function generefichesoinsAction() {
            $request = $this->getRequest();
            $form = new FP_Form_chat_FicheSoinsForm();
            
            if ($request->isPost()) {
                    if ($form->isValid($request->getPost())) {
                            $this->getService()->generateFicheSoinsFPDF($form);
                    }
            }
            exit;
	}
             
        /**
	 * Index de la partie admin pour les fiches des chats.
	 */
	public function demandesoinsadmAction() {
		
            if ($this->checkIsLogged()) {
                $this->initUrlForAdmin();
                $this->view->titre = "Fiches de soins";
                $this->view->gridName = "commonGrid";
                $this->render("fichessoinsgrid");
            }
	}
}
