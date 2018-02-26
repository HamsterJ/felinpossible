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
class FP_Model_Mapper_DemandeFicheSoinsMapper extends FP_Model_Mapper_CommonMapper {
    protected $idClassName = 'DemandeFicheSoins';

    protected $mappingDbToModel = array(// db => classe
                                        'id'               => 'id',
                                        'nom'              => 'nom',
                                        'login'            => 'login',
                                        'nomChat'          => 'nomChat',
                                        'idVeto'           => 'idVeto',
                                        'vetoCompl'        => 'vetoCompl',
                                        'dateDemande'      => 'dateDemande',
                                        'dateVisite'       => 'dateVisite',
                                        'soinIdent'        => 'soinIdent',
                                        'soinTests'        => 'soinTests',
                                        'soinVaccins'      => 'soinVaccins',
                                        'soinSterilisation'=> 'soinSterilisation',
                                        'soinVermifuge'    => 'soinVermifuge',
                                        'soinAntiParasites'=> 'soinAntiParasites', 
                                        'soinAutre'        => 'soinAutre',
                                        'token'            => 'token',
                                        'ficheGeneree'     => 'ficheGeneree',
                                        'envoiVeto'        => 'envoiVeto');
        
        
        /**
	 * (non-PHPdoc) - Retourne les données de demandes de fiches de soins pour le tableau de l'admin
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArray($sort, $order, $start, $count, $where)
	 */
	public function fetchAllToArray($sort = 'dateDemande', $order = FP_Util_TriUtil::ORDER_DESC_KEY, $start = null, $count = null, $where = null)
	{
            $subSelect = $this->getDbTable()->getAdapter()->select()
            ->from( array('d' => 'fp_soins_fiche'), 
                    array('d.id'
                        ,'d.token'
                        ,'dateDemande' => 'STR_TO_DATE(d.dateDemande, "%d-%m-%Y")'
                        ,'d.nom'
                        ,'d.login'
                        ,'nomChat' =>'UPPER(nomChat)'
                        ,'dateVisite' => 'STR_TO_DATE(dateVisite, "%d/%m/%Y")'
                        ,'soinIdent' => 'IF(d.soinIdent>0,"Oui","")'
                        ,'soinTests' => 'IF(d.soinTests>0,"Oui","")'
                        ,'soinVaccins' => 'IF(d.soinVaccins>0,"Oui","")'
                        ,'soinSterilisation' => 'IF(d.soinSterilisation>0,"Oui","")'
                        ,'soinVermifuge' => 'IF(d.soinVermifuge>0,"Oui","")'
                        ,'soinAntiParasites' => 'IF(d.soinAntiParasites>0,"Oui","")'
                        ,'ficheGeneree' => 'IF(d.ficheGeneree>0,"Oui","NON")'
                        ,'envoiVeto' => 'IF(d.envoiVeto>0,"OUI","Non")'
                        ));
                
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

            if ($sort && $order) {
            $select->order(array($sort." ".$order));       
	}
            
            $stmt = $select->query();
            return $stmt->fetchAll();
	}
}