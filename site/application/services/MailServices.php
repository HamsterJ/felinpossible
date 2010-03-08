<?php
/**
 * Services pour les mails.
 * @author Benjamin
 *
 */
class FP_Service_MailServices {
	const MAIL_TEMPLATE_KEY = 'template';
	const MAIL_SUBJECT_KEY = 'sujet';
	const MAIL_BODY_KEY = 'body';
	const MAIL_TO_KEY = 'to';
	const MAIL_CC_KEY = 'cc';
	const MAIL_DATA_KEY = 'data';

	/**
	 * Instance courante.
	 * @var FP_Service_MailServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_MailServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_MailServices();
		}
		return self::$instance;
	}

	/**
	 * Envoi d'un mail.
	 * @param string $mailSubjectsujet du mail
	 * @param string $mailContent contenu du mail
	 */
	private function sendMail($mailArguments) {
		if (array_key_exists(self::MAIL_TEMPLATE_KEY, $mailArguments)) {
			$template = $mailArguments[self::MAIL_TEMPLATE_KEY];
		} else {
			$body = $mailArguments[self::MAIL_BODY_KEY];
		}

		$mailTo = $mailArguments[self::MAIL_TO_KEY];
		$mailCc = $mailArguments[self::MAIL_CC_KEY];
		$subject = $mailArguments[self::MAIL_SUBJECT_KEY];

		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);

		if ($template) {
			$view = new Zend_View();
			$view->setScriptPath($config->email->folder);
			$view->data = $mailArguments[self::MAIL_DATA_KEY];;
			$body = $view->render($template);
		}

		$mail = new Zend_Mail();
		$mail->setBodyHtml(utf8_decode($body));
		$mail->setFrom($config->email->address, $config->email->nom);
		$mail->addTo($mailTo);

		if ($mailCc) {
			$mail->addTo($mailCc);
		}

		$mail->setSubject(utf8_decode($subject));
		$mail->send();
	}


	/**
	 * Construit les paramètres pour l'envoi de mail.
	 * @param string $template
	 * @param string $mailSubject
	 * @param string $mailTo
	 * @param array $data
	 * @return array
	 */
	private function buildMailParam($template, $mailSubject, $mailTo, $mailBody = null, $data = null, $mailCc = null) {
		$args = array();
		if ($template) {
			$args[self::MAIL_TEMPLATE_KEY] = $template;
		}
		$args[self::MAIL_SUBJECT_KEY] = $mailSubject;
		$args[self::MAIL_TO_KEY] = $mailTo;

		if ($mailCc) {
			$args[self::MAIL_CC_KEY] = $mailCc;
		}

		$args[self::MAIL_BODY_KEY] = $mailBody;

		if ($data) {
			$args[self::MAIL_DATA_KEY] = $data;
		}

		return $args;
	}

	/**
	 * Retourne le contenu du template.
	 * @param string $template
	 * @param array $data
	 * @return string
	 */
	public function getMailBody($template, $data) {
		$view = new Zend_View();
		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);
		$view->setScriptPath($config->email->folder);
		$view->data = $data;

		return $view->render($template);
	}

	/**
	 * Envoi un mail à l'association (parrainage, adoption, fa).
	 * @param string $mailSubject
	 * @param string $mailContent
	 * @param boolean $withCc
	 */
	public function envoiMailAsso($mailSubject, $mailContent, $withCc = false) {
		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);

		$mailTo = $config->email->address;
		$mailCc = null;

		if ($withCc) {
			$mailCc = $config->email->copy->address;
		}

		$args = $this->buildMailParam(null, $mailSubject, $mailTo, $mailContent, null, $mailCc);
		$this->sendMail($args);
	}

	/**
	 * Envoi un mail à l'association FA, avec CC (pour responsable FA).
	 * @param string $mailSubject
	 * @param string $mailContent
	 */
	public function envoiMailAssoFa($mailSubject, $mailContent) {
		$this->envoiMailAsso($mailSubject, $mailContent, true);
	}

	/**
	 * Envoi un mail à partir des données du formulaire d'envoi du mail de rappel de vaccins/stérilisation.
	 * @param array $data
	 */
	public function envoiMail($data) {
		$args = $this->buildMailParam(null, $data['sujet'], $data['destinataire'], $data['contenu'], null, $data['copy']);
		$this->sendMail($args);
	}

}