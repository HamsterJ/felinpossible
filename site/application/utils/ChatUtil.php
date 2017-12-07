<?php
/**
 * Class d'utilitaires pour les chats.
 * @author Benjamin
 *
 */
class FP_Util_ChatUtil {

	/** Tags utilisés dans le post du chat. */
	const RACE_POST_TAG = "Race";
	const YEUX_POST_TAG = "Yeux";
	const TESTS_POST_TAG = "Tests";
	const FIV_POST_TAG = "FIV[\/| ]*FELV";
	const VACCIN_POST_TAG = "Vaccin";
	const IDENTIFICATION_POST_TAG = "Identification";
	const CARACETERE_POST_TAG = "Caract";
	const COMMENT_POST_TAG = "Notes";
	const ORIGINE_POST_TAG = "Origine";
	const COULEUR_POST_TAG = "Couleur";
	const ROBE_POST_TAG = "Robe";
	const AGE_POST_TAG = "[Â|A]ge";
	const NE_POST_TAG = "Né[e]? le";
	const NE2_POST_TAG = "Date de naissance";
        const SEXE = "Sexe";

	/**
	 * Met à jour la fiche du chat à partir des information de son post.
	 * @param FP_Model_Bean_Chat $ficheChat
	 * @param FP_Moedl_Bean_Post $post
	 * @return FP_Model_Bean_Chat
	 */
	public static function updateChatFromPostId($ficheChat, $post){
		$ficheUpdated = $ficheChat;
		$postText = $post->getContent();
		$postDate = $post->getDate();

		$race = self::getInfosFromText($postText, self::RACE_POST_TAG);
		if ($race != "") {
			$ficheUpdated->setRace($race);
		}
			
		$idCouleur = self::getCouleurIdFromText($postText);
		if ($idCouleur) {
			$ficheUpdated->setIdCouleur($idCouleur);
		}
			
		$yeux = self::getInfosFromText($postText, self::YEUX_POST_TAG);
		if ($yeux != "") {
			$ficheUpdated->setYeux($yeux);
		}
			
		$tests = self::getInfosFromText($postText, self::TESTS_POST_TAG);
		if ($tests == "") {
			$tests = self::getInfosFromText($postText, self::FIV_POST_TAG);
		}
		if ($tests != "") {
			$ficheUpdated->setTests($tests);
		}

		$vaccins = self::getInfosFromText($postText, self::VACCIN_POST_TAG);
		if ($vaccins != "") {
			$ficheUpdated->setVaccins($vaccins);
		}

		$identification = self::getInfosFromText($postText, self::IDENTIFICATION_POST_TAG);
		if ($identification != "") {
			$ficheUpdated->setTatouage($identification);
		}

		$caractere = self::getInfosFromText($postText, self::CARACETERE_POST_TAG);
		if ($caractere != "") {
			$ficheUpdated->setCaractere($caractere);
		}
                
                $sexe = self::getInfosFromText($postText, self::SEXE);
		if ($sexe != "") {
			$ficheUpdated->setIdSexe(strtoupper($sexe)=="FEMELLE"?2:1);
		}

		$comment = self::getInfosFromText($postText, self::COMMENT_POST_TAG);
		if ($comment == "") {
			$comment = self::getInfosFromText($postText, self::ORIGINE_POST_TAG);
		}
		if ($comment != "") {
			$ficheUpdated->setCommentaires($comment);
		}

		$ficheUpdated->setDateNaissance(self::getDateNaissanceFromText($postText, $postDate));
		return $ficheUpdated;
	}

	/**
	 * Récupère l'info $info à partir d'une expression regulière sur le contenu du post du chat.
	 * @param string $postText
	 * @param string $info
	 * @return string la valeur associés à $info de $postText
	 */
	public static function getInfosFromText ($postText, $info) {
		$pattern = '/\][ ]*'.$info.'.*:?\[.*\][ ]*:?(.*)/i';
		preg_match($pattern, $postText, $matches);

		if (count($matches) > 1) {
			$value = trim($matches[1]);
			if (strlen($value) > 500) {
				$value = substr($value, 0, 500)."[...]";
			}
			return ucfirst($value);
		}
		return "";
	}

