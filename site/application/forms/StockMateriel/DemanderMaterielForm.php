<?php
/**
 * Formulaire pour la demande de matériel.
 * @author mmo
 */
class FP_Form_stockMateriel_DemanderForm extends FP_Form_common_Form {
   
    public function init() {
        $this->setMethod('post');
        $this->setName('DemandeMateriel');
        $this->setAttrib('class', 'formOrange');

        $login = new Zend_Form_Element_Text('login');
        $login->setLabel('Quel est votre pseudo sur le forum');
        $login->setFilters(array('StringTrim'));
        $login->setRequired(true);
        
        $idDemandeMateriel = new Zend_Form_Element_Hidden('idDemandeMateriel');
        $idDemandeMateriel->setValue(str_replace('.','',microtime(true)));  	
        
        $this->addElement($login);
        $this->addElement($idDemandeMateriel);
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Continuer',
            'class'    => 'btn btn-primary'));
    }
    
      
    public function afficher() {
        $service = FP_Service_StockMaterielServices::getInstance();
        $data = $service->getCompleteData($this->getElement('idDemandeMateriel')->getValue());
        
        if ($data)
        {
            $result = "<b>Famille d'accueil : </b>";
            $result .= $data['demande'][0]['infoFA'];
            $result .='</br></br>';
            $result .='<input type="hidden" name="idDemandeMateriel" value="'.$data['demande'][0]['idDemandeMateriel'].'">';
            $result .='<input type="hidden" name="login" value="'.$data['demande'][0]['login'].'">';
            $result .='<b><u>Matériels</u> : </b></br></br><table class ="table-condensed">';
            $result .='<tr style="text-align:left;border-bottom-width:1px;border-bottom-color:grey;border-bottom-style:solid;"><th></th><th>Matériel</th><th>État</th><th>Quantité</th></tr>';
            //construction d'un tableau de matériels (case à cocher - libellé - liste déroulante 'etat'
            foreach($data['mats'] as $pos => $mat){
                $result .= '<tr>'
                            . '<td width="40px"><input style="vertical-align:baseline;" type="checkbox" name="c'.$mat['materiel'].'"  checked/></td>'
                            . '<td width="250px"> '.$mat['descriptionMateriel'].'</td>'
                            . '<td width="120px"> <select name="l'.$mat['materiel'].'" style="width:100px;height:25px;margin-bottom:0px;padding:2px;vertical-align:middle;">';
                
                foreach(FP_Util_Constantes::$ETAT_MATERIEL as $key=>$value)
                    {$result .='<option>'.$value;} //<option>Bon<option>Moyen<option>Mauvais
                            
                $result .= '</select></td>'
                            . '<td width="100px"> <input type="text" name="q'.$mat['materiel'].'" style="width:50px;height:15px;margin-bottom:0px;" value="1"></td>'
                        . '<td width="100px">'.$mat['unite'].'</td>'
                        . '</tr>'; 
            }
            $result .= '<tr><td>&nbsp;</td><td id="submit-element"><input name="submit" class="btn btn-primary" id="submit" type="submit" value="Traiter"></td><td>&nbsp;</td></tr>';
        
            
            return $result; // retourne le tableau de matériels
        }
        return '';// la demande n'est pas trouvée : n'affiche rien
    }
}