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
class FP_Model_Mapper_StockMaterielFAMapper extends FP_Model_Mapper_CommonMapper {

    protected $idClassName = 'StockMaterielFA';

    protected $mappingDbToModel = array(
                    'id'                => 'id',
                    'idDemandeMateriel' => 'idDemandeMateriel',
                    'idFA'              => 'idFA',
                    'login'             => 'login',
                    'idMateriel'        => 'idMateriel',
                    'etat'              => 'etat',
                    'quantite'          => 'quantite'
        );

        protected $filterKeyToDbKey = array('login' => 'fa.login','nom' =>'fa.nom' ); 
    
    	/**
	 * Count number of rows in the table.
	 * @param $where where clause.
	 * @return int
	 */
	public function count($where = null)
	{ 
            $subSelect = $this->getDbTable()->getAdapter()->select()
            ->from( array('m' => 'fp_stock_materiel_fa'), 
                array('compte' => 'count(distinct idFA)'))
            ->joinLeft(array('fa' => 'fp_fa_fiche'), 'fa.id = m.idFA',array());
            
            if ($where)
                {
                    $subSelect->where($where);
                }
            
           // $subSelect->group('idFA');
            $stmt = $subSelect->query();
            
            return $stmt->fetch()['compte'];
	}
    
    //Sauvegarde des affectations de matériels à une FA
    public function saveAffect($data)
    {
       $this->getDbTable()->insert($data);
    }
      
    //Recup du nombre de matériels en prêt dans chaque FA
    public function fetchAllToArray($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
    {
        $subSelect = $this->getDbTable()->getAdapter()->select()
        ->from( array('m' => 'fp_stock_materiel_fa'), 
                array('m.idFA AS id'
                    ,"nb"=> "CONCAT(count(1),' matériel(s)')"))
        ->joinLeft(array('fa' => 'fp_fa_fiche'), 'fa.id = m.idFA', array('infoFA' => 'CONCAT(UPPER(fa.nom), \' \', fa.prenom)', 'login' => 'fa.login', 'nom' => 'fa.nom'))
        ;
        if ($where)
        {
            $subSelect->where($where);
        }
        $subSelect->group('idFA');

        $stmt = $subSelect->query();
        return $stmt->fetchAll();
    }
    
    //Récupération d'un prêt
    public function findMatosFA($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
    {
        $subSelect = $this->getDbTable()->getAdapter()->select()
        ->from( array(  'e' => 'fp_stock_materiel_fa'), 
                array(  'e.id'
                        ,'e.idMateriel'
                        ,'e.etat'
                        ,'e.quantite'
                        , 'e.login'))
        ->joinLeft(array('ma' => 'fp_stock_materiel'), 'e.idMateriel = ma.id', array('descriptionMateriel' => 'descriptionMateriel','suiviPrets' => 'suiviPrets' ))
        ;
        if ($where) {
            $subSelect->where($where);
        }
        $stmt = $subSelect->query();
        return $stmt->fetchAll();
    }

    //suppression du matos dans une FA
    public function supprimerMaterielDeFA($id) {
            $this->delete("id = ".$id);
    }
    
    //changer l'id FA d'une affectation
    public function transfererMatFA($idAffectation,$toFA) {
        $a = array();
        $a['idFa']=$toFA;
        $this->getDbTable()->update($a,'id='.$idAffectation);
    }  
}