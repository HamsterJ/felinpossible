<?php

/**
 * Data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_StockMaterielsDemandeMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'StockMaterielsDemande';
	
	protected $mappingDbToModel = array(
                                        'id'                => 'id',
                                        'idDemandeMateriel' => 'idDemandeMateriel',
                                        'materiel'          => 'materiel');
	
	/** Retourne le nombre de materiels dans la demande. **/
	public function getNbMaterielsPourDemande($idDemandeMateriel) {
		return $this->count("idDemandeMateriel= ".$idDemandeMateriel);
	}
	
	/**
	 * Supprimer le materiel de la demande.
	 */
	public function supprimerMaterielDeDemande($id) {
		$this->delete("id = ".$id);
	}
        
        public function supprimerTousMaterielsDemande($idDemandeMateriel) {
		$this->delete("idDemandeMateriel = ".$idDemandeMateriel);
	}
        
        public function fetchAllToArray($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
	{
            $subSelect = $this->getDbTable()->getAdapter()->select()
            ->from( array('m' => 'fp_stock_materiels_demande'), 
                    array('m.id'
                        ,'m.idDemandeMateriel'
                        ,'m.materiel'))
            ->join(array('sm' => 'fp_stock_materiel'), 'sm.id = m.materiel', array('descriptionMateriel' => 'sm.descriptionMateriel','unite' => 'unite'));
                
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
}