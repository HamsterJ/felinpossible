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
            $result .='<tr style="text-align:left;border-bottom-width:1px;border-bottom-color:grey;border-bottom-style:solid;">'
                    . '<th width="3%"></th><th width="43%">Matériel</th><th width="22%">État</th><th width="10%">Qté</th><th width="8%"></th><th></th></tr>';
            //construction d'un tableau de matériels (case à cocher - libellé - liste déroulante 'etat'
            foreach($data['mats'] as $pos => $mat){
                $result .= '<tr>'
                        . '<input type="hidden" name="n'.$mat['id'].'" value="'.$mat['materiel'].'"/><td width="3%">'
                        . '<input style="vertical-align:baseline;" type="checkbox" name="c'.$mat['id'].'"  checked/></td>'
                        . '<td width="43%"> '.$mat['descriptionMateriel'].'</td>'
                        . '<td width="22%"> <input type="text" name="l'.$mat['id'].'" style="width:150px;height:15px;margin-bottom:0px;" value="Bon">'
                        ;
                $result .= '</td>'
                        . '<td width="10%"> <input type="text" name="q'.$mat['id'].'" style="width:50px;height:15px;margin-bottom:0px;" value="'.$mat['quantite'].'"></td>'
                        . '<td width="8%"><span style="font-size:80%;">'.$mat['unite'].'</span></td>'
                        . '<td><i><span style="color: grey;font-size:80%;">Stock restant : '.$mat['StockRestant'].'</span></i></td>'
                        . '</tr>'; 
            }
            //$result .= '</tr></tr><tr><td colspan="6"><b>Commentaires :</b> '.$data['demande'][0]['commentaire'].'</td></tr>';
            //$result .= '<tr><td>&nbsp;</td><td id="submit-element"><input name="submit" class="btn btn-primary" id="submit" type="submit" value="Traiter"></td><td>&nbsp;</td></tr>';
        
            $result .= '</table><hr><b>Commentaires :</b> '.$data['demande'][0]['commentaire'].'</br><hr></br>';
            $result .= '<input name="submit" class="btn btn-primary" id="submit" type="submit" value="Traiter">';
        
 
            return $result; // retourne le tableau de matériels
        }
        return '';// la demande n'est pas trouvée : n'affiche rien
    }
}