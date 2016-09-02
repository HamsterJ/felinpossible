<?php
include_once(realpath(APPLICATION_PATH . '/forms/StockMateriel/DemanderMaterielForm.php')) ;
include_once(realpath(APPLICATION_PATH . '/forms/StockMateriel/StockMaterielForm.php')) ;
include_once(realpath(APPLICATION_PATH . '/forms/StockMateriel/AjouterMaterielForm.php')) ;
include_once(realpath(APPLICATION_PATH . '/forms/StockMateriel/AjouterMaterielFAForm.php')) ;
include_once(realpath(APPLICATION_PATH . '/forms/chat/DemanderFicheForm.php')) ;

/*
 * Controller pour gérer le stock de matériel (partie admin et demandes FA)
 */
class StockController extends FP_Controller_CommonController {

    
/*************  GESTION ADMIN DU STOCK ****************************************************/
    
    private function getService() {
        return FP_Service_StockMaterielServices::getInstance();
    }       
        
    /* Retourne la liste des catégories de matériels au format json */
    public function listeCategoriesAction() {
        if ($this->checkIsLogged()) {
            $request = $this->getRequest();
            echo $this->getService()->getJsonDataCategoriesMateriel();
            exit;
        }
    }
        
    /* Retourne la liste du stock pour l'admin au format json */
    public function listeAction () {
        if ($this->checkIsLogged()) {
            echo $this->getService()->getJsonData($this->getRequest()->getParams());
            exit;
        }
    }
        
    /* Index de la gestion du stock (partie admin)*/
    public function gestionadmAction() {
        if ($this->checkIsLogged()) {
            $this->view->urlListeJson = $this->view->url(array('action' => 'liste'));
            $this->view->urlAddItem = $this->view->url(array('action' => 'add'));
            $this->view->urlEditItem = $this->view->url(array('action' => 'edit'));
            $this->view->urlDeleteItem = $this->view->url(array('action' => 'delete'));
            $this->view->headerPath = "stock/headerstockadm.phtml";
            $this->view->class = "stockMateriel";
            $this->view->titre = "Stock";
            //$this->view->filterPath = "stock/filtermat.phtml";
            $this->view->gridName = "commonGrid";
            $this->view->defaultSort = 2;
            $this->view->nbElements = $this->getService()->getNbElementsForGrid();
            $this->render("indexgrid");
        }
    }

    /* Suppression d'une ligne de stock (partie admin) */
    public function deleteAction() {
        if ($this->checkIsLogged()) {
            $id = $this->getRequest()->getParam('id');
            $this->getService()->deleteElement($id);
            exit;
        }
    }

    /* Ajout d'une ligne de stock */
    public function addAction() {
        if ($this->checkIsLogged()){
            $request = $this->getRequest();
            $form = new FP_Form_StockMateriel_Form();
            if ($request->isPost()) {
                if ($form->isValid($request->getPost())) {
                    $this->getService()->save($form->getValues());
                    return $this->_helper->redirector('gestionadm');
                }
            }
            $form->setAction('javascript:callAjax("'.$this->view->url(array('action' => 'add')).'", null, null, "'.$form->getId().'")');
            $this->view->form = $form;
        }
    }

    /* Édition d'une ligne de stock */
    public function editAction() {
        if ($this->checkIsLogged()) {
            $request = $this->getRequest();
            $form = new FP_Form_StockMateriel_Form();
            $id = $request->getParam('id', null); //id de la ligne à éditer

            if ($id) {
                $data = $this->getService()->getData($id);
                if ($data){
                    $form->populate($data);
                    $form->setAction('javascript:callAjax("'.$this->view->url(array('action' => 'add')).'", null, null, "'.$form->getId().'")');
                    $this->view->form = $form;
                    $this->render("add");
                }
            }
        }
    }

/*************  GESTION DEMANDES DE MATERIEL  (PARTIE ADMIN) **********************************************/
  
    /* Index de la gestion des demandes de matériel (partie admin) */
    public function demandematerieladmAction() {
        if ($this->checkIsLogged()) {
            $this->view->urlListeJson = $this->view->url(array('controller' => 'stock','action' => 'listeDemandes'));
            $this->view->urlEditItem = $this->view->url(array('action' => 'editdem'));
            $this->view->urlDeleteItem = $this->view->url(array('action' => 'deleteDem'));
            $this->view->headerPath = "stock/headerdemandesadm.phtml";
            //$this->view->filterPath = "stock/filterdem.phtml";
            $this->view->class = "stockDemande";
            $this->view->titre = "Demandes de matériel";
            $this->view->gridName = "commonGrid";
            $this->view->defaultSort = -1;
            $this->view->nbElements = $this->getService()->getNbDemandes();
            $this->render("indexgriddem");
        }
    }
    
