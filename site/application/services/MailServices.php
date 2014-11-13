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
		$template = null;
		$mailCc = null;

		if (array_key_exists(self::MAIL_TEMPLATE_KEY, $mailArguments)) {
			$template = $mailArguments[self::MAIL_TEMPLATE_KEY];
		} else {
			$body = $mailArguments[self::MAIL_BODY_KEY];
		}

		$mailTo = $mailArguments[self::MAIL_TO_KEY];
		if (array_key_exists(self::MAIL_CC_KEY, $mailArguments)) {
			$mailCc = $mailArguments[self::MAIL_CC_KEY];
		}
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

		$configSmtp = array(
                  'auth' => 'plain',
                  'username' => $config->email->smtp->login,
                  'password' => $config->email->smtp->password,
                  'ssl' => 'tls',
                  'port' => ($config->email->smtp->port)?$config->email->smtp->port:'25'
		);          
		$transport = new Zend_Mail_Transport_Smtp($config->email->smtp->hostname , $configSmtp);

		$mail->setSubject(utf8_decode($subject));
		$transport->send($mail);
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
        
        /**
	 * Envoi un mail à l'asso lors de la soumission d'une demande de fiche de soins
	 * @param array $data
	 */
	public function envoiMailDemandeFicheSoins($form) {
		
                $data= array();
                $data['sujet']='Nouvelle demande de fiche de soins de '.$form['nom'];
                $config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);
                $data['destinataire']=$config->email->address;
                $pageTraitement = $config->ficheSoins->path;
                
                $beanVeto = FP_Model_Mapper_MapperFactory::getInstance()->vetoMapper->find($form['idVeto']);
                $veto = ($beanVeto)?($beanVeto->getRaison()):'';
    
                $data['contenu'] = '<table><th width="100px"></th><th></th>
                                    <tr><td colspan="2">Bonjour,</td></tr>
                                    <tr><td colspan="2">Une nouvelle demande de fiche de soins est arrivée.</td></tr>
                                    <tr><td colspan="2">Voici le résumé de la demande : </td></tr>
                                    <tr><td></td><td></td></tr>
                                    <tr><td>Demandeur : </td><td>'       .$form['nom'].($form['login']?' ('.$form['login'].')':'').'</td></tr>
                                    <tr><td>Chat : </td><td>'            .$form['nomChat'].'</td></tr>
                                    <tr><td>Date de visite : </td><td>'  .$form['dateVisite'].'</td></tr>
                                    <tr><td>Vétérinaire : </td><td>'     .$veto.($form['vetoCompl']?('   '.$form['vetoCompl']):'').'</td></tr>  
                                    <tr><td>Identification : </td><td>'  .($form['soinIdent']?'<b>OUI</b>':'Non')        .'</td></tr>
                                    <tr><td>Tests Fiv/Felv : </td><td>'  .($form['soinTests']?'<b>OUI</b>':'Non')        .'</td></tr>
                                    <tr><td>Vaccins : </td><td>'         .($form['soinVaccins']?'<b>OUI</b>':'Non')      .'</td></tr>
                                    <tr><td>Stérilisation : </td><td>'   .($form['soinSterilisation']?'<b>OUI</b>':'Non').'</td></tr>
                                    <tr><td>Vermifuge : </td><td>'       .($form['soinVermifuge']?'<b>OUI</b>':'Non')    .'</td></tr>
                                    <tr><td>Anti-puces : </td><td>'      .($form['soinAntiParasites']?'<b>OUI</b>':'Non').'</td></tr>
                                    <tr><td>Commentaires : </td><td>'    .($form['soinAutre']?$form['soinAutre']:'Non')        .'</td></tr>
                                    <tr><td></td><td></td></tr>
                                    <tr><td colspan="2">Pour la traiter, merci de cliquer ici : <a href="'.$pageTraitement.'?token='.$form['token'].'">'.$pageTraitement.'?token='.$form['token'].'</a></td></tr>
                                    </table>';
            
                $args = $this->buildMailParam(null, $data['sujet'], $data['destinataire'], $data['contenu'], null, null);
		$this->sendMail($args);
	}

}