	/**
	 * Récupère l'id de la couleur se rapprochant le plus de celle récupérée du post.
	 * @param string $postText
	 * @return string
	 */
	private static function getCouleurIdFromText($postText) {
		$couleur = self::getInfosFromText ($postText, self::COULEUR_POST_TAG);

		if ($couleur != "") {
			$couleurMapper = FP_Model_Mapper_MapperFactory::getInstance()->couleurMapper;
			$couleursMatch = $couleurMapper->select('id', "match(name, key_words) against('".$couleur."')");
			if (count($couleursMatch) > 0) {
				return $couleursMatch[0]->getId();
			}
		}
		else
		{
			$couleur = self::getInfosFromText ($postText, self::ROBE_POST_TAG);
			if ($couleur != "") {
				$couleurMapper = FP_Model_Mapper_MapperFactory::getInstance()->couleurMapper;
				$couleursMatch = $couleurMapper->select('id', "match(name, key_words) against('".$couleur."')");
				if (count($couleursMatch) > 0) {
					return $couleursMatch[0]->getId();
				}
			}
		}
		return null;
	}

	/**
	 * Calcule la date de naissance à partir des infos du post.
	 * @param $postText
	 * @param $postDate
	 * @return string
	 */
	private static function getDateNaissanceFromText($postText, $postDate) {
		$age = self::getInfosFromText ($postText, self::AGE_POST_TAG);

		if ($age != "") {
			$dateNaissance = self::computeAge($age, $postDate);
		} else {
			$dateNaissance = self::getInfosFromText($postText, self::NE_POST_TAG);
			if ($dateNaissance != ""){
				$date = explode('/',$dateNaissance);
				if (count($date) > 2) {
                                    $yearWithComments = explode(' ',$date[2]);
                                    $dateNaissance =$yearWithComments[0].'-'.$date[1].'-'.$date[0];
				}
			}
			else {
				$dateNaissance = self::getInfosFromText($postText, self::NE2_POST_TAG);
				if ($dateNaissance != ""){
					$date = explode('/',$dateNaissance);
					if (count($date) > 2) {
                                             $yearWithComments = explode(' ',$date[2]);
                                             $dateNaissance = $yearWithComments[0].'-'.$date[1].'-'.$date[0];
					}
				}
			}
		}
		return $dateNaissance;
	}

	/**
	 * Calcule l'âge du chat à partir des infos récupérées du post.
	 * @param $age
	 * @param $postDate
	 * @return string la date de naissance
	 */
	private static function computeAge($age, $postDate) {
		$nbMois = self::getInfosFromDate($age, "mois");
		$nbAns = self::getInfosFromDate($age, "an[s]?");
		$nbSemaines = self::getInfosFromDate($age, "semaine[s]?");
		$nbJours = self::getInfosFromDate($age, "jour[s]?");

		$resteNbAns = $nbAns - intval($nbAns);
		$resteNbMois = $nbMois - intval($nbMois);
		$resteNbSemaines = $nbSemaines - intval($nbSemaines);
		$resteNbJours = $nbJours - intval($nbJours);

		if ($resteNbAns != 0){
			$nbMois += intval(12*$resteNbAns);
			$nbAns = intval($nbAns);
		}
		if ($resteNbMois != 0){
			$nbSemaines += intval(4*$resteNbMois);
			$nbMois = intval($nbMois);
		}
		if ($resteNbSemaines != 0){
			$nbJours += intval(7*$resteNbSemaines);
			$nbSemaines = intval($nbSemaines);
		}

		return date('Y-m-d', mktime(0,0,0,date('m',$postDate) - $nbMois, date('d',$postDate) - 7*$nbSemaines - $nbJours, date('Y',$postDate) - $nbAns));
	}

	/**
	 * Récupère les informations relative à la date (nombre de jour/mois/année)
	 * @param $text
	 * @param $infos
	 * @return int
	 */
	private static function getInfosFromDate($text, $infos) {
		$patternDemi = '/(\d+).[ ]*'.$infos.'[ ]*(et demi|1\/2)/i';
		$patternClassique = '/(\d+)[ ]*'.$infos.'.*/i';

		if (preg_match($patternDemi, $text, $matches)) {
			return intval($matches[1]) + intval(1)/intval(2);
		} else if (preg_match($patternClassique, $text, $matches)) {
			return intval($matches[1]);
		}
		return 0;
	}
}