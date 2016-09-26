<?php

include_once(realpath(APPLICATION_PATH . '/models/DbTable/StockDemande.php')) ;
/**
 * DemandeFicheSoins data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_StockDemandeMapper extends FP_Model_Mapper_CommonMapper {
    protected $idClassName = 'StockDemande';

    protected $mappingDbToModel = array(// db => classe
                                            	'id'                => 'id' ,
                                                'idDemandeMateriel' => 'idDemandeMateriel' ,
                                                'login'             => 'login',
                                                'idFA'              => 'idFA'  ,
                                                'dateDemande'       => 'dateDemande',
                                                'token'             => 'token',
                                                'traitee'           => 'traitee',
                                                'commentaire'       => 'commentaire');
    
    
	public function fetchAllToArray($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
	{
            $subSelect = $this->getDbTable()->getAdapter()->select()
            ->from( array('m' => 'fp_stock_demande'), 
                    array('m.id'
                        ,'m.idDemandeMateriel'
                        ,'m.login'
                        ,'m.idFA'
                        ,'m.dateDemande'
                        ,'m.token'
                        ,'IF(m.traitee>0,"Oui","NON") traitee'
                        ,'m.commentaire'
                        ))
                    ->joinLeft(array('fa' => 'fp_fa_fiche'), 'fa.id = m.idFA', array('infoFA' => 'CONCAT(fa.prenom, \' \', fa.nom, COALESCE(CONCAT(\' (\', fa.login, \')\'), \'\'))','email' => 'email'));
                
            if ($sort && $order) {
                $subSelect->order($sort." ".$order);
            }

            if ($where) {
                $subSelect->where($where);
            }

            if ($count != null && $start != null) {
                $select = $this->getDbTable()->getAdapter()->select()
                ->from(array('subselect' => $subSelect))
                ->limit($count, $start);
            } else {
                $select = $subSelect;
            }
            $stmt = $select->query();
            return $stmt->fetchAll();
	}
        
        public function saveDemande($idDemande,$login,$idFa)
        {
            if ($this->count('idDemandeMateriel ='.$idDemande) == 0)
            {//on insert que si l'id est inconnu (solution au pb de cache Edge qui crée des doublons)
                $data = array();
                $data['idDemandeMateriel'] = $idDemande;
                $data['login'] = $login;
                $data['idFA'] = $idFa;
                $data['dateDemande'] = date('d-m-y H:i:s');
                $data['traitee'] = '0';
                $data['token'] = crypt($idDemande.$login);
                $data['submitted'] = 0;

                $this->getDbTable()->insert($data);
            }
        }
        
        /** Met à jour la demande pour signifier qu'elle est soumise **/
        public function updateStatut($idDemandeMateriel,$statut,$commentaires = null) {
            
            if ($statut == 'submitted'){
                $this->getDbTable()->update(array('submitted' => 1,'commentaire'=>$commentaires), "idDemandeMateriel = ".$idDemandeMateriel);}
            else if ($statut == 'traitee'){
                $this->getDbTable()->update(array('traitee' => 1), "idDemandeMateriel = ".$idDemandeMateriel);}
            else 
                {$this->getDbTable()->update(array('commentaire'=>$commentaires), "idDemandeMateriel = ".$idDemandeMateriel);}
        }
        
        /** Retourne le nombre de demandes (pour admin). **/
	public function getNbDemandes() {
		return $this->count();
	}
        
        public function supprimerDemande($idDemandeMateriel) {
		$this->delete("idDemandeMateriel = ".$idDemandeMateriel);
	}
}