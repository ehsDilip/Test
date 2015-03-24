<?php
//ob_start();
require('WriteHTML.php');

$pdf=new PDF_HTML();

$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 15);

$pdf->AddPage();
//$pdf->Image('logo.png',18,13,33);
//$pdf->SetFont('Arial','B',14);
//$pdf->WriteHTML('<para><h1>PHPGang Programming Blog, Tutorials, jQuery, Ajax, PHP, MySQL and Demos</h1><br>
//Website: <u>www.phpgang.com</u></para><br><br>How to Convert HTML to PDF with fpdf example');

$pdf->SetFont('Arial','B',7); 
$htmlTable='<table  border="1" >
        
        <tbody>
        
        <tr class="thead">
            <td height="21"><b>QUANTITY</b></td>
            <td><b>DESCRIPTION</b></td>
            <td><b>UNIT PRICE</b></td>
            <td><b>RETAIL PRICE</b></td>
            <td><b>LINE TOTAL</b></td>
        </tr>
        <tr class="trows">
            <td>1</td>
            <td>510</td>
            <td>$<span id="price_1">4.92</span>
                </td>
            <td>no</td>
            <td bgcolor="#fff" id="total_1" class="lTot">12</td>
        </tr>
        </tbody>
    </table>';
$pdf->WriteHTML3($htmlTable);
$pdf->SetFont('Arial','B',6);
$pdf->Output(); 
//ob_end_flush();

?>