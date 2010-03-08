<?php
include_once('./inc/functions_global.php');
include_once('./inc/mysql.php');

function Help($ToolTip,$Anker,$imgsize=12)
{
/*
	global $config;
	if($Anker!=""){
	return '<a href="language/'.$config['language'].'/help.php#'.$Anker.'" title="'.$ToolTip.'"><img src="'.$config['files']['iconpath'].'help16.gif" width="'.$imgsize.'" height="'.$imgsize.'" hspace="'.(round($imgsize/4,0)).'" vspace="0" border="0" alt="Help"></a>';
	} else {
	return '<img src="'.$config['files']['iconpath'].'help16.gif" width="'.$imgsize.'" height="'.$imgsize.'" alt="Help" title="'.$ToolTip.'" border="0" hspace="'.(round($imgsize/4,0)).'" vspace="0" >';
	}
*/
}

function DeleteFilesM($dir, $pattern = "*.*")
{
 	$deleted = false;
	$pattern = str_replace(array("\*","\?"), array(".*","."), preg_quote($pattern));
	if (substr($dir,-1) != "/") $dir.= "/";
	if (is_dir($dir))
	{    $d = opendir($dir);
	    while ($file = readdir($d))
	    {    if (is_file($dir.$file) && ereg("^".$pattern."$", $file))
	        {
				if (unlink($dir.$file))    $deleted[] = $file;
	        }
	    }
	    closedir($d);
	    return $deleted;
	}
	else return 0;
}

function SetDefault($load_default=false)
{
	global $config,$databases,$nl,$out,$lang,$preConfig;

	// Index fuer Perl retten
	$restore_values=false;

	if ($load_default===true)
	{
		if (file_exists($config['files']['parameter']) && (is_readable($config['files']['parameter'])))  include($config['files']['parameter']); // alte Config lesen
		$restore_values=array();
		$restore_values['cron_samedb']=intval($config['cron_samedb']);
		$restore_values['db_actual_cronindex']=$databases['db_actual_cronindex'];
		if ($databases['db_actual_cronindex']>=0) // eine bestimmte Db gewaehlt?
		{
			// Ja, Namen merken, um spaeter den Index wieder herzustellen
			$restore_values['db_actual']=$databases['Name'][$databases['db_actual_cronindex']];
		}
	}

	//Arrays löschen
   	$i=0;
	$databases['Name']=Array();

	$old_lang=isset($config['language']) && in_array($config['language'],$lang['languages']) ? $config['language'] : '';
	if($load_default==true)
	{
		if(file_exists($config['files']['parameter'])) @unlink($config['files']['parameter']);
		include("./config.php");
		if(is_array($preConfig)) {
			foreach($preConfig as $key=>$val) {$config[$key]=$val;}
		}

		if($old_lang!='') $config['language']=$old_lang;
		include("./language/".$config['language']."/lang.php");
	}

	//DB-Liste holen
   	MSD_mysql_connect();

	$create_statement='CREATE TABLE `mysqldumper_test_abcxyvfgh` (`test` varchar(200) default NULL, `id` bigint(20) unsigned NOT NULL auto_increment,'
  				.'PRIMARY KEY  (`id`)) TYPE=MyISAM;';

	$res = mysql_query("SHOW DATABASES ;",$config['dbconnection']);
	if (!$res===false)
	{
		$numrows=mysql_numrows($res);
		$a=0;
   		for($i=0;$i<$numrows;$i++)
		{
			$row = mysql_fetch_row($res);
			$found_db=$row[0];

			// Testverbindung - Tabelle erstellen, nachschauen, ob es geklappt hat und dann wieder löschen
			$use=@mysql_select_db($found_db);
			if ($use)
			{
				$res2=mysql_query("DROP TABLE IF EXISTS `mysqldumper_test_abcxyvfgh`",$config['dbconnection']);
				$res2=mysql_query($create_statement,$config['dbconnection']);
				if (!$res2===false)
				{
					$res2=mysql_query("DROP TABLE IF EXISTS `mysqldumper_test_abcxyvfgh`",$config['dbconnection']);

					if( isset($old_db) && $found_db==$old_db) $databases['db_selected_index']=$a;
					$databases['Name'][$a]=$found_db;
					$databases['praefix'][$a]="";
					$databases['command_before_dump'][$a] = "";
					$databases['command_after_dump'][$a] = "";
					$out.=$lang['saving_db_form']." ".$found_db." ".$lang['added']."$nl";
					$a++;
				}
			}
		}
		if(!isset($databases['db_selected_index']))
		{
			$databases['db_selected_index']=0;
			$databases['db_actual']=$databases['Name'][0];
		}
		$databases['db_actual_cronpraefix']="";
		$databases['db_actual_cronindex']=0;
	}

	WriteParams(1,$restore_values);
   	if($load_default===true) WriteLog("default settings loaded.");

	return $out;
}

