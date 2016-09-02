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

    //Sauvegarde des affectations de matériels à une FA
    public function saveAffect($data)
    {
       $this->getDbTable()->insert($data);
    }
    
    //Recup de toute la liste des emprunts (pour l'admin)
    public function fetchAllToArray($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
    {
        $subSelect = $this->getDbTable()->getAdapter()->select()
        ->from( array('m' => 'fp_stock_materiel_fa'), 
                array('m.id'
                    ,'m.idFA'
                    ,'m.login'
                    ,'m.idMateriel'
                    ,'m.idDemandeMateriel'
                    ,'m.etat'
                    ))
                ->joinLeft(array('sm' => 'fp_stock_materiel'), 'sm.id = m.idMateriel', array('descriptionMateriel' => 'sm.descriptionMateriel'))
                ->joinLeft(array('fa' => 'fp_fa_fiche'), 'fa.id = m.idFA', array('infoFA' => 'CONCAT(fa.prenom, \' \', fa.nom, COALESCE(CONCAT(\' (\', fa.login, \')\'), \'\'))'))
        ;

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
    
    //Recup du nombre de matériels en prêt dans chaque FA
    public function fetchFAEmpruntsToArray($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
    {
        $subSelect = $this->getDbTable()->getAdapter()->select()
        ->from( array('m' => 'fp_stock_materiel_fa'), 
                array('m.idFA AS id'
                    ,"CONCAT(count(1),' matériel(s)') nb"))
        ->joinLeft(array('fa' => 'fp_fa_fiche'), 'fa.id = m.idFA', array('infoFA' => 'CONCAT(fa.prenom, \' \', fa.nom)', 'login' => 'fa.login'))
        ;
        
        $subSelect->group('idFA');

        $stmt = $subSelect->query();
        return $stmt->fetchAll();
    }
    
    //Récupération d'un prêt
    public function findMatosFA($where)
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
}