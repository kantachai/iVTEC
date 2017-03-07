function defaultgar(){
	datedf = $('#submitdf').val();
	if(!datedf){
		var s = new Date();
		var d = s.getDate();
		var m = s.getMonth()+1;
		var y = s.getFullYear();
		datedf = y+'-'+m+'-'+d;
	}
	 var arrgar = new Array();
	$('input[name="check"]:checked').each(function() {
		parn = (this.value);
		arrgar.push(parn);
		// checkgar(datedf);
	});	
	// console.log(arrgar);
		$.ajax({
			beforeSend: function() { $('#progressbar').show(); },
			complete: function() { $('#progressbar').hide(); },
			type: "post",
			dataType: "json",
			url: 'ajaxInsertDelete.php',
			data: {datepick:datedf, garage: arrgar, action: "makedefault"},
			success: function (data) {
				$.each(arrgar, function(index, value){
					// console.log(value);
					if(value == 9){
						maintain(datedf);
					}else if(value == 10){
						carSale(datedf);
					}
				})
				$.each(data, function (index, value) {
					$('#car_'+index).addClass("active"); // อู่ / เบอร์รถ(serial)	
					add_color(value,index);
					// option_color(value,index);
				});
			}
		}); 
	// console.log(arrgar);
}

function defprint(date){
	// alert(date);
	$('#gar_printold').attr({'href': 'oriGarageReport.php?datepick=' + date});
}

// ======= Function เรียกข้อมูล สาเหตุ และ จำนวนรถมาแสดง ========//
// ================= Action 2 ===========//
function gar_val(date, act){
	$.ajax({
		type: "post",
		url: 'viewGarage.php',
		dataType: "json",
		data: {datepick: date, action: act},
		success: function (data) {
			// $('#car_sum').html(data); 
			// alert(data);
			$.each(data, function (index, value) {
				if (index == 'cause_uncheck') {
					$("#cause_info").val(value);
				}
				$('#span_garid_' + index).text('  จำนวน : ' + value + ' คัน');
				// console.log(value);
			});
		}
	});
	// alert(date);
} // Close function gar_val

// ======== Function เรียกข้อมูล สาเหตุ และ จำนวนรถมาแสดง ===========//
function gar_valShow(date, act) {
	$.ajax({
		type: "post",
		url: 'viewGarage.php',
		data: {datepick: date, action: act},
		success: function (data){
			$('#gSum').html(data);
		}
	});
}  // Close function gar_valShow

// ======== Function get currentdefData to table =======//
function currentdefFunction(){
	$.ajax({
		url: "currentDefdata.php"
	})
	.success(function( data ) {
		$('#viewInfoTable').html(data);
	}); 
}  // Close function currentdefData

/* ===  Function ระบุสีอู่ให้แต่ละคันที่เลือกแล้ว และ จำนวนรถมาแสดง ==Action 0==*/
function get_jsonCar(date, act) {
	$.ajax({
		beforeSend: function() { $('#progressbar').show(); },
		complete: function() { $('#progressbar').hide(); },
		type: "post",
		url: 'viewGarage.php',
		dataType: "json",
		data: {datepick: date, action: act},
		success: function (data) {
			$('.parn').removeClass('active').removeAttr('style');
			$.each(data, function (index, value) {
				add_color(value,index);
			});
		}
	});
}// Close function get_jsonCar

// ============ Function ลงสี อู่ศรีราชา ====
function garSeven(date){
	//Garage 7/0 and option 2/0 default marked
	$.ajax({
		type: "post",
		url: 'viewGarage.php',
		dataType: "json",
		data: {datepick:date, action: 77},
		success: function (data) {
			// carSale();
			$.each(data, function (index, value) {
				$('#car_'+index).addClass("active"); // อู่ / เบอร์รถ(serial)	
				add_color(value,index);
			});
			maintain(date);
		}
	});
}// Close function get_jsonCar

// ============  Function ลงสี รถขาย ====
function carSale(date) {
	// alert(date);
	$.ajax({
		type: "post",
		url: 'viewGarage.php',
		dataType: "json",
		data: {datepick: date, action: 10},
		success: function (data) {
			$.each(data, function (index, value) {
				$('#car_'+index).addClass("active");
				option_color(value,index);
				// console.log(value);
			});
		}
	});
}// Close function get_jsonCar

/* ===== Action 100 ระบุรถค้างซ่อม */
function maintain(date){
	$.ajax({
		type: "post",
		url: 'viewGarage.php',
		dataType: "json",
		data: {datepick: date, action: 100},
		success: function (data){
			$.each(data, function (index, value) {
				// $('#car_'+index).addClass("active");
				option_color(value,index);
				// console.log(value);
			});
			// garSeven();
		}
	});
} // Close function get_option

function option_color(garOpt, carIndex){
	if(garOpt == 1){
		$('#car_' + carIndex).css(color[9]);
	}
	if(garOpt == 2){
		$('#car_' + carIndex).css(color[10]);
	}
} // Close function option_color
/* ============Function selected car */
function add_color(gar, car) {
	if(gar == 1){
		$('#car_' + car).css("background-color", color[gar]);
		}else if(gar == 2){
		$('#car_' + car).css("background-color", color[gar]).css("color", '#fff');
		}else if(gar == 3){
		$('#car_' + car).css("background-color", color[gar]).css("color", '#fff');
		}else if(gar == 4){
		$('#car_' + car).css("background-color", color[gar]).css("color", '#fff');
		}else if(gar == 5){
		$('#car_' + car).css("background-color", color[gar]).css("color", '#fff');
		}else if(gar == 6){
		$('#car_' + car).css(color[gar]);
		}else if(gar == 7){
		$('#car_' + car).css(color[gar]);
		}else if(gar == 8){
		$('#car_' + car).css("background-color", color[gar]).css("color", '#fff');
		}else if(gar == 11){
		$('#car_' + car).removeClass('active').removeAttr('style');
		}
}  //Close function add_color
//วิธีการ Include ไฟล์ที่ต้องการใน Javacript
/* <script language="javascript" type="text/javascript" src="ไฟล์ที่ต้องการ Include"> */	