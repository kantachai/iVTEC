<?php
	include('../connect.php');
	include('../sumSQL_function.php');
	define('UP_PATH', "../../");
	include UP_PATH . "share/shareutf.inc.php";
	// $connect->debug=true;
	// var_dump($_POST['carno']);
	// armpre($_POST);
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
	
	/* ========Check_date===== */
	$sel_date  = "";
	$user_name = $_POST['user_name'];
	$strRecive = $_POST['datepick'];
	// $arrData = json_decode($strRecive,true);
	if (empty($strRecive)){
		$sel_date = date("Y-m-d");
		}else{
		$sel_date = date("Y-m-d", strtotime($strRecive));
	}/* =====CloseCheck_date=== */
	
	
	/* =========Action makedefault====== */
	if($_POST['action'] == "makedefault"){
		$arr = array();
		$arr2 = array();
		$gardf = $_POST['garage'];
		$strRecive = $_POST['datepick'];
		$gp = 'gar_id';
		$seloption = ', gar_id';
		foreach($gardf as $k=>$d){
			// print_r($d);
			if($d == 9){
				$d = 0;
				$optiononly = 'AND (garc_option = 1)';
				$gp = 'garc_option';
				$seloption = ', garc_option';
			}elseif($d == 10){
				$d = 0;
				$optiononly = 'AND (garc_option = 2)';
				$gp = 'garc_option';
				$seloption = ', garc_option';
			}
			$sqlgarseven = "SELECT TOP 1 MAX(garc_date) AS lastdate_garseven
			FROM  parn.dbo.garage_car
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = '{$d}') {$optiononly}";
			$rsgarseven = $connect->getrow($sqlgarseven);
			$lastdate_garseven = $rsgarseven['lastdate_garseven'];
			$inupdata1 = "
			INSERT INTO parn.dbo.garage_car
            (gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, marked)
			SELECT gar_id, garc_option, '{$sel_date}', garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, marked
			FROM   parn.dbo.garage_car AS g
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = {$d}) {$optiononly} AND (garc_date = '{$lastdate_garseven}') AND (NOT EXISTS
			(SELECT gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, marked
			FROM   parn.dbo.garage_car AS gc
			WHERE (g.gar_id = gar_id) AND (g.garc_carserial = garc_carserial) AND (g.garc_delete = garc_delete) AND (g.garc_option = garc_option) AND (garc_date = '{$sel_date}')))";
			
			$sqlcolor1 = "SELECT gar_id, garc_carserial
			FROM   parn.dbo.garage_car AS g
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = '{$d}') {$optiononly} AND (garc_date = '{$sel_date}')";
			
			// $sqlcolor1 = "SELECT gar_id, garc_carserial
			// FROM   parn.dbo.garage_car AS g
			// WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = '{$d}') {$optiononly} AND (garc_date = '2016-12-06')";
			$connect->EXECUTE($inupdata1);
			// armpre($inupdata1);
			// armpre($sqlgarseven);
			// armpre($sqlcolor1);
			$rscolor1 = $connect->EXECUTE($sqlcolor1);
			foreach ($rscolor1 as $data) {
				$arr[$data['garc_carserial']] = $data['gar_id'];
			}
		}
			echo json_encode($arr);
		// exit;
		/* if($gardf == 7){
			$sqlgarseven = "SELECT TOP 1 MAX(garc_date) AS lastdate_garseven
			FROM  parn.dbo.garage_car
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = '{$gardf}')";
			$rsgarseven = $connect->getrow($sqlgarseven);
			$lastdate_garseven = $rsgarseven['lastdate_garseven'];
			$inupdata1 = "
			INSERT INTO parn.dbo.garage_car
            (gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, marked)
			SELECT gar_id, garc_option, '{$sel_date}', garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, marked
			FROM   parn.dbo.garage_car AS g
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = 7) AND (garc_date = '{$lastdate_garseven}') AND (NOT EXISTS
			(SELECT gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, garc_delete, no_pick, marked
			FROM   parn.dbo.garage_car AS gc
			WHERE (g.gar_id = gar_id) AND (g.garc_carserial = garc_carserial) AND (g.garc_delete = garc_delete) AND (g.garc_option = garc_option) AND (garc_date = '{$sel_date}')))";
			
			$sqlcolor1 = "SELECT gar_id, garc_carserial
			FROM   parn.dbo.garage_car AS g
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = '{$gardf}') AND (garc_date = '{$sel_date}')";
			$connect->EXECUTE($inupdata1);
			// armpre($inupdata1);
			$rscolor1 = $connect->EXECUTE($sqlcolor1);
			foreach ($rscolor1 as $data) {
				$arr[$data['garc_carserial']] = $data['gar_id'];
			}
			echo json_encode($arr);
		}
		if($gardf == 2){
			$sqloptsale = "SELECT TOP 1 MAX(garc_date) AS lastdate_optionsale
			FROM   parn.dbo.garage_car
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = '0') AND (garc_option = 2)";
			$rsoptsale = $connect->getrow($sqloptsale);
			$lastdate_optionsale = $rsoptsale['lastdate_optionsale'];
			$inupdata = "
			INSERT INTO parn.dbo.garage_car
            (gar_id, garc_option, garc_date, garc_carserial, garc_userAdd, ct_id, garc_dateAdd, garc_delete, no_pick, marked)
			SELECT gar_id, garc_option, '{$sel_date}', garc_carserial, garc_userAdd, ct_id, garc_dateAdd, garc_delete, no_pick, marked
			FROM   parn.dbo.garage_car AS g
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = 0) AND (garc_option = 2) AND (garc_date = '{$lastdate_optionsale}') AND (NOT EXISTS
			(SELECT gar_id, garc_option, garc_date, garc_carserial, garc_userAdd, ct_id, garc_dateAdd, garc_delete, no_pick, marked
			FROM   parn.dbo.garage_car AS gc
			WHERE (g.gar_id = gar_id) AND (g.garc_carserial = garc_carserial) AND (g.garc_delete = garc_delete) AND (g.garc_option = garc_option) AND (garc_date = '{$sel_date}')))";
			
			$sqlcolor2 = "SELECT garc_option as gar_id, garc_carserial
			FROM   parn.dbo.garage_car AS g
			WHERE (COALESCE (garc_delete, 0) = 0) AND (gar_id = '0') AND (garc_date = '{$sel_date}') AND (garc_option = '{$gardf}')";
			$connect->EXECUTE($inupdata);
			$rscolor2 = $connect->EXECUTE($sqlcolor2);
			foreach ($rscolor2 as $data) {
				$arr[$data['garc_carserial']] = $data['gar_id'];
			}
			echo json_encode($arr);
		} */
	} //Close Action makedefault
	
	
	/* ===========Save or Update car was checked============ */
	if ($_POST['action'] == 2) {
		$record_delete = array(); //Initialize an array to hold the record data to insert
		$record_option = array(); //Initialize an array to hold the record data to insert
		$record_option2 = array(); //Initialize an array to hold the record data to insert
		$record_delete["garc_delete"] = 1; // 1 = true->DELETE (Bit value 1 = true, 0 = false)
		$record_delete["marked"] = 1; // 1 = true->DELETE (Bit value 1 = true, 0 = false)
		$record_option["garc_option"] = 1; // 1 = true->DELETE (Bit value 1 = true, 0 = false)
		$record_option2["garc_option"] = 2; // 1 = true->DELETE (Bit value 1 = true, 0 = false)
		
		$record = array(); //Initialize an array to hold the record data to insert
		$record["gar_id"] = $_POST['gar'];
		$record["garc_option"] = $_POST['gar_option'];
		$record["garc_date"] = $sel_date;
		$record["garc_carserial"] = $_POST['carSerial'];
		$record["ct_id"] = $_POST['carType'];
		$record["garc_userAdd"]   = $_POST['user_name'];
		$record["garc_dateAdd"]   = 'GETDATE()';
		$record["no_pick"] = $_POST['no_check'];
		$ifdate = 'GETDATE()';
		// armpre($_POST);
		if($_POST['gar'] == 0){
			$sqlif = "IF EXISTS (SELECT * FROM parn.dbo.garage_car WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0) AND (gar_id <> 0))
			BEGIN
			UPDATE parn.dbo.garage_car SET garc_option={$_POST['gar_option']} , garc_dateAdd = GETDATE(), garc_userAdd = '{$user_name}'
			WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0) AND (gar_id <> 0)
			END
			ELSE IF EXISTS (SELECT * FROM parn.dbo.garage_car WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0) AND (gar_id = 0))
			BEGIN
			UPDATE parn.dbo.garage_car SET garc_delete = 1
			WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0) AND (gar_id = 0)
			
			INSERT INTO parn.dbo.garage_car (gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, no_pick) VALUES ('{$_POST['gar']}','{$_POST['gar_option']}','{$sel_date}','{$_POST['carSerial']}','{$_POST['carType']}','{$_POST['user_name']}',GETDATE(),'{$_POST['no_check']}')
			END
			ELSE
			BEGIN
			INSERT INTO parn.dbo.garage_car (gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, no_pick) VALUES ('{$_POST['gar']}','{$_POST['gar_option']}','{$sel_date}','{$_POST['carSerial']}','{$_POST['carType']}','{$_POST['user_name']}',GETDATE(),'{$_POST['no_check']}')
			END";
			$connect->execute($sqlif);	
			}elseif($_POST['gar'] == 11){
			$check_del = "SELECT * FROM parn.dbo.garage_car WHERE (garc_carserial = '{$_POST['carSerial']}') AND (garc_date = '{$sel_date}') AND (COALESCE (garc_delete, 0) = 0)";
			$rs_checkDel = $connect->execute($check_del);
			$check_update = $connect->GetUpdateSQL($rs_checkDel, $record_delete);
			$connect->execute($check_update);
			}else{
			$sqlif = "IF EXISTS (SELECT * FROM parn.dbo.garage_car WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0) AND (gar_id = 0))
			BEGIN
			UPDATE parn.dbo.garage_car SET gar_id={$_POST['gar']} , garc_dateAdd = GETDATE(), garc_userAdd = '{$user_name}'
			WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0) AND (gar_id = 0)
			END
			ELSE IF EXISTS (SELECT * FROM parn.dbo.garage_car WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0))
			BEGIN
			UPDATE parn.dbo.garage_car SET garc_delete = 1 WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (COALESCE (garc_delete, 0) = 0)
			
			INSERT INTO parn.dbo.garage_car (gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, no_pick) VALUES ('{$_POST['gar']}','{$_POST['gar_option']}','{$sel_date}','{$_POST['carSerial']}','{$_POST['carType']}','{$_POST['user_name']}',GETDATE(),'{$_POST['no_check']}')
			END
			ELSE
			BEGIN
			INSERT INTO parn.dbo.garage_car (gar_id, garc_option, garc_date, garc_carserial, ct_id, garc_userAdd, garc_dateAdd, no_pick) VALUES ('{$_POST['gar']}','{$_POST['gar_option']}','{$sel_date}','{$_POST['carSerial']}','{$_POST['carType']}','{$_POST['user_name']}',GETDATE(),'{$_POST['no_check']}')
			END";
			$connect->execute($sqlif);
			// armpre($sqlif);					
			// $sql_update = "SELECT * FROM parn.dbo.garage_car WHERE (garc_date = '{$sel_date}')  AND (garc_carserial = '{$_POST['carSerial']}') AND (garc_delete = 0)";
			// $check = $connect->Execute($sql_update);
			// $update_car = $connect->GetUpdateSQL($check, $record_delete);
			// if($check) $connect->execute($update_car);
			// $sql = "SELECT * FROM [parn].[dbo].[garage_car]";
			// $rs = $connect->execute($sql);
			// $insertSQL = $connect->GetInsertSQL($rs, $record);
			// $connect->EXECUTE($insertSQL);
		}
	} //Close Save or Update car was checked
	
	
	/* ===========Cause_Info INSERT================= */
	if ($_POST['action'] == 3) {
		// armpre($_POST);
		$new = array();
		$new['cause_uncheck'] = $_POST['cause_info'];
		$sql_checkCause = "SELECT  [cause_uncheck] FROM [parn].[dbo].[uncheck_cause] WHERE (garc_date = '{$sel_date}') AND  (cause_uncheck IS NOT NULL) ";
		$rc = $connect->EXECUTE($sql_checkCause);
		if($rc->RecordCount( )!=0){
			$updateSQL = $connect->GetUpdateSQL($rc, $new);
			$connect->EXECUTE($updateSQL);
			}else{
			$cause_info = array();
			$cause_info["cause_uncheck"] = $_POST['cause_info'];
			$cause_info["garc_date"]     = $sel_date;
			$sql = "SELECT * FROM [parn].[dbo].[uncheck_cause]"; //Select an empty record from the database
			$rs = $connect->EXECUTE($sql); //EXECUTE the query and get the empty recordset
			$insertSQL = $connect->GetInsertSQL($rs, $cause_info);
			$connect->EXECUTE($insertSQL);
		}
		// var_dump($_POST);
		// get the correct format
	} //Close Cause_Info INSERT
	
	
	/* ============Car searching========= */
	if ($_POST['act'] == "car_search") {
		$carnum    = $_POST['carnum'];
		$s_carserial = $_POST['s_carserial'];
		$cartype = $_POST['cartype'];
		$arr = split_textarea($carnum); 
		// armpre($carnum);
		$testarr = array($arr);
		$arrlength = count($arr)-1;
		$data; $test;
		if($cartype != 0){
			foreach($arr as $key => $data){
				// $test = $data;
				if($key == $arrlength) {
					$test .= 'car.CarNo LIKE \''.$cartype.$data.'\'';
					}else{
					$test .= 'car.CarNo LIKE \''.$cartype.$data.'\' OR ';
				}
			}
			}else{
			if($arr){
				foreach($arr as $key => $data){
					// $test = $data;
					if($key == $arrlength) {
						$test .= 'car.CarNo LIKE \'%'.$data.'\'';
						}else{
						$test .= 'car.CarNo LIKE \'%'.$data.'\' OR ';
					}
				}
			}
		}
		echo '<div class="panel-body">
		<div class="table-responsive">
		<table name="carinfo_table" id="carinfo_table" class="table table-hover table-bordered display">
		<thead>
		<tr>
		<th style="background-color:#C8F7C5; text-align:center;"><input type="checkbox" id="checkAll" data-toggle="tooltip" title="เลือกทั้งหมด"></th>
		<th style="background-color:#C8F7C5; text-align:center;">No.<br></th>';
		$searchTitle2 = array("เลขรถ", "วันที่จดทะเบียน", "เลขที่จดทะเบียน", "ยีห้อรถ", "ประเภทรถ", "ที่นั่งทั้งหมด", "ประเภทการใช้งาน", "ประจำอยู่อู่ที่");
		$arrsearchTitle2 = count($searchTitle2);
		for($g = 0; $g < $arrsearchTitle2; $g++){
			echo '<th style="text-align:center; background-color:#C8F7C5;">'.$searchTitle2[$g].'</th>';
		}echo '</tr>
		</thead>
		<tbody name="carinfo" id="carinfo">';
		$sql = "SELECT car.CarSerial, car.CarNo, car.RegisterDate, car.RegisterNo, car.CarMarqueSerial, car.CarTypeSerial, car.TotalSeats, car.FactSeats, car.OwnDate, car.CarWorkTypeSerial, cWoktype.CarWorkType, cMarq.CarMarqueName, cType.CarTypeName, cWoktype.CarWorkType, df.gar_id
		FROM Personal2.dbo.Car AS car RIGHT OUTER JOIN
		parn.dbo.default_gar AS df ON car.CarSerial = df.df_carserial LEFT OUTER JOIN Personal2.dbo.CarWorkType AS cWoktype ON car.CarWorkTypeSerial = cWoktype.CarWorkTypeSerial LEFT OUTER JOIN
		Personal2.dbo.CarType AS cType ON car.CarTypeSerial = cType.CarTypeSerial LEFT OUTER JOIN
		Personal2.dbo.CarMarque AS cMarq ON car.CarMarqueSerial = cMarq.CarMarqueSerial
		WHERE {$test} AND (COALESCE(df.df_delete,0) = 0)
		ORDER BY car.CarNo";
		/*     * *****อะไรที่ไม่ใช่ NVARCHAR ไม่ต้องใส่ Singlecode เช่น '{$parn}'*******นอกนั้นใช้ {$parn} ไม่ต้องใช้ SingleCode**** */
		// armpre($sql);
		// armpre($test);
		
		if($test != ""){
			$rs = $connect->execute($sql);
			// $sop = $connect->execute($sqsq);
			// rs2html($sop);
			// rs2html($rs,array('CarSerial'));
			// armpre($sql);
			if ($rs->recordCount() > 0) {
				foreach ($rs as $key => $data) {
					$key++;
					$CarSerial = $data['CarSerial'];
					$CarNo = $data['CarNo'];
					$RegisterDate = parndatethai($data['RegisterDate']);
					$RegisterNo = $data['RegisterNo'];
					$CarMarqueSerial = $data['CarMarqueSerial'];
					$CarTypeSerial = $data['CarTypeSerial'];
					$TotalSeats = $data['TotalSeats'];
					$FactSeats = $data['FactSeats'];
					$OwnDate = parndatethai($data['OwnDate']);
					$CarWorkTypeSerial = $data['CarWorkTypeSerial'];
					$CarMarqueName = $data['CarMarqueName'];
					$CarTypeName = $data['CarTypeName'];
					$CarWorkType = $data['CarWorkType'];
					// condition ? right ... : wrong...;
					// ID name of input for process reference
					echo '<tr id="rowcar_'.$key.'">';
					echo '<td><input name="carmarked[]" id="carmark_'.$key.'" type="checkbox" value="'.$CarSerial.'"></td>';
					echo '<td>'.$key.'</td>';
					if($data['gar_id'] != ''){echo '<td id="carno" name="carno" title="ประจำอู่: '.$data['gar_id'].'"><strong style="color:blue">'.$CarNo.'</strong></td>';
						}else{
						echo '<td id="carno" name="carno" title="ไม่มีอู่ประจำ">'.$CarNo.'</td>';
					}
					echo '<td id="regdate" name="regdate">'.$RegisterDate.'</td>';
					echo '<td id="regno" name="regno">'.$RegisterNo.'</td>';
					echo '<td id="carbrand" name="carbrand">'.$CarMarqueName.'</td>';
					echo '<td id="cartype" name="cartype">'.$CarTypeName.'</td>';
					echo '<td id="totalseat" name="totalseat">'.$TotalSeats.'</td>';
					echo '<td id="carworktype" name="carworktype">'.$CarWorkType.'</td>';
					if($data['gar_id'] == 8 || $data['gar_id'] == ''){
						echo '<td id="dfgar" name="dfgar">ไม่มีอู่ประจำ</td>';
						}else{
						echo '<td id="dfgar" name="dfgar">'.$data['gar_id'].'</td>';
					}
					echo '</tr>';
				}
				}else { 
				echo '<tr class="text-center" style="color:red"><td colspan="9">* ไม่มีเลขรถที่ระบุ หรือ ข้อมูลที่กรอกไม่ถูกต้อง *<input id="warning" type="hidden" value="1"></td></tr>';
			}
			}else{
			echo '<tr id="warning" class="text-center" style="color:red"><td colspan="9">* ไม่มีเลขรถที่ระบุ หรือ ข้อมูลที่กรอกไม่ถูกต้อง *<input id="warning" type="hidden" value="1"></td></tr>';
		}echo '</tbody>
		</table>
		</div>
		<!-- /.table-responsive -->
		</div>';
		// echo $test;
		// echo $arrlength;WHERE (car.CarNo IN ({$test}))";
	}// Close car searching
	
	
	/* ====== Save car was moved ====== */
	if ($_POST['act'] == "save_df") {
		$checkarray = $_POST['checkarray'];
		$checkdel = $_POST['delfrom'];
		$garage = $_POST['garage'];
		$arr = $checkarray;
		$valore = array();
		newsavelog($user_name, $garage);
		// $listcar = array();
		$listcar;
		$valore = explode(',',$checkarray);
		
		if($valore[0] == "on"){
			unset($valore[0]); // Delete specific element array
			array_pop($valore); // Delete last element array
			$set = count($valore);
			for($i=1; $i<=$set; $i++){
				if($set == $i){
					$listcar .= $valore[$i];
				}
				else{
					$listcar .= $valore[$i].",";
				}
			}
			}else{
			array_pop($valore); // Delete last element array
			// $valore = (1=>$valore);
			$set = count($valore)-1;
			for($i=0; $i<=$set; $i++){
				if($set == $i){
					$listcar .= $valore[$i];
					}else{
					$listcar .= $valore[$i].",";
				}
			}
		}
		// armpre($listcar);
		$listarr = explode(",",$listcar); #explode = แตกกระจายเป็น array
		// armpre($listarr);
		// armpre($garage);
		// $df_carserial;
		// $testdata;
		
		// $connect->debug=true;
		foreach($listarr as $data){
			$updatecar = array();
			$updatecar['gar_id'] = $garage;
			$remove = 1;
			if($garage == 8){
				$inupdata = "IF EXISTS (SELECT * FROM  parn.dbo.default_gar WHERE df_carserial = {$data}  and ISNULL(df_delete, 0) = 0)
				BEGIN
				UPDATE parn.dbo.default_gar SET GAR_ID = NULL, df_dateupdate = GETDATE()
				WHERE df_carserial = {$data} and ISNULL(df_delete,0) = 0
				END";
				$connect->EXECUTE($inupdata);
				}else{
				// $sqlupdate = "SELECT * FROM  parn.co_kantachai_c.defaultGar_test WHERE df_carserial = '{$data}'";
				$inupdata = "IF EXISTS (SELECT * FROM  parn.dbo.default_gar WHERE df_carserial = {$data}  and df_delete IS NULL or df_delete = 0 or gar_id = 8)
				BEGIN
				UPDATE parn.dbo.default_gar SET GAR_ID={$garage} , df_dateupdate = GETDATE()
				WHERE df_carserial = {$data} and ISNULL(df_delete,0) = 0
				END
				ELSE
				BEGIN
				INSERT INTO parn.dbo.default_gar (gar_id, ct_id, df_carserial, df_date, car_no, user_edit)
				SELECT {$garage}, CarTypeSerial, CarSerial, GETDATE(), CarNo, '{$user_name}'
				FROM Personal2.dbo.Car WHERE (CarSerial = {$data})
				END";
				$connect->EXECUTE($inupdata);
			}
		}
	} //Close Save car was moved 
	
	
	/* ======Update default garage table====== */
	if ($_POST['act'] == "updateDeftable") {
		/* $yesterdate = date_create(date('Y-m-d'));
		date_modify($yesterdate, '-1 day');
		$yesterdate = date_format($yesterdate, 'Y-m-d');
		if($sel_date > $yesterdate){
			$disabled = "disabled";
		}else{
			$disabled = "";
		} */
		$tableupdate = "IF EXISTS (SELECT * FROM  parn.dbo.default_gar
		WHERE  (df_carserial IN
		(SELECT TOP 100 PERCENT c.CarSerial
		FROM      Personal2.dbo.Car AS c INNER JOIN
		parn.dbo.default_gar AS df ON c.CarSerial = df.df_carserial
		WHERE   (c.CarWorkTypeSerial = 7)) AND (COALESCE (df_delete, 0) = 0)))
		BEGIN 
		UPDATE parn.dbo.default_gar set df_delete = 1 WHERE (df_carserial IN
		(SELECT per.CarSerial
		FROM  Personal2.dbo.Car AS per INNER JOIN
		parn.dbo.default_gar AS dg ON per.CarSerial = dg.df_carserial
		WHERE (per.CarWorkTypeSerial = 7)))
		END
		IF EXISTS (SELECT garc_carserial, garc_delete, garc_date
		FROM   parn.dbo.garage_car
		WHERE (garc_carserial IN
		(SELECT garc_carserial
		FROM   Personal2.dbo.Car AS c RIGHT OUTER JOIN
		parn.dbo.garage_car AS gc ON c.CarSerial = gc.garc_carserial
		WHERE (c.CarWorkTypeSerial = 7) AND (COALESCE (gc.garc_delete, 0) = 0))))
		BEGIN 
		UPDATE parn.dbo.garage_car set garc_delete = 1 WHERE (garc_carserial IN
		(SELECT per.CarSerial
		FROM  Personal2.dbo.Car AS per RIGHT OUTER JOIN
		parn.dbo.garage_car AS gc ON per.CarSerial = gc.garc_carserial
		WHERE (per.CarWorkTypeSerial = 7) AND (COALESCE (gc.garc_delete, 0) = 0)))
		END
		IF EXISTS (SELECT dg.gar_id, dg.ct_id, dg.df_carserial, dg.car_no, dg.CarWorkTypeSerial
		FROM   Personal2.dbo.CarWorkType AS wt INNER JOIN
		Personal2.dbo.Car AS c ON wt.CarWorkTypeSerial = c.CarWorkTypeSerial LEFT OUTER JOIN
		parn.dbo.default_gar dg ON c.CarSerial = dg.df_carserial
		WHERE (c.CarNo NOT LIKE N'%\_') AND (c.CarNo NOT LIKE N'z%') AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') AND (c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)) 
		 AND (dg.df_carserial IS NULL))
		BEGIN
		INSERT INTO parn.dbo.default_gar (ct_id, df_carserial, df_date, car_no, df_dateupdate, user_edit, CarWorkTypeSerial)
		SELECT c.CarTypeSerial, c.CarSerial, GETDATE(), c.CarNo, GETDATE(), '{$user_name}', c.CarWorkTypeSerial
		FROM   Personal2.dbo.CarWorkType AS wt INNER JOIN
		Personal2.dbo.Car AS c ON wt.CarWorkTypeSerial = c.CarWorkTypeSerial LEFT OUTER JOIN
		parn.dbo.default_gar dg ON c.CarSerial = dg.df_carserial
		WHERE (c.CarNo NOT LIKE N'%\_') AND (c.CarNo NOT LIKE N'z%') AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') AND (c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12))  AND (dg.df_carserial IS NULL)
		END";
		$connect->execute($tableupdate);
	} // Close Update default garage table
	
?>