function WriteParams($as=0,$restore_values=false)
{
	// wenn $as=1 wird versucht den aktuellen Index der Datenbank nach dem Einlesen wieder zu ermitteln
	// auch wenn sich die Indexnummer durch Loeschaktionen geaendert hat
   	global $config,$databases,$config_dontsave;
	$nl="\n";

	// alte Werte retten
	if ($as)
	{
		if (is_array($restore_values))
		{
			$config['cron_samedb']=$restore_values['cron_samedb'];
			if ($restore_values['db_actual_cronindex']<0)
			{
				$databases['db_actual_cronindex']=$restore_values['db_actual_cronindex'];
			}
			else
			{
				//den Index der Datenbank aus der alten Konfiguration ermitteln
				$db_names=array();
				$db_names=array_flip($databases['Name']);
				if (isset($db_names[$restore_values['db_actual']]))
				{
					// alte Db existiert noch -> Index uebernehmen
					$databases['db_actual_cronindex']=$db_names[$restore_values['db_actual']];
					$databases['db_actual']=$restore_values['db_actual'];
				}
				else
				{
					// DB wurde zwischenzeitlich geloescht - erste DB zuordnen
					$databases['db_actual_cronindex']=0;
					$databases['db_actual']=$databases['Name'][0];
				}
			}
		}
	}
	FillMultiDBArrays();
	check_manual_dbs();

	//Parameter zusammensetzen
	$config['multipart_groesse']=$config['multipartgroesse1']*(($config['multipartgroesse2']==1) ? 1024 : 1024*1024);
	$param=$pars_all='<?php '.$nl;
	if(!isset($config['email_maxsize'])) $config['email_maxsize']=$config['email_maxsize1']*(($config['email_maxsize2']==1) ? 1024 : 1024*1024);
	if(!isset($config['cron_execution_path'])) $config['cron_execution_path']="msd_cron/";
	if($as==0) $config['paths']['root']=addslashes(Realpfad("./"));
	$config['files']['parameter']=$config['paths']['config'].$config['config_file'].'.php';
	foreach($config as $var => $val){
		if (!in_array($var,$config_dontsave))
		{
			if(is_array($val))
			{
				$pars_all.='$config[\''.$var.'\']=array();'.$nl;
				foreach($val as $var2 => $val2)
				{
					if ($config['magic_quotes_gpc']==1)
					{
						$val2=stripslashes($val2);
					}
					$pars_all.='$config[\''.$var.'\']['.((is_int($var2)) ? $var2 : "'".$var2."'").'] = \''.myaddslashes($val2)."';$nl";
				}
			}
			else
			{
				if ($config['magic_quotes_gpc']==1)
				{
					$val=stripslashes($val);
				}
				if(!in_array($var,$config_dontsave)) $pars_all.='$config[\''.$var.'\'] = \''.myaddslashes($val)."';$nl";
			}
		}
	}
	foreach($databases as $var => $val){
		if(is_array($val))
		{
			$pars_all.='$databases[\''.$var.'\']=array();'.$nl;
			foreach($val as $var2 => $val2){
				if ($config['magic_quotes_gpc']==1 || $as==1) {
					$pars_all.='$databases[\''.$var.'\']['.((is_int($var2)) ? $var2 : "'".$var2."'").'] = \''.myaddslashes(stripslashes($val2))."';$nl";
				} else {
					$pars_all.='$databases[\''.$var.'\']['.((is_int($var2)) ? $var2 : "'".$var2."'").'] = \''.myaddslashes($val2)."';$nl";
				}
			}
		} else 	{
			if ($config['magic_quotes_gpc']==0 || $as==1) {
				$pars_all.='$databases[\''.$var.'\'] = \''.addslashes($val)."';$nl";
			} else {
				$pars_all.='$databases[\''.$var.'\'] = \''.$val."';$nl";
			}
		}
	}



   $param.='?>'; $pars_all.='?>';

   //Datei öffnen und schreiben
	$ret=true;
	$file=$config['paths']['config'].$config['config_file'].'.php';
	if ($fp=fopen($file, "wb"))
	{
		if (!fwrite($fp,$pars_all)) $ret=false;
		if (!fclose($fp)) $ret=false;
		@chmod($file, 0777);
	}
	else $ret=false;

	$ret=WriteCronScript($restore_values);
	return $ret;
}