    /* Retourne la liste des demandes de matériel au format json (pour la partie admin)*/
    public function listedemandesAction() {
        if ($this->checkIsLogged()) {
            echo $this->getService()->getJsonDataDemandes($this->getRequest()->getParams());
            exit;
        }
    }
    
    /* Suppression d'une demande de matériel (partie admin) */
    public function deletedemAction() {
    $demId = $this->getRequest()->getParam('id');
    $dId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        if ($demId) {
                $this->getService()->deleteDem($demId);}
        exit;	
    }
    
    /* Ouverture d'une demande de matériel (partie admin)*/
    public function editdemAction() {
        if ($this->checkIsLogged()) {
            $request = $this->getRequest();
            $form = new FP_Form_stockMateriel_DemanderForm();
            $idDemande = $request->getParam('id', null);

            if ($idDemande) {
                $data = $this->getService()->getDemData($idDemande);
                if ($data){
                    $form->populate($data);
                    $form->setAction('javascript:callAjax("'.$this->view->url(array('action' => 'visudem')).'", null, null, "'.$form->getId().'")');
                    $this->view->form = $form;
                    $this->render("visudem");
                }
            }
        }
    }
    
    /* Traitement d'une demande de matériel :  (partie admin)*/
    public function traiterdemandematerieladmAction(){
        $request = $this->getRequest();
        $affectations = $request->getPost();
        $idDemande = $affectations['idDemandeMateriel'];
        $login = $affectations['login'];
        
        foreach ($affectations as $key => $value)
        {
            if (substr ($key,0,1) == 'c')
            {
                $this->getService()->affectMatos(substr($key,1),$login,$idDemande,$affectations['l'.substr($key,1)],  round(floatval(str_replace(',','.',$affectations['q'.substr($key,1)])),3)+0); 
            }           
        }
        
        $this->getService()->terminerDemande($idDemande);
        $this->render('traiterdemandemateriel-adm');
    }

    
/*************  GESTION DES MATERIELS DANS LES FA  (PARTIE ADMIN) **********************************************/
    /* Index de la gestion des materiels empruntés par les FA (partie admin) */
    public function empruntsadmAction() {
       if ($this->checkIsLogged()) {
           $this->view->urlListeJson = $this->view->url(array('controller' => 'stock','action' => 'listeemprunts'));
           $this->view->urlEditItem = $this->view->url(array('action' => 'editemprunt', 'id' => null));
           $this->view->urlDeleteItem = $this->view->url(array('action' => 'deleteemprunt', 'id' => null));
           $this->view->headerPath = "stock/headerempruntsadm.phtml";
           $this->view->class = "StockMaterielFA";
           $this->view->titre = "Matériels empruntés par les FA";
           $this->view->gridName = "commonGrid";
           $this->view->defaultSort = -1;
           $this->render("indexgridemprunt");
       }
    }
    
    /* retourne la liste des emprunts des FA */
    public function listeempruntsAction () {
        $request = $this->getRequest();
        echo $this->getService()->getJsonDataEmprunts($request->getParams());
        exit;
    } 
    
        
    /* retourne la liste des materiels d'une FA */
    public function listematerielsempruntAction () {
        $request = $this->getRequest();
        echo $this->getService()->getJsonDataMaterielsEmprunt($request->getParams());
        exit;
    } 
    
        /* Ouverture de la liste des matos d'une FA (partie admin)*/
    public function editempruntAction() {
        if ($this->checkIsLogged()) {
            $request = $this->getRequest();
            $idFA = $request->getParam('id', null);
            
            if ($idFA) {
                $data = $this->getService()->getEmpruntData($idFA);
                if ($data){
                    
                    $loginFA = $data[0]['login'];
                    $this->view->$loginFA = $loginFA;
                    $this->view->data = $data;
                    
                    $this->view->urlListeJson = $this->view->url(array('controller' => 'stock','action' => 'listematerielsemprunt','idFA' =>$idFA));
                    $this->view->urlAddItem = $this->view->url(array('action' => 'ajoutermaterielfa',  'loginFA' => $loginFA));
                    $this->view->urlDeleteItem = $this->view->url(array('action' => 'deletematerielfa', 'id' => null));
                    $this->view->defaultSort = 1;
                    $this->view->headerPath = "stock/headermatFA.phtml";
                    $this->view->titre = "Liste des matériels";
                    
                    $this->render("editemprunt");
                }
            }
        }
    }
    
