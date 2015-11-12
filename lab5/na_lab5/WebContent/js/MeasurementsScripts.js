(function() {
	
$(document).ready(function() {
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
	
	// grab measurement data from server and create charts
	$.ajax({
		'url': 'measurements_get_all',
		'dataType': 'json',
		'success': createCharts,
		'error': failedRetreivingChartData
	});
	
});

function failedRetreivingChartData() {
	alert('Error retreiving blood pressure measurements json');
}

function createCharts(response) {
	var allMeasurements = { };
	var systolicData = [];
	var diastolicData = [];
	var meas;
	
	var glucoseMeasurements = response.glucose;

	// add each measurement to data arrays for inputting into highcharts
	for (var i = 0; i < bpMeasurements.length; i++) {
		meas = bpMeasurements[i];
		systolicData.push(  [ Date.parse(meas.dateAndTime), meas.systolicPressure ] );
		diastolicData.push( [ Date.parse(meas.dateAndTime), meas.diastolicPressure ] );
	}
	
	// create blood pressure measurement charts
	$('#charts_bloodPressure').highcharts({
		chart: { type: 'line' },
		title: { text: 'Past Week' },
		xAxis: { type: 'datetime' },
		yAxis: { title: { text: 'mm HG' } },
		series: [
            { name: 'Systolic Pressure', data: systolicData },
		    { name: 'Diastolic Pressure', data: diastolicData }
        ]
	});
	
	// assign handlers for chart date range buttons 
	$('#yearly_chart_btn').click(viewYearChart);
	$('#monthly_chart_btn').click(viewMonthChart);
	$('#weekly_chart_btn').click(viewWeekChart);
}

function viewYearChart() {
	var myChart = Highcharts.charts[0];
	myChart.xAxis[0].setExtremes(Date.UTC(2014, 10, 12), Date.UTC(2015, 10, 11, 23, 59, 59));
}

function viewWeekChart() {
	var myChart = Highcharts.charts[0];
	myChart.xAxis[0].setExtremes(Date.UTC(2015, 10, 5), Date.UTC(2015, 10, 11, 23, 59, 59));
}

function viewMonthChart() {
	var myChart = Highcharts.charts[0];
	myChart.xAxis[0].setExtremes(Date.UTC(2015, 9, 12), Date.UTC(2015, 10, 11));
}

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