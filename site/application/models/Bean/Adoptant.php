<?php
/**
 * Adoptant model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_AdoptantMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_Adoptant extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	protected $nom;
	protected $prenom;
	protected $adresse;
	protected $codePostal;
	protected $ville;
	protected $telephoneFixe;
	protected $telephonePortable;
	protected $email;
	protected $idLogement;
	protected $idDependance;
	protected $depSecurise;
	protected $hasChatiere;
	protected $etage;
	protected $nbPersonnes;
	protected $age;
	protected $nbEnfants;
	protected $enfantsAge;
	protected $heuresDansFoyer;
	protected $personnesAllergiques;
	protected $personnesDesirantPasChat;
	protected $animauxAutres;
	protected $habitudeChat;
	protected $chats;
	protected $motivations;
	protected $criteres;
	protected $repererChat;
	protected $idDestineA;
	protected $revenusReguliers;
	protected $assumerFraisVeto;
	protected $idSolutionGardeVacances;
	protected $idSolutionDemenagement;
	protected $idFonderFamille;
	protected $garderTouteSaVie;
	protected $isSecurFenetres;
	protected $isSurvFenetres;
	protected $contacterVeto;
	protected $traiterParasites;
	protected $rappelVaccins;
	protected $bonneAlimentation;
	protected $steriliser;
	protected $passerTempsAvecChat;
	protected $garderLitierePropre;
	protected $signalerChangementAdr;
	protected $donnerNouvelles;
	protected $accepterVisite;
	protected $restituerChat;
	protected $remarques;
	protected $idConnaissanceAsso;
	protected $profession;
	protected $heureJoignable;
	protected $login;
	protected $dateSubmit;
	protected $superficie;
	protected $connaissanceAssoDetail;

	/**
	 * Retrieve id
	 *
	 * @param  id
	 * @return la valeur de id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set entry id
	 *
	 * @param id
	 */
	public function setId($id) {
		$this->id = (int) $id;
	}

	/**
	 * Retrieve nom
	 *
	 * @param  nom
	 * @return la valeur de nom
	 */
	public function getNom() {
		return $this->nom;
	}

	/**
	 * Set entry nom
	 *
	 * @param nom
	 */
	public function setNom($nom) {
		$this->nom = $nom;
	}

	/**
	 * Retrieve prénom
	 *
	 * @param  prénom
	 * @return la valeur de prénom
	 */
	public function getPrenom() {
		return $this->prenom;
	}

	/**
	 * Set entry prénom
	 *
	 * @param prénom
	 */
	public function setPrenom($prenom) {
		$this->prenom = $prenom;
	}

	/**
	 * Retrieve adresse
	 *
	 * @param  adresse
	 * @return la valeur de adresse
	 */
	public function getAdresse() {
		return $this->adresse;
	}

	/**
	 * Set entry adresse
	 *
	 * @param adresse
	 */
	public function setAdresse($adresse) {
		$this->adresse = $adresse;
	}

	/**
	 * Retrieve codePostal
	 *
	 * @param  codePostal
	 * @return la valeur de codePostal
	 */
	public function getCodePostal() {
		return $this->codePostal;
	}

	/**
	 * Set entry codePostal
	 *
	 * @param codePostal
	 */
	public function setCodePostal($codePostal) {
		$this->codePostal = $codePostal;
	}

	/**
	 * Retrieve ville
	 *
	 * @param  ville
	 * @return la valeur de ville
	 */
	public function getVille() {
		return $this->ville;
	}

	/**
	 * Set entry ville
	 *
	 * @param ville
	 */
	public function setVille($ville) {
		$this->ville = $ville;
	}

	/**
	 * Retrieve telephoneFixe
	 *
	 * @param  telephoneFixe
	 * @return la valeur de telephoneFixe
	 */
	public function getTelephoneFixe() {
		return $this->telephoneFixe;
	}

	/**
	 * Set entry telephoneFixe
	 *
	 * @param telephoneFixe
	 */
	public function setTelephoneFixe($telephoneFixe) {
		$this->telephoneFixe = $telephoneFixe;
	}

	/**
	 * Retrieve telephonePortable
	 *
	 * @param  telephonePortable
	 * @return la valeur de telephonePortable
	 */
	public function getTelephonePortable() {
		return $this->telephonePortable;
	}

	/**
	 * Set entry telephonePortable
	 *
	 * @param telephonePortable
	 */
	public function setTelephonePortable($telephonePortable) {
		$this->telephonePortable = $telephonePortable;
	}

	/**
	 * Retrieve email
	 *
	 * @param  email
	 * @return la valeur de email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set entry email
	 *
	 * @param email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Retrieve idLogement
	 *
	 * @param  idLogement
	 * @return la valeur de idLogement
	 */
	public function getIdLogement() {
		return $this->idLogement;
	}

	/**
	 * Set entry idLogement
	 *
	 * @param idLogement
	 */
	public function setIdLogement($idLogement) {
		$this->idLogement = $idLogement;
	}

	/**
	 * Retrieve idDependance
	 *
	 * @param  idDependance
	 * @return la valeur de idDependance
	 */
	public function getIdDependance() {
		return $this->idDependance;
	}

	/**
	 * Set entry idDependance
	 *
	 * @param idDependance
	 */
	public function setIdDependance($idDependance) {
		$this->idDependance = $idDependance;
	}

	/**
	 * Retrieve depSecurise
	 *
	 * @param  depSecurise
	 * @return la valeur de depSecurise
	 */
	public function getDepSecurise() {
		return $this->depSecurise;
	}

	/**
	 * Set entry depSecurise
	 *
	 * @param depSecurise
	 */
	public function setDepSecurise($depSecurise) {
		$this->depSecurise = $depSecurise;
	}

	/**
	 * Retrieve hasChatiere
	 *
	 * @param  hasChatiere
	 * @return la valeur de hasChatiere
	 */
	public function getHasChatiere() {
		return $this->hasChatiere;
	}

	/**
	 * Set entry hasChatiere
	 *
	 * @param hasChatiere
	 */
	public function setHasChatiere($hasChatiere) {
		$this->hasChatiere = $hasChatiere;
	}

	/**
	 * Retrieve etage
	 *
	 * @param  etage
	 * @return la valeur de etage
	 */
	public function getEtage() {
		return (int) $this->etage;
	}

	/**
	 * Set entry etage
	 *
	 * @param etage
	 */
	public function setEtage($etage) {
		$this->etage = (int) $etage;
	}

	/**
	 * Retrieve nbPersonnes
	 *
	 * @param  nbPersonnes
	 * @return la valeur de nbPersonnes
	 */
	public function getNbPersonnes() {
		return $this->nbPersonnes;
	}

	/**
	 * Set entry nbPersonnes
	 *
	 * @param nbPersonnes
	 */
	public function setNbPersonnes($nbPersonnes) {
		$this->nbPersonnes = $nbPersonnes;
	}

	/**
	 * Retrieve age
	 *
	 * @param  age
	 * @return la valeur de age
	 */
	public function getAge() {
		return $this->age;
	}

	/**
	 * Set entry age
	 *
	 * @param age
	 */
	public function setAge($age) {
		$this->age = $age;
	}

	/**
	 * Retrieve nbEnfants
	 *
	 * @param  nbEnfants
	 * @return la valeur de nbEnfants
	 */
	public function getNbEnfants() {
		return (int) $this->nbEnfants;
	}

	/**
	 * Set entry nbEnfants
	 *
	 * @param nbEnfants
	 */
	public function setNbEnfants($nbEnfants) {
		$this->nbEnfants = (int) $nbEnfants;
	}

	/**
	 * Retrieve enfantsAge
	 *
	 * @param  enfantsAge
	 * @return la valeur de enfantsAge
	 */
	public function getEnfantsAge() {
		return $this->enfantsAge;
	}

	/**
	 * Set entry enfantsAge
	 *
	 * @param enfantsAge
	 */
	public function setEnfantsAge($enfantsAge) {
		$this->enfantsAge = $enfantsAge;
	}

	/**
	 * Retrieve heuresDansFoyer
	 *
	 * @param  heuresDansFoyer
	 * @return la valeur de heuresDansFoyer
	 */
	public function getHeuresDansFoyer() {
		return $this->heuresDansFoyer;
	}

	/**
	 * Set entry heuresDansFoyer
	 *
	 * @param heuresDansFoyer
	 */
	public function setHeuresDansFoyer($heuresDansFoyer) {
		$this->heuresDansFoyer = $heuresDansFoyer;
	}

	/**
	 * Retrieve personnesAllergiques
	 *
	 * @param  personnesAllergiques
	 * @return la valeur de personnesAllergiques
	 */
	public function getPersonnesAllergiques() {
		return $this->personnesAllergiques;
	}

	/**
	 * Set entry personnesAllergiques
	 *
	 * @param personnesAllergiques
	 */
	public function setPersonnesAllergiques($personnesAllergiques) {
		$this->personnesAllergiques = $personnesAllergiques;
	}

	/**
	 * Retrieve personnesDesirantPasChat
	 *
	 * @param  personnesDesirantPasChat
	 * @return la valeur de personnesDesirantPasChat
	 */
	public function getPersonnesDesirantPasChat() {
		return $this->personnesDesirantPasChat;
	}

	/**
	 * Set entry personnesDesirantPasChat
	 *
	 * @param personnesDesirantPasChat
	 */
	public function setPersonnesDesirantPasChat($personnesDesirantPasChat) {
		$this->personnesDesirantPasChat = $personnesDesirantPasChat;
	}

	/**
	 * Retrieve animauxAutres
	 *
	 * @param  animauxAutres
	 * @return la valeur de animauxAutres
	 */
	public function getAnimauxAutres() {
		return $this->animauxAutres;
	}

	/**
	 * Set entry animauxAutres
	 *
	 * @param animauxAutres
	 */
	public function setAnimauxAutres($animauxAutres) {
		$this->animauxAutres = $animauxAutres;
	}

	/**
	 * Retrieve habitudeChat
	 *
	 * @param  habitudeChat
	 * @return la valeur de habitudeChat
	 */
	public function getHabitudeChat() {
		return $this->habitudeChat;
	}

	/**
	 * Set entry habitudeChat
	 *
	 * @param habitudeChat
	 */
	public function setHabitudeChat($habitudeChat) {
		$this->habitudeChat = $habitudeChat;
	}

	/**
	 * Retrieve chats
	 *
	 * @param  chats
	 * @return la valeur de chats
	 */
	public function getChats() {
		return $this->chats;
	}

	/**
	 * Set entry chats
	 *
	 * @param chats
	 */
	public function setChats($chats) {
		$this->chats = $chats;
	}

	/**
	 * Retrieve motivations
	 *
	 * @param  motivations
	 * @return la valeur de motivations
	 */
	public function getMotivations() {
		return $this->motivations;
	}

	/**
	 * Set entry motivations
	 *
	 * @param motivations
	 */
	public function setMotivations($motivations) {
		$this->motivations = $motivations;
	}

	/**
	 * Retrieve criteres
	 *
	 * @param  criteres
	 * @return la valeur de criteres
	 */
	public function getCriteres() {
		return $this->criteres;
	}

	/**
	 * Set entry criteres
	 *
	 * @param criteres
	 */
	public function setCriteres($criteres) {
		$this->criteres = $criteres;
	}

	/**
	 * Retrieve repererChat
	 *
	 * @param  repererChat
	 * @return la valeur de repererChat
	 */
	public function getRepererChat() {
		return $this->repererChat;
	}

	/**
	 * Set entry repererChat
	 *
	 * @param repererChat
	 */
	public function setRepererChat($repererChat) {
		$this->repererChat = $repererChat;
	}

	/**
	 * Retrieve idDestineA
	 *
	 * @param  idDestineA
	 * @return la valeur de idDestineA
	 */
	public function getIdDestineA() {
		return $this->idDestineA;
	}

	/**
	 * Set entry idDestineA
	 *
	 * @param idDestineA
	 */
	public function setIdDestineA($idDestineA) {
		$this->idDestineA = $idDestineA;
	}

	/**
	 * Retrieve revenusReguliers
	 *
	 * @param  revenusReguliers
	 * @return la valeur de revenusReguliers
	 */
	public function getRevenusReguliers() {
		return $this->revenusReguliers;
	}

	/**
	 * Set entry revenusReguliers
	 *
	 * @param revenusReguliers
	 */
	public function setRevenusReguliers($revenusReguliers) {
		$this->revenusReguliers = $revenusReguliers;
	}

	/**
	 * Retrieve assumerFraisVeto
	 *
	 * @param  assumerFraisVeto
	 * @return la valeur de assumerFraisVeto
	 */
	public function getAssumerFraisVeto() {
		return $this->assumerFraisVeto;
	}

	/**
	 * Set entry assumerFraisVeto
	 *
	 * @param assumerFraisVeto
	 */
	public function setAssumerFraisVeto($assumerFraisVeto) {
		$this->assumerFraisVeto = $assumerFraisVeto;
	}

	/**
	 * Retrieve idSolutionGardeVacances
	 *
	 * @param  idSolutionGardeVacances
	 * @return la valeur de idSolutionGardeVacances
	 */
	public function getIdSolutionGardeVacances() {
		return $this->idSolutionGardeVacances;
	}

	/**
	 * Set entry idSolutionGardeVacances
	 *
	 * @param idSolutionGardeVacances
	 */
	public function setIdSolutionGardeVacances($idSolutionGardeVacances) {
		$this->idSolutionGardeVacances = $idSolutionGardeVacances;
	}

	/**
	 * Retrieve idSolutionDemenagement
	 *
	 * @param  idSolutionDemenagement
	 * @return la valeur de idSolutionDemenagement
	 */
	public function getIdSolutionDemenagement() {
		return $this->idSolutionDemenagement;
	}

	/**
	 * Set entry idSolutionDemenagement
	 *
	 * @param idSolutionDemenagement
	 */
	public function setIdSolutionDemenagement($idSolutionDemenagement) {
		$this->idSolutionDemenagement = $idSolutionDemenagement;
	}

	/**
	 * Retrieve idFonderFamille
	 *
	 * @param  idFonderFamille
	 * @return la valeur de idFonderFamille
	 */
	public function getIdFonderFamille() {
		return $this->idFonderFamille;
	}

	/**
	 * Set entry idFonderFamille
	 *
	 * @param idFonderFamille
	 */
	public function setIdFonderFamille($idFonderFamille) {
		$this->idFonderFamille = $idFonderFamille;
	}

	/**
	 * Retrieve garderTouteSaVie
	 *
	 * @param  garderTouteSaVie
	 * @return la valeur de garderTouteSaVie
	 */
	public function getGarderTouteSaVie() {
		return $this->garderTouteSaVie;
	}

	/**
	 * Set entry garderTouteSaVie
	 *
	 * @param garderTouteSaVie
	 */
	public function setGarderTouteSaVie($garderTouteSaVie) {
		$this->garderTouteSaVie = $garderTouteSaVie;
	}

	/**
	 * Retrieve isSecurFenetres
	 *
	 * @param  isSecurFenetres
	 * @return la valeur de isSecurFenetres
	 */
	public function getIsSecurFenetres() {
		return $this->isSecurFenetres;
	}

	/**
	 * Set entry isSecurFenetres
	 *
	 * @param isSecurFenetres
	 */
	public function setIsSecurFenetres($isSecurFenetres) {
		$this->isSecurFenetres = $isSecurFenetres;
	}

	/**
	 * Retrieve isSurvFenetres
	 *
	 * @param  isSurvFenetres
	 * @return la valeur de isSurvFenetres
	 */
	public function getIsSurvFenetres() {
		return $this->isSurvFenetres;
	}

	/**
	 * Set entry isSurvFenetres
	 *
	 * @param isSurvFenetres
	 */
	public function setIsSurvFenetres($isSurvFenetres) {
		$this->isSurvFenetres = $isSurvFenetres;
	}

	/**
	 * Retrieve contacterVeto
	 *
	 * @param  contacterVeto
	 * @return la valeur de contacterVeto
	 */
	public function getContacterVeto() {
		return $this->contacterVeto;
	}

	/**
	 * Set entry contacterVeto
	 *
	 * @param contacterVeto
	 */
	public function setContacterVeto($contacterVeto) {
		$this->contacterVeto = $contacterVeto;
	}

	/**
	 * Retrieve traiterParasites
	 *
	 * @param  traiterParasites
	 * @return la valeur de traiterParasites
	 */
	public function getTraiterParasites() {
		return $this->traiterParasites;
	}

	/**
	 * Set entry traiterParasites
	 *
	 * @param traiterParasites
	 */
	public function setTraiterParasites($traiterParasites) {
		$this->traiterParasites = $traiterParasites;
	}

	/**
	 * Retrieve rappelVaccins
	 *
	 * @param  rappelVaccins
	 * @return la valeur de rappelVaccins
	 */
	public function getRappelVaccins() {
		return $this->rappelVaccins;
	}

	/**
	 * Set entry rappelVaccins
	 *
	 * @param rappelVaccins
	 */
	public function setRappelVaccins($rappelVaccins) {
		$this->rappelVaccins = $rappelVaccins;
	}

	/**
	 * Retrieve bonneAlimentation
	 *
	 * @param  bonneAlimentation
	 * @return la valeur de bonneAlimentation
	 */
	public function getBonneAlimentation() {
		return $this->bonneAlimentation;
	}

	/**
	 * Set entry bonneAlimentation
	 *
	 * @param bonneAlimentation
	 */
	public function setBonneAlimentation($bonneAlimentation) {
		$this->bonneAlimentation = $bonneAlimentation;
	}

	/**
	 * Retrieve steriliser
	 *
	 * @param  steriliser
	 * @return la valeur de steriliser
	 */
	public function getSteriliser() {
		return $this->steriliser;
	}

	/**
	 * Set entry steriliser
	 *
	 * @param steriliser
	 */
	public function setSteriliser($steriliser) {
		$this->steriliser = $steriliser;
	}

	/**
	 * Retrieve passerTempsAvecChat
	 *
	 * @param  passerTempsAvecChat
	 * @return la valeur de passerTempsAvecChat
	 */
	public function getPasserTempsAvecChat() {
		return $this->passerTempsAvecChat;
	}

	/**
	 * Set entry passerTempsAvecChat
	 *
	 * @param passerTempsAvecChat
	 */
	public function setPasserTempsAvecChat($passerTempsAvecChat) {
		$this->passerTempsAvecChat = $passerTempsAvecChat;
	}

	/**
	 * Retrieve garderLitierePropre
	 *
	 * @param  garderLitierePropre
	 * @return la valeur de garderLitierePropre
	 */
	public function getGarderLitierePropre() {
		return $this->garderLitierePropre;
	}

	/**
	 * Set entry garderLitierePropre
	 *
	 * @param garderLitierePropre
	 */
	public function setGarderLitierePropre($garderLitierePropre) {
		$this->garderLitierePropre = $garderLitierePropre;
	}

	/**
	 * Retrieve signalerChangementAdr
	 *
	 * @param  signalerChangementAdr
	 * @return la valeur de signalerChangementAdr
	 */
	public function getSignalerChangementAdr() {
		return $this->signalerChangementAdr;
	}

	/**
	 * Set entry signalerChangementAdr
	 *
	 * @param signalerChangementAdr
	 */
	public function setSignalerChangementAdr($signalerChangementAdr) {
		$this->signalerChangementAdr = $signalerChangementAdr;
	}

	/**
	 * Retrieve donnerNouvelles
	 *
	 * @param  donnerNouvelles
	 * @return la valeur de donnerNouvelles
	 */
	public function getDonnerNouvelles() {
		return $this->donnerNouvelles;
	}

	/**
	 * Set entry donnerNouvelles
	 *
	 * @param donnerNouvelles
	 */
	public function setDonnerNouvelles($donnerNouvelles) {
		$this->donnerNouvelles = $donnerNouvelles;
	}

	/**
	 * Retrieve accepterVisite
	 *
	 * @param  accepterVisite
	 * @return la valeur de accepterVisite
	 */
	public function getAccepterVisite() {
		return $this->accepterVisite;
	}

	/**
	 * Set entry accepterVisite
	 *
	 * @param accepterVisite
	 */
	public function setAccepterVisite($accepterVisite) {
		$this->accepterVisite = $accepterVisite;
	}

	/**
	 * Retrieve restituerChat
	 *
	 * @param  restituerChat
	 * @return la valeur de restituerChat
	 */
	public function getRestituerChat() {
		return $this->restituerChat;
	}

	/**
	 * Set entry restituerChat
	 *
	 * @param restituerChat
	 */
	public function setRestituerChat($restituerChat) {
		$this->restituerChat = $restituerChat;
	}

	/**
	 * Retrieve remarques
	 *
	 * @param  remarques
	 * @return la valeur de remarques
	 */
	public function getRemarques() {
		return $this->remarques;
	}

	/**
	 * Set entry remarques
	 *
	 * @param remarques
	 */
	public function setRemarques($remarques) {
		$this->remarques = $remarques;
	}

	/**
	 * Retrieve idConnaissanceAsso
	 *
	 * @param  idConnaissanceAsso
	 * @return la valeur de idConnaissanceAsso
	 */
	public function getIdConnaissanceAsso() {
		return $this->idConnaissanceAsso;
	}

	/**
	 * Set entry idConnaissanceAsso
	 *
	 * @param idConnaissanceAsso
	 */
	public function setIdConnaissanceAsso($idConnaissanceAsso) {
		$this->idConnaissanceAsso = $idConnaissanceAsso;
	}

	/**
	 * Retrieve profession
	 *
	 * @param  profession
	 * @return la valeur de profession
	 */
	public function getProfession() {
		return $this->profession;
	}

	/**
	 * Set entry profession
	 *
	 * @param profession
	 */
	public function setProfession($profession) {
		$this->profession = $profession;
	}

	/**
	 * Retrieve heureJoignable
	 *
	 * @param  heureJoignable
	 * @return la valeur de heureJoignable
	 */
	public function getHeureJoignable() {
		return $this->heureJoignable;
	}

	/**
	 * Set entry heureJoignable
	 *
	 * @param heureJoignable
	 */
	public function setHeureJoignable($heureJoignable) {
		$this->heureJoignable = $heureJoignable;
	}

	/**
	 * Retrieve login
	 *
	 * @param  login
	 * @return la valeur de login
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * Set entry login
	 *
	 * @param login
	 */
	public function setLogin($login) {
		$this->login = $login;
	}

	/**
	 * Retrieve dateSubmit
	 *
	 * @param  dateSubmit
	 * @return la valeur de dateSubmit
	 */
	public function getDateSubmit() {
		if ($this->dateSubmit == '') {
			return null;
		}
		return $this->dateSubmit;
	}

	/**
	 * Set entry dateSubmit
	 *
	 * @param dateSubmit
	 */
	public function setDateSubmit($dateSubmit) {
		$this->dateSubmit = $dateSubmit;
	}
	
	/**
	 * Retrieve superficie
	 *
	 * @param  superficie
	 * @return la valeur de superficie
	 */
	public function getSuperficie() {
		return $this->superficie;
	}

	/**
	 * Set entry superficie
	 *
	 * @param superficie
	 */
	public function setSuperficie($superficie) {
		$this->superficie = $superficie;
	}

    /**
	 * Retrieve connaissanceAssoDetail
	 *
	 * @param  connaissanceAssoDetail
	 * @return la valeur de connaissanceAssoDetail
	 */
	public function getConnaissanceAssoDetail() {
		return $this->connaissanceAssoDetail;
	}

	/**
	 * Set entry connaissanceAssoDetail
	 *
	 * @param connaissanceAssoDetail
	 */
	public function setConnaissanceAssoDetail($connaissanceAssoDetail) {
		$this->connaissanceAssoDetail = $connaissanceAssoDetail;
	}
}