function escape_specialchars($text)
{
	$suchen=ARRAY('@','$','\\\\','"');
	$ersetzen=ARRAY('\@','\$','\\','\"');
	$text=str_replace($suchen,$ersetzen,$text);
	return $text;
}

// definiert einen SAtring, der ein Array nach Perlsyntax aufbaut
function my_implode($arr,$mode=0) // 0=String, 1=intval
{
	global $nl;
	if (!is_array($arr)) return false;
	foreach ($arr as $key=>$val)
	{
		if ($mode==0) $arr[$key]=escape_specialchars($val);
		else $arr[$key]=intval($val);
	}
	if ($mode==0) $ret='("'.implode('","',$arr).'");'.$nl;
	else $ret='('.implode(',',$arr).');'.$nl;
	return $ret;
}

function WriteCronScript($restore_values=false)
{
	global $nl, $config, $databases, $cron_save_all_dbs,
	$cron_db_array,$cron_dbpraefix_array,$cron_db_cbd_array,$cron_db_cad_array;

	if (!isset($databases['db_selected_index'])) $databases['db_selected_index']=0;
	if(!isset($databases['praefix'][$databases['db_selected_index']])) $databases['praefix'][$databases['db_selected_index']]="";
	if(!isset($databases['db_actual_cronindex'])) $databases['db_actual_cronindex']=$databases['db_selected_index'];
	if(!isset($config['email_maxsize'])) $config['email_maxsize']=$config['email_maxsize1']*(($config['email_maxsize2']==1) ? 1024 : 1024*1024);

	if($config['cron_samedb']==1)
	{
		$cron_dbname=(isset($databases['db_actual'])) ? $databases['db_actual']:0;
		$cron_dbpraefix = $databases['praefix'][$databases['db_selected_index']];
	}
	else
	{
		if($databases['db_actual_cronindex']>=0)
		{
			$cron_dbname=$databases['Name'][$databases['db_actual_cronindex']];
			$cron_dbpraefix = $databases['db_actual_cronpraefix'];

		}
		else
		{
			$cron_dbname=$databases['db_actual'];
			$cron_dbpraefix = $databases['praefix'][$databases['db_selected_index']];
		}
	}

	if($databases['db_actual_cronindex']=="-2")
	{
		$cron_save_all_dbs=1;
		$datenbanken=count($databases['Name']);
		$cron_db_array=str_replace(";","|",$databases['multisetting']);
		$cron_dbpraefix_array=str_replace(";","|",$databases['multisetting_praefix']);
		$cron_db_cbd_array=str_replace(";","|",$databases['multisetting_commandbeforedump']);
		$cron_db_cad_array=str_replace(";","|",$databases['multisetting_commandafterdump']);
	}

	if ($databases['db_actual_cronindex']=="-3")
	{
		$cron_save_all_dbs=1;
		$cron_db_array=implode("|",$databases['Name']);
		$cron_dbpraefix_array=implode("|",$databases['praefix']);
		$cron_db_cbd_array=implode("|",$databases['command_before_dump']);
		$cron_db_cad_array=implode("|",$databases['command_after_dump']);
	}

	if($databases['db_actual_cronindex']<0)
	{
		$csadb='$cron_save_all_dbs=1;'.$nl;
		$csadb.='$cron_db_array=qw('.$cron_db_array.');'.$nl;
		$csadb.='$cron_dbpraefix_array=qw('.$cron_dbpraefix_array.');'.$nl;
		$csadb.='$dbpraefix="'.$cron_dbpraefix.'";'.$nl;
		$csadb.='$command_beforedump_array="'.$cron_db_cbd_array.'";'.$nl;
		$csadb.='$command_afterdump_array="'.$cron_db_cad_array.'";'.$nl;
	}
	else
	{
		$csadb='$cron_save_all_dbs=0;'.$nl;
		$csadb.='$cron_db_array="";'.$nl;
		$csadb.='$cron_dbpraefix_array="";'.$nl;
		$csadb.='$dbpraefix="'.$cron_dbpraefix.'";'.$nl;
		if (!isset($databases['command_before_dump'][$databases['db_selected_index']])) $databases['command_before_dump'][$databases['db_selected_index']]='';
		$csadb.='$command_beforedump_array="'.$databases['command_before_dump'][$databases['db_selected_index']].'";'.$nl;
		if (!isset($databases['command_after_dump'][$databases['db_selected_index']])) $databases['command_after_dump'][$databases['db_selected_index']]='';
		$csadb.='$command_afterdump_array="'.$databases['command_after_dump'][$databases['db_selected_index']].'";'.$nl;
	}


	$r=str_replace("\\\\","/",$config['paths']['root']);
	$r=str_replace("@","\@",$r);
	$p1=$r.$config['paths']['backup'];
	$p2=$r.$config['files']['perllog'].(($config['logcompression']==1) ? '.gz':'');
	$p3=$r.$config['files']['perllogcomplete'].(($config['logcompression']==1) ? '.gz':'');

	// auf manchen Server wird statt 0 ein leerer String gespeichert -> fuehrt zu einem Syntax-Fehler
	// hier die entsprechenden Ja/Nein-Variablen sicherheitshalber in intvalues aendern
	$int_array=array('dbport','cron_compression','cron_printout','multi_part',
				'multipart_groesse','email_maxsize','auto_delete','del_files_after_days',
				'del_files_after_days','max_backup_files','perlspeed','optimize_tables_beforedump',
				'logcompression','log_maxsize','cron_completelog','cron_use_sendmail','cron_smtp_port');
	foreach ($int_array as $i)
	{
		if (is_array($i))
		{
			foreach ($i as $key=>$val)
			{
				$int_array[$key]=intval($val);
			}
		}
		else $config[$i]=intval($config[$i]);
	}
	if ($config['dbport']==0) $config['dbport']=3306;

	$cronscript="<?php\n#Vars - written at ".date("Y-m-d").$nl;
	$cronscript.='$dbhost="'.$config['dbhost'].'";'.$nl;
	$cronscript.='$dbname="'.$cron_dbname.'";'.$nl;
   	$cronscript.='$dbuser="'.escape_specialchars($config['dbuser']).'";'.$nl;
	$cronscript.='$dbpass="'.escape_specialchars($config['dbpass']).'";'.$nl;
	$cronscript.='$dbport='.$config['dbport'].';'.$nl;
	$cronscript.=$csadb;
	$cronscript.='$compression='.$config['cron_compression'].';'.$nl;
	$cronscript.='$backup_path="'.$p1.'";'.$nl;
	$cronscript.='$logdatei="'.$p2.'";'.$nl;
	$cronscript.='$completelogdatei="'.$p3.'";'.$nl;
	$cronscript.='$sendmail_call="'.escape_specialchars($config['cron_sendmail']).'";'.$nl;
	$cronscript.='$nl="\n";'.$nl;
	$cronscript.='$cron_printout='.$config['cron_printout'].';'.$nl;
   	$cronscript.='$cronmail='.$config['send_mail'].';'.$nl;
	$cronscript.='$cronmail_dump='.$config['send_mail_dump'].';'.$nl;
	$cronscript.='$cronmailto="'.escape_specialchars($config['email_recipient']).'";'.$nl;
	$cronscript.='$cronmailto_cc="'.escape_specialchars($config['email_recipient_cc']).'";'.$nl;
	$cronscript.='$cronmailfrom="'.escape_specialchars($config['email_sender']).'";'.$nl;
	$cronscript.='$cron_use_sendmail='.$config['cron_use_sendmail'].';'.$nl;
	$cronscript.='$cron_smtp="'.escape_specialchars($config['cron_smtp']).'";'.$nl;
	$cronscript.='$cron_smtp_port="'.$config['cron_smtp_port'].'";'.$nl;
	$cronscript.='@ftp_server='.my_implode($config['ftp_server']);
	$cronscript.='@ftp_port='.my_implode($config['ftp_port'],1);
	$cronscript.='@ftp_mode='.my_implode($config['ftp_mode'],1);
	$cronscript.='@ftp_user='.my_implode($config['ftp_user']);
	$cronscript.='@ftp_pass='.my_implode($config['ftp_pass']);
	$cronscript.='@ftp_dir='.my_implode($config['ftp_dir']);
	$cronscript.='@ftp_timeout='.my_implode($config['ftp_timeout'],1);
	$cronscript.='@ftp_useSSL='.my_implode($config['ftp_useSSL'],1);
	$cronscript.='@ftp_transfer='.my_implode($config['ftp_transfer'],1);
	$cronscript.='$mp='.$config['multi_part'].';'.$nl;
	$cronscript.='$multipart_groesse='.$config['multipart_groesse'].';'.$nl;
	$cronscript.='$email_maxsize='.$config['email_maxsize'].';'.$nl;
	$cronscript.='$auto_delete='.$config['auto_delete'].';'.$nl;
	$cronscript.='$cron_del_files_after_days='.$config['del_files_after_days'].';'.$nl;
	$cronscript.='$max_backup_files='.$config['max_backup_files'].';'.$nl;
	$cronscript.='$max_backup_files_each='.$config['max_backup_files_each'].';'.$nl;
	$cronscript.='$perlspeed='.$config['perlspeed'].';'.$nl;
	$cronscript.='$optimize_tables_beforedump='.$config['optimize_tables_beforedump'].';'.$nl;
	$cronscript.='$logcompression='.$config['logcompression'].';'.$nl;
	$cronscript.='$log_maxsize='.$config['log_maxsize'].';'.$nl;
	$cronscript.='$complete_log='.$config['cron_completelog'].';'.$nl;
	$cronscript.='$my_comment="'.escape_specialchars(stripslashes($config['cron_comment'])).'";'.$nl;
	$cronscript.="?>";


	//Datei öffnen und schreiben
	$ret=true;
	$ext=($config['cron_extender']==0) ? "pl" : "cgi";

	// Fix, um alte Dateien zu loeschen
	$sfile=$config['paths']['config'].$config['config_file'].'.conf';
	if(file_exists($sfile)) @unlink($sfile);
	$sfile.='.php';

	if ($fp=fopen($sfile, "wb"))
	{
		if (!fwrite($fp,$cronscript)) $ret=false;
		if (!fclose($fp)) $ret=false;
		@chmod("$sfile",0777);
	}
	else $ret=false;

    if(file_exists($config['paths']['config']."mysqldumper.conf")) @unlink($config['paths']['config']."mysqldumper.conf");

	if(!file_exists($config['paths']['config']."mysqldumper.conf.php")) {
		$sfile=$config['paths']['config']."mysqldumper.conf.php";
		if ($fp=fopen($sfile, "wb"))
		{
			if (!fwrite($fp,$cronscript)) $ret=false;
			if (!fclose($fp)) $ret=false;
			@chmod("$sfile",0777);
		}
		else $ret=false;
	}
	return $ret;

}

