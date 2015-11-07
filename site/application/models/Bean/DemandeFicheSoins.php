<?php
/**
 * DemandeFicheSoins model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_CommonMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_DemandeFicheSoins extends FP_Model_Bean_Common
{
  
	/** @var int  */
	protected $id;
        /** @var date  */
	protected $dateDemande;
	/** @var string  */
	protected $nom;
	/** @var string  */
	protected $login;
	/** @var string  */
	protected $nomChat;
        /** @var date  */
	protected $dateVisite;
	/** @var int  */
	protected $idVeto;
	/** @var string  */
	protected $vetoCompl;
	/** @var boolean  */
	protected $soinIdent;
	/** @var boolean  */
	protected $soinTests;
	/** @var boolean  */
	protected $soinVaccins;
	/** @var boolean  */
	protected $soinSterilisation;
	/** @var boolean  */
	protected $soinVermifuge;
	/** @var boolean  */
	protected $soinAntiParasites;
	/** @var string  */
	protected $soinAutre;
        /** @var string  */
	protected $token;  
        /** @var int  */
	protected $ficheGeneree; 
        /** @var int  */
	protected $envoiVeto; 
        /** @var string  */
	protected $submit;

        
        public function getId() {
            return $this->id;
        }
        
        public function getFicheGeneree() {
            return $this->ficheGeneree;
        }

        public function getDateDemande() {
            return $this->dateDemande;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getLogin() {
            return $this->login;
        }

        public function getNomChat() {
            return $this->nomChat;
        }
        
        public function getDateVisite() {
            return $this->dateVisite;
        }

        public function getIdVeto() {
            return $this->idVeto;
        }

        public function getVetoCompl() {
            return $this->vetoCompl;
        }

        public function getSoinIdent() {
            return $this->soinIdent;
        }

        public function getSoinTests() {
            return $this->soinTests;
        }

        public function getSoinVaccins() {
            return $this->soinVaccins;
        }

        public function getSoinSterilisation() {
            return $this->soinSterilisation;
        }

        public function getSoinVermifuge() {
            return $this->soinVermifuge;
        }

        public function getSoinAntiParasites() {
            return $this->soinAntiParasites;
        }

        public function getSoinAutre() {
            return $this->soinAutre;
        }

         public function getSubmit() {
            return $this->submit;
        }

        public function setSubmit($submit) {
            $this->submit = $submit;
        }
        
        public function setId($id) {
            $this->id = $id;
        }
        
        public function setFicheGeneree($ficheGeneree) {
            $this->ficheGeneree = $ficheGeneree;
        }

        public function setDateDemande($dateDemande) {
            $this->dateDemande = $dateDemande;
        }

        public function setNom($nom) {
            $this->nom = $nom;
        }

        public function setLogin($login) {
            $this->login = $login;
        }

        public function setNomChat($nomChat) {
            $this->nomChat = $nomChat;
        }

        public function setDateVisite($dateVisite) {
            $this->dateVisite = $dateVisite;
        }
        
        public function setIdVeto($idVeto) {
            $this->idVeto = $idVeto;
        }

        public function setVetoCompl($vetoCompl) {
            $this->vetoCompl = $vetoCompl;
        }

        public function setSoinIdent($soinIdent) {
            $this->soinIdent = $soinIdent;
        }

        public function setSoinTests($soinTests) {
            $this->soinTests = $soinTests;
        }

        public function setSoinVaccins($soinVaccins) {
            $this->soinVaccins = $soinVaccins;
        }

        public function setSoinSterilisation($soinSterilisation) {
            $this->soinSterilisation = $soinSterilisation;
        }

        public function setSoinVermifuge($soinVermifuge) {
            $this->soinVermifuge = $soinVermifuge;
        }

        public function setSoinAntiParasites($soinAntiParasites) {
            $this->soinAntiParasites = $soinAntiParasites;
        }

        public function setSoinAutre($soinAutre) {
            $this->soinAutre = $soinAutre;
        }
        
        public function setToken($token) {
            $this->token = $token;
        }
        public function getToken() {
            return $this->token;
        }
        
        public function setEnvoiVeto($envoiVeto) {
            $this->envoiVeto = $envoiVeto;
        }
        public function getEnvoiVeto() {
            return $this->envoiVeto;
        }   
}