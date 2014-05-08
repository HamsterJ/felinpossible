<?php

require('fpdf17/fpdf.php');

function print_tag($pdf,$nom,$valeur,$x,$y)
{
    $pdf->SetFont('Arial','BU',12);
    $pdf->setXY($x,$y);
    $pdf->Cell(10,10,utf8_decode($nom));
    
    $pdf->SetFont('Arial','',12);
    $pdf->setXY($x+32,$y);
    $pdf->Cell(10,10,utf8_decode($valeur));
}

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
        print_tag($pdf,'',  substr($beanVeto->getRaison(), 0, 52),30,45);
        print_tag($pdf,'',$beanVeto->getAdresse(),30,50);
        print_tag($pdf,'',$beanVeto->getCodePostal().' - '. $beanVeto->getVille(),30,55);
        print_tag($pdf,'',$beanVeto->getTelephoneFixe(),30,60);
    }

    //Chat
    $pdf->setXY(20,80);
    $pdf->Cell(170,30,'',1,2,'L');
    $pdf->SetFont('Arial','BU',16);
    $pdf->setXY(20,80);
    $pdf->Cell(10,10,'Chat :');

    print_tag($pdf,'Nom :',$ficheSoinForm->nomChat->getValue(),30,90);
    print_tag($pdf,'Sexe :',$ficheSoinForm->sexe->getValue(),120,90);
    print_tag($pdf,'Couleur :',$ficheSoinForm->couleur->getValue(),30,95);
    print_tag($pdf,utf8_decode('Ne(e) le :'),$ficheSoinForm->dateNaissance->getValue(),120,95);
    print_tag($pdf,'Identification :',$ficheSoinForm->identification->getValue(),30,100);

    //FA
    $pdf->setXY(20,120);
    $pdf->Cell(170,35,'',1,2,'L');
    $pdf->SetFont('Arial','BU',16);
    $pdf->setXY(20,120);
    $pdf->Cell(10,10,$ficheSoinForm->qualite->getValue()." :");

    print_tag($pdf,'Nom :',$ficheSoinForm->nom->getValue(),30,130);
    print_tag($pdf,'Adresse :',$ficheSoinForm->adresse->getValue(),30,135);
    print_tag($pdf,'Ville :',$ficheSoinForm->codePostal->getValue().' - '.$ficheSoinForm->ville->getValue(),30,140);
    print_tag($pdf,'Tel. Fixe :',$ficheSoinForm->telephoneFixe->getValue(),30,145);
    print_tag($pdf,'Tel. Portable :',$ficheSoinForm->telephonePortable->getValue(),110,145);

    //Soins
    $pdf->SetFont('Arial','BU',16);
    $pdf->setXY(20,165);
    $pdf->Cell(10,10,utf8_decode("Soins à effectuer :"));

    $pdf->SetFont('Arial','',12);
    $pos_nom_soin = 0;
    
    if ($ficheSoinForm->soinPuce->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->Cell(10,10,'Identification (puce)'); 
    }
    if ($ficheSoinForm->soinTatouage->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->Cell(10,10,'Identification (tatouage)'); 
    }
    if ($ficheSoinForm->soinVaccins->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->Cell(10,10,'Vaccins TCL'); 
    }
    if ($ficheSoinForm->soinTests->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->Cell(10,10,'Tests FIV/FELV'); 
    }
    if ($ficheSoinForm->soinAntiParasites->checked) {   
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->Cell(10,10,'Anti-parasitaire externe'); 
    }
    if ($ficheSoinForm->soinVermifuge->checked) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->Cell(10,10,'Vermifuge'); 
    }
    if ($ficheSoinForm->soinSterilisation->getValue()) {
        $pdf->setXY(30,175+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->Cell(10,10,utf8_decode($ficheSoinForm->soinSterilisation->getMultiOption($ficheSoinForm->soinSterilisation->getValue())));
    }
    if ($ficheSoinForm->soinAutre->getValue()) {
        $pdf->setXY(30,180+$pos_nom_soin);
        $pos_nom_soin=$pos_nom_soin+5;
        $pdf->MultiCell(154,5,utf8_decode($ficheSoinForm->soinAutre->getValue()));
    }

    //cadre soins (fait a posteriori)
    $pdf->setXY(20,165);
    $pdf->Cell(170,23+$pos_nom_soin,'',1,2,'L');
    
    $pdf->setXY(20,190+$pos_nom_soin);
    $pdf->SetFont('Arial','BU',12);
    $pdf->Cell(10,10,utf8_decode('Notes destinées au vétérinaire :'));

    //surlignages
    $pdf->SetFillColor(255,255,90);
    $pdf->setXY(88,200+$pos_nom_soin);
    $pdf->Cell(50,5,'',1,1,'C',true);
    $pdf->setXY(20,210+$pos_nom_soin);
    $pdf->Cell(35,5,'',1,1,'C',true);
    $pdf->SetFillColor(0,0,0);
    
    $pdf->SetFont('Arial','I',12);
    $pdf->setXY(20,200+$pos_nom_soin);
    $pdf->MultiCell(180,5,utf8_decode("- Merci de bien vouloir pratiquer les Tarifs Protection Animale"));
    $pdf->setXY(20,205+$pos_nom_soin);
    $pdf->MultiCell(180,5,utf8_decode("- Pour une première visite, merci de bien vouloir vérifier systématiquement la présence d'une puce électronique sur le chat"));
    $pdf->setXY(20,215+$pos_nom_soin);
    $pdf->MultiCell(180,5,utf8_decode("- L'identification est à faire au nom de l'association (coordonnées en bas de page)"));
    $pdf->setXY(20,220+$pos_nom_soin);
    $pdf->MultiCell(180,5,utf8_decode("- Cette fiche est à joindre avec la facture à l'adresse indiquée en bas de page"));
    $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','BI',12);
    $pdf->setXY(20,225+$pos_nom_soin);
    $pdf->MultiCell(180,5,utf8_decode("- Merci de nous contacter au 06 28 19 73 84 pour tout acte à réaliser, non-indiqué sur cette fiche, ou toute question concernant le chat, le tarif ou l'association."));
    
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
    $pdf->MultiCell(180,3, utf8_decode("Association FELIN POSSIBLE - Chez Mlle CANTIN - Sèvegrand - 35520 LA CHAPELLE DES FOUGERETZ - 06.28.19.73.84 - asso@felinpossible.fr - www.felinpossible.fr"));
    
    $pdf->Output('Soins_'.$ficheSoinForm->nomChat->getValue().'_'.date('dmY', time()).'.pdf','D');
}

?>