function LogFileInfo($logcompression) {
	global $config;

	$l=Array();$sum=$s=$l['log_size']=$l['perllog_size']=$l['perllogcomplete_size']=$l['errorlog_size']=$l['log_totalsize']=0;
	if($logcompression==1) {
		$l['log']=$config['files']['log'].".gz";
		$l['perllog']=$config['files']['perllog'].".gz";
		$l['perllogcomplete']=$config['files']['perllogcomplete'].".gz";
		$l['errorlog']=$config['paths']['log']."error.log.gz";
	} else {
		$l['log']=$config['files']['log'];
		$l['perllog']=$config['files']['perllog'];
		$l['perllogcomplete']=$config['files']['perllogcomplete'];
		$l['errorlog']=$config['paths']['log']."error.log";
	}
	$l['log_size']+=@filesize($l['log']);$sum+=$l['log_size'];
	$l['perllog_size']+=@filesize($l['perllog']);$sum+=$l['perllog_size'];
	$l['perllogcomplete_size']+=@filesize($l['perllogcomplete']);$sum+=$l['perllogcomplete_size'];
	$l['errorlog_size']+=@filesize($l['errorlog']);$sum+=$l['errorlog_size'];
	$l['log_totalsize']+=$sum;


	return $l;
}

function DeleteLog()
{
	global $config;
	//Datei öffnen und schreiben
	$log=date('d.m.Y H:i:s')." Log created.\n";
	if($config['logcompression']==1) {
		$fp = @gzopen($config['files']['log'].'.gz', "wb");
		@gzwrite ($fp,$log);
		@gzclose ($fp);
		@chmod($config['files']['log'].'.gz',0755);
	} else {
		$fp = @fopen($config['files']['log'], "wb");
		@fwrite ($fp,$log);
		@fclose ($fp);
		@chmod($config['files']['log'],0755);
	}
}

