<?php
/*
FILE PDF
*/
require('require/fpdf184/fpdf.php');
require('require/funzioni.php');

// Recuper id del proprietario
$idP = $_REQUEST['idP'];

$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
$sql = "SELECT * FROM `p73e6_proprietario` WHERE id='$idP'";
$rs = $db->query($sql);
$record = $rs->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();

// Anagrafica proprietario
$pdf->SetFont('Arial','B',24);
$pdf->Multicell(180,10,"Proprietario",0,'C');

$pdf->SetFont('Arial','I',16);
$pdf->Write(10,"Il proprietario ");
$pdf->SetFont('Arial','IU',16);
$pdf->Write(10," ".$record['nome']." ".$record['cognome'].",");
$pdf->Ln();
$pdf->SetFont('Arial','I',16);
$pdf->Write(10,"Residente in ");
$pdf->SetFont('Arial','IU',16);
$pdf->Write(10," ".$record['via']." ".$record['civico']." (".$record['citta']."),");
$pdf->Ln();
$pdf->SetFont('Arial','I',16);
$pdf->Write(10,"Numero ");
$pdf->SetFont('Arial','IU',16);
$pdf->Write(10," ".$record['telefono']." ");
$pdf->SetFont('Arial','I',16);
$pdf->Write(10,", Mail ");
$pdf->SetFont('Arial','IU',16);
$pdf->Write(10," ".$record['mail']);

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',24);
$pdf->Multicell(180,10,"Immobili",0,'C');
$pdf->Ln();
$pdf->Ln();

//Lista Immobili
$sql = "SELECT i.nome AS immobile, i.via, i.civico, i.metratura, i.piano, i.locali, z.nome AS zona, t.nome AS tipologia, pr.nome, pr.cognome
    FROM p73e6_proprietario AS pr, p73e6_possiede AS po, p73e6_immobile AS i, p73e6_zona AS z, p73e6_tipologia AS t
    WHERE po.idProprietario='$idP' AND po.idProprietario=pr.id AND po.idImmobile=i.id AND i.idZona=z.id AND i.idTipologia=t.id";
$rs = $db->query($sql);
if($rs->num_rows >0){
    $record= $rs->fetch_assoc();
    // header
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(30,10,"Immobile",1,'','C');
    $pdf->Cell(30,10,"Via",1,'','C');
    $pdf->Cell(15,10,"Civico",1,'','C');
    $pdf->Cell(21,10,"Metratura",1,'','C');
    $pdf->Cell(13,10,"Piano",1,'','C');
    $pdf->Cell(14,10,"Locali",1,'','C');
    $pdf->Cell(25,10,"Zona",1,'','C');
    $pdf->Cell(25,10,"Tipologia",1,'','C');
    // record
    while($record){
        $pdf->Ln();
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(30,10,$record['immobile'],1,'','C');
        $pdf->Cell(30,10,$record['via'],1,'','C');
        $pdf->Cell(15,10,$record['civico'],1,'','C');
        $pdf->Cell(21,10,$record['metratura'],1,'','C');
        $pdf->Cell(13,10,$record['piano'],1,'','C');
        $pdf->Cell(14,10,$record['locali'],1,'','C');
        $pdf->Cell(25,10,$record['zona'],1,'','C');
        $pdf->Cell(25,10,$record['tipologia'],1,'','C');
        $record= $rs->fetch_assoc();
    }
}
else{
    $pdf->SetFont('Arial','I',16);
    $pdf->Write(10,"Nessun Immobile Acquistato");
}

$db->close();
$pdf->Output();
?>