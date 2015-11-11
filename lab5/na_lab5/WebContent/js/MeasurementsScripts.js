(function() {
	
$(document).ready(function() {
	var allMeasurements = { };
	var systolicData = [null, null, null, null, null, null, null];
	var diastolicData = [null, null, null, null, null, null, null];
	
	// calculate date stuff for chart
	var dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'];
	var today = new Date();
	var dayOfWeekNum = today.getDay();
	var dayOfWeek = dayNames[dayOfWeekNum];
	var date = today.getDate();
	var month = today.getMonth()+1;
	
	// hide certain content on load
	$('.add_measurement_section').hide();
	$('.edit_measurement_section').hide();
	$('.editMeasurement').parent().hide();
	$('.deleteMeasurement').parent().hide();
	
	// add listeners for page navigation buttons (i.e. for jumping to a measurement)
	$('#page-nav button').click(function(event) {
		window.location.assign('#' + $(this).attr('name'));
	});
	$('body').click(deselectAllRows);
	
	// add listener for add/edit/delete measurement and cancel buttons
	$('.addMeasurement').click(addMeasurement);
	$('.editMeasurement').click(editMeasurement);
	$('.deleteMeasurement').click(deleteMeasurement);
	$('.cancelMeasurement').click(cancelMeasurement);
	
	// add hover and click listeners for measurement rows
	$('tbody > tr.measurementRow').each(function(index, element) {
		$(element).hover(
			function() { $(element).addClass('rowHover');    },
			function() { $(element).removeClass('rowHover'); }
		);
		$(element).click(rowClicked);
	});
	
	// grab measurement data from server
	$.ajax({
		'url': 'measurements_get_bloodPressure',
		'dataType': 'json',
		'success': function(response) {
//			var str = "object returned:\n";
//			for (var i = 0; i < response.length; i++) {
//				str += "\tsystolicPressure: " + response[i].systolicPressure + "\n";
//				str += "\tdiastolicPressure: " + response[i].diastolicPressure + "\n";
//				str += "\tdateAndTime: " + response[i].dateAndTime + "\n";
//				str += "\tnotes: " + response[i].notes + "\n";
//				str += "\tuserName: " + response[i].userName + "\n";
//			}
//			alert(str);
			allMeasurements.bloodPressure = response;
			
			// for each date on the x-axis of our chart, find existing measurements
			for (var i = 0; i < 7; i++) {
//				data[i] =
				// for the current x-axis date, search for existing measurements on that date
				for (var j = 0; j < allMeasurements.bloodPressure.length; j++) {
					var measurement = allMeasurements.bloodPressure[j];
					
					if (month == measurement.month && date-(6-i) == measurement.date) {
						systolicData[i] = measurement.systolicPressure;
						diastolicData[i] = measurement.diastolicPressure;
//						var str = 'Measurement found for ' + dayNames[(dayOfWeekNum+(1+i))%7] + ':\n';
//						str += '\tsystolicPressure: ' + systolicData[i] + '\n';
//						str += '\tdiastolicPressure: ' + diastolicData[i] + '\n';
//						alert(str);
					} else {
//						var str = 'measurement was not a match:\n';
//						str += '\tsystolicPressure: ' + measurement.systolicPressure + '\n';
//						str += '\tdiastolicPressure: ' + measurement.diastolicPressure + '\n';
					}
				}
			}
			
			// create blood pressure measurement charts
			$('#charts_bloodPressure').highcharts({
				chart: { type: 'bar' },
				title: { text: 'Past Week' },
				xAxis: { categories: [
		                dayNames[(dayOfWeekNum+1)%7] + ' ' + month + '/' + (date-6),
		                dayNames[(dayOfWeekNum+2)%7] + ' ' + month + '/' + (date-5),
		                dayNames[(dayOfWeekNum+3)%7] + ' ' + month + '/' + (date-4),
		                dayNames[(dayOfWeekNum+4)%7] + ' ' + month + '/' + (date-3),
		                dayNames[(dayOfWeekNum+5)%7] + ' ' + month + '/' + (date-2),
		                dayNames[(dayOfWeekNum+6)%7] + ' ' + month + '/' + (date-1),
		                dayNames[(dayOfWeekNum+7)%7] + ' ' + month + '/' + date
		            ] },
				yAxis: { title: { text: 'mm HG' } },
				series: [
				    {
				    	name: 'Systolic Pressure',
				    	data: systolicData
				    },
				    {
				    	name: 'Diastolic Pressure',
				    	data: diastolicData
				    }
		        ]
			});
		},
		'error': function() {
			alert('Error retreiving blood pressure measurements json');
		}
	});
	
	
	
	
	
	
	
	
	
});

// an add measurement button was clicked
function addMeasurement(event) {
	event.stopPropagation();
	
	// show the add measurement section for the associated measurement type and jump to it
	var btnID_pieces = $(this).attr('id').split('_');
	var form_type = btnID_pieces[0];
	var meas_type = btnID_pieces[1];
	showFormSection(meas_type, form_type);	
	window.location.assign('#add_' + meas_type + '_section');
}

// an edit measurement button was clicked
function editMeasurement(event) {
	event.stopPropagation();
	
	// fill the edit form with data from the currently selected measurement, then show it and jump to it
	var meas_type = fillEditForm();
	showFormSection(meas_type, 'edit');
	window.location.assign('#edit_' + meas_type + '_section');
}

// a delete measurement button was clicked
function deleteMeasurement(event) {
	if (window.confirm('Are you sure you want to delete the selected measurement?')) {
		event.stopPropagation();
		
		// determine the measurement to delete
		var id = $('tbody > tr.rowSelected').attr('id');
		var measurementPieces = id.split('_');
		var measurementType = measurementPieces[0];
		var measurementDate = measurementPieces[1];
		var measurementTime = measurementPieces[2];
		
		// send delete request to measurements controller
		window.location.assign('measurements_delete_' + measurementType + '_' + measurementDate + ' ' + measurementTime);
	}
}

// a cancel button for an add or edit form was clicked
function cancelMeasurement(event) {
	event.stopPropagation();
	
	// determine which measurement and which form (add/edit) to hide
	var btnID_pieces = $(this).attr('id').split('_');
	var form_type = btnID_pieces[1];
	var meas_type = btnID_pieces[2];
	
	// hide form and jump to the associated measurements table
	hideFormSection(meas_type, form_type);
	window.location.assign('#view_' + meas_type + '_section');
}

// selects a row when clicked, and fills in the edit form if it is visible
function rowClicked(event) {
	event.stopPropagation();
	var meas_type = $(this).attr('id').split('_')[0];
	deselectAllRows();
	selectRow(this);
	if ($('#edit_' + meas_type + '_section').is(':visible'))
		fillEditForm();
}

function hideFormSection(meas_type, form_type) {
	
	// hide form
	$('#' + form_type + '_' + meas_type + '_section').hide();
	$('#view_' + meas_type + '_section').removeClass('col-sm-8');
	$('#view_' + meas_type + '_section').addClass('col-sm-12');
	
	// deactivate button
	$('#' + form_type + '_' + meas_type + '_btn').removeClass('active');
}

function showFormSection(meas_type, form_type) {
	
	// hide other section if it is visible, and deactivate its corresponding button
	if (form_type == 'add' && $('#edit_' + meas_type + '_section').is(':visible'))
		hideFormSection(meas_type, 'edit');
	else if (form_type == 'edit' && $('#add_' + meas_type + '_section').is(':visible'))
		hideFormSection(meas_type, 'add');
	
	// show form
	$('#' + form_type + '_' + meas_type + '_section').show();
	$('#view_' + meas_type + '_section').removeClass('col-sm-12');
	$('#view_' + meas_type + '_section').addClass('col-sm-8');
	
	// activate button
	$('#' + form_type + '_' + meas_type + '_btn').addClass('active');
}

// collects data from currently selected row, and fills in its corresponding edit form
// returns the type of measurement of the currently selected row
function fillEditForm() {
	
	// collect data from selected row
	var selected_row = $('tbody > tr.rowSelected');
	var rowID_pieces = selected_row.attr('id').split('_');
	var meas_type = rowID_pieces[0];
	var date = rowID_pieces[1];
	var time = rowID_pieces[2];
	
	// fill in edit form fields
	selected_row.children().each(function(index, element) {
		var col_name = $(element).attr('id').split('_')[0];
		$('#' + col_name + '_' + meas_type + '_edit').val($(element).text());
	});
	$('#oldDateTime_' + meas_type).val(date + ' ' + time);
	
	return meas_type;
}

function selectRow(row) {
	
	// show edit/delete buttons
	var section = $(row).attr('id').split('_')[0];
	$('#edit_' + section + '_btn').parent().show();
	$('#delete_' + section + '_btn').parent().show();
	
	// select row
	$(row).addClass('rowSelected');
}

function deselectAllRows() {
	$('tbody > tr.measurementRow').each(function(index, element) {
		
		// hide edit/delete buttons
		var section = $(element).attr('id').split('_')[0];
		$('#edit_' + section + '_btn').parent().hide();
		$('#delete_' + section + '_btn').parent().hide();
		
		// deselect rows
		$(element).removeClass('rowSelected');
	});
}


})();