function SwitchLogfileFormat()
{
	global $config;
	$del=DeleteFilesM($config['paths']['log'],"*");
	DeleteLog();
}


function CreateDirsFTP() {

	global $config,$lang,$install_ftp_server,$install_ftp_port,$install_ftp_user_name,$install_ftp_user_pass,$install_ftp_path;
	// Herstellen der Basis-Verbindung
	 echo '<hr>'.$lang['connect_to'].' `'.$install_ftp_server.'` Port '.$install_ftp_port.' ...<br>';
    $conn_id = ftp_connect($install_ftp_server);
    // Einloggen mit Benutzername und Kennwort
    $login_result = ftp_login($conn_id, $install_ftp_user_name, $install_ftp_user_pass);
    // Verbindung überprüfen
    if ((!$conn_id) || (!$login_result)) {
            echo $lang['ftp_notconnected'];
            echo $lang['connwith']." $tinstall_ftp_server ".$lang['asuser']." $install_ftp_user_name ".$lang['notpossible'];
            return 0;
    } else {
		if ($config['ftp_mode']==1) ftp_pasv($conn_id,true);
		//Wechsel in betroffenes Verzeichnis
		echo $lang['changedir'].' `'.$install_ftp_path.'` ...<br>';
	    ftp_chdir($conn_id,$install_ftp_path);
		// Erstellen der Verzeichnisse
		echo $lang['dircr1'].' ...<br>';
	    ftp_mkdir($conn_id,"work");
	    ftp_site($conn_id, "CHMOD 0777 work");
		echo $lang['changedir'].' `work` ...<br>';
		ftp_chdir($conn_id,"work");
		echo $lang['indir'].' `'.ftp_pwd($conn_id).'`<br>';
		echo $lang['dircr5'].' ...<br>';
		ftp_mkdir($conn_id,"config");
	    ftp_site($conn_id, "CHMOD 0777 config");
		echo $lang['dircr2'].' ...<br>';
		ftp_mkdir($conn_id,"backup");
	    ftp_site($conn_id, "CHMOD 0777 backup");
		echo $lang['dircr3'].' ...<br>';
		ftp_mkdir($conn_id,"structure");
	    ftp_site($conn_id, "CHMOD 0777 structure");
		echo $lang['dircr4'].' ...<br>';
		ftp_mkdir($conn_id,"log");
	    ftp_site($conn_id, "CHMOD 0777 log");

	    // Schließen des FTP-Streams
	    ftp_quit($conn_id);
		return 1;
	}
}