        public function ajoutermaterielfaAction() {
        $request = $this->getRequest();
        $loginFA = null;
        if (isset($request->getParams()['loginFA']))
            {$loginFA = $request->getParams()['loginFA'];}
        $form = new FP_Form_AjouterMaterielFA_Form($loginFA);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $this->getService()->saveAjout($form->getValues());
                return $this->_helper->redirector('empruntsadm', null, null, array('from'=>'addMaterielFA','loginFA'=>$loginFA));
            }
        }
        $form->setAction('javascript:callAjax("'.$this->view->url(array('action' => 'ajoutermaterielfa')).'", null, null, "'.$form->getId().'")');
        $this->view->form = $form;
    }    
    
    /* Suppression d'un matériel d'une FA (partie admin)*/
    public function deletematerielfaAction() {
        $affId = $this->getRequest()->getParams()['id'];

        if ($affId)
            {
                $this->getService()->deleteMatFA($affId);
        }
        exit;	
    }  
    
    
/*************  GESTION DEMANDES DE MATERIEL DES FA (PARTIE PUBLIQUE) **********************************************/
    
    private function getDemandeMaterielService() {
        return FP_Service_DemandeMaterielServices::getInstance();
    } 
        
    /* Affichage du 1er formulaire de demande de matériel (partie publique destinée aux FA) */ 
    public function demandermaterielAction() {     
        $form = new FP_Form_stockMateriel_DemanderForm();
        $form->setAction($this->view->url(array('controller' => 'stock', 'action' => 'demandermateriel2')));
        $this->view->form = $form;
    }      
   
    /* Affichage du 2ème formulaire de demande de matériel (partie publique destinée aux FA) */ 
    public function demandermateriel2Action() {  
        $request = $this->getRequest();
        $from = $request->getParam('from'); 

        if($from !='addMateriel')//Si on n'est pas en mode "ajout de matériel" (on vient donc du 1er formulaire)           
        {
            $form = new FP_Form_stockMateriel_DemanderForm();
            if (!$form->isValid($request->getPost())) {
                $this->view->form = $form;     
                $this->render('demandermateriel');
                return;
            }
            
            $f= $request->getPost();
            $demandeMaterielId = $f['idDemandeMateriel'];
            
            if ($this->getService()->controlerEtEnregistrerDemande($f) != 'OK')
            {
                $this->view->form = $form;
                $this->view->errorMessage = 'Nous ne trouvons pas ce login dans notre base des familles d\'accueil, veuillez le vérifier ou vous inscrire <a href="/fa/process">ici </a>';
                $this->render('demandermateriel');
                return;
            }      
        }   
        else {  //On vient donc du 2ème formulaire, on boucle pour l'ajout de plusieurs matériels   
            $demandeMaterielId = $request->getParam('id');
        }
        
        $this->view->urlListeJson = $this->view->url(array('controller' => 'stock','action' => 'listemateriel', 'id' => $demandeMaterielId));
        $this->view->urlAddItem = $this->view->url(array('action' => 'ajoutermateriel', 'id' => $demandeMaterielId, 'idMateriel' => null));
        $this->view->urlDeleteItem = $this->view->url(array('action' => 'deletemateriel', 'id' => null));
        $this->view->defaultSort = 1;
        $this->view->headerPath = "stock/headermat.phtml";
        $this->view->titre = "Liste des matériels";
        $this->view->serv = $this->getService();
        $this->view->idd = $demandeMaterielId;

        if ($demandeMaterielId) {
            $this->view->nbMateriels = $this->getService()->getNbMaterielsPourDemande($demandeMaterielId);
        }
        $this->render('demandermateriel2');
        return;
    } 

    /* Enregistrement d'une demande de matériel (partie publique)*/
    public function storedemAction() {     
        $request = $this->getRequest();
        $this->getService()->validerDemande($request->getParam('idd'),$request->getParam('commentaires'));
    } 
        
    /* Ajout d'une ligne de matériel dans une demande (partie publique FA)*/
    public function ajoutermaterielAction() {
        $request = $this->getRequest();
        $idd = null;
        if (isset($request->getParams()['id']))
            {$idd = $request->getParams()['id'];}
        $form = new FP_Form_AjouterMateriel_Form($idd);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $this->getDemandeMaterielService()->save($form->getValues());
                return $this->_helper->redirector('demandermateriel2', null, null, array('from'=>'addMateriel','id'=>$idd));
            }
        }
        $form->setAction('javascript:callAjax("'.$this->view->url(array('action' => 'ajoutermateriel')).'", null, null, "'.$form->getId().'")');
        $this->view->form = $form;
    }    
        
    /* Suppression d'un matériel d'une demande (partie publique FA)*/
    public function deletematerielAction() {
       // $matId = filter_input(INPUT_GET, 'idm', FILTER_SANITIZE_STRING);
        $matId = $this->getRequest()->getParams()['id'];
        if ($matId) {
                $this->getService()->deleteMat($matId);
        }
        exit;	
    }  
    
    /* retourne la liste des materiels d'une demande */
    public function listematerielAction () {
        $request = $this->getRequest();
        echo $this->getService()->getJsonDataMateriel($request->getParams());
        exit;
    }   
}