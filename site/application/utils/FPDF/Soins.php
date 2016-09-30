<?php
/**
 * Utilitaires pour l'impression PDF des fiches de soins depuis l'admin
 * @author FlickFlack2104
 *
 */
 
require('fpdf17/fpdf.php');

/**
 * Imprime une information de chat sous la forme "<info>(gras/souligné) : <valeur>" 
 * @param $pdf : le document pdf
 * @param $nom : Nom de l'information à imprimer
 * @param $valeur : Valeur de l'information à imprimer	 
 * @param $x,$y : Position de l'information à imprimer	 
 */
function print_tag($pdf,$nom,$valeur,$x,$y,$max_length)
{
    $pdf->SetFont('Arial','BU',12);
    $pdf->setXY($x,$y);
    $pdf->Cell(10,10,utf8_decode($nom));
    
    //adaptation taille à la rache
    if (strlen($valeur) < $max_length)
    {$pdf->SetFont('Arial','',12);}
    else if (strlen($valeur) < $max_length + 15)
    {$pdf->SetFont('Arial','',10);}
    else 
    {$pdf->SetFont('Arial','',8);}

    $pdf->setXY($x+32,$y);
    $pdf->Cell(10,10,utf8_decode($valeur));
}

/**
 * Imprime le pdf de fiche de soin à partir du formulaire web 
 * @param $ficheSoinForm : Formulaire web PHP contenant les infos à imprimer
 */
