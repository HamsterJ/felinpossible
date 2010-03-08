<?php
/**
 * FaIndispo model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_FaStatutMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_FaIndispo extends FP_Model_Bean_Common {
	/**
	 * Id. de l'indisponibilité.
	 * @var int
	 */
	protected $id;
	
	/**
	 * Id de la FA.
	 * @var int
	 */
	protected $idFa;
	
	/**
	 * Date de début de l'indisponibilité.
	 * @var date
	 */
	protected $dateDebut;
	
	/**
	 * Date de fin de l'indisponibilité.
	 * @var date
	 */
	protected $dateFin;

	/**
	 * Commentaires
	 * @var string
	 */
	protected $commentaires;
	
	/**
	 * id du statut de l'indisponibilité.
	 * @var int
	 */
	protected $idStatut;
	
	/**
	  * Retrieve id
	  *
	  * @param  id
	  * @return la valeur de id
	*/
	public function getId() {
	    return (int) $this->id;
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
	  * Retrieve idFa
	  *
	  * @param  idFa
	  * @return la valeur de idFa
	*/
	public function getIdFa() {
	    return (int) $this->idFa;
	}
	
	/**
	  * Set entry idFa
	  *
	  * @param idFa
	*/
	public function setIdFa($idFa) {
	    $this->idFa = (int) $idFa;
	}
	
	/**
	  * Retrieve dateDebut
	  *
	  * @param  dateDebut
	  * @return la valeur de dateDebut
	*/
	public function getDateDebut() {
	    return $this->dateDebut;
	}
	
	/**
	  * Set entry dateDebut
	  *
	  * @param dateDebut
	*/
	public function setDateDebut($dateDebut) {
	    $this->dateDebut = $dateDebut;
	}
	
	/**
	  * Retrieve dateFin
	  *
	  * @param  dateFin
	  * @return la valeur de dateFin
	*/
	public function getDateFin() {
	    return $this->dateFin;
	}
	
	/**
	  * Set entry dateFin
	  *
	  * @param dateFin
	*/
	public function setDateFin($dateFin) {
	    $this->dateFin = $dateFin;
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
	  * Retrieve idStatut
	  *
	  * @param  idStatut
	  * @return la valeur de idStatut
	*/
	public function getIdStatut() {
	    return (int) $this->idStatut;
	}
	
	/**
	  * Set entry idStatut
	  *
	  * @param idStatut
	*/
	public function setIdStatut($idStatut) {
	    $this->idStatut = (int) $idStatut;
	}
}