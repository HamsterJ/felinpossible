<?php
include_once('./language/'.$config['language'].'/lang.php');
include_once('./language/'.$config['language'].'/lang_dump.php');

$tblr=($tblfrage_refer=='dump') ? 'Backup' : 'Restore';
$filename=(isset($_GET['filename'])) ? $_GET['filename'] : '';
if (isset($_POST['file'][0])) $filename=$_POST['file'][0];

$sel_dump_encoding=isset($_POST['sel_dump_encoding']) ? $_POST['sel_dump_encoding']:'';

//Informationen zusammenstellen
if($tblr=='Backup')
{
	//Info aus der Datenbank lesen
	MSD_mysql_connect();
	$res=mysql_query('SHOW TABLE STATUS FROM `'.$databases['db_actual'].'`');
	$numrows=mysql_num_rows($res);
	$button_name='dump_tbl';
	$button_caption=$lang['startdump'];
	$tbl_zeile='';
	for($i=0;$i<$numrows;$i++) {
		$row=mysql_fetch_array($res);
		// Get nr of records -> need to do it this way because of incorrect returns when using InnoDBs
		$sql_2="SELECT count(*) as `count_records` FROM `".$databases['db_actual']."`.`".$row['Name']."`";
		$res2=@mysql_query($sql_2);
		$row2=mysql_fetch_array($res2);
		$row['Rows']=$row2['count_records'];

		$klasse=($i % 2) ? 1:'';
		$tbl_zeile.='<tr class="dbrow'.$klasse.'"><td class="sm" align="left"><input type="checkbox" class="checkbox" name="chk_tbl" value="'.$row['Name'].'">'.$row['Name'].'</td>';
		$tbl_zeile.='<td class="sm" align="left"><strong>'.$row['Rows'].'</strong> '.$lang['datawith'].' <strong>'.byte_output($row['Data_length']+$row['Index_length']).'</strong>, '.$lang['lastbufrom'].' '.$row['Update_time'].'</td></tr>';
	}
} else {
	//Restore - Header aus Backupfile lesen
	$button_name='restore_tbl';
	$button_caption=$lang['fm_restore'];
	$gz = (substr($filename,-3))=='.gz' ? 1 : 0;
	if ($gz)
	{
		$fp = gzopen ($fpath.$filename, "r");
		$statusline=gzgets($fp,40960);
		$offset= gztell($fp);
	} else 	{
		$fp = fopen ($fpath.$filename, "r");
		$statusline=fgets($fp,5000);
		$offset= ftell($fp);
	}
	//Header auslesen
	$sline=ReadStatusline($statusline);

	$anzahl_tabellen=$sline['tables'];
	$anzahl_eintraege=$sline['records'];
	$tbl_zeile='';
	$part=($sline['part']=="") ? 0 : substr($sline['part'],3);
	if($anzahl_eintraege==-1)
	{
		// not a backup of MySQLDumper
		$tbl_zeile.='<tr class="dbrow"><td class="sm" colspan="2">'.$lang['not_supported'].'</td>';
	}
	else
	{
		$tabledata=array();
		$i=0;
		//Tabellenfinos lesen
		gzseek($fp,$offset);
		$eof=false;
		WHILE (!$eof)
		{
			$line=$gz ? gzgets($fp,40960):fgets($fp,40960);

			if (substr($line,0,9)=='-- TABLE|')
			{
				$d=explode('|',$line);
				$tabledata[$i]['name']=$d[1];
				$tabledata[$i]['records']=$d[2];
				$tabledata[$i]['size']=$d[3];
				$tabledata[$i]['update']=$d[4];
				$i++;
			}
			if (substr($line,0,6)=='-- EOF') $eof=true;
			if (substr(strtolower($line),0,6)=='create') $eof=true;
		}

		for($i=0;$i<sizeof($tabledata);$i++)
		{
			$s=explode("|",$statusline);
			$class=($i % 2) ? 'dbrow':'dbrow1';
			$tbl_zeile.='<tr class="'.$class.'">'
					."\n".'<td align="left">'
					.'<input type="checkbox" class="checkbox" name="chk_tbl" value="'.$tabledata[$i]['name'].'">'.$tabledata[$i]['name']
					."</td>\n";
			$tbl_zeile.='<td align="left"><strong>'.$tabledata[$i]['records'].'</strong> '
					.$lang['datawith'].' <strong>'.byte_output($tabledata[$i]['size']).'</strong>, '
					.$lang['lastbufrom'].' '.$tabledata[$i]['update'].'</td>'
					."\n".'</tr>';
		}
	}
	if($gz) gzclose ($fp); else fclose ($fp);
}

$buttons='<tr><td colspan="2"><table width="100%" border="0"><tr>'
.'<td><input type="button" class="Formbutton" onclick="Sel(true);" value="'.$lang['selectall'].'"></td>'
.'<td><input type="button" onclick="Sel(false);" value="'.$lang['deselectall'].'" class="Formbutton"></td>'
.'<td><input type="submit" class="Formbutton" style="width:180px;" name="'.$button_name.'" value="'.$button_caption.'"></td>'
.'</tr></table></td></tr>';

echo '<div id="pagetitle">'.$tblr.' - '.$lang['tableselection'].'</div><h6>'.$lang['db'].': '.$databases['db_actual'].'</h6>';
echo '<div id="content">';
echo '<form name="frm_tbl" action="filemanagement.php" method="post" onSubmit="return chkFormular()">';
echo '<table class="bordersmall">';

echo $buttons;
echo $tbl_zeile;
echo $buttons;

if (!isset($dk)) $dk='';

echo '</table>
<input type="hidden" name="dumpKommentar" value="'.$dk.'">
<input type="hidden" name="tbl_array" value="">
<input type="hidden" name="filename" value="'.$filename.'">
<input type="hidden" name="sel_dump_encoding" value="'.$sel_dump_encoding.'">
</form>';
echo '</div><br><br><br>';

?>