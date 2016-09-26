<?php
/**
 * Services pour le stock.
 * @author mmo
 *
 */
class FP_Service_StockMaterielServices extends FP_Service_CommonServices {

    private static $instance;

    public static function getInstance() {
            if (empty(self::$instance)) {
                    self::$instance = new FP_Service_StockMaterielServices();
            }
            return self::$instance;
    }
    
    protected function getEmptyBean() {
        $stock = new FP_Model_Bean_StockMateriel();
        return $stock;
    }

/*** Récupération des mappers ***/
    protected function getMapper() {
            return FP_Model_Mapper_MapperFactory::getInstance()->StockMaterielMapper;
    }

    protected function getStockMaterielsDemandeMapper() {
        return FP_Model_Mapper_MapperFactory::getInstance()->StockMaterielsDemandeMapper;
    }
    
    protected function getStockDemandeMapper() {
        return FP_Model_Mapper_MapperFactory::getInstance()->StockDemandeMapper;
    }

    protected function getStockMaterielFAMapper() {
        return FP_Model_Mapper_MapperFactory::getInstance()->StockMaterielFAMapper;
    }
    
    
/*** Récupération des données en JSON pour affichage dans les listes ***/    
   
    //Récupération des matériels au format JSON pour affichage dans une liste
    public function getJsonDataMateriel($demande) {
        $mapper = $this->getStockMaterielsDemandeMapper();

        if ($demande)
            {$idDemande = $demande['id'];}
        else 
            {$idDemande = -99;}

        $data = $mapper->fetchAllToArray('id',null,null,null,'m.idDemandeMateriel='.$idDemande);
        $dojoData= new Zend_Dojo_Data('id', $data, 'name');
        $dojoData->setMetadata('numRows', count($data));

        return $dojoData->toJson();
    }
    
    /** Retourne les catégories de matériel en JSON **/
    public function getJsonDataCategoriesMateriel() {
        $mapper = $this->getMapper();
        $data = $mapper->fetchAllToArray('nom');
        $dojoData= new Zend_Dojo_Data('id', $data, 'name');
        $dojoData->setMetadata('numRows', count($data));
        return $dojoData->toJson();
    }

    //Retourne les demandes de materiel des FA au format json.
    public function getJsonDataDemandes() {
        $mapper = $this->getStockDemandeMapper();
        $data = $mapper->fetchAllToArray('id','desc',null,null,'submitted=1');
        $dojoData= new Zend_Dojo_Data('id', $data, 'name');
        $dojoData->setMetadata('numRows', count($data));
        return $dojoData->toJson();
    }

    //Retourne les emprunts des FA au format json.
    public function getJsonDataEmprunts() {
        $mapper = $this->getStockMaterielFAMapper();
        $data = $mapper->fetchFAEmpruntsToArray('idFA','desc');
        $dojoData= new Zend_Dojo_Data('id', $data, 'name');
        $dojoData->setMetadata('numRows', count($data));
        return $dojoData->toJson();
    }
       
    //Récupération des emprunts au format JSON pour affichage dans une liste admin
    public function getJsonDataMaterielsEmprunt($params) {
        $mapper = $this->getStockMaterielFAMapper();
        $data = $this->getEmpruntData($params['idFA']);
        $dojoData= new Zend_Dojo_Data('id', $data, 'name');
        $dojoData->setMetadata('numRows', count($data));
        return $dojoData->toJson();
    }
   
    
 /*** Demandes ***/     
    
    // Retourne le nombre de materiel pour la demande.
    public function getNbMaterielsPourDemande($idDemande) {
        return $this->getStockMaterielsDemandeMapper()->getNbMaterielsPourDemande($idDemande);
    }
    
    //retourne les infos connues (tous les formulaires remplis) de la FA à partir de son login
    public function  getFAFromLogin ($login)       
    {
        if ($login)// on ne fait pas attention aux accents, on récupère  les infos FA
            {
                $fs = FP_Service_FaServices::getInstance();
                $fa = $fs->getMapper()->select(null,'upper(login)=upper("'.str_replace('"','',$login).'") COLLATE utf8_general_ci','dateSubmit desc');
            }
        return $fa;    
    }
    
    
    //Contrôle qu'on a tous les éléments et enregistrement de la demande
    public function  controlerEtEnregistrerDemande ($form)
    {
        $login = $form['login'];
        $idDemande = $form['idDemandeMateriel'];
        $fa = $this->getFAFromLogin($login);
        $idFA = null;
        
        if ($fa){
            $idFA = $fa[0]->id; //le dernier formulaire FA connu s'il y en a plusieurs
            $this->getStockDemandeMapper()->saveDemande($idDemande,$login,$idFA);
            return 'OK';
        }
        else{
            return 'KO';
        }
    } 
    
    //Sppression du matériel d'une demande avant soumission
    public function deleteMat($idDMat) {
        $mapper = $this->getStockMaterielsDemandeMapper();
        $mapper->supprimerMaterielDeDemande($idDMat);        
    }
    
        //Soumission d'une demande par la FA
    public function  validerDemande ($idDemande,$commentaires = null)
    {
        //MAJ de la demande en base
        $this->getStockDemandeMapper()->updateStatut($idDemande,'submitted',$commentaires);
        // Envoi mail a l'asso avec lien pour traitement
        FP_Service_MailServices::getInstance()->envoiMailDemandeMateriel($idDemande);
    }   
    
    //Nombre de demandes
    public function getNbDemandes() {
       return $this->getStockDemandeMapper()->getNbDemandes();
    }
    
