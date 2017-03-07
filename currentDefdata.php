<?php
	include("../connect.php");
	include("../sumSQL_function.php");
	// define('UP_PATH', "../../");
	// include UP_PATH . "share/shareutf.inc.php";
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
?>
<div class="panel-body">
	<div class="table-responsive">
		<table class="table display" id="currentTable">
			<thead>
				<tr>
					<th style="text-align:center; background-color:#C4F0FF">No.</th>
					<?php 
						# >>>Search PO/Stock/Unstock
						$searchTitle2 = array("เลขรถ", "วันที่จดทะเบียน", "เลขที่จดทะเบียน", "ยีห้อรถ", "ประเภทรถ", "ที่นั่งทั้งหมด", "ประเภทการใช้งาน", "ประจำอยู่อู่ที่");
						$arrsearchTitle2 = count($searchTitle2);
						for($g = 0; $g < $arrsearchTitle2; $g++){
							echo '<th style="text-align:center;background-color:#C4F0FF">'.$searchTitle2[$g].'</th>';
						}
					?>
				</tr>
			</thead>
			<tbody id="current">
				<?php
					$sqldefgar = "SELECT car.CarSerial, car.CarNo, car.RegisterDate, car.RegisterNo, car.CarMarqueSerial, car.CarTypeSerial, car.TotalSeats, car.FactSeats, car.OwnDate, car.CarWorkTypeSerial, cWoktype.CarWorkType, 
					cMarq.CarMarqueName, cType.CarTypeName, cWoktype.CarWorkType AS Expr1, df.gar_id
					FROM   Personal2.dbo.Car AS car RIGHT OUTER JOIN
					parn.dbo.default_gar AS df ON car.CarSerial = df.df_carserial LEFT OUTER JOIN
					Personal2.dbo.CarWorkType AS cWoktype ON car.CarWorkTypeSerial = cWoktype.CarWorkTypeSerial LEFT OUTER JOIN
					Personal2.dbo.CarType AS cType ON car.CarTypeSerial = cType.CarTypeSerial LEFT OUTER JOIN
					Personal2.dbo.CarMarque AS cMarq ON car.CarMarqueSerial = cMarq.CarMarqueSerial
					WHERE (df.df_delete <> 1)
					ORDER BY df.gar_id";
					$rs = $connect->execute($sqldefgar);
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
							echo '<td>'.$key.'</td>';
							if($data['gar_id'] != ''){
								echo '<td id="carno" name="carno" title="ประจำอู่: '.$data['gar_id'].'"><strong style="color:blue">'.$CarNo.'</strong></td>';
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
						}else{
						echo '<tr class="text-center" style="color:red"><td colspan="9">* ไม่มีเลขรถที่ระบุ หรือ ข้อมูลที่กรอกไม่ถูกต้อง *<input id="warning" type="hidden" value="1"></td></tr>';
					}
				?>
			</tbody>
		</table>
	</div><!-- /.table-responsive -->
</div>
<script type="text/javascript"  language="JavaScript">
	$(document).ready(function (){
		$('#currentTable').DataTable( {
			"dom": '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
			"order": [[0, "asc"]],
			"aLengthMenu": [[5, 10, 15, 25, 50, 100, - 1], [5, 10, 15, 25, 50, 100, "All"]],
			"iDisplayLength": 25,
			"bFilter": true,
			"bInfo": true,
			"bLengthChange": true,
			"bPaginate": true
		});
	});
</script>