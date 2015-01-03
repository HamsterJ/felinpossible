<?php
/**
 * Services pour les Demandes de fiches de soins.
 * @author mmo
 *
 */
class FP_Service_DemandeFicheSoinsServices extends FP_Service_CommonServices {
	/**
	 * Instance courante.
	 * @var FP_Service_DemandeFicheSoinsServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_DemandeFicheSoinsServices
	 */
	public static function getInstance() {
            if (empty(self::$instance)) {
                    self::$instance = new FP_Service_DemandeFicheSoinsServices();
            }
            return self::$instance;
	}

	/**
	 * Return le mapper des fiches de soins
	 * @return FP_Model_Mapper_FicheSoinsMapper
	 */
	protected function getMapper() {
            return FP_Model_Mapper_MapperFactory::getInstance()->DemandeFicheSoinsMapper;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getEmptyBean()
	 * @return FP_Model_Bean_DemandeFicheSoins
	 */
	protected function getEmptyBean() {
            $fs = new FP_Model_Bean_DemandeFicheSoins();
            return $fs;
	}

        
        /*
	 * Enregistre une demande de fiche de soins
	 * @param form $Fiche : Formulaire de demande de fiche
        */ 
        public function saveDemandeFicheSoins($fiche) {
            $fiche['token'] = crypt($fiche['nomChat'].$fiche['dateDemande']);
            $this->save($fiche);
            // Envoi mail a l'asso avec lien pour traitement
            FP_Service_MailServices::getInstance()->envoiMailDemandeFicheSoins($fiche);
	}
       
        /*
	 * Récupère une demande de fiche de soins à partir de la clef (token) recue par mail
	 * @param form $token : Clef de demande de fiche
        */
        public function getDemandeParToken($token ) {
            $tok = str_replace('"', '', $token); //supression des " pour éviter les emmerdes
            $replace_sql = " REPLACE(token,'\"','')";
            return $this->getMapper()->select('*',$replace_sql.'="'.$tok.'"','dateDemande desc');
	}
     
        /*
	 * Récupère une demande de fiche de soins à partir de son id (pour l'admin)
        */
        public function getDataDemandeSoins($id) {
            return $this->getMapper()->select('*','id="'.$id.'"','dateDemande desc');
	}
        
        /*
	 * Pré-remplissage du formulaire de génération de fiches de soins à partir de la demande saisie par la FA
	 * @param form $demande : Demande de la FA sous form de FP_Model_Bean_DemandeFicheSoins
         * @return FP_Form_chat_DemanderFicheForm
         */
        public function getFormPrerempli($demande) {
        
            $elements = array();
            $elements['token'] = $demande->getToken();
            
            if ($demande->getLogin())// Si on a le login de la FA (on ne fait pas attention aux accents), on récupère toutes ses infos
            {
                $fs = FP_Service_FaServices::getInstance();
                $fa = $fs->getMapper()->select(null,'upper(login)=upper("'.$demande->getLogin().'") COLLATE utf8_general_ci',null);
            }
            
            if ($fa == null) // login mal tapé, ou pas de login renseigné dans la base FA (cas où c'est une nouvelle FA ou pour un chat hors asso)
            {
                //on cherche à partir du nom/prenom (on ne fait pas attention aux accents), mais bon il faut que ca soit exact, c'est pas gagné !
                $nom = str_replace(' ','',$demande->getNom());//on enleve les espaces dans le nom entré par la FA
                $fs = FP_Service_FaServices::getInstance();
                $fa = $fs->getMapper()
                        ->select(null
                                ,'upper(nom)=upper("'.$nom.'" COLLATE utf8_general_ci) 
                                  OR CONCAT(upper(nom),upper(prenom))=upper("'.$nom.'" COLLATE utf8_general_ci)
                                  OR CONCAT(upper(prenom),upper(nom))=upper("'.$nom.'" COLLATE utf8_general_ci)'
                                ,null);
            }
           
            if ($fa){//on a trouvé quelque chose en base avec les infos FA qu'on avait dans la demande
                $elements['nom']                = ($fa[0]->nom).' '.($fa[0]->prenom); 
                $elements['qualite']            = "Famille d'accueil"; 
                $elements['adresse']            = $fa[0]->adresse; 
                $elements['codePostal']         = $fa[0]->codePostal; 
                $elements['ville']              = $fa[0]->ville; 
                $elements['telephoneFixe']      = $fa[0]->telephoneFixe;
                $elements['telephonePortable']  = $fa[0]->telephonePortable;
            }
            else {
                $elements['nom']                = $demande->getNom(); 
            }
            
            if ($demande->getNomChat())
            {
                $cs = FP_Service_ChatServices::getInstance();
                $infosChat = $cs->getMapper()->select(null,'upper(nom)=upper("'.$demande->getNomChat().'"  COLLATE utf8_general_ci)',null);
                
                if ($infosChat){//Si on a un nom de chat précis (et pas "les 3 chatons noirs"), on récupère les infos
                    $coulMapper = FP_Model_Mapper_MapperFactory::getInstance()->couleurMapper;
                    $couleur = $coulMapper->find($infosChat[0]->idCouleur);

                    $sexeMapper = FP_Model_Mapper_MapperFactory::getInstance()->sexeMapper;
                    $sexe = $sexeMapper->find($infosChat[0]->idSexe);

                    $elements['nomChat']            = $infosChat[0]->nom; 
                    $elements['couleur']            = $couleur->libelleCouleur; 
                    $elements['identification']     = $infosChat[0]->tatouage; 
                    $elements['dateNaissance']      = DateTime::createFromFormat('Y-m-d', $infosChat[0]->dateNaissance)->format('d/m/Y'); 
                    $elements['dateApprox']         = $infosChat[0]->dateApproximative; 
                    $elements['sexe']               = $sexe->libelle; 
                }
                else 
                { 
                    $elements['nomChat']            = $demande->getNomChat();
                }
            } 

            $elements['qualite']                = "Famille d'accueil"; 
            $elements['idVeto']                 = $demande->getIdVeto();
            $elements['soinPuce']               = $demande->getSoinIdent();
            $elements['soinTatouage']           = $demande->getSoinIdent();
            $elements['soinTests']              = $demande->getSoinTests();
            $elements['soinVaccins']            = $demande->getSoinVaccins();
            $elements['soinVermifuge']          = $demande->getSoinVermifuge();
            $elements['soinAntiParasites']      = $demande->getSoinAntiParasites();
            //$elements['soinAutre']              = $demande->getSoinAutre();
            
            if ($infosChat){
            //Petite bidouille pour dire si c'est ovario ou castration (en fonction du sexe)
                $elements['soinSterilisation']      = $demande->getSoinSterilisation()*(($infosChat[0]->idSexe == 2)?1:3); // femelle => ovario / mâle => castration
            }
            else { //sinon on met arbitrairement 'stérilisation' au besoin
                $elements['soinSterilisation']      = $demande->getSoinSterilisation()*4;
            }
    
	        //Pour le veto, c'est un peu plus compliqué : ca peut être "Autre" ou "Aucun", 
            //dans ce cas on ajoute le commentaire véto de la demande dans le commentaire général de la fiche
            
            if (($demande->getIdVeto() == -1)||($demande->getIdVeto() == -99)){
                $elements['soinAutre'] = $demande->getSoinAutre().'. '.$demande->getVetoCompl();
            }
			else
			{
				$elements['soinAutre'] = $demande->getSoinAutre();
			}
	
            $form = new FP_Form_chat_FicheSoinsForm();
            $form->populate($elements);
			
            $form->idVeto->setValue = $demande->idVeto;
			
            return $form; 
        }    
        
         /**
	 * Génère la fiche de soins PDF pour le chat sélectionné, et flag la demande d'origine le cas échéant.
	 * @param FP_Form_chat_FicheSoinsForm $ficheSoinForm
	 */
	public function generateFicheSoinsFPDF($ficheSoinForm) {
            include('../application/utils/FPDF/Soins.php');           
            print_pdf($ficheSoinForm);
             
            //si l'impression provient d'une demande, on la récupère par le token et on note qu'elle est traitée
            if ($ficheSoinForm->token->getValue()){
                $demande = $this->getDemandeParToken($ficheSoinForm->token->getValue());
                if ($demande){
                    $demande[0]->setFicheGeneree(1);
                    $this->getMapper()->save($demande[0]);
                }
            }    
	}
}