<?php
/**
 * DemandeFicheSoins data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_StockMaterielMapper extends FP_Model_Mapper_CommonMapper {
    protected $idClassName = 'StockMateriel';

    protected $mappingDbToModel = array(// db => classe
                                        'id'                   => 'id' ,
                                        'DescriptionMateriel'  => 'DescriptionMateriel',
                                        'StockEnPret'           => 'StockEnPret'  ,
                                        'StockRestant'         => 'StockRestant',
                                        'Unite'                => 'Unite',
                                        'Categorie'            => 'Categorie',
                                        'SuiviPrets'            => 'SuiviPrets');

<<<<<<< HEAD
    protected $filterKeyToDbKey = array('DescriptionMateriel' => 'DescriptionMateriel'); 
    
=======
    protected $filterKeyToDbKey = array('DescriptionMateriel' => 'DescriptionMateriel');
        
>>>>>>> 99bdaa0b... fix: ajout mapping pour clef utilisée dans les filtres
    //Recupération des données matériels pour la liste admin
    public function fetchAllToArray($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
    {
        $subSelect = $this->getDbTable()->getAdapter()->select()
        ->from( array('m' => 'fp_stock_materiel'), 
                array('m.id'
                    ,'m.DescriptionMateriel'
                    ,'IF(m.SuiviPrets>0,m.StockEnPret,"-") StockEnPret'
                    ,'m.StockRestant'
                    ,'m.Unite'
                    ,'IF(m.SuiviPrets>0,"O","N") SuiviPrets' 
                    ))
            ->joinLeft(array('c' => 'fp_stock_categorie_materiel'), 'c.id = m.Categorie', array('c.libelle'));

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

    //Recupéartion des données matériel pour affichage dans le formulaire
    public function buildArrayForForm($idColumnName = 'id', $valueColumnName = 'Categorie', $otherColumn = NULL, $emptyValue = false) {
        $select = $this->getDbTable()->getAdapter()->select()
        ->from(array('m' => 'fp_stock_materiel'), array('m.id', 'm.DescriptionMateriel'))
        ->joinLeft(array('c' => 'fp_stock_categorie_materiel'), 'c.id = m.Categorie', array('c.libelle'))
        ;
        $select->where('StockRestant > 0');
        $select->order($valueColumnName);
        $stmt = $select->query();

        $result = array();

        foreach ($stmt->fetchAll() as $row) {
            $result[empty($row['libelle'])?'_AUTRE':strtoupper($row['libelle'])][$row['id']] = $row['DescriptionMateriel'];            
        }
        
        ksort($result);
        foreach($result as $key=>$value)
        {asort($result[$key]);}
        
        return $result;  
    }
    
    //Recuperation d'un matériel
    public function findMatos($where)
    {
        $subSelect = $this->getDbTable()->getAdapter()->select()
        ->from( array(  'e' => 'fp_stock_materiel'), 
                array(  'e.id'
                        ,'e.DescriptionMateriel'
                        ,'e.StockEnPret'
                        ,'e.StockRestant'
                        , 'e.Unite'
                        , 'e.Categorie'
                        , 'e.SuiviPrets'))
        ->joinLeft(array('c' => 'fp_stock_categorie_materiel'), 'c.id = e.Categorie', array('c.libelle AS Categorie'));
        if ($where) {
            $subSelect->where($where);
        }
        $stmt = $subSelect->query();
        return $stmt->fetchAll();
    }
    
    //Mise à jour d'une ligne de stock
    public function updateStock($array,$where) {
    $this->getDbTable()->update($array,$where);
    }
}