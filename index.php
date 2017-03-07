<?php
	define('UP_PATH', "../../");
	require_once UP_PATH . "share/conf_global.php";
	include UP_PATH . "adodb/adodb.inc.php"; # load code common to ADOdb
	include UP_PATH . "share/shareutf.inc.php";
	include UP_PATH . "share/user_classUTF.inc.php";
	include UP_PATH . "share/view_page.inc.php";
	$prj_no = 49;
	$u_o = new User_Login($conn_u, UP_PATH . "share/");
	$u_o->assign_session($login, $password, $prj_no, $act);
	if($u_o->form_login())exit;
	// var_dump($u_o);
	include('../connect.php');
	global $user_name; $sqlsumeachcar = "SELECT COUNT(carm) AS sum_m, COUNT(card) AS sum_d, COUNT(carc) AS sum_c, COUNT(carp) AS sum_p, COUNT(carv) AS sum_v
	FROM   (SELECT TOP 100 PERCENT CASE WHEN c.CarNo LIKE 'M%' THEN COUNT(c.CarNo) END AS carm, CASE WHEN c.CarNo LIKE 'D%' THEN COUNT(c.CarNo) END AS card, 
	CASE WHEN c.CarNo LIKE 'C%' THEN COUNT(c.CarNo) END AS carc, CASE WHEN c.CarNo LIKE 'P%' THEN COUNT(c.CarNo) END AS carp, CASE WHEN c.CarNo LIKE 'V%' THEN COUNT(c.CarNo) 
	END AS carv
	FROM   Personal2.dbo.Car AS c INNER JOIN
	Personal2.dbo.CarWorkType AS cw ON c.CarWorkTypeSerial = cw.CarWorkTypeSerial
	WHERE (c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)) 
	GROUP BY c.CarNo, c.CarWorkTypeSerial
	HAVING (NOT (c.CarNo LIKE N'%\_')) AND (NOT (c.CarNo LIKE N'z%')) AND (NOT (c.CarNo LIKE N'P0%')) AND (NOT (c.CarNo LIKE N'P1%')) AND (NOT (c.CarNo LIKE N'D210'))
	ORDER BY c.CarNo) AS np
	GROUP BY carm, card, carc, carp, carv";
	$sumeachcar = $connect->execute($sqlsumeachcar);
	$user_name = $u_o->userinfo[u_login];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="garthor" content="">
        <link rel="icon" href="../../favicon.ico">
        <title>ระบบจัดการข้อมูลการจัดเก็บกุญแจ</title>
		
        <link href="../css/jquery-ui.css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/dashboard.css" rel="stylesheet">
        <link href="../css/btn-parnButTab.css" rel="stylesheet">
        <link href="../js/jsPanel2/jsPanel.css" rel="stylesheet">
        <link href="../css/datatable-1.10.10/datatables.min.css" rel="stylesheet">
		<link href="../cusBackToTop-jQuery/demo/css/backTop.css" rel="stylesheet">
		
        <script src="../js/jquery-ui-1.11.4/jquery.js"></script>
        <script src="../js/jquery-ui-1.11.4/jquery-ui.js"></script>
        <script src="bootstrap/js/bootstrap.js"></script>
        <script src="../js/jquery.jspanel.min.js"></script>
        <script src="../css/datatable-1.10.10/datatables.min.js"></script>
		<script src="../cusBackToTop-jQuery/src/jquery.backTop.min.js"></script>
		<script src="../sum_jsFunction.js"></script>
		<script src="keyJS_function.js"></script>
        <style>
            table#t01 tr:nth-child(even) {background-color: #eee;}
            table#t01 tr:nth-child(odd) {background-color: #fff;}
            table#t01 th {background-color: black;color: white;}
			td {border: 1px solid #fff;text-align : center;vertical-align: top;}
            @media {.modal-dialog  {width: 96%;}}
			.modal-error {
			position: fixed;
			top: 10%;
			left: 50%;
			z-index: 1050;
			width: 560px;
			margin-left: -280px;
			}
            .nav-tabs{width: 1024px;}
            .tab-pane{height: 640px;overflow: auto;}
            #report_p {position: fixed;top: 57px;right: 100px; }
            a {text-decoration: none !important;}
            ul#car_sum li {background-color: #FEFCFF; color: black;text-align : left;padding: 6px 6px; border:1px solid #000;-moz-border-radius:8px;-webkit-border-radius:8px;}
            body{background-color:#FEFCFF;}
            .col-sm-6 h3{background-color:#8D38C9;color:#FEFCFF;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;}
            textarea #cause .form-control{margin: 0px 98.636360168457px 0px 0px; width: 200px; height: 33px;}
            #label {color: #000; /* makes the text-black */}
			div.relative {position: relative;top:45px;}
			<!--#formmain{
				padding-bottom: 110px;
				}
				
				#formmain{
				padding-top:50px;
			}-->
			#back-top {
			position: fixed;
			right: 20px;
			bottom: 50px;
			}
			
			#back-top a {
			width: 108px;
			text-align: center;
			font: 11px/100% Arial, Helvetica, sans-serif;
			text-transform: uppercase;
			text-decoration: none;
			color: #bbb;
			
			-webkit-transition: 1s;
			-moz-transition: 1s;
			transition: 1s;
			}
			#back-top a:hover {
			color: #000;
			}
			
			#back-top span {
			opacity:0.4;
			width: 100px;
			height: 100px;
			display: block;
			margin-bottom: 7px;
			background: #ddd url("../img/back-to-top.png") no-repeat center center;
			
			/* rounded corners */
			-webkit-border-radius: 15px;
			-moz-border-radius: 15px;
			border-radius: 55px;
			
			/* transition */
			-webkit-transition: 1s;
			-moz-transition: 1s;
			transition: 1s;
			}
			#back-top a:hover span {
			opacity:5;
			background-color: #777;
			}
			@media (min-width:2000px){
			   #formmain{	
					padding-top: 100px;
					padding-bottom: 100px;
					}
			}
			
			@media (min-width:400px){
			#formmain{
			padding-top: 150px;
			padding-bottom: 150px;
			}
			}
			
		</style>
	</head>
	<body onload="now();">
        <!---===========Group garage menu==========---->
		<nav class="navbar navbar-default navbar-fixed-top" style="background-color:#2C3539">
			<div class="container-fluid">
                <div class="navbar-header">
                    <button class="navbar-toggle collapsed" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse"  type="button"   aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="?" style="color:yellow; font-family:THNiramitAS;">ระบบจัดการข้อมูลการจัดเก็บกุญแจ</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a class="glyphicon glyphicon-user" style="color:#81BEF7;">
								<?php echo $u_o->userinfo[u_login];?>
							</a>
						</li>
						<li>
							<a class="glyphicon glyphicon-off" id="btnlogout" style="color:yellow;" type="button" href=	"index.php?act=logout">Logout
							</a>
						</li>
					</ul>
					<ul>
						<a class="navbar-brand" style="color:yellow; font-size:16px;" href="Manual Key_manage.html" target='_blank'>				(คู่มือการใช้งาน)
						</a> 
						<a class="navbar-brand" style="color:white; font-size:16px;">วันที่ต้องการ : <input type="text" id="datepicker" style="color:black" placeholder='<?= date('d-m-Y'); ?>'></a>
						<a class="navbar-brand" id="gar_printnew" href="garageReportPDF.php?datepick=<?= date('Y-m-d'); ?>" target='_blank'><u style="color:yellow; font-size:16px;"><strong style="color:yellow;">พิมพ์ตามต้นฉบับ (ผจก. อารีย์) </strong></u>/ </a>&nbsp <a class="navbar-brand" id="gar_printold" href="" target='_blank'><u style="color:yellow; font-size:16px;"><strong style="color:yellow;">พิมพ์ (MD)</strong></u></a>&nbsp 
						<button type="button" class="btn btn-success btn-md navbar-btn garReport" data-toggle="modal" data-target="#manageGarDf" id="managedef">จัดการข้อมูลต้นฉบับ</button> &nbsp 
						<button type="button" class="btn btn-danger btn-md navbar-btn" id="updatecarinfo">อัพเดทข้อมูลรถทั้งหมด</button> 
						<? /* &nbsp <a style="color:yellow; font-size:16px;" href="ManualGarManage.pdf" target='_blank'>				คู่มือการจัดข้อมูลรถประจำอู่)
						</a> */
						?>
					</ul>
					<form class="navbar-form navbar-right" id="defaultform" role="form">
						<?php
							$garno = array(1,2,3,4,5,6,7,8,9,10);
							// $garno = array(7,2);
							$garname = array('อู่1','อู่2','อู่3','อู่4','อู่5','อู่6','อู่ศรีราชา', 'ค้าง ตจว.', 'ค้างซ่อม', 'รอขาย');
							// $garname = array('อู่ศรีราชา', 'รอขาย');
							foreach($garno as $key=>$data){
								echo '<label class="checkbox-inline" style="color:white">
								<input id="check" name="check" type="checkbox" value="'.$data.'"><strong>'.$garname[$key].'</strong>
								</label>';
							}
						?>
						<label class="checkbox-inline">
							<input type="button" onclick="defaultgar()" value="Submit"><input type="hidden" id="submitdf">
						</label>
					</form>
					<ul class="nav navbar-nav navbar-right">
                        <li class="parn_cho">
							<?php 
								echo "<br>";
								$sql_gar = 'SELECT gar_id, gar_name, gar_option
								FROM parn.dbo.garage';
								$rs = $connect->execute($sql_gar);
								// $row = count($rs);
								foreach ($rs as $row) {
									$gar_id = $row['gar_id'];
									$gar_name = $row['gar_name'];
									$gar_option = $row['gar_option'];
									$opt = 0;
									if($gar_id == 9 || $gar_id == 10){
										echo '<button type="button" value="' . $opt . ','.$gar_option.'" class="btn-nav navbar-btn btn btn-gar' . $gar_id . '">' . $gar_name . '</button><input id="checkgar" type="hidden" value="'.$gar_id.'">';
										}else{
										echo '<button type="button" value="' . $gar_id . ','.$opt.'" class="btn-nav navbar-btn btn btn-gar' . $gar_id . '">' . $gar_name . '</button><input id="checkopt" type="hidden" value="'.$gar_option.'">';
									}
								}
							?>
							&nbsp
							<u style="color:white; font-size:16px;"></u><span id="dPanel" style="color:white; font-size:14px" class="label label-primary"></span><u style="color:yellow; font-size:16px;"><strong style="color:yellow"></strong></u> &nbsp 
							<?php 
								$sumeachcartype = array('');
								$atrcartype = array('','M', 'D', 'C', 'PM', 'V');
								foreach($sumeachcar as $value){
									$value['sum_m'] <> 0 ? $sumeachcartype[1] = $value['sum_m'] : '';
									$value['sum_d'] <> 0 ? $sumeachcartype[2] = $value['sum_d'] : '';
									$value['sum_c'] <> 0 ? $sumeachcartype[3] = $value['sum_c'] : '';
									$value['sum_p'] <> 0 ? $sumeachcartype[4] = $value['sum_p'] : '';
									$value['sum_v'] <> 0 ? $sumeachcartype[5] = $value['sum_v'] : '';
								}
								for($q=1;$q<=count($sumeachcartype)-1;$q++){
									echo '<span class="label" title="'.$sumeachcartype[$q].' คัน" style="background-color:#8d38c9; color:white; font-size:14px">'.$atrcartype[$q].': '.$sumeachcartype[$q].' คัน</span>&nbsp';
								}
							?> &nbsp <span class="label label-danger" style="color:white; font-size:14px">พบปัญหาโทร 206 (ป่าน)</span>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav> <!--Close navbar class-->
		<!---======== Display selected/unselected car ========-->
		<div class="container-fluid" id="formmain">
			<div class="row relative">
				<div class="col-md-12 col-xs-12">
					<?php
						$sql = "SELECT c.CarSerial AS cars_all, c.CarNo AS carn_all, cw.CarWorkTypeSerial AS carwt_all, 
						CASE WHEN c.CarNo LIKE 'M%' THEN 1 WHEN c.CarNo LIKE 'D%' THEN 2 WHEN c.CarNo LIKE 'C%' THEN 3 WHEN c.CarNo LIKE 'P%' THEN 4 WHEN c.CarNo LIKE 'V%' THEN 5 END AS carty
						FROM   Personal2.dbo.Car AS c LEFT OUTER JOIN
						Personal2.dbo.CarWorkType AS cw ON c.CarWorkTypeSerial = cw.CarWorkTypeSerial
						WHERE (c.CarNo NOT LIKE N'%\_') AND (c.CarNo NOT LIKE N'z%') AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') AND (c.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)) AND (c.CarNo IS NOT NULL) AND (c.RegisterDate IS NOT NULL)
						ORDER BY carty, c.CarNo";
						$recordSet = $connect->execute($sql);
						$ss = array();
					?>
					<div class="row placeholders">
						<div id="progressbar" align="center">
							<img src="../img/ajax-loader2.gif"/>
						</div>
						<!-- <img data-src="holder.js/200x200/garto/sky" class="img-responsive" alt="Generic placeholder thumbnail"> -->
						<!-- <span class="text-muted">Something else</span> -->
						<?php
							$k = ($recordSet->RecordCount() - 1);
							$sumeachcartype = array('');
							foreach($sumeachcar as $val){
								$val['sum_m'] <> 0 ? $sumeachcartype[1] = $val['sum_m'] : '';
								$val['sum_d'] <> 0 ? $sumeachcartype[2] = $val['sum_d'] : '';
								$val['sum_c'] <> 0 ? $sumeachcartype[3] = $val['sum_c'] : '';
								$val['sum_p'] <> 0 ? $sumeachcartype[4] = $val['sum_p'] : '';
								$val['sum_v'] <> 0 ? $sumeachcartype[5] = $val['sum_v'] : '';
							}
							
							$carTy = array(
							"",
							"รถตู้",
							"รถบัสเล็ก",
							"รถบัสใหญ่",
							"รถส่วนบุคคล",
							"รถบัส 2 ชั้น"
							);
							$cType = count($carTy);
							// rs2html($recordSet);
							foreach ($recordSet as $key => $data) {
								$cars_all = $data['cars_all'];
								if ($key == 0) {
									$cType = $data['carty'];
									$i = 0;
									echo '<div class="col-xs-6 col-sm-6" style="text-align:center;">' . '<h3 title="'.$sumeachcartype[$cType].' คัน">' . $carTy[$cType] . '</h3><table id="ra" width="100%" class="table table-hover table-bordered" style="background-color:#fff">';
								}
								if ($cType != $data['carty']) { //เงื่อนไขการปิด Column รถแต่ละประเภท โดยใช้ Column span และกำหนด Cartype ใหม่
									$i_last = substr($i, -1, 1); //วิธีเช็ค column ที่เหลือ
									// armpre($i_last);
									if ($i_last != 5 && $i_last < 5) {
										echo '<td colspan="' . (5 - $i_last) . '"></td></tr>'; // Column span
										} else {
										if ($i_last > 5) {
											echo '<td colspan="' . (10 - $i_last) . '"></td></tr>'; // Column span
										}
									}
									
									$cType = $data['carty']; // เอ Cartype ใหม่มาใส่ เพื่อตารางต่อไป                
									$i = 0;
									echo '</table></div>'; //ปิด Table และ div ตารางประเภทรถ
									echo '<div class="col-xs-6  col-sm-6" style="text-align:center;">' . '<h3 title="'.$sumeachcartype[$cType].' คัน">' . $carTy[$cType] . '</h2>' . '<table width="100%" class="table table-hover table-bordered" style="background-color:#fff">'; //เปิด Table และ div ตารางประเภทรถใหม่
								}
								
								if ($i % 5 == 0) { //เงื่อนไขเปิด Row ?>
								<tr>
								<?php } ?>
								<!---ข้อมูลรถที่แสดง-->
								<td id="car_<?php echo $cars_all; ?>" class="parn">
									<input type="hidden" id="cType_<?php echo $cars_all; ?>" name="cType_<?php echo $cars_all;?>" value="<?php echo $cType;?>">
									<?php echo $data['carn_all'] . "<br>";?>
								</td>
								
								<? if ($i % 5 == 4) { //เงื่อนไขปิด Row  ?>
								</tr>
								<?php }
								
								if ($key == $k) {
									$i_last = (substr($i, -1, 1) + 1);
									// armpre($i_last);
									if ($i_last != 5 && $i_last < 5) {
										echo '<td colspan="' . (5 - $i_last) . '"></td></tr>';
										} else {
										if ($i_last > 5) {
											echo '<td colspan="' . (10 - $i_last) . '"></td></tr>';
										}
									}
									echo '</table></div>';
								}
							?>
						<?php $i++; } ?>
					</div><!--Close class="row placeholders"-->
				</div><!--Close class="col-sm-12"-->
			</div> <!--Close Row relative-->
		</div> <!--Display selected/unselected car-->
		
		<!--Modal Manage DefaultGarage-->
		<div class="modal fade" id="manageGarDf" role="dialog" aria-labelledby="exampleModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">จัดการข้อมูลต้นฉบับ</h4>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-6">
									<form class="form-horizontal" id="oriGarform" name="oriGarform" role="form">
										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-8">  
												<label class="control-label" style="color:blue; font-size:16px;">เบอร์รถ*</label>
												<textarea id="carnum" type="text" class="form-control" name="carnum" placeholder="ข้อมูลต่างๆ"></textarea>	  
											</div>
										</div>
										<div id="selgar" class="form-group">       
											<label class="control-label col-sm-2">เลือกอู่: </label>
											<div class="col-sm-2">
												<div class="checkbox">
													<?php
														$garage = array("1","2", "3", "4", "5", "6", "7", "8");
														// var_dump($garage);
														echo "<select id='garage' name='garage'>
														<option id='firstGar' value=''>เลือกอู่</option>";
														$cou = count($garage)-1;
														foreach ($garage as $key => $data){
															if($cou == $key){
																echo "<option value=".$data.">เอาออก</option></select>";
																}else{
																echo "<option value=".$data.">อู่ ".$data."</option>";
															}
														}
													?>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"></label>&nbsp &nbsp
											<button id="carsearch" data-toggle="toltip" title="ค้นหา">search</button>
											<input id="resetform" name="resetform"  data-toggle="toltip" title="รีเซ๊ตข้อมูล" type="reset" value="Reset">
										</div>
										<div id="progressbarmodal" align="center">
											<img src="../img/ajax-loader2.gif"/>
										</div>
										<div class="form-group" style="overflow:auto;">
											<div class="col-xs-10 col-sm-10 col-sm-offset-1" ></div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												ข้อมูลที่ค้นหา
											</div><!-- /.panel-heading -->
											<div id="parntest">
												<!--------Table ข้อมูลที่ค้นหา--------------------------------->
											</div>
										</div><!-- /.panel -->
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<input type="submit" id="submitform" name="submitform" class="btn btn-success" data-dismiss="modal" value="Submit">
										</div>
									</form>
								</div><!-- /.col-lg-6 -->
								<div class="col-lg-6">
									<div class="panel panel-default">
										<div class="panel-heading">
											ข้อมูลรถประจำอู่
											<div align="right">
												<button onclick="currentdefFunction();" title="Refresh ตาราง" data-toggle="tooltip" aria-label="Center Align" class="btn btn-default" style="background-color:yellow" type="button" id="reBillinfo">
													<span aria-hidden="true" class="glyphicon glyphicon-refresh"></span>
												</button>
											</div>
										</div><!-- /.panel-heading -->
										<div id="progressmanageoriginal" align="center">
											<img src="../img/ajax-loader2.gif"/>
										</div>
										<div id="viewInfoTable">
											<!--------Table ข้อมูลรถประจำอู่--------------------------------->
										</div><!-- /.panel-body -->
									</div><!-- /.panel -->
								</div><!-- /.col-lg-6 -->
							</div>
						</div> <!--Close class container-fluid-->
					</div> <!--Close class modal-body-->
				</div> <!---Close class modal-content-->
			</div> <!--Close class modal-dialog-->
		</div> <!--Close Modal Manage DefaultGarage-->
		
		<!--====Display total car of each garage bottom of page=====--->
        <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation" style="background-color: #2C3539;">
            <div class="collapse navbar-collapse" id="navbar_car_sum">
                <!--a class="navbar-brand" style="color:white"></a-->
				<!--จำนวนรถแต่ละอู่-->
                <div>
					<ul  id="car_sum" class="nav navbar-nav">
                        <?php
							// $date('Y-m-d')
							$sql = 'SELECT gar_id,gar_name
							FROM parn.dbo.garage';
							$rs = $connect->execute($sql);
							$gar_color = array(1 => '#FFFF99','#FF9966','#000099','#FF9CD6','#73D549','#800080','#909090','#6495ed','background-image: -webkit-linear-gradient(-248deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%);
							background-image: -moz-linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%);
							background-image: linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%);
							background-position: 50% 50%;
							-webkit-background-origin: padding-box;
							background-origin: padding-box;
							-webkit-background-clip: border-box;
							background-clip: border-box;
							-webkit-background-size: auto auto;
							background-size: auto auto;','background-image: -webkit-linear-gradient(-248deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140,198,63,1) 46%, rgba(140,198,63,1) 48%, rgba(140,198,63,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%);
							background-image: -moz-linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140,198,63,1) 46%, rgba(140,198,63,1) 48%, rgba(140,198,63,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%);
							background-image: linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140,198,63,1) 46%, rgba(140,198,63,1) 48%, rgba(140,198,63,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%);
							background-position: 50% 50%;
							-webkit-background-origin: padding-box;
							background-origin: padding-box;
							-webkit-background-clip: border-box;
							background-clip: border-box;
							-webkit-background-size: auto auto;
							background-size: auto auto;','#ff0000');
							
							$sql_gar = "SELECT gar.gar_name, gar.gar_id,
							COUNT(gc.garc_id) AS total_eType
							FROM parn.dbo.garage AS gar 
							LEFT OUTER JOIN
							parn.dbo.garage_car AS gc 
							ON (gar.gar_id = gc.gar_id ) AND gc.garc_delete = 0 AND  (gc.garc_date = GETDATE()) 
							GROUP BY gar.gar_name, gar.gar_id
							ORDER BY gar.gar_id";
							
							$rs = $connect->execute($sql_gar);
							foreach ($rs as $row) {
								$gar_id = $row['gar_id'];
								$gar_name = $row['gar_name'];
								$count = $row['total_eType'];
								//กำหนดสีตัวอักษร
								if ($gar_id == 1 || $gar_id == 2 || $gar_id == 4 || $gar_id == 5 || $gar_id == 9|| $gar_id == 10) {
									echo '<li><span class="label" style="color:black; font-size:13px; background-color:' . $gar_color[$gar_id] . '">' . $gar_name . '</span><span id="span_garid_' . $gar_id . '"></span></li>';
									} else {
									echo '<li><span class="label" style="color:white; font-size:13px; background-color:' . $gar_color[$gar_id] . '">' . $gar_name . '</span><span id="span_garid_' . $gar_id . '"></span></li>';
								}
							}
							
							$sqlPicAnTotal = "SELECT COUNT(*) AS total_monti
							FROM   Personal2.dbo.Car AS c LEFT OUTER JOIN Personal2.dbo.CarWorkType AS cw ON c.CarWorkTypeSerial = cw.CarWorkTypeSerial
							WHERE (cw.CarWorkTypeSerial IN (1, 2, 3, 5, 6, 10, 11, 12)) AND (c.CarNo NOT LIKE N'%\_') AND (c.CarNo NOT LIKE N'z%') AND (c.CarNo NOT LIKE N'P0%') AND (c.CarNo NOT LIKE N'P1%') AND (c.CarNo NOT LIKE N'D210') AND (c.RegisterDate IS NOT NULL)";
							$rs_picAnTotal  = $connect->getrow($sqlPicAnTotal);
							$totalcar = $rs_picAnTotal['total_monti'];
							
							echo '<li><span class="label" style="color:white; font-size:13px; background-color:black; color:white;">ทั้งหมด</span>  จำนวน: '.$totalcar.' คัน</li>';
						?>
					</ul>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#causeModal" data-whatever="สาเหตุ: รถที่ไม่ถูกระบุ">สาเหตุ: รถที่ไม่ถูกระบุ</button>
						
					</div>
				</div>
			</div>
            <div class="container-fluid">
                <div class="navbar-header">
                    <button  class="navbar-toggle collapsed" data-target="#navbar_car_sum" data-toggle="collapse"  type="button"  aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
					</button>
				</div>
			</div>
		</nav><!--== Close Display total car of each garage bottom of page==-->
		
		<!--========= Form input cause ============-->    
		<div class="modal fade" id="causeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">สาเหตุ: รถที่ไม่ถูกระบุ
							
						</h4>
					</div>
					<form name="myForm">
						<div class="modal-body">
							<div id="cause-group" class="form-group">
								<textarea id="cause_info" name="cause_info" class="form-control"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-success" data-dismiss="modal" id="send_cause">Submit <span class="fa fa-arrow-right"></span></button>
						</div>
					</form>
				</div>
			</div>
		</div> <!--======== Close Form input cause========-->
		<p id="back-top">
			<a href="#top"><span></span></a>
		</p>
	</body>
    <!--============Bootstrap core JavaScript=============-->
    <!--===Placed at the end of the document so the pages load faster=== -->
	<script type="text/javascript" language="JavaScript">
		//=========== Default variable value ============= .
		$('#progressbar').hide();
		$('#progressbarmodal').hide();
		$('#progressmanageoriginal').hide();
        var datepick = ('<?= date('Y-m-d'); ?>');
		$(window).load(function(){
			$('#gar_printold').attr({'href': 'oriGarageReport.php?datepick=' + datepick});
			$('#gar_printnew').attr({'href': 'oriGarageReportCopy.php?datepick=' + datepick});
			gar_val('<?= date('Y-m-d'); ?>', 2);
			// gar_valShow('<?= date('Y-m-d'); ?>', 1);
			get_jsonCar('<?= date('Y-m-d'); ?>', 0);
			// maintain('<?= date('Y-m-d'); ?>');
			garSeven(datepick);
			carSale(datepick);
			$.ajax({
				url: "currentDefdata.php"
			})
			.success(function( data ){
				$('#viewInfoTable').html(data);
			});
			
		});
		var user_name = ('<? echo $user_name; ?>');
		var option = 0;
		var gar = 0;
		var check_blue = { "background-image": "-webkit-linear-gradient(-248deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(0,0,255,1) 46%, rgba(0,0,255,1) 48%, rgba(0,0,255,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "-moz-linear-gradient(180deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(0,0,255,1) 46%, rgba(0,0,255,1) 48%, rgba(0,0,255,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "linear-gradient(180deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(0,0,255,1) 46%, rgba(0,0,255,1) 48%, rgba(0,0,255,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-position": "50% 50%", "-webkit-background-origin": "padding-box", "background-origin": "padding-box", "-webkit-background-clip": "border-box", "background-clip": "border-box", "-webkit-background-size": "auto auto", "background-size": "auto auto"};
		var check_red = {
			"background-image": "-webkit-linear-gradient(-248deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "-moz-linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(255,0,0,1) 46%, rgba(255,0,0,1) 48%, rgba(255,0,0,1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-position": "50% 50%", "-webkit-background-origin": "padding-box", "background-origin": "padding-box", "-webkit-background-clip": "border-box", "background-clip": "border-box", "-webkit-background-size": "auto auto", "background-size": "auto auto"
		};
		var check_green = {
			"background-image": "-webkit-linear-gradient(-248deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140, 198, 63, 1) 46%, rgba(140, 198, 63, 1) 48%, rgba(140, 198, 63, 1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "-moz-linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140, 198, 63, 1) 46%, rgba(140, 198, 63, 1) 48%, rgba(140, 198, 63, 1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-image" : "linear-gradient(338deg, rgba(0,0,0,0) 0, rgba(0,0,0,0) 44%, rgba(140, 198, 63, 1) 46%, rgba(140, 198, 63, 1) 48%, rgba(140, 198, 63, 1) 50%, rgba(0,0,0,0) 53%, rgba(0,0,0,0) 100%)", "background-position": "50% 50%", "-webkit-background-origin": "padding-box", "background-origin": "padding-box", "-webkit-background-clip": "border-box", "background-clip": "border-box", "-webkit-background-size": "auto auto", "background-size": "auto auto"
		};
		var check6 = {
			"background-color": "#800080",
			"border": "1px solid #fff",
			"color": "#fff"
		};
		var check7 = {
			"background-color": "#B7B7B7",
			"border": "1px solid #fff",
			"color": "#fff"
		};
		var color = ['', "#FFFF99", "#FF9966", "#000099", "#FF9CD6", "#73D549", check6, check7, "#6495ED", check_red, check_green, ""];
		var coloropt = ['', check_red, check_green, ""];
		
		//============Start Jquery==============
        $(document).ready(function () {
			$('#selgar').hide();
			// ----------Reset form management garage
			$('#managedef').click(function(e){
				e.preventDefault();
				document.forms["oriGarform"].reset();
				// $("#carinfo").empty();
			});// ----------Close form management garage
			
			$('#updatecarinfo').click(function(e){
				$.ajax({
					beforeSend: function() { $('#progressbar').show(); },
					complete: function() { $('#progressbar').hide(); },
					type:"post",
					url:"ajaxInsertDelete.php",
					data: {act: 'updateDeftable'},
				});
				e.preventDefault();
			});
			
			// ----------Option Back to top
			$("#back-top").hide();
			$(function () {
				$(window).scroll(function () {
					if ($(this).scrollTop() > 100) {
						$('#back-top').fadeIn();
						} else {
						$('#back-top').fadeOut();
					}
				});
				// scroll body to 0px on click
				$('#back-top a').click(function () {
					$('body,html').animate({
						scrollTop: 0
					}, 800);
					return false;
				});
			});// ----------Close Option Back to top
			
			
			// ----------Option Tooltip
			$(function() {
				$( document ).tooltip({
					track: true
				});
			});// ----------Close Option Tooltip
			
			
			// ----------Option Submit by enter or click Car search
			$('#carnum').keypress(function(e){
				if(e.which == 13) {
					e.preventDefault();
					$("#carsearch").click();
				}
			});
			$('#carsearch').click(function(e){
				var s_carserial = $('#s_carserial').val();
				var carnum = $('#carnum').val();
				var cartype = $('#cartype').val();
				$('#garage').prop('selectedIndex',0);
				var act;
				if(carnum == ""){
					$('#carnum').focus();
					$('#carnum').attr('title', "กรอกเลขรถ");
				}
				$.ajax({
					beforeSend: function() { $('#progressbarmodal').show(); },
					complete: function() { $('#progressbarmodal').hide(); },
					type:"post",
					url:'ajaxInsertDelete.php',
					// cache: false,
					// dataType: json,
					data: {s_carserial: s_carserial, carnum: carnum, cartype: cartype, act: 'car_search'},
					success: function(data){
						// alert(data);
						$('#parntest').html(data);
						var count = $('#carinfo_table tr').length-1;
						$('#firstType').val(0);
						$('#firstGar').val(0);
						var test = $('#warning').val();
						// alert(count);
						if(count != 0 && test != ""){
							$('#selgar').show();
						}
					}
				});
				e.preventDefault(); //Pause event click of this button
			});// ----------Close Option Submit by enter or click Car search
			
			
			$(".btn-nav").click(function (e) {
                // $(".btn-nav").removeClass("active");
                console.log($(this).siblings());
                $(this).addClass("active").siblings().removeClass("active"); //Add แล้วลบเลย
                gar = $(this).val();
				var tmp = gar.split(',');
				gar = parseInt(tmp[0]);
				option = parseInt(tmp[1]);
				e.preventDefault(); // *** Pause event click of this button ***
			});
			
			// ========= Event ให้เลือกรถและระบุสีที่ต้องการ =========== //
            $('.parn').click(function (e) {
				if (gar == 0 && option == 0) {
                    alert("กรุณาเลือกอู่รถก่อนครับ/ ค่ะ");
					} else {
					// alert(gar);
					// alert(option);
					// if there is gar and check option
					// alert(datepick);
                    var tmp_html = $(this).html();
					var tmp = $(this).attr('id').split("_");
					var carSerial = parseInt(tmp[1]);
					var no_check = 0;
					var carType = $('#cType_'+carSerial).val();
					if ($(this).hasClass("active")) { //check class active if duplicate->replace
						$(this).css("background-color", 'color[gar]'); 	
						if(gar == 0){
							$('#car_' + carSerial).css(coloropt[option]);
						}
						}else {
						$(this).addClass("active"); //ให้ class active
						if(gar == 0){
							$('#car_' + carSerial).css(coloropt[option]);
						}
					}
					
                    if ($(this).hasClass("active") == true) {
                        $.ajax({
                            type: "post",
                            url: 'ajaxInsertDelete.php',
							data: {gar: gar, datepick: datepick, carSerial: carSerial, carType: carType, no_check: no_check, user_name:user_name , gar_option:option, action: 2},
                            success: function (data) {
                                gar_val($('#datepicker').val(), 2);
                                add_color(gar, carSerial);
								// option_color(option, carSerial);
								// delColor(gar, carSerial);
							}
						});
					}
				}
				e.preventDefault(); // *** Pause event click of this button ***
			});//!----close Click function
			
			
			// ----------Option Reset car search
			$('#resetform').click(function(e){
				$('#carinfo').empty();
				$('#selgar').hide();
				$('#carnum').empty();
				document.forms["oriGarform"].reset();
				e.preventDefault();
			});// ----------Close Option Reset car search
			
			
			/* =========Option Submit======== */
			$('#submitform').click(function(e){
				// document.forms["oriGarform"].reset();
				var data_save = $(this).serializeArray();
				var checkarray = [];
				var carmarked = $('#carmarked').val();
				var carno = $('#carno').val();
				var garage = parseInt($('#garage').val());
				var regdate = $('#regdate').val();
				var regno = $('#regno').val();
				var carbrand = $('#carbrand').val();
				var cartype = $('#cartype').val();
				var totalseat = $('#totalseat').val();
				var carworktype = $('#carworktype').val();
				var firstGar = $('#firstGar').val();
				var firstType = $('#firstType').val();
				var checkbox_value = "";
				var count = $('#carinfo_table tr').length-1;
				var origArray = [0,1,2,3,4,5];
				
				// alert(garage);
				
				$(":checkbox").each(function () {
					var ischecked = $(this).is(":checked");
					// }
					if (ischecked) {
						checkarray += $(this).val() + ",";
					}
				});
				if(garage == 0){alert("กรุณาเลือกอู่รถก่อนครับ/ค่ะ"); $('#firstGar').focus(); return false;}else if(firstGar == ""){alert("ไม่มีข้อมูลเพื่อบันทึกรายการ"); return false;}
				if(count == 0){alert("cant submit");return false;}
				if(checkarray == ""){alert("กรุณาเลือกรถที่ต้องการ");return false;}
				
				data_save.push({name: "act", value: "save_df"});
				data_save.push({name: "user_name", value: user_name});
				data_save.push({name: "garage", value: garage});
				
				data_save.push({name: "checkarray", value: checkarray});
				if (confirm("คุณต้องการบันทึกข้อมูล?")) {
					$.ajax({
						type: "post",
						url: 'ajaxInsertDelete.php',
						data: data_save,
						success: function (data) {
							currentdefFunction();
						},
						error: function () {
							console.log('กรุณาแจ้ง Programmer');
							return false;
						}
					});
				} 
				e.preventDefault();
			}); /* =========Close Option Submit======== */
			
			
			//=======Default variable value======
            var gar = 0;
            var gar_id = 0;
            var date, day, month, year;
			
			//======Cause button=====
            $("#send_cause").click(function (e) {
                var cause_data = $("#cause_info").val();
				// alert(cause_data);
				// return false;
                if (cause_data == null || cause_data == "") {
                    alert("กรุณาระบุสาเหตุ");
                    return false;
				}
				// alert(cause_data);
				$.ajax({
                    type: "post",
                    url: 'ajaxInsertDelete.php',
                    data: {datepick: datepick, cause_info: cause_data, action: 3},
                    success: function (data) {
                        // alert(data);
					}
				});
				e.preventDefault(); // *** Pause event click of this button ***
			}); //======Close Cause button=====
			
			
            //================= Form for info =============
			//=============Event Insert and Update Info =======
			//----------------------Event Select garage ------------------------------    
            
            //=============== Date for SUM Info =============  
            $('#datepicker_show').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                onSelect: function (dateText, inst) {
                    date = $(this).datepicker('getDate'),
					day = date.getDate(),
					month = date.getMonth() + 1,
					year = date.getFullYear();
                    datepick = (year + '-' + month + '-' + day);
                    gar_valShow(datepick, 1);
                    $('#gar_printnew').attr({'href': 'oriGarageReportCopy.php?datepick=' + datepick});
                    $('#gar_printold').attr({'href': 'oriGarageReport.php?datepick=' + datepick});
				}
			});
            $('#datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                onSelect: function (dateText, inst) {
                    date = $(this).datepicker('getDate'),
					day = date.getDate(),
					month = date.getMonth() + 1,
					year = date.getFullYear();
                    datepick = (year + '-' + month + '-' + day);
					$('#submitdf').val(datepick);
					$('#gar_printold').attr({'href': 'oriGarageReport.php?datepick=' + datepick});
                    $('#gar_printnew').attr({'href': 'oriGarageReportCopy.php?datepick=' + datepick});
                    gar_val(datepick, 2);
                    get_jsonCar(datepick, 0);
					garSeven(datepick);
					// maintain(datepick);
					carSale(datepick);
				}
			});
			
			/* =============dateforprint============ */
			$('#defprint').click(function(){
				dateforprint = $('#defprint').val();
				defprint(dateforprint);
			}); // Close dateforprint
			
		});//!----close jQuery function
        //วิธีการ Include ไฟล์ที่ต้องการใน Javacript
        /* <script language="javascript" type="text/javascript" src="ไฟล์ที่ต้องการ Include"> */
	</script>
</html>