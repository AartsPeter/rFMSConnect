<?php
	//create and attach PDF file
function print_pdf($report,$filename){
    if (!isset($filename)){ $filename =  'rFMS-Connect_report_'.random_int(10000000,99999999).'pdf';}
	require_once('plugins/tcpdf/tcpdf.php');
    ob_start();
    define('SET_PDF_AUTHOR', 'rFMS Connect');
    define('SET_PDF_FONTSIZE', '7pt');
    define('SET_PDF_TITLE', 'Report');
    define('SUM_HEADER', TRUE);
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(SET_PDF_AUTHOR);
    $pdf->SetTitle(SET_PDF_TITLE);
    $pdf->SetMargins(30,50,30,true);
    $pdf->SetHeaderMargin(30);
    $pdf->SetFooterMargin(40);
    $pdf->SetAutoPageBreak(TRUE, 40);
    $pdf->setPageUnit("pt");
    $pdf->SetTitle(SET_PDF_TITLE);
    $pdf->SetFontSize(SET_PDF_FONTSIZE);
    $pdf->SetSubject('Report');
    $pdf->setPrintHeader(false);
    $pdf->setPageMark();
    // $pdf->setPageOrientation( SET_PDF_ORIENTATION,'','');	//PDF_ORIENTATION
    // convert TTF font to TCPDF format and store it on the fonts folder
    $fontname = TCPDF_FONTS::addTTFfont('lib/plugins/tcpdf/fonts/barlow/Barlow-Regular.ttf', 'TrueTypeUnicode', '', 96);
    // use the font
    $pdf->SetFont($fontname, '', 7, '', false);
    $pdf->AddPage();
    $pdf->writeHTML($report, true, false, true, false, '');
    // ---------------------------------------------------------
    //Close and output PDF document
    $pdf->Output($filename, 'F');

	ob_end_clean();
}

