<?php
	// ini_set("memory_limit","64M");
	error_reporting(E_ALL & ~E_NOTICE);
	// require_once('connectttt.php');
	// require_once('tcpdf_include.php');
	// include('connect.php');
	include('../adodb5/adodb.inc.php'); 
	include ('../adodb5/tohtml.inc.php');
	include('../sum_function.php'); 
	$databasetype = 'mssql';
	$server = 'montrigroup1';
	$user   = 'co_kantachai_c';
	$password = 'Kusyam11';
	$database = '';
	$connect = ADONewConnection($databasetype); 
	$connect->Connect($server, $user, $password, $database); 
	$connect->SetFetchMode(ADODB_FETCH_ASSOC);
	// var_dump($_GET);
	$sel_date = "";
	$strRecive = $_GET['datepick'];
	// $strRecive = $_GET['datepick_show'];
	// $arrData = json_decode($strRecive,true);
	if(empty($strRecive)){
		$sel_date = date("Y-m-d");
		}else{
		$sel_date = date("Y-m-d",strtotime($strRecive));
	}
	// armpre(parndatethai($sel_date));
	//=========================================
	require_once('../tcpdf/tcpdf.php');
	class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// Logo
			global $sel_date; $carwt; $alldisg1; $alldism7; $alldisplus; $alldism5; $alldisg1; $alldisunique; $alldiscdg4; $alldisg4; $alldismarL; $alldismarR; $colsum; $cou;
		}
		
		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetFont('angsanaupc', '', 14);
			$this->Cell(28, 5, date("d/m/Y H:i:s"), 0, false, 'R', 0, '', 0, false, 'T', 'M');
			$this->Cell(338, 5, 'page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			$this->SetY(-50);
		}
	}  
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'mm', array(210,330), true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nicola Asuni');
	$pdf->SetTitle('ต้นฉบับกุญแจประจำอู่');
	$pdf->SetSubject('TCPDF Tutorial');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
	
	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	// set margins
	// $pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarL, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
	
	// ---------------------------------------------------------
	$pdf->SetFont('angsanaupc', '', 14);
	$pdf->AddPage('P', 'F4');
	$underline = '______________________________________________________________________________________________________';
	$text = '';
	$tmp_r = '';
	$gar_color = array(1 => '#FFFF99','#FF9966','#3333FF; color:white;','#FF9CD6','#73D549','#800080; color:white;','#e0e0e0; color:black;','#33CCFF', $checkred);
	$pdf->SetFont('angsanaupc', '', 14);
	$date = parndatethai($sel_date);
	$nnn = armtis620($date);
	$d = explode(' ', $date);
	
	$image = array(1=>'<img src="../img/cross.png" border="0" align="center"/>', '<img src="../img/check.png" border="0" align="center"/>');
	
	$image2 = array(9=>'<img src="../img/cross.png" border="0" align="center"/>', 10=>'<img src="../img/check.png" border="0" align="center"/>');
	
	$sql = "SELECT dg.df_id, dg.gar_id, dg.car_no, ISNULL(tmp.gar_id, 0) AS gar_cha, tmp.garc_option, SUBSTRING(dg.car_no, 1, 1) AS Initial, ISNULL(tmp.garc_option, 0) AS gar_option
	FROM   parn.dbo.default_gar AS dg LEFT OUTER JOIN
	(SELECT TOP 100 PERCENT gc.gar_id, gc.garc_option, gc.garc_carserial, Personal2.dbo.Car.CarNo
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car ON gc.garc_carserial = Personal2.dbo.Car.CarSerial
	WHERE (gc.garc_date = '{$sel_date}') AND (COALESCE (gc.garc_delete, 0) = 0)
	GROUP BY gc.garc_carserial, gc.gar_id, gc.garc_option, Personal2.dbo.Car.CarNo
	ORDER BY gc.gar_id, Personal2.dbo.Car.CarNo) AS tmp ON dg.df_carserial = tmp.garc_carserial
	WHERE (COALESCE(dg.df_delete,0) = 0)
	ORDER BY dg.gar_id, dg.car_no";
	// armpre($sql);
	$rs = $connect->execute($sql);
	$i = 0;
	foreach($rs as $key => $data){
		if($gar_id!=$data['gar_id'] ){
			$gar_id=$data['gar_id'];
			$i=0;
		}
		if($data['Initial'] == 'C' || $data['Initial'] == 'D' || $data['Initial'] == 'M' || $data['Initial'] == 'P'){
			if($Initial!=$data['Initial'] ){
				$Initial=$data['Initial'];
				$i=0;
			}
			$cout = count($arr[$data['gar_id']][$data['Initial']][$i]);
			if($cout == 49){
				$arr[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
				
				$arr2[$data['gar_id']][$data['Initial']][$i][] = $data['gar_cha'];
				
				$arr3[$data['gar_id']][$data['Initial']][$i][] = $data['gar_option'];
				
				// $arr4[$data['gar_cha']][$data['Initial']][$i][] = $data['gar_option'];
				$i++;
				}else{
				$arr[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
				$arr[$data['gar_id']][] = $data['car_no'];
				
				$arr2[$data['gar_id']][$data['Initial']][$i][] = $data['gar_cha'];
				$arr2[$data['gar_id']][] = $data['gar_cha'];
				
				$arr3[$data['gar_id']][$data['Initial']][$i][] = $data['gar_option'];
				$arr3[$data['gar_id']][] = $data['gar_option'];
				
				// $arr4[$data['gar_cha']][$data['Initial']][$i][] = $data['gar_option'];
			}
			}else {
			$arr[$data['gar_id']][$data['Initial']][] = $data['car_no'];
			$arr[$data['gar_id']][] = $data['car_no'];
			
			$arr2[$data['gar_id']][$data['Initial']][] = $data['gar_cha'];
			$arr2[$data['gar_id']][] = $data['gar_cha'];
			$arr2plus[$data['gar_id']][] = $data['gar_cha'];
			
			$arr3[$data['gar_id']][$data['Initial']][$i][] = $data['gar_option'];
			$arr3[$data['gar_id']][$data['Initial']][] = $data['gar_option'];
			$arr3[$data['gar_id']][] = $data['gar_option'];
			// $arr3[$data['gar_id']][$data['Initial']][] = $data['gar_option'];
			// $arr4[$data['gar_cha']][$data['Initial']][$i][] = $data['gar_option'];
		}
		if($data['Initial'] == 'C' || $data['Initial'] == 'D' || $data['Initial'] == 'V' || $data['Initial'] == 'P'){
			$gar2plus[$data['gar_id']][] = $data['car_no'];
			$gar2cha_plus[$data['gar_id']][] = $data['gar_cha'];
			$gar2op_plus[$data['gar_id']][] = $data['gar_option'];
		}
		if($data['Initial'] == 'V' || $data['Initial'] == 'P'){
			$gar4plus[$data['gar_id']][] = $data['car_no'];
			$gar4cha_plus[$data['gar_id']][] = $data['gar_cha'];
			$gar4op_plus[$data['gar_id']][] = $data['gar_option'];
		}
		if($data['Initial'] == 'V'){
			$gar1plus[$data['gar_id']][] = $data['car_no'];
			$gar1cha_plus[$data['gar_id']][] = $data['gar_cha'];
			$gar1op_plus[$data['gar_id']][] = $data['gar_option'];
		}
	}
	$text .= '<style type="text/css">
	.tg td{sans-serif;font-size:14px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border: 2px solid #000;}
	.tg th{sans-serif;font-size:14px;font-weight:normal;overflow:hidden;word-break:normal;}
	.tg .tg-5wpd{font-weight:bold;font-size:18px; sans-serif text-align:center;vertical-align:top}
	.tg .tg-guz1{font-weight:bold;font-size:14px; text-align:center;vertical-align:top}
	.tg .tg-baqh{text-align:center;vertical-align:top; width:32.99px;}
	.tg .tg-t9ra{font-size:12px; sans-serif text-align:center;vertical-align:top}
	.tg .tg-tvby{font-size:14px; sans-serif vertical-align:top}
	.tg .tg-m7{text-align:center;vertical-align:top; width:199.3px;}
	.tg .tg-m5{text-align:center;vertical-align:top; width:96px;}
	.tg .tg-m3{text-align:center;vertical-align:top; width:63.6px;}
	.tg .tg-cd{text-align:center;vertical-align:top; width:99px;}
	.tg .tg-p{text-align:center;vertical-align:top; width:34.5px;}
	.tg .tg-v{text-align:center;vertical-align:top; width:20px;}
	.tg .tg-c{text-align:center;vertical-align:top; width:25px; border-left:2px solid #000; border-right:0.1px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000; }
	.tg .tg-v1{text-align:center;vertical-align:top; width:30px; border-left:0.1px solid #000; border-right:2px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000; }
	.tg .tg-unique{text-align:center;vertical-align:top; width:30px; border-left:0.1px solid #000; border-right:0.1px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000;}
	.tg .tg-plus{text-align:center;vertical-align:top; width:30px; border-left:0.1px solid #000; border-right:0.1px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000;}
	.tg .tg-uniqueLast{text-align:center;vertical-align:top; width:30px;  border-left:0.1px solid #000; border-right:2px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000;}
	.tg .tg-uniqueLastgar1{text-align:center;vertical-align:top; width:34.5px; border-left:0.1px solid #000; border-right:2px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000;}
	.tg .tg-uniqueLastgar2{text-align:center;vertical-align:top; width:30px; border-left:0.1px solid #000; border-right:2px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000;}
	.tg .tg-uniqueLastgar3{text-align:center;vertical-align:top; width:30px; border-left:0.1px solid #000; border-right:2px solid #000; border-bottom:0.1px solid #000; border-top:0.1px solid #000;}
	.tg .tg-d{text-align:center;vertical-align:top; width:28px;}
	.tg .tg-thin{border: 0.1px solid #000;}
	.color_2{background-color:#FFFF99}
	.foo {
	float: left;
	width: 20px;
	height: 20px;
	margin: 5px 5px 5px 5px;
	border-width: 1px;
	border-style: solid;
	border-color: rgba(0,0,0,.2);
	}
	
	.red{
	color:red;
	}
	</style>
	<table class="tg">
	<tr>
	<thead>
	<th class="tg-5wpd" colspan="10">ตรวจเช็คกุญแจรถที่จอดประจำอู่  วันที่ <u>'.$d[0].'</u> เดือน <u>'.$d[1].'</u> พ.ศ <u>'.$d[2].'</u></th>
	</thead>
	</tr> 
	<tr>
	<td colspan="2" style="font-size:14px">
	'.$image[1].' = ค้างซ่อม, '.$image[2].' = รอขาย
	</td>
	</tr>
	<tr>';
	if($gar1plus[1] != ''){
		$text .= '<td class="tg-guz1"colspan="3" style="width:287px">อู่1</td>';
		}else{
		$text .= '<td class="tg-guz1"colspan="3" style="width:257px">อู่1</td>';
	}
	if($gar2plus[2] != ''){
		$text .= '<td class="tg-guz1"colspan="3" style="width:126.5px">อู่2</td>';
		}else{
		$text .= '<td class="tg-guz1"colspan="3" style="width:96.5px">อู่2</td>';
	}
	$text .= '<td class="tg-guz1 tg-unique">อู่3</td>';
	if($gar4plus[4] != ''){
		$text .= '<td class="tg-guz1"colspan="3" style="width:192.6px">อู่4</td>';
		}else{
		$text .= '<td class="tg-guz1"colspan="3" style="width:162.6px">อู่4</td>';
	}
	$text .= '
	<td class="tg-guz1 tg-unique">อู่5</td>
	<td class="tg-guz1 tg-unique">อู่6</td>
	<td class="tg-guz1 tg-unique">SR</td>
	</tr>
	<tr>';
	if($gar1plus[1] != '' && $gar2plus[2] == '' && $gar4plus[4] == ''){
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 666.1;
		$alldismarg1L = 5;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		}elseif($gar1plus[1] != '' && $gar2plus[2] != '' && $gar4plus[4] == ''){
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 696.1;
		$alldismarg1L = 10;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		}elseif($gar1plus[1] != '' && $gar2plus[2] != '' && $gar4plus[4] != ''){
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 726.1;
		$alldismarg1L = 13;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		}elseif($gar1plus[1] == '' && $gar2plus[2] != '' && $gar4plus[4] != ''){
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 696.1;
		$alldismarg1L = 9;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		}elseif($gar1plus[1] == '' && $gar2plus[2] == '' && $gar4plus[4] != ''){
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 666.1;
		$alldismarg1L = 5;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		}elseif($gar1plus[1] != '' && $gar2plus[2] == '' && $gar4plus[4] != ''){
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 696.1;
		$alldismarg1L = 9;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		}elseif($gar1plus[1] == '' && $gar2plus[2] != '' && $gar4plus[4] == ''){
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-plus"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 666.1;
		$alldismarg1L = 5;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		}else{
		$text .= '
		<td class="tg-guz1 tg-c">C</td>
		<td class="tg-guz1 tg-baqh thin">D</td>
		<td class="tg-guz1 tg-m7">รถตู้</td>
		<td class="tg-guz1 tg-m5">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-cd">C,D</td>
		<td class="tg-guz1 tg-m3">รถตู้</td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique"></td>
		<td class="tg-guz1 tg-unique">รถตู้</td>';
		$colsum = 636.1;
		$alldismarg1L = 1;
		$pdf->SetMargins(PDF_MARGIN_LEFT-$alldismarg1L, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	}
	$text .= '</tr>';
	for($i = 0; $i<= 49; $i++){
		$text .= '<tr>
		<td class="tg-c tg-thin" style="background-color:'.$gar_color[$arr2[1]['C'][0][$i]].';">'.$arr[1]['C'][0][$i].''.$image[$arr3[1]['C'][0][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[1]['D'][0][$i]].';">'.$arr[1]['D'][0][$i].''.$image[$arr3[1]['D'][0][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[1]['M'][0][$i]].'">'.$arr[1]['M'][0][$i].''.$image[$arr3[1]['M'][0][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[1]['M'][1][$i]].'">'.$arr[1]['M'][1][$i].''.$image[$arr3[1]['M'][1][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[1]['M'][2][$i]].'">'.$arr[1]['M'][2][$i].''.$image[$arr3[1]['M'][2][$i]]. '</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[1]['M'][3][$i]].'">'.$arr[1]['M'][3][$i].''.$image[$arr3[1]['M'][3][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[1]['M'][4][$i]].'">'.$arr[1]['M'][4][$i].''.$image[$arr3[1]['M'][4][$i]].'</td>
		<td class="tg-p tg-thin"  style="background-color:'.$gar_color[$arr2[1]['P'][0][$i]].'">'.$arr[1]['P'][0][$i].''.$image[$arr3[1]['P'][0][$i]].'</td>';
		
		if($gar1plus[1] != ''){
			$text .= '<td class="tg-v1 tg-thin" style="background-color:'.$gar_color[$gar1cha_plus[1][$i]].'">'.$gar1plus[1][$i].''.$image[$gar1op_plus[1][$i]]. '</td>';
		}
		
		$text .= '
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[2]['M'][0][$i]].'">'.$arr[2]['M'][0][$i].''.$image[$arr3[2]['M'][0][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[2]['M'][1][$i]].'">'.$arr[2]['M'][1][$i].''.$image[$arr3[2]['M'][1][$i]].'</td>
		<td class="tg-baqh tg-thin tg-uniqueLastgar2"  style="background-color:'.$gar_color[$arr2[2]['M'][2][$i]].'">'.$arr[2]['M'][2][$i].''.$image[$arr3[2]['M'][2][$i]].'</td>';
		
		if($gar2plus[2] != ''){
			$text .= '<td class="tg-v1 tg-thin" style="background-color:'.$gar_color[$gar2cha_plus[2][$i]].'">'.$gar2plus[2][$i].''.$image[$gar2op_plus[2][$i]]. '</td>';
		}
		
		$text .= '
		<td class=" tg-thin tg-v1" style="background-color:'.$gar_color[$arr2[3][$i]].'">'.$arr[3][$i].''.$image[$arr3[3][$i]].'</td>
		
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[4]['C'][0][$i]].'">'.$arr[4]['C'][0][$i].''.$image[$arr3[4]['C'][0][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[4]['D'][0][$i]].'">'.$arr[4]['D'][0][$i].''.$image[$arr3[4]['D'][0][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[4]['D'][1][$i]].'">'.$arr[4]['D'][1][$i].''.$image[$arr3[4]['D'][1][$i]].'</td>
		<td class="tg-baqh tg-thin" style="background-color:'.$gar_color[$arr2[4]['M'][0][$i]].'">'.$arr[4]['M'][0][$i].''.$image[$arr3[4]['M'][0][$i]].'</td>
		<td class="tg-baqh tg-thin tg-uniqueLastgar2"  style="background-color:'.$gar_color[$arr2[4]['M'][1][$i]].'">'.$arr[4]['M'][1][$i].''.$image[$arr3[4]['M'][1][$i]].'</td>';
		
		if($gar4plus[4] != ''){
			$text .= '<td class="tg-v1 tg-thin" style="background-color:'.$gar_color[$gar4cha_plus[4][$i]].'">'.$gar4plus[4][$i].''.$image[$gar4op_plus[4][$i]]. '</td>';
		}
		
		$text .='
		<td class=" tg-thin tg-uniqueLastgar3" style="background-color:'.$gar_color[$arr2[5][$i]].'">'.$arr[5][$i].''.$image[$arr3[5][$i]].'</td>
		
		<td class="tg-uniqueLast tg-thin" style="background-color:'.$gar_color[$arr2[6][$i]].'">'.$arr[6][$i].''.$image[$arr3[6][$i]].'</td>
		
		<td class="tg-baqh tg-thin tg-uniqueLastgar2"  style="background-color:'.$gar_color[$arr2[7]['M'][0][$i]].'">'.$arr[7]['M'][0][$i].''.$image[$arr3[7]['M'][0][$i]].'</td>
		</tr>';
	}   
	
	$carwt = "c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)";
	$sqlnew = "SELECT COUNT(*) AS selectedcar,
	(SELECT COUNT(*) AS allcar
	FROM   Personal2.dbo.Car AS c INNER JOIN
	Personal2.dbo.CarWorkType AS cw ON c.CarWorkTypeSerial = cw.CarWorkTypeSerial
	WHERE (c.CarNo NOT LIKE N'%\_') AND (c.CarNo NOT LIKE N'z%') AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') AND ({$carwt})) AS totalcar,
	(SELECT COUNT(*) AS onselected
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.gar_id NOT IN (0)) AND {$carwt}) AS onselected,
	(SELECT COUNT(*) AS sumonsale
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_option = 2) AND ({$carwt}) AND (gc.garc_date = '{$sel_date}')) AS sumonsale,
	(SELECT COUNT(*) AS pureonsale
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.gar_id = 0) AND (gc.garc_option = 2) AND {$carwt} AND (gc.garc_date = '{$sel_date}')) AS pureonsale,
	(SELECT COUNT(*) AS puremaintain
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.gar_id = 0) AND (gc.garc_option = 1) AND (gc.garc_date = '{$sel_date}') AND ({$carwt})) AS puremaintain,
	(SELECT COUNT(*) AS fixedGarmaintain
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.garc_option = 1) AND ({$carwt}) AND (gc.gar_id = 7)) AS sumfixedGarmaintain,
	(SELECT COUNT(*) AS ongarage
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.gar_id NOT IN (0, 7)) AND (gc.garc_option = 1) AND ({$carwt})) AS maintainongarage,
	(SELECT COUNT(garc_id) AS Expr1
	FROM   parn.dbo.garage_car AS gc
	WHERE (gar_id = 1) AND (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}')
	GROUP BY gar_id) AS garone,
	(SELECT COUNT(garc_id) AS Expr1
	FROM   parn.dbo.garage_car AS gc
	WHERE (gar_id = 2) AND (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}')
	GROUP BY gar_id) AS gar2,
	(SELECT COUNT(garc_id) AS Expr1
	FROM   parn.dbo.garage_car AS gc
	WHERE (gar_id = 3) AND (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}')
	GROUP BY gar_id) AS gar3,
	(SELECT COUNT(garc_id) AS Expr1
	FROM   parn.dbo.garage_car AS gc
	WHERE (gar_id = 4) AND (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}')
	GROUP BY gar_id) AS gar4,
	(SELECT COUNT(garc_id) AS Expr1
	FROM   parn.dbo.garage_car AS gc
	WHERE (gar_id = 5) AND (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}')
	GROUP BY gar_id) AS gar5,
	(SELECT COUNT(garc_id) AS Expr1
	FROM   parn.dbo.garage_car AS gc
	WHERE (gar_id = 6) AND (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}')
	GROUP BY gar_id) AS gar6,
	(SELECT COUNT(garc_id) AS Expr1
	FROM   parn.dbo.garage_car AS gc
	WHERE (gar_id = 7) AND (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}')
	GROUP BY gar_id) AS gar7,
	(SELECT COUNT(*) AS Expr1
	FROM  parn.dbo.garage_car AS gc
	WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}') AND (gar_id = 8)) AS gar8,
	(SELECT COUNT(*) AS fixedGarmaintain
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE(gc.garc_delete,0) = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.garc_option = 1) AND {$carwt} AND (gc.gar_id = 7)) AS sumfixedGarmaintain,
	(SELECT COUNT(df_carserial) AS sumnotselected
	FROM   parn.dbo.default_gar
	WHERE (NOT (df_carserial IN
	(SELECT TOP 100 PERCENT garc_carserial
	FROM   parn.dbo.garage_car
	WHERE (garc_date = '{$sel_date}') AND (COALESCE (garc_delete, 0) = 0)
	ORDER BY garc_carserial))) AND (COALESCE (df_delete, 0) = 0)) as sumnotselected
	FROM  parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}') AND (gc.gar_id <> 0) AND {$carwt}";
	
	// armpre($sqlnew);
	// $sqlsumseled = "";
	
	$rssum = $connect->getrow($sqlnew);
	// $rssumseled = $connect->getrow($sqlsumseled);
	$selectedcar = $rssum['selectedcar'];
	$totalcar = $rssum['totalcar'];
	$sumonsale = $rssum['sumonsale'];
	$pureonsale = $rssum['pureonsale'];
	$maintainongarage = $rssum['maintainongarage'];
	$puremaintain = $rssum['puremaintain'];
	$sumfixedGarmaintain = $rssum['sumfixedGarmaintain'];
	$netmaintain = $puremaintain + $sumfixedGarmaintain+$maintainongarage;
	$garone = $rssum['garone'];
	$gar2 = $rssum['gar2'];
	$gar3 = $rssum['gar3'];
	$gar4 = $rssum['gar4'];
	$gar5 = $rssum['gar5'];
	$gar6 = $rssum['gar6'];
	$gar7 = $rssum['gar7'];
	$gar8 = $rssum['gar8'];
	$sumnotselected = $rssum['sumnotselected'];
	$sql_gar = "SELECT gar.gar_name, gar.gar_id, COUNT(gc.garc_id) AS total_eType
	FROM   parn.dbo.garage AS gar LEFT OUTER JOIN
	parn.dbo.garage_car AS gc ON gar.gar_id = gc.gar_id AND gc.garc_delete = 0 AND gc.garc_date = '{$sel_date}'
	GROUP BY gar.gar_name, gar.gar_id
	ORDER BY gar.gar_id";
	$rs = $connect->execute($sql_gar);
	$arr = array();
	$arrgar = array();
	/* =============================== */
	// if($sumnotselected == null){$sumnotselected = 0;}
	// if($netmaintain == null){$netmaintain = 0;}
	// if($sumonsale == null){$sumonsale = 0;}
	if($garone == null){$garone = 0;}
	if($gar2 == null){$gar2 = 0;}
	if($gar3 == null){$gar3 = 0;}
	if($gar4 == null){$gar4 = 0;}
	if($gar5 == null){$gar5 = 0;}
	if($gar6 == null){$gar6 = 0;}
	if($gar7 == null){$gar7 = 0;}
	foreach ($rs as $data) {
		$gar_id = $data['gar_id'];
		$gar_name = $data['gar_name'];
		$count = $data['total_eType'];
		$sumOption = $data['sumOption'];
		$arrgar[] = armutf8($data['gar_name']);
		$key = $key+1;
		if ($gar_id == 1) {
			$arr[$gar_id] = $garone;
			}elseif($gar_id == 2){
			$arr[$gar_id] = $gar2;
			}elseif($gar_id == 3){
			$arr[$gar_id] = $gar3;
			}elseif($gar_id == 4){
			$arr[$gar_id] = $gar4;
			}elseif($gar_id == 5){
			$arr[$gar_id] = $gar5;
			}elseif($gar_id == 6){
			$arr[$gar_id] = $gar6;
			}elseif($gar_id == 7){
			$arr[$gar_id] = $gar7;
			}elseif($gar_id == 7){
			$arr[$gar_id] = $gar7;
			}elseif($gar_id == 8){
			$arr[$gar_id] = $gar8;
			}elseif($gar_id == 9){
			$arr[$gar_id] = $netmaintain;
			}elseif($gar_id == 10){
			$arr[$gar_id] = $sumonsale;
			}elseif($gar_id == 11){
			$arr[$gar_id] = $sumnotselected;
		}
		$coco += count($gar_id);
		// var_dump($arrgar[1]);
	}
	// var_dump($totalcar);
	$text .= '
	<tr><td width="'.$colsum.'"></td></tr>
	<tr>
	<td class="tg-tvby" width="'.$colsum.'">';
	for($j = 1; $j <= $coco; $j++){
		$text .= '<span style="color:black; font-size:13px; background-color:'.$gar_color[$j].'" class="label" align="center">'.$arrgar[$j-1].': '.$arr[$j].' คัน'.$image2[$j].'</span> '; 
	}
	$text .= '<span style="color:black; font-size:13px; background-color:'.$gar_color[12].'" class="label" align="center">ทั้งหมด: '.$totalcar.' คัน </span>';
	$text .= '</td>
	</tr>';
	$sqlnotidentify = "SELECT df_id, gar_id, ct_id, df_carserial, df_date, car_no, df_dateupdate, df_delete, user_edit, CarWorkTypeSerial
	FROM   parn.dbo.default_gar AS df
	WHERE (gar_id IS NULL)
	ORDER BY df_date";
	
	$sqlnotselected = "SELECT df_carserial, car_no
	FROM   parn.dbo.default_gar
	GROUP BY df_carserial, car_no, df_delete
	HAVING (NOT (df_carserial IN
	(SELECT TOP 100 PERCENT garc_carserial
	FROM  parn.dbo.garage_car
	WHERE (garc_date = '{$sel_date}') AND (COALESCE (garc_delete, 0) = 0)
	ORDER BY garc_carserial))) AND (COALESCE(df_delete,0) = 0)";
	$rsundefine = $connect->execute($sqlnotidentify);
	$rcnotselected = $connect->execute($sqlnotselected);
	$co = count($rcnotselected);
	$po = count($rsundefine);
	if($rsundefine->recordCount()>0){
		$text .= '<tr>
		<td class="tg-tvby" width="'.$colsum.'"><span style="color:black; font-size:13px; background-color:'.$gar_color[12].'" class="label" align="left">เบอร์รถที่ไม่ถูกระบุอู่ประจำ: </span>';
		foreach($rsundefine as $key=>$val){
			$testorderno = $val['car_no'];
			$textpo .= $testorderno.", ";
		}
		$text .= '<span style="color:black; font-size:13px; background-color:'.$gar_color[12].'" class="label" align="left">'.substr(trim($textpo),0,-1).' </span>';
		$text .= '</td>
		</tr>';
	}
	if($rcnotselected->recordCount()>0){
		$text .= '<tr>
		<td class="tg-tvby" width="'.$colsum.'"><span style="color:black; font-size:13px; background-color:'.$gar_color[12].'" class="label" align="left">เบอร์รถที่ไม่ถูกเลือก: </span>';
		foreach($rcnotselected as $key=>$val){
			$testorderno2 = $val['car_no'];
			$textpo2 .= $testorderno2.", ";
		}
		$cou = count($textpo2);
		$text .= '<span style="color:black; font-size:13px; background-color:'.$gar_color[12].'" class="label" align="left">'.substr(trim($textpo2),0,-1).' </span>';
		$text .= '</td>
		</tr>';
	}
	$text .= '</table>';
	$pdf->writeHTMLCell(0, 0, '', 10, $text, 0, 1, 0, true, '', true);
	// $pdf->writeHTML($text, true, false, false, false, '');
	$pdf->writeHTML($text2, true, false, false, false, '');
	$pdf->Output('OriginalGarage '.$sel_date.'.pdf', 'I');
	$pdf->lastPage();
?>