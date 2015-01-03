<?php
/**
 * Constantes de l'application.
 * @author Benjamin
 *
 */
class FP_Util_Constantes {
	/**
	 * Format de la date
	 * @var string
	 */
	const DATE_FORMAT = 'dd/MM/yyyy';

	/**
	 * Format de la date YYYYMMJJ
	 * @var string
	 */
	const DATE_FORMAT_YYYYMMJJ = 'yyyyMMdd';
	
	/**
	 * Format de la date pour mysql
	 * @var string
	 */
	const DATE_FORMAT_MYSQL = 'yyyy-MM-dd';
	
	/**
	 * Encodage par défaut.
	 * @var string
	 */
	const ENCODING = 'utf8';
	
	/**
	 * id pour accéder à la configuration
	 * @var string
	 */
	const CONFIG_ID = 'config';

	/**
	 * Id correspondant à aucune valeur dans les listes de choix.
	 * @var int
	 */
	const EMPTY_VALUE_ID = -1;
	
	/**
	 * Constantes pour les sexes des chats.
	 * @var int
	 */
	const ID_SEXE_MALE = 1;
	const ID_SEXE_FEMELLE = 2;

	/**
	 * Dons possibles pour le parrainage.
	 * @var array
	 */
	public static $LISTE_DONS = array('5' => '5€',
                                    '10' => '10€',
                                    '15' => '15 €',
                                    '20' => '20 €',
                                    '0' => 'Autres');

	/**
	 * Oui/Non pour les formulaires.
	 * @var array
	 */
	public static $LISTE_OUI_NON = array(1 => 'Oui',
	0 => 'Non');
	/**
	 * Traduction des messages d'erreurs.
	 */
	public static $LISTE_MESSAGES_ERREUR_FORM =  array(
	        'notAlnum' => "'%value%' ne contient pas que des lettres et/ou des chiffres.",
	        'notAlpha' => "'%value%' ne contient pas que des lettres.",
	        'notBetween' => "'%value%' n'est pas compris entre %min% et %max% inclus.",
	        'notBetweenStrict' => "'%value%' n'est pas compris entre %min% et %max% exclus.",
	        'dateNotYYYY-MM-DD'=> "'%value%' n'est pas une date au format AAAA-MM-JJ (exemple : 2000-12-31).",
	        'dateInvalid' => "'%value%' n'est pas une date valide.",
	        'dateFalseFormat' => "'%value%' n'est pas une date valide au format JJ/MM/AAAA (exemple : 31/12/2000).",
	        'notDigits' => "'%value%' ne contient pas que des chiffres.",
            'emailAddressInvalidFormat' => "'%value%' n'est pas une adresse mail valide selon le format local-part@hostname",
	        'emailAddressInvalid' => "'%value%' n'est pas une adresse mail valide selon le format adresse@domaine.",
	        'emailAddressInvalidHostname' => "'%hostname%' n'est pas un domaine valide pour l'adresse mail '%value%'.",
	        'emailAddressInvalidMxRecord' => "'%hostname%' n'accepte pas l'adresse mail '%value%'.",
	        'emailAddressDotAtom' => "'%localPart%' ne respecte pas le format dot-atom.",
	        'emailAddressQuotedString' => "'%localPart%' ne respecte pas le format quoted-string.",
	        'emailAddressInvalidLocalPart' => "'%localPart%' n'est pas une adresse individuelle valide.",
	        'notFloat' => "'%value%' n'est pas un nombre décimal.",
	        'notGreaterThan' => "'%value%' n'est pas strictement supérieur à '%min%'.",
	        'notInt'=> "'%value%' n'est pas un nombre entier.",
	        'notLessThan' => "'%value%' n'est pas strictement inférieur à '%max%'.",
	        'isEmpty' => "Ce champ est vide, vous devez le compléter.",
	        'stringEmpty' => "Ce champ est vide, vous devez le compléter.",
	        'regexNotMatch' => "'%value%' ne respecte pas le format '%pattern%'.",
	        'stringLengthTooShort' => "'%value%' fait moins de %min% caractères.",
	        'stringLengthTooLong' => "'%value%' fait plus de %max% caractères."
	);

	/** Constantes pour les formulaires. */
	const IDENTIFICATION_FORM_ID = 'identificationForm';
	const LOGEMENT_FORM_ID = 'logementForm';
	const FOYER_FORM_ID = 'foyerForm';
	const MOTIVATION_FORM_ID = 'motivationForm';
	const CONDITIONS_FORM_ID = 'conditionsForm';
	const FUTUR_CHAT_FORM_ID = 'futurChatForm';
	const BUDGET_FORM_ID = 'budgetForm';
	const VACANCES_FORM_ID = 'vacancesProjetsForm';
	const REMARQUES_FORM_ID = 'remarquesForm';
	
	/** Constantes pour les statuts des FA. */
	const FA_ACTIVE_STATUT = 1;
	const FA_INACTIVE_STATUT = 2;
	const FA_DISPONIBLE_STATUT = 3;
	const FA_STAND_BY_STATUT = 4;
	const FA_INDISPONIBLE_STATUT = 5;
	const FA_INDESIRABLE_STATUT = 6;
	const FA_CANDIDATURE_STATUT = 7;

	/** Constantes pour les statuts des indisponibilités. */
	const INDISPO_A_VENIR_STATUT = 1;
	const INDISPO_EN_COURS_STATUT = 2;
	const INDISPO_TERMINEE_STATUT = 3;
	
	/**
	 * Clef pour la clause where
	 * @var string
	 */
	const WHERE_KEY = 'where';
	
	/** Constantes pour les clauses where des chats. */
	const CHAT_FICHES_A_VALIDER = 1;
	const CHAT_FICHES_A_ADOPTION = 2;
	const CHAT_FICHES_ADOPTES = 3;
	const CHAT_FICHES_DISPARUS = 4;
	const CHAT_FICHES_A_PARRAINER = 5;
	const CHAT_FICHES_RESERVES = 6;	
	const CHAT_AVEC_DATE_VACCINS = 7;
	const CHAT_FICHES_A_PLACER = 8;
	const CHAT_A_STERILISER = 9;
	const CHAT_FICHES_A_ADOPTION_NON_RES = 10;
	const CHAT_CHGT_PROPRIETAIRE = 11;
	
	/** Templates pour les mails. */
	const MAIL_TEMPLATE_VACCINS_FA = 'rappelsVaccins.phtml';
	const MAIL_TEMPLATE_STERILISATION_FA = 'rappelsSterilisations.phtml';
	
	/** Sujets de mail */
	const MAIL_PARRAINER_SUJET = 'Formulaire de parrainage';
	const MAIL_ADOPTER_SUJET = 'Formulaire d\'adoption';
	const MAIL_BE_FA_SUJET = 'Formulaire Famille d\'accueil';
	const MAIL_VETO = 'Formulaire vétérinaire';
	const MAIL_RAPPEL_VACCINS_SUJET = 'Rappels de vaccins de ';
	const MAIL_RAPPEL_STERILISATION_SUJET = 'Rappel pour la stérilisation de ';
	
	/** Templates pour les documents */
	const DOCUMENT_FICHE_SOINS_PATH = "documents/ficheSoins.docx";
	
	/**
	 * Stérilisations possibles.
	 */
	public static $LISTE_STERILISATION =  array(0 => 'Aucune',
	1 => 'Ovariectomie',
	2 => 'Ovario-hystérectomie',
	3 => 'Castration',
        4 => 'Stérilisation');
	
}
