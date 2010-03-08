<?php
/**
 * FA model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_SexeMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_Fa extends FP_Model_Bean_Common
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
	protected $hasChatiere;
	protected $etage;
	protected $nbPersonnes;
	protected $nbEnfants;
	protected $enfantsAge;
	protected $animauxAutres;
	protected $chats;
	protected $motivations;
	protected $isSecurFenetres;
	protected $isSurvFenetres;
	protected $contacterVeto;
	protected $contacterAssociation;
	protected $patienceAvecChat;
	protected $jouerAvecChat;
	protected $fournirCroquettes;
	protected $accueillirMere;
	protected $biberonnerChatons;
	protected $accueillirChatons;
	protected $accueillirChatFiv;
	protected $accueillirChatFelv;
	protected $donnerSoins;
	protected $mettreChatQuarantaine;
	protected $isolerChat;
	protected $statutId;
	protected $notes;
	protected $login;
	protected $dateSubmit;
	protected $superficie;
	protected $dateContratFa;

	public function __construct(array $options = null) {
		parent::__construct($options);
	}

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
	 * Retrieve contacterAssociation
	 *
	 * @param  contacterAssociation
	 * @return la valeur de contacterAssociation
	 */
	public function getContacterAssociation() {
		return $this->contacterAssociation;
	}

	/**
	 * Set entry contacterAssociation
	 *
	 * @param contacterAssociation
	 */
	public function setContacterAssociation($contacterAssociation) {
		$this->contacterAssociation = $contacterAssociation;
	}

	/**
	 * Retrieve patienceAvecChat
	 *
	 * @param  patienceAvecChat
	 * @return la valeur de patienceAvecChat
	 */
	public function getPatienceAvecChat() {
		return $this->patienceAvecChat;
	}

	/**
	 * Set entry patienceAvecChat
	 *
	 * @param patienceAvecChat
	 */
	public function setPatienceAvecChat($patienceAvecChat) {
		$this->patienceAvecChat = $patienceAvecChat;
	}

	/**
	 * Retrieve jouerAvecChat
	 *
	 * @param  jouerAvecChat
	 * @return la valeur de jouerAvecChat
	 */
	public function getJouerAvecChat() {
		return $this->jouerAvecChat;
	}

	/**
	 * Set entry jouerAvecChat
	 *
	 * @param jouerAvecChat
	 */
	public function setJouerAvecChat($jouerAvecChat) {
		$this->jouerAvecChat = $jouerAvecChat;
	}

	/**
	 * Retrieve fournirCroquettes
	 *
	 * @param  fournirCroquettes
	 * @return la valeur de fournirCroquettes
	 */
	public function getFournirCroquettes() {
		return $this->fournirCroquettes;
	}

	/**
	 * Set entry fournirCroquettes
	 *
	 * @param fournirCroquettes
	 */
	public function setFournirCroquettes($fournirCroquettes) {
		$this->fournirCroquettes = $fournirCroquettes;
	}

	/**
	 * Retrieve accueillirMere
	 *
	 * @param  accueillirMere
	 * @return la valeur de accueillirMere
	 */
	public function getAccueillirMere() {
		return $this->accueillirMere;
	}

	/**
	 * Set entry accueillirMere
	 *
	 * @param accueillirMere
	 */
	public function setAccueillirMere($accueillirMere) {
		$this->accueillirMere = $accueillirMere;
	}

	/**
	 * Retrieve accueillirChatons
	 *
	 * @param  accueillirChatons
	 * @return la valeur de accueillirChatons
	 */
	public function getAccueillirChatons() {
		return $this->accueillirChatons;
	}

	/**
	 * Set entry accueillirChatons
	 *
	 * @param accueillirChatons
	 */
	public function setAccueillirChatons($accueillirChatons) {
		$this->accueillirChatons = $accueillirChatons;
	}

	/**
	 * Retrieve biberonnerChatons
	 *
	 * @param  biberonnerChatons
	 * @return la valeur de biberonnerChatons
	 */
	public function getBiberonnerChatons() {
		return $this->biberonnerChatons;
	}

	/**
	 * Set entry biberonnerChatons
	 *
	 * @param biberonnerChatons
	 */
	public function setBiberonnerChatons($biberonnerChatons) {
		$this->biberonnerChatons = $biberonnerChatons;
	}

	/**
	 * Retrieve accueillirChatFiv
	 *
	 * @param  accueillirChatFiv
	 * @return la valeur de accueillirChatFiv
	 */
	public function getAccueillirChatFiv() {
		return $this->accueillirChatFiv;
	}

	/**
	 * Set entry accueillirChatFiv
	 *
	 * @param accueillirChatFiv
	 */
	public function setAccueillirChatFiv($accueillirChatFiv) {
		$this->accueillirChatFiv = $accueillirChatFiv;
	}

	/**
	 * Retrieve accueillirChatFelv
	 *
	 * @param  accueillirChatFelv
	 * @return la valeur de accueillirChatFelv
	 */
	public function getAccueillirChatFelv() {
		return $this->accueillirChatFelv;
	}

	/**
	 * Set entry accueillirChatFelv
	 *
	 * @param accueillirChatFelv
	 */
	public function setAccueillirChatFelv($accueillirChatFelv) {
		$this->accueillirChatFelv = $accueillirChatFelv;
	}

	/**
	 * Retrieve donnerSoins
	 *
	 * @param  donnerSoins
	 * @return la valeur de donnerSoins
	 */
	public function getDonnerSoins() {
		return $this->donnerSoins;
	}

	/**
	 * Set entry donnerSoins
	 *
	 * @param donnerSoins
	 */
	public function setDonnerSoins($donnerSoins) {
		$this->donnerSoins = $donnerSoins;
	}

	/**
	 * Retrieve mettreChatQuarantaine
	 *
	 * @param  mettreChatQuarantaine
	 * @return la valeur de mettreChatQuarantaine
	 */
	public function getMettreChatQuarantaine() {
		return $this->mettreChatQuarantaine;
	}

	/**
	 * Set entry mettreChatQuarantaine
	 *
	 * @param mettreChatQuarantaine
	 */
	public function setMettreChatQuarantaine($mettreChatQuarantaine) {
		$this->mettreChatQuarantaine = $mettreChatQuarantaine;
	}

	/**
	 * Retrieve isolerChat
	 *
	 * @param  isolerChat
	 * @return la valeur de isolerChat
	 */
	public function getIsolerChat() {
		return $this->isolerChat;
	}

	/**
	 * Set entry isolerChat
	 *
	 * @param isolerChat
	 */
	public function setIsolerChat($isolerChat) {
		$this->isolerChat = $isolerChat;
	}

	/**
	 * Retrieve statutId
	 *
	 * @param  statutId
	 * @return la valeur de statutId
	 */
	public function getStatutId() {
		return (int) $this->statutId;
	}

	/**
	 * Set entry statutId
	 *
	 * @param statutId
	 */
	public function setStatutId($statutId) {
		$this->statutId = (int) $statutId;
	}

	/**
	 * Retrieve notes
	 *
	 * @param  notes
	 * @return la valeur de notes
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * Set entry notes
	 *
	 * @param notes
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
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
	 * Retrieve dateContratFa
	 *
	 * @param  dateContratFa
	 * @return la valeur de dateContratFa
	 */
	public function getDateContratFa() {
		if ($this->dateContratFa == '') {
			return null;
		}
		return $this->dateContratFa;
	}

	/**
	 * Set entry dateContratFa
	 *
	 * @param dateContratFa
	 */
	public function setDateContratFa($dateContratFa) {
		$this->dateContratFa = $dateContratFa;
	}
}