    //Suppression d'une demande
    public function deleteDem($idDem) {
         //Suppresion des matériels
        $mapperD = $this->getStockDemandeMapper();
        $idDemMat = $mapperD->find($idDem)->idDemandeMateriel;
        
        $mapperM = $this->getStockMaterielsDemandeMapper();
        $mapperM->supprimerTousMaterielsDemande($idDemMat);
        
        $mapperD->supprimerDemande($idDemMat);  
    }  
    
   //Récupération d'une demande de matériel 
   public function getDemData($id)
   {
        $element = $this->getStockDemandeMapper()->find($id);
	if ($element) {
            return $element->toArray();
	}
	return array();
   }
   
   //Récupération d'une demande et de ses matériels associés
   public function getCompleteData ($id)
   {
        $elements['demande'] = $this->getStockDemandeMapper()->fetchAllToArray('id',null,null,null,'m.idDemandeMateriel='.$id);
        $elements['mats'] = $this->getStockMaterielsDemandeMapper()->fetchAllToArray('id',null,null,null,'idDemandeMateriel='.$id);
        return $elements;
   }
    
   // Application du statut "Traitée" à une demande de matériel d'une FA
   public function terminerDemande ($idDemandeMateriel)
   {
    $this->getStockDemandeMapper()->updateStatut($idDemandeMateriel,'traitee');
   }
   
/*** Admin des prets ***/ 

    //Suppression du matériel (toutes instances) de la FA
     public function deleteMatFA($idAff,$retour) {
        $mapper = $this->getStockMaterielFAMapper();
        $aff = $mapper->findMatosFA('e.id='.$idAff)[0];
        $mapper->supprimerMaterielDeFA($idAff);
        
        //MAJ de la table de stock
        $this->updateStock($aff['idMateriel'],$aff[quantite],'+',1/*suiviPrets*/,$retour);
    }
    
   //Affecter un matériel à une FA
   public function affectMatos ($idMateriel = null,$login = null,$idDemandeMateriel = null,$etat = null,$quantite=null)
   {
       //on récupère le matériel pour savoir si le suivi de prets est activé
        $mapper = $this->getMapper();
        $m = $mapper->findMatos('e.id='.$idMateriel)[0];
       
        //quantité castée
        $q = round(floatval(str_replace(',','.',$quantite)),3)+0;
            
        if($m['SuiviPrets'] == 1)
        {
            //Ajout d'une ligne d'affectation dans  fp_stock_materiel_fa
            //on enregistre avec le login plutot que l'idFA car la FA peut soumettre plusieurs formulaires FA (ex : changement d'adresse!)
            $a = array('id'=>00,'idMateriel'=>$idMateriel,'login'=>$login,'idDemandeMateriel'=>$idDemandeMateriel,'etat'=>$etat,'quantite'=>$q);
            $mapperMF = $this->getStockMaterielFAMapper();
            $mapperMF->saveAffect($a);
            $this->updateAffectsFromLogin($login);//MAJ de la table des affectations pour mettre le bon idFA en fonction du login (même sur les anciennes affect)  
             }
        //MAJ de la table de stock
        $this->updateStock($idMateriel,$q,'-',$m['SuiviPrets'] /*impact prets*/,1 /*impact stock*/);
   }
   
   //Mise à jour du stock suite à un prêt ou un retour  (impactPrets : incrémente/décrémente la quantité prétée, impactStock : incrémente/décrémente le stock restant)
   public function updateStock ($idMateriel = null,$quantite=null,$sens = null,$impactPrets,$impactStock)
   {      
        //nouveau stock restant
        $sr = new Zend_Db_Expr('ROUND(`StockRestant`'.$sens.$quantite.',3)');
        
        //nouveau stock prêté (si suivi de stock uniquement!)
        $sp = new Zend_Db_Expr('ROUND(`StockEnPret`'.($sens=='-'?'+':'-').$quantite.',3)');
       
        //lancement de la MAJ
        $a = array();
        
        if($impactStock == 1)
        {$a['StockRestant'] = $sr;}
        
        
        if($impactPrets == 1)
        {$a['StockEnPret'] = $sp;}
        
        if(sizeof($a) > 0)  // si on a quelque chose à updater
        {
            $mapper = $this->getMapper();
            $mapper->updateStock($a,'id='.$idMateriel);
        }
   }
   
   
   // Met à jour la table des affectations avec le dernier idFa d'un même login 
   // (le clef c'est le login car une FA peut soumettre plusieurs form FA au court des années 
   // - pour demenagements par exemple)
   public function updateAffectsFromLogin ($login)
   {      
        if ($login)// on ne fait pas attention aux accents, on récupère  les infos FA
        {
            $fs = FP_Service_FaServices::getInstance();
            $fa = $fs->getMapper()->select(null,'upper(login)=upper("'.str_replace('"','',$login).'") COLLATE utf8_general_ci','dateSubmit desc');
        }
        if ($fa){
            $lastIdFA = $fa[0]->id; //le dernier formulaire FA connu s'il y en a plusieurs
            $this->getStockMaterielFAMapper()
                    ->getDbTable()
                    ->update(array('idFA' => $lastIdFA), 'upper(login)=upper("'.str_replace('"','',$login).'") COLLATE utf8_general_ci');
        }
   }
   
   //Récupération des données de prets d'une FA
   public function getEmpruntData($idFA)
   {
        $elements = $this->getStockMaterielFAMapper()->findMatosFA('idFA='.$idFA);
	if ($elements) {
            return $elements;
	}
	return array();
   }
   
   //Ajout d'un matériel dans une FA
   public function saveAjout($formValues)
   {
       $this->affectMatos($formValues['materiel'],$formValues['loginFA'],-1, $formValues['etat'],$formValues['quantite']);
   }
}