function print_pdf(FP_Form_chat_FicheSoinsForm $ficheSoinForm)
{
    
    $pdf = new FPDF();
    $pdf->SetAutoPagebreak(False);
    $pdf->SetMargins(0,0,0);
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);

    //Entête
    $pdf->Image('../application/utils/FPDF/logo.png',10,12,30,0,'','');
    $pdf->SetFont('Arial','B',24);
    $pdf->setXY(60,15);
    $pdf->Cell(10,10,utf8_decode('Fiche de soins vétérinaires'));

    //Véto
    $beanVeto = FP_Model_Mapper_MapperFactory::getInstance()->vetoMapper->find($ficheSoinForm->idVeto->getValue());
    
    $pdf->setXY(20,40);
    $pdf->Cell(170,30,'',1,2,'L');
    $pdf->SetFont('Arial','BU',16);
    $pdf->setXY(20,40);
    $pdf->Cell(10,10,utf8_decode('Vétérinaire :'));

    
    if ($beanVeto) {     
        print_tag($pdf,'',  substr($beanVeto->getRaison(), 0, 52),30,45,50);
        print_tag($pdf,'',$beanVeto->getAdresse(),30,50,50);
        print_tag($pdf,'',$beanVeto->getCodePostal().' - '. $beanVeto->getVille(),30,55,50);
        print_tag($pdf,'',$beanVeto->getTelephoneFixe(),30,60,50);
    }

    //Chat
    $pdf->setXY(20,80);
    $pdf->Cell(170,30,'',1,2,'L');
    $pdf->SetFont('Arial','BU',16);
    $pdf->setXY(20,80);
    $pdf->Cell(10,10,'Chat :');

    print_tag($pdf,'Nom :',$ficheSoinForm->nomChat->getValue(),30,90,50);
    print_tag($pdf,'Sexe :',$ficheSoinForm->sexe->getValue(),110,95,20);
    print_tag($pdf,'Couleur :',$ficheSoinForm->couleur->getValue(),30,95,20);
    print_tag($pdf,utf8_decode('Ne(e) le :'),$ficheSoinForm->dateNaissance->getValue(),110,100,20);
    print_tag($pdf,'Identification :',$ficheSoinForm->identification->getValue(),30,100,20);

    //FA
    $pdf->setXY(20,120);
    $pdf->Cell(170,35,'',1,2,'L');
    $pdf->SetFont('Arial','BU',16);
    $pdf->setXY(20,120);
    $pdf->Cell(10,10,$ficheSoinForm->qualite->getValue()." :");

    print_tag($pdf,'Nom :',$ficheSoinForm->nom->getValue(),30,130,50);
    print_tag($pdf,'Adresse :',$ficheSoinForm->adresse->getValue(),30,135,50);
    print_tag($pdf,'Ville :',$ficheSoinForm->codePostal->getValue().' - '.$ficheSoinForm->ville->getValue(),30,140,50);
    print_tag($pdf,'Tel. Fixe :',$ficheSoinForm->telephoneFixe->getValue(),30,145,20);
    print_tag($pdf,'Tel. Portable :',$ficheSoinForm->telephonePortable->getValue(),110,145,20);

    //Soins
    $pdf->SetFont('Arial','BU',16);
    $pdf->setXY(20,165);
    $pdf->Cell(10,10,utf8_decode("Soins à effectuer :"));

    $pdf->SetFont('Arial','',10);
    $pos_nom_soin = 0;
    
    if ($ficheSoinForm->soinPuce->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+4;
        $pdf->Cell(10,10,'Identification (puce)'); 
    }
    if ($ficheSoinForm->soinTatouage->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+4;
        $pdf->Cell(10,10,'Identification (tatouage)'); 
    }
    if ($ficheSoinForm->soinVaccins->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+4;
        $pdf->Cell(10,10,'Vaccins TCL'); 
    }
    if ($ficheSoinForm->soinTests->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+4;
        $pdf->Cell(10,10,'Tests FIV/FELV'); 
    }
    if ($ficheSoinForm->soinAntiParasites->checked) {   
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+4;
        $pdf->Cell(10,10,'Anti-parasitaire externe'); 
    }
    if ($ficheSoinForm->soinVermifuge->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+4;
        $pdf->Cell(10,10,'Vermifuge'); 
    }
    if ($ficheSoinForm->soinSterilisation->getValue()) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+4;
        $pdf->Cell(10,10,utf8_decode($ficheSoinForm->soinSterilisation->getMultiOption($ficheSoinForm->soinSterilisation->getValue())));
    }
    
    $com_ss_retligne = $ficheSoinForm->soinAutre->getValue() ? (str_replace(CHR(13).CHR(10),' - ',$ficheSoinForm->soinAutre->getValue())) :'';
    
    if ($ficheSoinForm->soinAutre->getValue()) {
        $pdf->setXY(30,180+$pos_nom_soin);
        if ($pos_nom_soin < 10)
            {$pdf->SetFont('Arial','',10);}
        else if ($pos_nom_soin > 20)
            {$pdf->SetFont('Arial','',7);}
        else 
            {$pdf->SetFont('Arial','',8);}
        $pos_nom_soin=$pos_nom_soin+4;
        
        $pdf->MultiCell(154,4,utf8_decode($com_ss_retligne));
    }
    
    //cadre soins (fait a posteriori)
    $pdf->setXY(20,165);
    $pdf->Cell(170,60,'',1,2,'L'); // 2+L : retour auto à la ligne
    
    //$pdf->setXY(20,190+$pos_nom_soin);
    $pdf->SetFont('Arial','BU',12);
    $pdf->Cell(10,10,utf8_decode('Notes destinées au vétérinaire :'));

    $pos_fin = $pdf->getY()+10;
    
    
    //surlignages (avant d'ecrire pour que ce soit en dessous)
    $pdf->SetFillColor(255,255,90);
    $pdf->setXY(88,$pos_fin);
    $pdf->Cell(50,5,'',1,1,'C',true);
    $pdf->setXY(20,$pos_fin+10);
    $pdf->Cell(35,5,'',1,1,'C',true);
    $pdf->SetFillColor(0,0,0);
    
    $pdf->SetFont('Arial','I',12);
    $pdf->setXY(20,$pos_fin);
    $pdf->MultiCell(180,5,utf8_decode("- Merci de bien vouloir pratiquer les Tarifs Protection Animale"));
    $pdf->setXY(20,$pos_fin+5);
    $pdf->MultiCell(180,5,utf8_decode("- Pour une première visite, merci de bien vouloir vérifier systématiquement la présence d'une puce électronique sur le chat"));
    $pdf->setXY(20,$pos_fin+15);
    $pdf->MultiCell(180,5,utf8_decode("- L'identification est à faire au nom de l'association (coordonnées en bas de page)"));
    $pdf->setXY(20,$pos_fin+20);
    $pdf->MultiCell(180,5,utf8_decode("- Cette fiche est à joindre avec la facture à l'adresse indiquée en bas de page"));
    $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','BI',12);
    $pdf->setXY(20,$pos_fin+25);
    $pdf->MultiCell(180,5,utf8_decode("- Merci de nous contacter au 06 28 19 73 84 pour tout acte à réaliser, non-indiqué sur cette fiche, ou toute question concernant le chat, le tarif ou l'association."));
    
    //Pied de page
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','I',12);
    $pdf->setXY(20,272); 
    $cdate =  date('d/m/Y', time());
    $pdf->SetFont('Arial','BU',12);
    $pdf->Cell(10,10,utf8_decode('Fait le '.$cdate));
    
    $pdf->setXY(100,272);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(10,10,utf8_decode('Signature FELIN POSSIBLE : '));
    $pdf->Image('../application/utils/FPDF/signature.png',160,272,30,0,'','');
     
    $pdf->setXY(20,285);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(100,100,100);
    $pdf->MultiCell(180,3, utf8_decode("Association FELIN POSSIBLE - Chez Mme Véronique CHANU - 5 allée Roger Le Poulennec - 35000 RENNES - 06.28.19.73.84 - asso@felinpossible.fr - www.felinpossible.fr/"));
    
	//Impression du pdf
    $pdf->Output('Soins_'.$ficheSoinForm->nomChat->getValue().'_'.date('dmY', time()).'.pdf','D');
}

?>
