

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
	
	// grab measurement data from server and create tables
	var table_bloodPressure = $('#bloodPressure_table').DataTable(tableOptions());
	
});

function tableOptions() {
	return {
		ajax: {
			url: '/na_lab5/measurements_get_bloodPressure',
			dataSrc: ''
		},
		columns: [
	        {
	        	data: 'systolicPressure',
	        	title: 'Systolic Pressure'
	        },
		    {
		    	data: 'diastolicPressure',
		    	title: 'Diastolic Pressure'
		    },
		    {
		    	data: 'date',
		    	title: 'Date'
	    	},
		    {
	    		data: 'time',
	    		title: 'Time'
			},
	    	{
	    		data: 'notes',
	    		title: 'Notes'
			}
		],
		scrollY: '35vh',
		scrollCollapse: true,
		paging: false,
		select: true,
		dom: 'Bft',
		buttons: {
			name: 'primary',
			buttons: ['columnsToggle']
		}
	
	};
}

function failedRetreivingChartData() {
	alert('Error retreiving blood pressure measurements json');
}

function createCharts(response) {
	var glucoseData = [];
	var systolicData = [];
	var diastolicData = [];
	var calorieData = [];
	var exDurationData = [];
	var exTypeData = [];
	var sleepData = [];
	var weightData = [];
	var meas;

	for (var i = 0; i < response.glucose.length; i++) {
		meas = response.glucose[i];
		glucoseData.push( [ Date.parse(meas.dateAndTime), meas.glucose ] );
	}
	createCharts_helper('glucose', 'Glucose', 'mg/dL', [glucoseData], ['glucose']);
	
	for (var i = 0; i < response.bloodPressure.length; i++) {
		meas = response.bloodPressure[i];
		systolicData.push(  [ Date.parse(meas.dateAndTime), meas.systolicPressure ] );
		diastolicData.push( [ Date.parse(meas.dateAndTime), meas.diastolicPressure ] );
	}
	createCharts_helper('bloodPressure', 'Blood Pressure', 'mm Hg', [systolicData, diastolicData], ['systolicPressure', 'diastolicPressure']);
	
	for (var i = 0; i < response.calories.length; i++) {
		meas = response.calories[i];
		calorieData.push( [ Date.parse(meas.dateAndTime), meas.calories ] );
	}
	createCharts_helper('calories', 'Calories', 'calories', [calorieData], ['calories']);
	
	for (var i = 0; i < response.exercise.length; i++) {
		meas = response.exercise[i];
		exDurationData.push( [ Date.parse(meas.dateAndTime), meas.duration ] );
		exTypeData.push( [ Date.parse(meas.dateAndTime), meas.type ] );
	}
	createCharts_helper('exercise', 'Exercise', 'minutes', [exDurationData], ['duration']);
	
	for (var i = 0; i < response.sleep.length; i++) {
		meas = response.sleep[i];
		sleepData.push( [ Date.parse(meas.dateAndTime), meas.duration ] );
	}
	createCharts_helper('sleep', 'Sleep', 'minutes', [sleepData], ['duration']);
	
	for (var i = 0; i < response.weight.length; i++) {
		meas = response.weight[i];
		weightData.push( [ Date.parse(meas.dateAndTime), meas.weight ] );
	}
	createCharts_helper('weight', 'Weight', 'kg', [weightData], ['weight']);
	
	// assign handlers for chart date range buttons 
	$('.btn-yearly').click(viewYearChart);
	$('.btn-monthly').click(viewMonthChart);
	$('.btn-weekly').click(viewWeekChart);
}

function createCharts_helper(measType, properType, units, data, name) {
	
	for (var i = 0; i < 2; i++) {
		
		var min =      (i == 0) ? lastWeek()  : lastMonth();
		var subtitle =    (i == 0) ? 'Past Week' : 'Past Month';
		var idSuffix = (i == 0) ? 'primary'   : 'secondary';
		var series = [];
		var numOfSeriesData = data.length;
		
		for (var j = 0; j < numOfSeriesData; j++) {
			series.push( {
				name: name[j],
				data: data[j]
			} );
		}
		
		$('#' + measType + '_chart_' + idSuffix).highcharts({
			chart: { type: 'line' },
			title: { text: properType },
			subtitle: { text: subtitle},
			xAxis: {
				type: 'datetime',
				min: min,
				max: todaysEnd()
			},
			yAxis: { title: { text: units } },
			series: series,
			tooltip: {
				formatter: function() {
					var date = new Date(this.x);
					var dateStr = date.toDateString();
					var timeStr = dateTo12HourLocalTimeString(date);
					
					var resultStr = '<span style="font-size: smaller;">' +dateStr+ '</span>';
					resultStr += '<br /><span style="font-size: smaller;">' +timeStr+ '</span>';
					resultStr += '<br /><span style="color: ' +this.series.color+ '">\u25CF</span> ' +this.series.name+ ': <strong>' +this.y+ '</strong>';

			        return resultStr;
				}
			}
		});
	}
}

