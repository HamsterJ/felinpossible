<?php
/**
 * Services pour les exports excel.
 * @author Benjamin
 *
 */
class FP_Service_ExportServices {
	/**
	 * Instance courante.
	 * @var FP_Service_ExportServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_ExportServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_ExportServices();
		}
		return self::$instance;
	}

	/**
	 * Construit le fichier excel à partir des paramètres.
	 * @param array $data
	 * @param string $filename
	 * @return Spreadsheet_Excel_Writer
	 */
	private function buildExcelFile($data, $filename) {
		require_once 'Spreadsheet/Excel/Writer.php';

		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send($filename);

		$formatHeader = $workbook->addFormat(array('Size' => 10,//taille du texte
              'Align' => 'center',//alignement du texte
              'Color' => 'white',//couleur du texte
              'BgColor' => 'black'));//couleur du fond de cellule

		$worksheet = $workbook->addWorksheet();

		$ligneTmp = 1;
		$colTmp = 0;

		foreach ($data as $ligneData) {
			$worksheet->write($ligneTmp, $colTmp,$ligneTmp);
			
			if ($ligneData && $ligneData instanceof Zend_Db_Table_Row_Abstract) {
				$ligneData = $ligneData->toArray();
			}
			
			foreach ($ligneData as $key => $value) {
				if ($ligneTmp == 1) {
					$worksheet->write(0, $colTmp, utf8_decode(ucfirst($key)), $formatHeader);
				}
				$worksheet->write($ligneTmp, $colTmp, utf8_decode($value));
				$colTmp += 1;
			}
			$ligneTmp += 1;
			$colTmp = 0;
		}

		$workbook->close();
		return $workbook;
	}


	/**
	 * Construit le fichier excel pour les chats à l'adoption.
	 * @return Spreadsheet_Excel_Writer_Worksheet
	 */
	public function buildExcelChatAdoption() {
		$param = array(FP_Util_Constantes::WHERE_KEY => FP_Util_Constantes::CHAT_FICHES_A_ADOPTION);
		$data = FP_Service_ChatServices::getInstance()->getDataForExport($param);
		$filename = "chatAdoptions.xls";
		return $this->buildExcelFile($data, $filename);
	}

	/**
	 * Construit le fichier excel pour tous les chats.
	 * @return Spreadsheet_Excel_Writer_Worksheet
	 */
	public function buildExcelAllChats() {
		$data = FP_Service_ChatServices::getInstance()->getDataForExport();
		$filename = "chats.xls";
		return $this->buildExcelFile($data, $filename);
	}

	/**
	 * Construit le fichier excel pour toutes les FA.
	 * @return Spreadsheet_Excel_Writer_Worksheet
	 */
	public function buildExcelAllFa() {
		$data = FP_Service_FaServices::getInstance()->getDataForExport();
		$filename = "fa.xls";
		return $this->buildExcelFile($data, $filename);
	}

	/**
	 * Construit le fichier excel pour les adoptants.
	 * @return Spreadsheet_Excel_Writer_Worksheet
	 */
	public function buildExcelAllAdoptants() {
		$data = FP_Service_AdoptantServices::getInstance()->getDataForExport();
		$filename = "adoptants.xls";
		return $this->buildExcelFile($data, $filename);
	}

	/**
	 * Envoi csv
	 * @param string $filename
	 * @return unknown_type
	 */
	private function sendCsv($filename) {
        header("Content-Type: text; charset=ISO-8859-1");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
	}
	
	/**
	 * Construit le fichier csv contenant les contacts du forum.
	 * @return string (flux csv)
	 */
	public function buildCsvContactsForum() {
		$this->sendCsv("contacts".FP_Util_DateUtil::getDateFormattedForExport().".csv");
		$userMapper = FP_Model_Mapper_MapperFactory::getInstance()->userMapper;
		
		$listeUsers = $userMapper->getListeContacts();
		foreach ($listeUsers as $user) {
			$ligne = html_entity_decode($user->getLogin(), ENT_NOQUOTES, 'ISO-8859-1').",";
			$ligne .= $user->getEmail().",";
			$ligne .= $user->getGroupName();
			$ligne .= "\n";
			
			echo utf8_decode($ligne);
		}
	}
}