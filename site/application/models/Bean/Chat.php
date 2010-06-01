<?php
/**
 * Chat model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_ChatMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_Chat extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $nom;
	/** @var int  */
	protected $idSexe;
	/** @var date  */
	protected $dateNaissance;
	/** @var boolean  */
	protected $dateApproximative;
	/** @var string  */
	protected $race;
	/** @var int  */
	protected $idCouleur;
	/** @var string  */
	protected $yeux;
	/** @var string  */
	protected $tests;
	/** @var string  */
	protected $vaccins;
	/** @var string  */
	protected $tatouage;
	/** @var string  */
	protected $caractere;
	/** @var string  */
	protected $commentaires;
	/** @var string  */
	protected $miniature;
	/** @var boolean  */
	protected $adopte;
	/** @var boolean  */
	protected $reserve;
	/** @var boolean  */
	protected $parrain;
	/** @var boolean  */
	protected $disparu;
	/** @var string  */
	protected $lienTopic;
	/** @var int  */
	protected $topicId;
	/** @var date  */
	protected $dateAdoption;
	/** @var boolean  */
	protected $aValider;
	/** @var int  */
	protected $postId;
	/** @var string  */
	protected $libelleSexe;
	/** @var string  */
	protected $libelleCouleur;

	/**
	 * Date de rappel des vaccins
	 * @var string
	 */
	protected $dateRappelVaccins;

	protected $notesPrivees;
	protected $datePriseEnCharge;
	protected $dateAntiPuces;
	protected $dateVermifuge;
	protected $statutVisite;
	protected $visitePostPar;
	protected $fa;
	protected $dateTests;
	protected $dateSterilisation;
	protected $declarationCession;
	protected $sterilise;
	protected $dateEnvoiRappelVac;
	protected $dateEnvoiRappelSte;
	protected $dateContratAdoption;

	/**
	 * Set entry id
	 *
	 * @param  int $id
	 * @return FP_Model_News
	 */
	public function setId($id)
	{
		$this->id = (int) $id;
		return $this;
	}

	/**
	 * Retrieve entry id
	 *
	 * @return null|int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Retrieve nom
	 *
	 * @param  string nom
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
	 * Retrieve idSexe
	 *
	 * @param  idSexe
	 * @return la valeur de idSexe
	 */
	public function getIdSexe() {
		return $this->idSexe;
	}

	/**
	 * Set entry idSexe
	 *
	 * @param idSexe
	 */
	public function setIdSexe($idSexe) {
		$this->idSexe = (int) $idSexe;
	}

	/**
	 * Retrieve dateNaissance
	 *
	 * @param  dateNaissance
	 * @return la valeur de dateNaissance
	 */
	public function getDateNaissance() {
		return $this->dateNaissance;
	}

	/**
	 * Set entry dateNaissance
	 *
	 * @param dateNaissance
	 */
	public function setDateNaissance($dateNaissance) {
		$this->dateNaissance = $dateNaissance;
	}

	/**
	 * Retrieve race
	 *
	 * @param  race
	 * @return la valeur de race
	 */
	public function getRace() {
		return $this->race;
	}

	/**
	 * Set entry race
	 *
	 * @param race
	 */
	public function setRace($race) {
		$this->race = $race;
	}

	/**
	 * Retrieve idCouleur
	 *
	 * @param  idCouleur
	 * @return la valeur de idCouleur
	 */
	public function getIdCouleur() {
		return $this->idCouleur;
	}

	/**
	 * Set entry idCouleur
	 *
	 * @param idCouleur
	 */
	public function setIdCouleur($idCouleur) {
		$this->idCouleur = (int) $idCouleur;
	}

	/**
	 * Retrieve yeux
	 *
	 * @param  yeux
	 * @return la valeur de yeux
	 */
	public function getYeux() {
		return $this->yeux;
	}

	/**
	 * Set entry yeux
	 *
	 * @param yeux
	 */
	public function setYeux($yeux) {
		$this->yeux = $yeux;
	}

	/**
	 * Retrieve tests
	 *
	 * @param  tests
	 * @return la valeur de tests
	 */
	public function getTests() {
		return $this->tests;
	}

	/**
	 * Set entry tests
	 *
	 * @param tests
	 */
	public function setTests($tests) {
		$this->tests = $tests;
	}

	/**
	 * Retrieve vaccins
	 *
	 * @param  vaccins
	 * @return la valeur de vaccins
	 */
	public function getVaccins() {
		return $this->vaccins;
	}

	/**
	 * Set entry vaccins
	 *
	 * @param vaccins
	 */
	public function setVaccins($vaccins) {
		$this->vaccins = $vaccins;
	}

	/**
	 * Retrieve tatouage
	 *
	 * @param  tatouage
	 * @return la valeur de tatouage
	 */
	public function getTatouage() {
		return $this->tatouage;
	}

	/**
	 * Set entry tatouage
	 *
	 * @param tatouage
	 */
	public function setTatouage($tatouage) {
		$this->tatouage = $tatouage;
	}

	/**
	 * Retrieve caractere
	 *
	 * @param  caractere
	 * @return la valeur de caractere
	 */
	public function getCaractere() {
		return $this->caractere;
	}

	/**
	 * Set entry caractere
	 *
	 * @param caractere
	 */
	public function setCaractere($caractere) {
		$this->caractere = $caractere;
	}

	/**
	 * Retrieve commentaires
	 *
	 * @param  commentaires
	 * @return la valeur de commentaires
	 */
	public function getCommentaires() {
		return $this->commentaires;
	}

	/**
	 * Set entry commentaires
	 *
	 * @param commentaires
	 */
	public function setCommentaires($commentaires) {
		$this->commentaires = $commentaires;
	}

	/**
	 * Retrieve miniature
	 *
	 * @param  miniature
	 * @return la valeur de miniature
	 */
	public function getMiniature() {
		return $this->miniature;
	}

	/**
	 * Set entry miniature
	 *
	 * @param miniature
	 */
	public function setMiniature($miniature) {
		$this->miniature = $miniature;
	}

	/**
	 * Retrieve adopte
	 *
	 * @param  adopte
	 * @return la valeur de adopte
	 */
	public function getAdopte() {
		return (int) $this->adopte;
	}

	/**
	 * Set entry adopte
	 *
	 * @param adopte
	 */
	public function setAdopte($adopte) {
		$this->adopte = (int) $adopte;
	}

	/**
	 * Retrieve reserve
	 *
	 * @param  reserve
	 * @return la valeur de reserve
	 */
	public function getReserve() {
		return (int) $this->reserve;
	}

	/**
	 * Set entry reserve
	 *
	 * @param reserve
	 */
	public function setReserve($reserve) {
		$this->reserve = (int) $reserve;
	}

	/**
	 * Retrieve parrain
	 *
	 * @param  parrain
	 * @return la valeur de parrain
	 */
	public function getParrain() {
		return (int) $this->parrain;
	}

	/**
	 * Set entry parrain
	 *
	 * @param parrain
	 */
	public function setParrain($parrain) {
		$this->parrain = (int) $parrain;
	}

	/**
	 * Retrieve disparu
	 *
	 * @param  disparu
	 * @return la valeur de disparu
	 */
	public function getDisparu() {
		return (int) $this->disparu;
	}

	/**
	 * Set entry disparu
	 *
	 * @param disparu
	 */
	public function setDisparu($disparu) {
		$this->disparu = (int) $disparu;
	}

	/**
	 * Retrieve lienTopic
	 *
	 * @param  lienTopic
	 * @return la valeur de lienTopic
	 */
	public function getLienTopic() {
		return $this->lienTopic;
	}

	/**
	 * Set entry lienTopic
	 *
	 * @param lienTopic
	 */
	public function setLienTopic($lienTopic) {
		$this->lienTopic = $lienTopic;
	}

	/**
	 * Retrieve topicId
	 *
	 * @param  topicId
	 * @return la valeur de topicId
	 */
	public function getTopicId() {
		return $this->topicId;
	}

	/**
	 * Set entry topicId
	 *
	 * @param topicId
	 */
	public function setTopicId($topicId) {
		$this->topicId = (int) $topicId;
	}

	/**
	 * Retrieve dateAdoption
	 *
	 * @param  dateAdoption
	 * @return la valeur de dateAdoption
	 */
	public function getDateAdoption() {
		if ($this->dateAdoption == '') {
			return null;
		}
		return $this->dateAdoption;
	}

	/**
	 * Set entry dateAdoption
	 *
	 * @param dateAdoption
	 */
	public function setDateAdoption($dateAdoption) {
		$this->dateAdoption = $dateAdoption;
	}

	/**
	 * Retrieve aValider
	 *
	 * @param  aValider
	 * @return la valeur de aValider
	 */
	public function getAValider() {
		return (int) $this->aValider;
	}

	/**
	 * Set entry aValider
	 *
	 * @param aValider
	 */
	public function setAValider($aValider) {
		$this->aValider = (int) $aValider;
	}

	/**
	 * Retrieve postId
	 *
	 * @param  postId
	 * @return la valeur de postId
	 */
	public function getPostId() {
		return $this->postId;
	}

	/**
	 * Set entry postId
	 *
	 * @param postId
	 */
	public function setPostId($postId) {
		$this->postId = (int) $postId;
	}

	/**
	 * Retrieve libelleSexe
	 *
	 * @param  libelleSexe
	 * @return la valeur de libelleSexe
	 */
	public function getLibelleSexe() {
		return $this->libelleSexe;
	}

	/**
	 * Set entry libelleSexe
	 *
	 * @param libelleSexe
	 */
	public function setLibelleSexe($libelleSexe) {
		$this->libelleSexe = $libelleSexe;
	}

	/**
	 * Retrieve libelleCouleur
	 *
	 * @param  libelleCouleur
	 * @return la valeur de libelleCouleur
	 */
	public function getLibelleCouleur() {
		return $this->libelleCouleur;
	}

	/**
	 * Set entry libelleCouleur
	 *
	 * @param libelleCouleur
	 */
	public function setLibelleCouleur($libelleCouleur) {
		$this->libelleCouleur = $libelleCouleur;
	}

	/**
	 * Retrieve dateRappelVaccins
	 *
	 * @param  dateRappelVaccins
	 * @return la valeur de dateRappelVaccins
	 */
	public function getDateRappelVaccins() {
		if ($this->dateRappelVaccins == '') {
			return null;
		}
		return $this->dateRappelVaccins;
	}

	/**
	 * Set entry dateRappelVaccins
	 *
	 * @param dateRappelVaccins
	 */
	public function setDateRappelVaccins($dateRappelVaccins) {
		$this->dateRappelVaccins = $dateRappelVaccins;
	}

	/**
	 * Retrieve notesPrivees
	 *
	 * @param  notesPrivees
	 * @return la valeur de notesPrivees
	 */
	public function getNotesPrivees() {
		return $this->notesPrivees;
	}

	/**
	 * Set entry notesPrivees
	 *
	 * @param notesPrivees
	 */
	public function setNotesPrivees($notesPrivees) {
		$this->notesPrivees = $notesPrivees;
	}

	/**
	 * Retrieve dateAntiPuces
	 *
	 * @param  dateAntiPuces
	 * @return la valeur de dateAntiPuces
	 */
	public function getDateAntiPuces() {
		if ($this->dateAntiPuces == '') {
			return null;
		}
		return $this->dateAntiPuces;
	}

	/**
	 * Set entry dateAntiPuces
	 *
	 * @param dateAntiPuces
	 */
	public function setDateAntiPuces($dateAntiPuces) {
		$this->dateAntiPuces = $dateAntiPuces;
	}

	/**
	 * Retrieve dateVermifuge
	 *
	 * @param  dateVermifuge
	 * @return la valeur de dateVermifuge
	 */
	public function getDateVermifuge() {
		if ($this->dateVermifuge == '') {
			return null;
		}
		return $this->dateVermifuge;
	}

	/**
	 * Set entry dateVermifuge
	 *
	 * @param dateVermifuge
	 */
	public function setDateVermifuge($dateVermifuge) {
		$this->dateVermifuge = $dateVermifuge;
	}

	/**
	 * Retrieve visitePostPar
	 *
	 * @param  visitePostPar
	 * @return la valeur de visitePostPar
	 */
	public function getVisitePostPar() {
		return $this->visitePostPar;
	}

	/**
	 * Set entry visitePostPar
	 *
	 * @param visitePostPar
	 */
	public function setVisitePostPar($visitePostPar) {
		$this->visitePostPar = $visitePostPar;
	}

	/**
	 * Retrieve statutVisite
	 *
	 * @param  statutVisite
	 * @return la valeur de statutVisite
	 */
	public function getStatutVisite() {
		return (int) $this->statutVisite;
	}

	/**
	 * Set entry statutVisite
	 *
	 * @param statutVisite
	 */
	public function setStatutVisite($statutVisite) {
		$this->statutVisite = (int) $statutVisite;
	}

	/**
	 * Retrieve datePriseEnCharge
	 *
	 * @param  datePriseEnCharge
	 * @return la valeur de datePriseEnCharge
	 */
	public function getDatePriseEnCharge() {
		if ($this->datePriseEnCharge == '') {
			return null;
		}
		return $this->datePriseEnCharge;
	}

	/**
	 * Set entry datePriseEnCharge
	 *
	 * @param datePriseEnCharge
	 */
	public function setDatePriseEnCharge($datePriseEnCharge) {
		$this->datePriseEnCharge = $datePriseEnCharge;
	}

	/**
	 * Retrieve fa
	 *
	 * @param  fa
	 * @return la valeur de fa
	 */
	public function getFa() {
		return $this->fa;
	}

	/**
	 * Set entry fa
	 *
	 * @param fa
	 */
	public function setFa($fa) {
		$this->fa = $fa;
	}

	/**
	 * Retrieve dateTests
	 *
	 * @param  dateTests
	 * @return la valeur de dateTests
	 */
	public function getDateTests() {
		if ($this->dateTests == '') {
			return null;
		}
		return $this->dateTests;
	}

	/**
	 * Set entry dateTests
	 *
	 * @param dateTests
	 */
	public function setDateTests($dateTests) {
		$this->dateTests = $dateTests;
	}

	/**
	 * Retrieve dateSterilisation
	 *
	 * @param  dateSterilisation
	 * @return la valeur de dateSterilisation
	 */
	public function getDateSterilisation() {
		if ($this->dateSterilisation == '') {
			return null;
		}
		return $this->dateSterilisation;
	}

	/**
	 * Set entry dateSterilisation
	 *
	 * @param dateSterilisation
	 */
	public function setDateSterilisation($dateSterilisation) {
		$this->dateSterilisation = $dateSterilisation;
	}

	/**
	 * Retrieve declarationCession
	 *
	 * @param  declarationCession
	 * @return la valeur de declarationCession
	 */
	public function getDeclarationCession() {
		return (int) $this->declarationCession;
	}

	/**
	 * Set entry declarationCession
	 *
	 * @param declarationCession
	 */
	public function setDeclarationCession($declarationCession) {
		$this->declarationCession = (int) $declarationCession;
	}
	
	/**
	  * Retrieve sterilise
	  *
	  * @param  sterilise
	  * @return la valeur de sterilise
	*/
	public function getSterilise() {
	    return (int) $this->sterilise;
	}
	
	/**
	  * Set entry sterilise
	  *
	  * @param sterilise
	*/
	public function setSterilise($sterilise) {
	    $this->sterilise = (int) $sterilise;
	}
	
	/**
	 * Retrieve dateEnvoiRappelSte
	 *
	 * @param  dateEnvoiRappelSte
	 * @return la valeur de dateEnvoiRappelSte
	 */
	public function getDateEnvoiRappelSte() {
		if ($this->dateEnvoiRappelSte == '') {
			return null;
		}
		return $this->dateEnvoiRappelSte;
	}

	/**
	 * Set entry dateEnvoiRappelSte
	 *
	 * @param dateEnvoiRappelSte
	 */
	public function setDateEnvoiRappelSte($dateEnvoiRappelSte) {
		$this->dateEnvoiRappelSte = $dateEnvoiRappelSte;
	}
	
	/**
	 * Retrieve dateEnvoiRappelVac
	 *
	 * @param  dateEnvoiRappelVac
	 * @return la valeur de dateEnvoiRappelVac
	 */
	public function getDateEnvoiRappelVac() {
		if ($this->dateEnvoiRappelVac == '') {
			return null;
		}
		return $this->dateEnvoiRappelVac;
	}

	/**
	 * Set entry dateEnvoiRappelVac
	 *
	 * @param dateEnvoiRappelVac
	 */
	public function setDateEnvoiRappelVac($dateEnvoiRappelVac) {
		$this->dateEnvoiRappelVac = $dateEnvoiRappelVac;
	}

	/**
	 * Retrieve dateContratAdoption
	 *
	 * @param  dateContratAdoption
	 * @return la valeur de dateContratAdoption
	 */
	public function getDateContratAdoption() {
		if ($this->dateContratAdoption == '') {
			return null;
		}
		return $this->dateContratAdoption;
	}

	/**
	 * Set entry dateContratAdoption
	 *
	 * @param dateContratAdoption
	 */
	public function setDateContratAdoption($dateContratAdoption) {
		$this->dateContratAdoption = $dateContratAdoption;
	}
	
	/**
	 * Gets the dateApproximative field.
	 * @return the field value.
	 */
	public function getDateApproximative() {
	  return $this->dateApproximative;
	}
	
	/**
	 * Sets the dateApproximative field.
	 */
	public function setDateApproximative($dateApproximative) {
	  $this->dateApproximative = (int) $dateApproximative;
	}
	
}