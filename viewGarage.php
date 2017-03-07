<?php
	include('../connect.php');
	global $carwt;
	// include('../sum_function.php');
	function parndatethai($str) {
		if ($str) {
			$strDate = date("Y-m-d", strtotime(str_replace('/', '-', $str)));
			$strYear = date("Y", strtotime($strDate)) + 543;
			$strMonth = date("n", strtotime($strDate));
			$strDay = date("j", strtotime($strDate));
			$strHH = date("H", strtotime($strDate));
			$strMM = date("i", strtotime($strDate));
			
			$strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
			$strMonthThai = $strMonthCut[$strMonth];
			$temp = "$strDay $strMonthThai $strYear "; //<br /> เวลา $strHH:$strMM น.
			} else {
			$temp = '';
		}
		return $temp;
	}
	$carwt = "c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)";
	// include('../sum_function.php');
	// error_reporting(E_ALL & ~E_NOTICE);
	// armpre($_POST);
	// $connection->debug=true;
	// var_dump($_POST);
	$strRecive = $_POST['datepick'];
	// $arrData = json_decode($strRecive,true);
	if (empty($strRecive)) {
		$sel_date = date("Y-m-d");
		} else {
		$sel_date = date("Y-m-d", strtotime($strRecive));
	}
	$seled = parndatethai($sel_date);
	/* ===============ACTION 0 ลงสีรถแต่ละอู่ ==================== */
	if ($_POST['action'] == 0) {
		$arr = array();
		$sql_json = "SELECT gc.gar_id AS garID, gc.garc_userAdd, gc.garc_dateAdd, gc.garc_carserial, gc.garc_option
		FROM  parn.dbo.garage_car AS gc INNER JOIN
		parn.dbo.carType AS ct ON gc.ct_id = ct.ct_id INNER JOIN
		Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial FULL OUTER JOIN
		parn.dbo.garage AS ga ON gc.gar_id = ga.gar_id
		WHERE (COALESCE (gc.garc_delete, 0) = 0) AND (gc.garc_date = '{$sel_date}') AND {$carwt} AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') 
		ORDER BY gc.gar_id";
		
		$rs = $connect->execute($sql_json);
		foreach ($rs as $data) {
			$arr[$data['garc_carserial']] = $data['garID'];
		}
		echo json_encode($arr);
	} // Close ACTION 0
	
	/* =========ACTION 2 เรียกข้อมูล สาเหตุ และ จำนวนรถมาแสดง====== */
	if ($_POST['action'] == 2) {
		$sql_gar = "SELECT gar.gar_name, gar.gar_id, COUNT(gc.garc_id) AS total_eType
		FROM   parn.dbo.garage AS gar LEFT OUTER JOIN
		parn.dbo.garage_car AS gc ON gar.gar_id = gc.gar_id AND gc.garc_delete = 0 AND gc.garc_date = '{$sel_date}'
		GROUP BY gar.gar_name, gar.gar_id
		ORDER BY gar.gar_id";
		$rs = $connect->execute($sql_gar);
		
		$sqlnew = "SELECT COUNT(*) AS selectedcar,
		(SELECT COUNT(*) AS allcar
		FROM   Personal2.dbo.Car AS c INNER JOIN
		Personal2.dbo.CarWorkType AS cw ON c.CarWorkTypeSerial = cw.CarWorkTypeSerial
		WHERE (c.CarNo NOT LIKE N'%\_') AND (c.CarNo NOT LIKE N'z%') AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') AND {$carwt} AND (c.RegisterDate IS NOT NULL))
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
		(SELECT COUNT(parn.dbo.default_gar.df_carserial) AS sumnotselected
		FROM parn.dbo.default_gar RIGHT OUTER JOIN
		Personal2.dbo.Car ON parn.dbo.default_gar.df_carserial = Personal2.dbo.Car.CarSerial 
		WHERE (NOT (parn.dbo.default_gar.df_carserial IN
                             (SELECT TOP 100 PERCENT garc_carserial
                               FROM parn.dbo.garage_car
                               WHERE (garc_date = '{$sel_date}') AND (COALESCE (garc_delete, 0) = 0)
                               ORDER BY garc_carserial))) AND (COALESCE (parn.dbo.default_gar.df_delete, 0) = 0) AND (Personal2.dbo.Car.RegisterDate IS NOT NULL)) as sumnotselected
		FROM  parn.dbo.garage_car AS gc INNER JOIN
		Personal2.dbo.Car AS c ON gc.garc_carserial = c.CarSerial
		WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_date = '{$sel_date}') AND (gc.gar_id <> 0) AND {$carwt}";
		
		$sqlsumnotseled = "";
		$rssum = $connect->getrow($sqlnew);
		// $rssumnotseled = $connect->getrow($sqlsumnotseled);
		// print_r($sqlnew);
		$selectedcar = $rssum['selectedcar'];
		$totalcar = $rssum['totalcar'];
		$onselected = $rssum['onselected'];
		$totalsri = $rssum['totalsri'];
		$maintainongarage = $rssum['maintainongarage'];
		$puremaintain = $rssum['puremaintain'];
		$sumfixedGarmaintain = $rssum['sumfixedGarmaintain'];
		$sumonsale = $rssum['sumonsale'];
		$sumnotselected = $rssum['sumnotselected'];
		$pureonsale = $rssum['pureonsale'];
		$netmaintain = $puremaintain + $sumfixedGarmaintain+$maintainongarage;
		$netselected = $selectedcar+$puremaintain+$pureonsale;
		// armpre($netselected);
		$noselected = $totalcar-$netselected;
		if($sumnotselected == null){$sumnotselected = 0;}
		if($totalsri == null){$totalsri = 0;}
		if($netmaintain == null){$netmaintain = 0;}
		if($sumonsale == null){$sumonsale = 0;}
		// $totalcar = $totalcar - $selectedcar;
		// armpre($sqlnew);
		$arr = array();
		$arrgar = array();
		/* ==================== */
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
		// $arr['tatal'] = $totalcar;
		$sql_getrow = "SELECT [cause_uncheck]
		FROM [parn].[dbo].[uncheck_cause]
		WHERE garc_date = '{$sel_date}'";
		$rs = $connect->getrow($sql_getrow);
		$arr['cause_uncheck'] = $rs['cause_uncheck'];
		echo json_encode($arr);
		
		/* ======================== */
	}
	
	/* ===========Action 77 Srirachar  ============= */
	if ($_POST['action'] == 77){
		$arr = array();
		$sql_json = "SELECT gar_id, garc_carserial, garc_delete
		FROM   parn.dbo.garage_car
		WHERE (gar_id = 7) AND (COALESCE (garc_delete, 0) = 0) and (garc_date = '{$sel_date}')
		GROUP BY garc_carserial, gar_id, garc_delete";
		$rs = $connect->execute($sql_json);
		foreach ($rs as $data) {
			$arr[$data['garc_carserial']] = $data['gar_id'];
			// $arr[$data['garc_carserial']] = $data['garID'];
		}
		echo json_encode($arr);
	} 
	
	/* ==========Action 10 ลงสี รถขาย  =============== */
	if ($_POST['action'] == 10){
		$arr2 = array();
		$sql_garPlus = "SELECT garc_carserial, garc_option
		FROM   parn.dbo.garage_car
		WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_option = 2) and (garc_date = '{$sel_date}')
		GROUP BY garc_carserial, garc_option";
		
		// $sql_garPlus = "SELECT garc_carserial, garc_option
		// FROM   parn.dbo.garage_car
		// WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_option = 2) and (garc_date = '2016-12-06')
		// GROUP BY garc_carserial, garc_option";
		$rs_plus = $connect->execute($sql_garPlus);
		foreach ($rs_plus as $data) {
			$arr2[$data['garc_carserial']] = $data['garc_option'];
		}
		echo json_encode($arr2);
	}
	
	/* =========Action 100 ระบุรถค้างซ่อม  ============= */		
	if($_POST['action'] == 100){
		$arr2 = array();
		$sql_garPlus = "SELECT garc_id, gar_id, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, garc_option
		FROM   parn.dbo.garage_car
		WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_option = 1) and (garc_date = '{$sel_date}')
		ORDER BY garc_carserial, garc_option";
		
		// $sql_garPlus = "SELECT garc_id, gar_id, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, garc_option
		// FROM   parn.dbo.garage_car
		// WHERE (COALESCE (garc_delete, 0) = 0) AND (garc_option = 1) and (garc_date = '2016-12-06')
		// ORDER BY garc_carserial, garc_option";
		$rs_plus = $connect->execute($sql_garPlus);
		// armpre($rs_plus);
		foreach ($rs_plus as $data) {
			$arr2[$data['garc_carserial']] = $data['garc_option'];
		}
		echo json_encode($arr2);
	}
	
?>