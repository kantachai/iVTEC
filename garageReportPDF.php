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
	
	//==================================================================================================
	require_once('../tcpdf/tcpdf.php');
	class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// Logo
			global $sel_date;
			$date = parndatethai($sel_date);
			$this->SetFont('angsanaupc', '', 14);
			$this->Cell(110, 0, 'page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			$this->Cell(80, 0, date("d/m/Y H:i:s"), 0, false, 'R', 0, '', 0, false, 'T', 'M');
			// Set fontl
			//==============================left, top, width, heigh============================
			$this->Image('../../arm/armjs/img/montri-d.jpg', 15,  6, 25,19, 'JPG', '', 'M', false, 300, '', false, false, 0, false, false, false);
			// Title
			// $this->Cell(0, 1, 'ข้อมูลกุญแจที่จัดเก็บ', 0, false, 'C', 0, '', 0, false, 'M', 'M');
			// $this->Cell(0, 8, 'ตรวจเช็คกุญแจรถที่จอดประจำอู่  วัน '.$sel_date, 0, false, 'C', 0, '', 0, false, 'M', 'M');
			$this->MultiCell(0, 5, 'ข้อมูลกุญแจที่จัดเก็บ', 0, 'C', 0, 0,10, 10, true);
			
			$this->SetFont('angsanaupc', 'B', 13);
			$this->MultiCell(0, 5,'ตรวจเช็คกุญแจรถประจำอู่วันที่ '.$date, 0, 'C', 0, 0,10, 18, true);
			
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
	// $pdf = new TCPDF('L', 'mm', array(210,97), true, 'UTF-8', false);
	// create new PDF document
	// $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'mm', array(210,330), true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nicola Asuni');
	$pdf->SetTitle('ตรวจเช็คกุญแจรถประจำอู่');
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
	$pdf->SetMargins(PDF_MARGIN_LEFT-7, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT-7);
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
	// set font
	
	// add a page
	$pdf->AddPage('P', 'F4');
	// $date_pick = $_GET['datepick'];
	$underline = '______________________________________________________________________________________________________';
	$text = '';
	
	$tmp_r = '';
	$gar_color = array(1 => '#FFFF99','#FF9966','#3333FF','#FF9CD6','#73D549','#800080','#6a6a6a','#00a2ff', '"background-image": "-webkit-linear-gradient(-248deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "-moz-linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-position": "50% 50%", "-webkit-background-origin": "padding-box", "background-origin": "padding-box", "-webkit-background-clip": "border-box", "background-clip": "border-box", "-webkit-background-size": "auto auto", "background-size": "auto auto"',  '"background-image": "-webkit-linear-gradient(-248deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140, 198, 63, 1) 46%, rgba(140, 198, 63, 1) 48%, rgba(140, 198, 63, 1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "-moz-linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140, 198, 63, 1) 46%, rgba(140, 198, 63, 1) 48%, rgba(140, 198, 63, 1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140, 198, 63, 1) 46%, rgba(140, 198, 63, 1) 48%, rgba(140, 198, 63, 1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-position": "50% 50%", "-webkit-background-origin": "padding-box", "background-origin": "padding-box", "-webkit-background-clip": "border-box", "background-clip": "border-box", "-webkit-background-size": "auto auto", "background-size": "auto auto"', '#ff0023');
	
	//WHERE gar_id IN (1,2,3)
	$sql = 'SELECT gar_id,gar_name
	FROM parn.dbo.garage ';
	$rs = $connect->execute($sql);
	// rs2html($rs);
	foreach($rs as $row){
		$gar_id = $row['gar_id'];
		if ($gar_id == 11) {
			$sql = "SELECT c.CarNo AS carn_all, c.CarSerial AS cars_all, ct.ct_name, ct.ct_id AS carty
			FROM  parn.dbo.carType AS ct INNER JOIN
			Personal2.dbo.Car AS c ON LEFT(ct.ct_char, 1) = LEFT(c.CarNo, 1) FULL OUTER JOIN
			parn.dbo.garage_car AS gc ON c.CarSerial = gc.garc_carserial AND (gc.garc_date = '{$sel_date}') AND c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)
			WHERE COALESCE(gc.garc_carserial,0) = 0
			ORDER BY ct.ct_id,c.CarSerial";
			
			$sql_cause = "SELECT DISTINCT uc.cause_uncheck AS uc_info, uc.garc_date AS uc_date
			FROM  parn.dbo.uncheck_cause AS uc INNER JOIN
			parn.dbo.garage_car AS gc ON uc.garc_date = gc.garc_date
			WHERE (gc.garc_date = '{$sel_date}')";
			} else {
			$sql = "SELECT gc.garc_id, gc.gar_id AS garID, gc.garc_carserial AS cars_all, gc.ct_id AS carty, gc.garc_userAdd, gc.garc_dateAdd, gc.garc_delete, c.CarNo AS carn_all, ct.ct_name, ga.gar_name AS ga_name, gc.garc_option AS gar_option
			FROM   parn.dbo.garage_car AS gc LEFT OUTER JOIN
			parn.dbo.carType AS ct ON gc.ct_id = ct.ct_id LEFT OUTER JOIN
			Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial LEFT OUTER JOIN
			parn.dbo.garage AS ga ON gc.gar_id = ga.gar_id
			WHERE (gc.garc_delete = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.gar_id = '{$gar_id}')
			ORDER BY c.CarNo";
		}
		$recordSet  = $connect->execute($sql);
		// rs2html($recordSet); 
		// echo $sql;
		// rs2html($recordSet);
		$k = ($recordSet->RecordCount()-1); // $k เก็นจำนวน record ทั้งหมดแต่เรื่มจากหนึ่ง ต้อง -1 (เพื่อเปรียบเทียบกับค่า $key ได้)
		// rs2html($recordSet);
		$tmp_typen = array();
		$td_carty= array();
		$td_carname = array();
		// $td_garname = array();
		// $carTy = array("","รถตู้", "รถบัสเล็ก", "รถบัสใหญ่", "รถส่วนบุคคล", "รถบัส 2 ชั้น");
		
		foreach($recordSet as $key => $data){ //$key = 800-1 -> 799 หรือตัวเลขอื่นแต่ต้อง ลบด้วย1  เพราะเริ่มจาก 0
			// echo '<table width="100%" >';
			$gar_option = $data['gar_option'];
			$cars_all = $data['cars_all'];
			if($key==0){
				$ctype = $data['carty'];
				$i = 0;
				$tmp_typen[$ctype] = '
				<style>
				</style>';
				$tmp_typen[$ctype] .= '<table style="margin-left: -20px;" id="gar_inner">';
				$td_carty[]=$data['carty'];
				// $td_carname[]=$data['ct_name'];
				$td_carname[] = armutf8($data['ct_name']);
				// $td_garname[] = armutf8($data['ga_name']);
			}
			
			if($ctype != $data['carty']){
				$i_last = substr($i, -1, 1);
				// armpre($i_last);
				if($i_last>0 && $i_last<5){
					$tmp_typen[$ctype] .= '<td colspan="'.(5-$i_last).'"></td></tr>';
					
					}else{
					if($i_last>5&&$i_last<10){
						$tmp_typen[$ctype] .= '<td colspan="'.(10-$i_last).'"></td></tr>';
					}
				}
				
				
				$tmp_typen[$ctype] .= '</table>';
				$ctype = $data['carty'];	
				$td_carty[]=$data['carty'];		
				$td_carname[] = armutf8($data['ct_name']);
				$i = 0;
				$tmp_typen[$ctype] .= '<table style="margin-left: -20px;" id="gar_inner">';
				
				
			}
			if($i % 5 == 0){
				$tmp_typen[$ctype] .= '<tr>';
			}
			$tmp_typen[$ctype] .= '<td>'.$data['carn_all'].'</td>';	
			if($i % 5 == 4){
				$tmp_typen[$ctype] .= '</tr>';
			} 
			if($key == $k){
				$i_last = (substr($i, -1, 1)+1);
				// armpre($i_last);
				if($i_last>0 && $i_last<5){
					$tmp_typen[$ctype] .= '<td colspan="'.(5-$i_last).'"></td></tr>';
					}else{
					if($i_last>5&&$i_last<10){
						$tmp_typen[$ctype] .= '<td colspan="'.(10-$i_last).'"></td></tr>';
					}
				}
				$tmp_typen[$ctype] .= '</table>';
			}
			$i++;  
		}
		if($td_carname){
			$color = array(1=>'#000', '#000', '#fff', '#000', '#000', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff');
			$text .= '
			<style>
			table {
			text-align: center;
			border-collapse: collapse;
			font-size:16px;
			padding: 0px;
			}
			table#gar_inner, td{
			border: 1px solid #000;
			font-size:16px;
			padding: 0px;
			}
			table#gar'.$gar_id.' th{
			font-size:20px;font-weight:bold;background-color:'.$gar_color[$gar_id].';color: '.$color[$gar_id].';padding:6px;vertical-align: middle;text-align: center;border: 1px solid #000;
			}
			table#gar'.$gar_id.' td#test{
			font-size:18px;font-weight:boldpadding:5px;vertical-align: middle;text-align: center;border: 1px solid #000;
			}
			</style>
			<table id="gar'.$gar_id.'">
			<tr>
			<th style="width:100%;" colspan="'.count($td_carty).' ">'.armutf8($row['gar_name']).'</th>
			</tr>
			<tr>';
			foreach($td_carname as $row){
				$text .= '<td id="test" style="text-decoration: underline; border: 1px solid #000;">'.$row.'</td>';
			}
			$text .= '</tr><tr>';
			foreach($td_carty as $row){
				$text .='<td >'.$tmp_typen[$row].'</td>';
			}
			$text .= '</tr><tr><td align="left">';
			$sql_option1 = "SELECT gc.garc_id, gc.gar_id AS garID, gc.garc_carserial AS cars_all, gc.ct_id AS carty, gc.garc_userAdd, gc.garc_dateAdd, gc.garc_delete, c.CarNo AS carn_all, 
			ct.ct_name, ga.gar_name AS ga_name, gc.garc_option AS gar_option
			FROM  parn.dbo.garage_car AS gc LEFT OUTER JOIN
			parn.dbo.carType AS ct ON gc.ct_id = ct.ct_id LEFT OUTER JOIN
			Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial FULL OUTER JOIN
			parn.dbo.garage AS ga ON gc.gar_id = ga.gar_id
			WHERE (gc.garc_delete = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.garc_option = 1) AND (gc.gar_id = '{$gar_id}')
			ORDER BY gc.ct_id, gc.garc_id	";
			$rs_option1 = $connect->execute($sql_option1);
			$resultstr = array();
			foreach($rs_option1 as $data){
				$resultstr[] = $data['carn_all'];
			}
			$data = implode(", ", $resultstr);
			$text .= '<span style="color:red"><u><b>ค้างซ่อม</b></u>:<span style="color:blue"> ' . $data . '</span></span>';
			
			$sql_option2 = "SELECT gc.garc_id, gc.gar_id AS garID, gc.garc_carserial AS cars_all, gc.ct_id AS carty, gc.garc_userAdd, gc.garc_dateAdd, gc.garc_delete, c.CarNo AS carn_all, 
			ct.ct_name, ga.gar_name AS ga_name, gc.garc_option AS gar_option
			FROM  parn.dbo.garage_car AS gc LEFT OUTER JOIN
			parn.dbo.carType AS ct ON gc.ct_id = ct.ct_id LEFT OUTER JOIN
			Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial FULL OUTER JOIN
			parn.dbo.garage AS ga ON gc.gar_id = ga.gar_id
			WHERE (gc.garc_delete = 0) AND (gc.garc_date = '{$sel_date}') AND (gc.garc_option = 2) AND (gc.gar_id = '{$gar_id}')
			ORDER BY gc.ct_id, gc.garc_id	";
			$rs_option2 = $connect->execute($sql_option2);
			$resultstr = array();
			foreach($rs_option2 as $data){
				$resultstr[] = $data['carn_all'];
			}
			$data = implode(", ", $resultstr);
			$text .= '<span style="color:red"><u><b>รถขาย</b></u>:<span style="color:blue"> ' . $data . '</span></span>';
			$text .= '</td></tr></table><br/>';
		}
	}
	
	/* ====================Cause Information============= */
	$sql_cause = "SELECT DISTINCT uc.cause_uncheck AS uc_info, uc.garc_date AS uc_date
	FROM  parn.dbo.uncheck_cause AS uc INNER JOIN
	parn.dbo.garage_car AS gc ON uc.garc_date = gc.garc_date
	WHERE (gc.garc_date = '{$sel_date}')";
	
	$rs_cause = $connect->getrow($sql_cause);
	if (!empty($rs_cause)) {
		$text2 = '<div style="text-align:left; overflow-y:scroll; height:100px; "><h4 style="color:red">สาเหตุที่ไม่ระบุสถานที่: </h4><h4>' . ($rs_cause['uc_info']) . '</h4><br></div>';
		} else {
		$text2 .= '<div style="text-align:left; overflow-y:scroll; height:100px; "><h4 style="color:red">สาเหตุที่ไม่ระบุสถานที่:  &nbsp;&nbsp;-</h4></div>';
	}
	
	$pdf->writeHTML($text, true, false, false, false, '');
	$pdf->writeHTML($text2, true, false, false, false, '');
	$pdf->lastPage();
	$pdf->Output('ตรวจเช็คกุญแจรถประจำอู่.pdf', 'I');
?>