function ftp_mkdirs($config,$dirname)
{
   $dir=split("/", $dirname);
   for ($i=0;$i<count($dir)-1;$i++)
   {
       $path.=$dir[$i]."/";
       @ftp_mkdir($config['dbconnection'],$path);
   }
   if (@ftp_mkdir($config['dbconnection'],$dirname))
       return 1;
}

function IsWritable($dir)
{
	$testfile=$dir . "/.writetest";
	if ($writable = @fopen ($testfile, 'w')) {
    	@fclose ($writable);
    	@unlink ($testfile);
    }
	return $writable;
}

function SearchDatabases($printout,$db='')
{
	global $databases,$config,$lang;

	if(!isset($config['dbconnection'])) MSD_mysql_connect();
	$db_list = array();
	if ($db=='')
	{
		// Datenbanken automatisch erkennen
		$show_dbs=mysql_query("SHOW DATABASES",$config['dbconnection']);
		if (!$show_dbs===false)
		{
			WHILE ($row=mysql_fetch_row($show_dbs))
			{
				if (trim($row[0])>'') $db_list[]=$row[0];
			}
		}
	}
	else $db_list[]=$db; // DB wurde manuell angegeben

	if (sizeof($db_list)>0)
	{
		$databases['db_selected_index'] = 0;
		for ($i=0;$i<sizeof($db_list);$i++)
		{
//echo "<br>Prüfe Db ".$db_list[$i];

			// Test-Select um zu sehen, ob Berechtigungen existieren
		    if(!@mysql_query("SHOW TABLES FROM `".$db_list[$i]."`",$config['dbconnection'])===false)
			{
				$databases['Name'][$i]=$db_list[0];
				$databases['praefix'][$i] = '';
				$databases['command_before_dump'][$i] = '';
				$databases['command_after_dump'][$i] = '';

				if($printout==1) echo $lang['found_db'].' `'.$db_list[$i].'`<br />';
			}
		}
	}
	if (isset($databases['Name'][0])) $databases['db_actual']=$databases['Name'][0];
}

// removes tags from inputs recursivly
function my_strip_tags($value)
{
	global $dont_strip;
	if (is_array($value))
	{
		foreach ($value as $key=>$val)
		{
			if (!in_array($key,$dont_strip)) $ret[$key]=my_strip_tags($val);
			else $ret[$key]=$val;
		}
	}
	else $ret=trim(strip_tags($value));
	return $ret;
}

function myaddslashes($t)
{
	return str_replace("'","\'",$t);
}

?>