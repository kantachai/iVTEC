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
			global $sel_date; $carwt;
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
	$pdf->SetMargins(PDF_MARGIN_LEFT -7, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT -2);
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
	$text .= '<style type="text/css">
	.tg td{sans-serif;font-size:14px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border: 2px solid #000;}
	.tg th{sans-serif;font-size:14px;font-weight:normal;overflow:hidden;word-break:normal;}
	.tg .tg-5wpd{font-weight:bold;font-size:18px; sans-serif text-align:center;vertical-align:top}
	.tg .tg-guz1{font-weight:bold;font-size:14px; text-align:center;vertical-align:top}
	
	.tg .tg-gar1{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:36.5%;}
	.tg .tg-gar1Tc{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar1Td{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar1Tm{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:27.5%;}
	.tg .tg-gar1carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border:0.1px solid #000;}
	.tg .tg-gar1car{text-align:center;vertical-align:top; width:4.5%; border:0.1px solid #000;}
	.tg .tg-gar1carLast{text-align:center;vertical-align:top; width:5%; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar2{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:9%;}
	.tg .tg-gar2Tm{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:9%;}
	.tg .tg-gar2carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border:0.1px solid #000;}
	.tg .tg-gar2carLast{text-align:center;vertical-align:top; width:4.5%; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar3{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:9%;}
	.tg .tg-gar3Tsum{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:9%;}
	.tg .tg-gar3carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border:0.1px solid #000;}
	.tg .tg-gar3carLast{text-align:center;vertical-align:top; width:4.5%; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar4{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:18%;}
	.tg .tg-gar4Tcd{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:9%;}
	.tg .tg-gar4Tm{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:9%;}
	.tg .tg-gar4carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border:0.1px solid #000;}
	.tg .tg-gar4car{text-align:center;vertical-align:top; width:4.5%; border:0.1px solid #000;}
	.tg .tg-gar4carLast{text-align:center;vertical-align:top; width:4.5%; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar5{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar5Tc{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar5carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar6{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar6Tc{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar6carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar7{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar7Tc{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar7carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar8{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar8Tsum{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar8Tc{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:4.5%;}
	.tg .tg-gar8carFirst{text-align:center;vertical-align:top; width:4.5%; border-left:2px solid #000; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-gar9{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:12%;}
	.tg .tg-gar9Tsum{font-weight:bold;font-size:14px; text-align:center;vertical-align:top; width:12%;}
	.tg .tg-gar9carFirst{text-align:center;vertical-align:top; width:6%; border-left:2px solid #000; border:0.1px solid #000;}
	.tg .tg-gar9carLast{text-align:center;vertical-align:top; width:6%; border-right:2px solid #000; border:0.1px solid #000;}
	
	.tg .tg-tvby{font-size:14px; sans-serif vertical-align:top}
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
	<tr>
	<td class="tg-guz1 tg-gar1">อู่1</td>
	<td class="tg-guz1 tg-gar2">อู่2</td>
	<td class="tg-guz1 tg-gar3">อู่3</td>
	<td class="tg-guz1 tg-gar4">อู่4</td> 
	<td class="tg-guz1 tg-gar5">อู่5</td>
	<td class="tg-guz1 tg-gar6">อู่6</td>
	<td class="tg-guz1 tg-gar7">SR</td>
	<td class="tg-guz1 tg-gar8">ค้าง ตจว.</td>
	<td class="tg-guz1 tg-gar9">ซ่อม, ขาย</td>
	</tr>
	<tr>
	<td class="tg-guz1 tg-gar1Tc">C</td>
	<td class="tg-guz1 tg-gar1Td thin">D</td>
	<td class="tg-guz1 tg-gar1Tm">รถตู้</td>
	<td class="tg-guz1 tg-gar2Tm">รถตู้</td>
	<td class="tg-guz1 tg-gar3Tsum"></td>
	<td class="tg-guz1 tg-gar4Tcd">C,D</td>
	<td class="tg-guz1 tg-gar4Tm">รถตู้</td>
	<td class="tg-guz1 tg-gar5Tc"></td>
	<td class="tg-guz1 tg-gar6Tc"></td>
	<td class="tg-guz1 tg-gar7Tc">รถตู้</td>
	<td class="tg-guz1 tg-gar8Tsum"></td>
	<td class="tg-guz1 tg-gar9Tsum"></td>
	</tr>';
	
	$sql = "SELECT gc.gar_id, gc.garc_option, gc.garc_carserial, c.CarNo AS car_no, SUBSTRING(c.CarNo, 1, 1) AS Initial
	FROM  parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (gc.garc_date = '{$sel_date}') AND (COALESCE (gc.garc_delete, 0) = 0)
	ORDER BY gc.gar_id,c.CarNo"; //SUBSTRING ( expression ,start , length )
	
	$rs = $connect->execute($sql);
	// armpre($sql);
	// rs2html($rs);
	$i = 0;
	$Initial='';$gar_id='';
	$arr = array();
	$arr2 = array();
	$arr3 = array();
	$arr4 = array();
	$arr5 = array();
	$arr6 = array();
	foreach($rs as $key => $data){
		// $i = $key;
		// armpre($sumnight[8]);
		//
		if($gar_id!=$data['gar_id'] ){
			$gar_id=$data['gar_id'];
			$i=0;
			// armpre('sssss');
		}
		if($data['Initial'] == 'M' || $data['Initial'] == 'D' || $data['Initial'] == 'C' || $data['Initial'] == 'P' || $data['Initial'] == 'V'){
			if($Initial!=$data['Initial'] ){
				$Initial=$data['Initial'];
				$i=0;
				// armpre('sssss');
			}
			$cout = count($arr[$data['gar_id']][$data['Initial']][$i]);
			if($cout == 49){
				$arr[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
				
				$arr2[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				
				$arr3[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
				if($data['gar_id'] == 1 && $data['Initial'] == 'P' || $data['Initial'] == 'V'){
					$arr25[$data['gar_id']][] = $data['car_no'];
					$arr26[$data['gar_id']][] = $data['garc_option'];
					$arr27[$data['gar_id']][] = $data['gar_id'];
				}
				
				if($data['gar_id'] == 3 || $data['Initial'] != 'C' || $data['Initial'] != 'D' || $data['Initial'] != 'P' || $data['Initial'] != 'V' || $data['Initial'] == 'M'){
					$arr7[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
					$arr11[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
					$arr12[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				}
				if($data['gar_id'] == 3 && $data['Initial'] != 'M'){
					$arr8[$data['gar_id']][] = $data['car_no'];
					$arr9[$data['gar_id']][] = $data['garc_option'];
					$arr10[$data['gar_id']][] = $data['gar_id'];
				}
				
				if($data['gar_id'] == 4 || $data['Initial'] != 'C' || $data['Initial'] != 'D' || $data['Initial'] != 'P' || $data['Initial'] != 'V' || $data['Initial'] == 'M'){
					$arr19[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
					$arr20[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
					$arr21[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				}
				if($data['gar_id'] == 4 && $data['Initial'] != 'M'){
					$arr22[$data['gar_id']][$i][] = $data['car_no'];
					$arr23[$data['gar_id']][$i][] = $data['garc_option'];
					$arr24[$data['gar_id']][$i][] = $data['gar_id'];
				}
				
				if($data['gar_id'] == 0 && $data['Initial'] != 'D'){
					$arr13[$data['gar_id']][] = $data['car_no'];
					$arr14[$data['gar_id']][] = $data['garc_option'];
					$arr15[$data['gar_id']][] = $data['gar_id'];
				}
				if($data['gar_id'] == 0 || $data['Initial'] != 'C' || $data['Initial'] != 'M' || $data['Initial'] != 'P' || $data['Initial'] != 'V'){
					$arr16[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
					$arr17[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
					$arr18[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				}
				$i++;
				}else{
				$arr[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
				
				$arr2[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				
				$arr3[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
				if($data['gar_id'] == 1 && $data['Initial'] == 'P' || $data['Initial'] == 'V'){
					$arr25[$data['gar_id']][] = $data['car_no'];
					$arr26[$data['gar_id']][] = $data['garc_option'];
					$arr27[$data['gar_id']][] = $data['gar_id'];
				}
				if($data['gar_id'] == 3 || $data['Initial'] != 'C' || $data['Initial'] != 'D' || $data['Initial'] != 'P' || $data['Initial'] != 'V' || $data['Initial'] == 'M'){
					$arr7[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
					$arr11[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
					$arr12[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				}
				if($data['gar_id'] == 3 && $data['Initial'] != 'M'){
					$arr8[$data['gar_id']][] = $data['car_no'];	
					$arr9[$data['gar_id']][] = $data['garc_option'];
					$arr10[$data['gar_id']][] = $data['gar_id'];
				}
				
				
				if($data['gar_id'] == 4 || $data['Initial'] != 'C' || $data['Initial'] != 'D' || $data['Initial'] != 'P' || $data['Initial'] != 'V' || $data['Initial'] == 'M'){
					$arr19[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
					$arr20[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
					$arr21[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				}
				if($data['gar_id'] == 4 && $data['Initial'] != 'M'){
					$arr22[$data['gar_id']][$i][] = $data['car_no'];
					$arr23[$data['gar_id']][$i][] = $data['garc_option'];
					$arr24[$data['gar_id']][$i][] = $data['gar_id'];
				}
				
				if($data['gar_id'] == 0 && $data['Initial'] != 'D'){
					$arr13[$data['gar_id']][] = $data['car_no'];
					$arr14[$data['gar_id']][] = $data['garc_option'];
					$arr15[$data['gar_id']][] = $data['gar_id'];
				}
				if($data['gar_id'] == 0 || $data['Initial'] != 'C' || $data['Initial'] != 'M' || $data['Initial'] != 'P' || $data['Initial'] != 'V'){
					$arr16[$data['gar_id']][$data['Initial']][$i][] = $data['car_no'];
					$arr17[$data['gar_id']][$data['Initial']][$i][] = $data['garc_option'];
					$arr18[$data['gar_id']][$data['Initial']][$i][] = $data['gar_id'];
				}
				$arr4[$data['gar_id']][] = $data['car_no'];
				$arr5[$data['gar_id']][] = $data['garc_option'];
				$arr6[$data['gar_id']][] = $data['gar_id'];
				
			}
		}
	}
	$sum1 = count($arr2[7]);
	// armpre($arr[7]);
	for($i = 0; $i<= 49; $i++){
		$text .= '<tr>
		<td class="tg-gar1carFirst" style="background-color:'.$gar_color[$arr2[1]['C'][0][$i]].';">'.$arr[1]['C'][0][$i].''.$image[$arr3[1]['C'][0][$i]].'</td>
		<td class="tg-gar1car" style="background-color:'.$gar_color[$arr2[1]['D'][0][$i]].';">'.$arr[1]['D'][0][$i].''.$image[$arr3[1]['D'][0][$i]].'</td>
		<td class="tg-gar1car" style="background-color:'.$gar_color[$arr2[1]['M'][0][$i]].'">'.$arr[1]['M'][0][$i].''.$image[$arr3[1]['M'][0][$i]].'</td>
		<td class="tg-gar1car" style="background-color:'.$gar_color[$arr2[1]['M'][1][$i]].'">'.$arr[1]['M'][1][$i].''.$image[$arr3[1]['M'][1][$i]].'</td>
		<td class="tg-gar1car" style="background-color:'.$gar_color[$arr2[1]['M'][2][$i]].'">'.$arr[1]['M'][2][$i].''.$image[$arr3[1]['M'][2][$i]]. '</td>
		<td class="tg-gar1car" style="background-color:'.$gar_color[$arr2[1]['M'][3][$i]].'">'.$arr[1]['M'][3][$i].''.$image[$arr3[1]['M'][3][$i]].'</td>
		<td class="tg-gar1car" style="background-color:'.$gar_color[$arr2[1]['M'][4][$i]].'">'.$arr[1]['M'][4][$i].''.$image[$arr3[1]['M'][4][$i]].'</td>
		<td class="tg-gar1carLast"  style="background-color:'.$gar_color[$arr27[1][$i]].'">'.$arr25[1][$i].''.$image[$arr26[1][$i]]. '</td>
		';
		/* if($arr[1]['V'][0][$i] != ''){
			$text .= '<td class="tg-gar1carLast"  style="background-color:'.$gar_color[$arr2[1]['V'][0][$i]].'">'.$arr[1]['V'][0][$i].''.$image[$arr3[1]['V'][0][$i]]. '</td>';
		} */
		
		$text .= '<td class="tg-gar2carFirst" style="background-color:'.$gar_color[$arr2[2]['M'][0][$i]].'">'.$arr[2]['M'][0][$i].''.$image[$arr3[2]['M'][0][$i]].'</td>
		<td class="tg-gar2carLast" style="background-color:'.$gar_color[$arr2[2]['M'][1][$i]].'">'.$arr[2]['M'][1][$i].''.$image[$arr3[2]['M'][1][$i]].'</td>
		
		<td class=" tg-gar3carFirst" style="background-color:'.$gar_color[$arr10[3][$i]].'">'.$arr8[3][$i].''.$image[$arr9[3][$i]].'</td>
		<td class=" tg-gar3carLast" style="background-color:'.$gar_color[$arr12[3]['M'][0][$i]].'">'.$arr7[3]['M'][0][$i].''.$image[$arr11[3]['M'][0][$i]].'</td>
		
		<td class="tg-gar4carFirst" style="background-color:'.$gar_color[$arr24[4][0][$i]].'">'.$arr22[4][0][$i].''.$image[$arr23[4][0][$i]].'</td>
		<td class="tg-gar4carLast" style="background-color:'.$gar_color[$arr24[4][1][$i]].'">'.$arr22[4][1][$i].''.$image[$arr23[4][1][$i]].'</td>
		<td class="tg-gar4carFirst" style="background-color:'.$gar_color[$arr21[4]['M'][0][$i]].'">'.$arr19[4]['M'][0][$i].''.$image[$arr20[4]['M'][0][$i]].'</td>
		<td class="tg-gar4carLast" style="background-color:'.$gar_color[$arr21[4]['M'][1][$i]].'">'.$arr19[4]['M'][1][$i].''.$image[$arr20[4]['M'][1][$i]].'</td>
		
		<td class="tg-gar5carFirst" style="background-color:'.$gar_color[$arr6[5][$i]].'">'.$arr4[5][$i].''.$image[$arr5[5][$i]].'</td>
		
		<td class="tg-gar6carFirst" style="background-color:'.$gar_color[$arr6[6][$i]].'">'.$arr4[6][$i].''.$image[$arr5[6][$i]].'</td>
		
		<td class="tg-gar7carFirst"  style="background-color:'.$gar_color[$arr2[7]['M'][0][$i]].'">'.$arr[7]['M'][0][$i].''.$image[$arr3[7]['M'][0][$i]].'</td>
		
		<td class="tg-gar8carFirst"  style="background-color:'.$gar_color[$arr6[8][$i]].'">'.$arr4[8][$i].''.$image[$arr5[8][$i]].'</td>
		
		<td class="tg-gar9carFirst">'.$arr13[0][$i].''.$image[$arr14[0][$i]].'</td>
		<td class="tg-gar9carLast">'.$arr16[0]['D'][0][$i].''.$image[$arr17[0]['D'][0][$i]].'</td>
		</tr>';
	}
	$carwt = "c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)";
	$sqlnew = "SELECT COUNT(*) AS selectedcar,
	(SELECT COUNT(*) AS allcar
	FROM   Personal2.dbo.Car AS c INNER JOIN
	Personal2.dbo.CarWorkType AS cw ON c.CarWorkTypeSerial = cw.CarWorkTypeSerial
	WHERE (c.CarNo NOT LIKE N'%\_') AND (c.CarNo NOT LIKE N'z%') AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') AND {$carwt})
	AS totalcar, 
	(SELECT COUNT(*) AS onselected
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.gar_id NOT IN (0)) AND {$carwt}) AS onselected,
	(SELECT COUNT(*) AS ongarage
	FROM  parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.gar_id NOT IN (0, 7)) AND (gc.garc_option = 1) AND {$carwt}) AS maintainongarage, 
	(SELECT COUNT(*) AS sumsrirachar
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.gar_id = 7) AND {$carwt} AND (gc.garc_date = '{$sel_date}')) AS totalsri,
	(SELECT COUNT(*) AS puremaintain
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.gar_id = 0) AND (gc.garc_option = 1) AND (gc.garc_date = '{$sel_date}') AND {$carwt}) AS puremaintain,
	(SELECT COUNT(*) AS summaintain
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.gar_id <> 0) AND (gc.garc_option = 1) AND (gc.garc_date = '{$sel_date}') AND {$carwt}) AS maintainonGar,
	(SELECT COUNT(*) AS fixedGarmaintain
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.garc_option = 1) AND {$carwt} AND (gc.gar_id = 7)) AS sumfixedGarmaintain,
	(SELECT COUNT(*) AS sumonsale
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car as c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_option = 2) AND {$carwt} AND (gc.garc_date = '{$sel_date}')) AS sumonsale,
	(SELECT COUNT(*) AS pureonsale
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.gar_id = 0) AND (gc.garc_option = 2) AND {$carwt} AND (gc.garc_date = '{$sel_date}')) AS pureonsale,
	(SELECT COUNT(*) AS Expr1
	FROM   parn.dbo.default_gar AS dg LEFT OUTER JOIN
	(SELECT TOP 100 PERCENT gc.gar_id, gc.garc_option, gc.garc_carserial, Car_2.CarNo
	FROM   parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS Car_2 ON gc.garc_carserial = Car_2.CarSerial
	WHERE (gc.garc_date = '{$sel_date}') AND (COALESCE (gc.garc_delete, 0) = 0)
	GROUP BY gc.garc_carserial, gc.gar_id, gc.garc_option, Car_2.CarNo
	ORDER BY gc.gar_id, Car_2.CarNo) AS tmp ON dg.df_carserial = tmp.garc_carserial
	WHERE (dg.gar_id IS NOT NULL)) AS mergedef
	FROM  parn.dbo.garage_car AS gc INNER JOIN
	Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
	WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}') AND (gc.gar_id <> 0) AND {$carwt}";
	
	$sqlsumnotseled = "SELECT COUNT(df_carserial) AS sumnotselected
	FROM   parn.dbo.default_gar
	WHERE (NOT (df_carserial IN
	(SELECT TOP 100 PERCENT garc_carserial
	FROM  parn.dbo.garage_car
	WHERE (garc_date = '{$sel_date}') AND (COALESCE (garc_delete, 0) = 0)
	ORDER BY garc_carserial))) AND (COALESCE (df_delete, 0) = 0)";
	
	$rssum = $connect->getrow($sqlnew);
	$rssumnotseled = $connect->getrow($sqlsumnotseled);
	$selectedcar = $rssum['selectedcar'];
	$totalcar = $rssum['totalcar'];
	$mergedef = $rssum['mergedef'];
	$onselected = $rssum['onselected'];
	$sumnotselected = $rssumnotseled['sumnotselected'];
	$totalsri = $rssum['totalsri'];
	$maintainongarage = $rssum['maintainongarage'];
	$puremaintain = $rssum['puremaintain'];
	$sumfixedGarmaintain = $rssum['sumfixedGarmaintain'];
	$sumonsale = $rssum['sumonsale'];
	$pureonsale = $rssum['pureonsale'];
	$netmaintain = $puremaintain + $sumfixedGarmaintain+$maintainongarage;
	$netselected = $onselected+$puremaintain+$pureonsale;
	// armpre($netselected);
	$noselected = $totalcar-$netselected;
	
	$sql_gar = "SELECT gar.gar_name, gar.gar_id, COUNT(gc.garc_id) AS total_eType
	FROM   parn.dbo.garage AS gar LEFT OUTER JOIN
	parn.dbo.garage_car AS gc ON gar.gar_id = gc.gar_id AND gc.garc_delete = 0 AND gc.garc_date = '{$sel_date}'
	GROUP BY gar.gar_name, gar.gar_id
	ORDER BY gar.gar_id";
	$rs = $connect->execute($sql_gar);
	// $totalcar = $totalcar - $selectedcar;
	// armpre($sqlnew);
	$arr = array();
	$arrgar = array();
	/* =============================================== */
	
	if($sumnotselected == null){$sumnotselected = 0;}
	if($totalsri == null){$totalsri = 0;}
	if($netmaintain == null){$netmaintain = 0;}
	if($sumonsale == null){$sumonsale = 0;}
	foreach ($rs as $data) {
		$gar_id = $data['gar_id'];
		$gar_name = $data['gar_name'];
		$count = $data['total_eType'];
		$sumOption = $data['sumOption'];
		$arrgar[] = armutf8($data['gar_name']);
		$key = $key+1;
		if ($gar_id == 7) {
			$arr[$gar_id] = $totalsri;
			}elseif($gar_id == 9){
			$arr[$gar_id] = $netmaintain;
			}elseif($gar_id == 10){
			$arr[$gar_id] = $sumonsale;
			}elseif($gar_id == 11){
			$arr[$gar_id] = $sumnotselected;
			}else{
			$arr[$gar_id] = $count;
		}
		$coco += count($gar_id);
		// var_dump($arrgar[1]);
	}
	// armpre($arrgar);
	// $arrgar[0] = "สวัสดีครับ";
	$text .= '
	<tr><td width="102.5%"></td></tr>
	<tr>
	<td class="tg-tvby" width="102.5%">';
	for($j = 1; $j <= $coco; $j++){
		$text .= '<span style="color:black; font-size:13px; background-color:'.$gar_color[$j].'" class="label" align="left">'.$arrgar[$j-1].': '.$arr[$j].' คัน '.$image2[$j].'</span> ';
	}
	$text .= '<span style="color:black; font-size:13px; background-color:'.$gar_color[12].'" class="label" align="center">ทั้งหมด: '.$totalcar.' คัน </span>';
	$text .= '</td>
	</tr>';
	$sqlnotselected = "SELECT df_carserial, car_no
	FROM   parn.dbo.default_gar
	GROUP BY df_carserial, car_no, df_delete
	HAVING (NOT (df_carserial IN
	(SELECT TOP 100 PERCENT garc_carserial
	FROM  parn.dbo.garage_car
	WHERE (garc_date = '{$sel_date}') AND (COALESCE (garc_delete, 0) = 0)
	ORDER BY garc_carserial))) AND (COALESCE(df_delete,0) = 0)";
	$rcnotselected = $connect->execute($sqlnotselected);
	$co = count($rcnotselected);
	if($rcnotselected->recordCount()>0){
		$text .= '<tr>
		<td class="tg-tvby" width="102.5%"><span style="color:black; font-size:13px; background-color:'.$gar_color[12].'" class="label" align="left">เบอร์รถที่ไม่ถูกเลือก: </span>';
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