// takes a Date object and returns a time string in a 12-hour format, e.g.: 2:06 pm (CST) 
function dateTo12HourLocalTimeString(date) {
	var pieces = date.toTimeString().split(' ');
	
	var timeZone = '(';
	$.each(pieces.slice(2), function(index, string) { timeZone += (index == 0) ? string[1] : string[0]; } );
	timeZone += ')';
	var time = pieces[0];
	var timePieces = time.split(':');
	var hour = timePieces[0];
	var minute = timePieces[1];
	var amOrPm = (hour >= 12) ? 'pm' : 'am';
	hour = hour % 12;
	
	return hour+ ':' +minute+ ' ' +amOrPm+ ' ' +timeZone
}

function todaysEnd() {
	var today = new Date();
	
	var endOfToday = Date.UTC(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59, 59);
	
	return endOfToday;
}

function lastWeek() {
	var today = new Date();
	
	var sixDaysAgoDate = new Date(today.valueOf() - (1000 * 60 * 60 * 24 * 6))
	var sixDaysAgoUTC = Date.UTC(sixDaysAgoDate.getFullYear(), sixDaysAgoDate.getMonth(), sixDaysAgoDate.getDate());
	
	return sixDaysAgoUTC;
}

function lastMonth() {
	var today = new Date();
	
	var thirtyDaysAgoDate = new Date(today.valueOf() - (1000 * 60 * 60 * 24 * 30));
	var thirtyDaysAgoUTC = Date.UTC(thirtyDaysAgoDate.getFullYear(), thirtyDaysAgoDate.getMonth(), thirtyDaysAgoDate.getDate());
	
	return thirtyDaysAgoUTC;
}

function lastYear() {
	var today = new Date();
	
	var threeFiftySixDaysAgoDate = new Date(today.valueOf() - (1000 * 60 * 60 * 24 * 356))
	var threeFiftySixDaysAgoUTC = Date.UTC(threeFiftySixDaysAgoDate.getFullYear(), threeFiftySixDaysAgoDate.getMonth(), threeFiftySixDaysAgoDate.getDate());
	
	return threeFiftySixDaysAgoUTC;
}

function viewYearChart() {
	// deactivate/activate associated buttons
	$(this).parent().parent().find('.active').removeClass('active');
	$(this).addClass('active');
	
	// update chart
	var chart = getChart($(this).attr('id'));
	chart.setTitle( { text: $(this).attr('name') }, { text: 'Past Year' } );
	chart.xAxis[0].setExtremes(lastYear(), todaysEnd());
}

function viewWeekChart() {
	// deactivate/activate associated buttons
	$(this).parent().parent().find('.active').removeClass('active');
	$(this).addClass('active');
	
	// update chart
	var chart = getChart($(this).attr('id'));
	chart.setTitle( { text: $(this).attr('name') }, { text: 'Past Week' } );
	chart.xAxis[0].setExtremes(lastWeek(), todaysEnd());
}

function viewMonthChart() {
	// deactivate/activate associated buttons
	$(this).parent().parent().find('.active').removeClass('active');
	$(this).addClass('active');
	
	// update chart
	var chart = getChart($(this).attr('id'));
	chart.setTitle( { text: $(this).attr('name') }, { text: 'Past Month' } );
	chart.xAxis[0].setExtremes(lastMonth(), todaysEnd());
}

// takes the id attribute of a chart, and returns the chart's javascript object
function getChart(chartID) {
	var index = -1;
	var idPieces = chartID.split('_');
	var measType = idPieces[0];
	var primaryOrSecondary = idPieces[4];
	
	switch (measType) {
		case 'glucose': index = 0; break;
		case 'bloodPressure': index = 2; break;
		case 'calories': index = 4; break;
		case 'exercise': index = 6; break;
		case 'sleep': index = 8; break;
		case 'weight': index = 10; break;
	}
	
	if (primaryOrSecondary == 'secondary')
		index++;
	
	return Highcharts.charts[index];
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


