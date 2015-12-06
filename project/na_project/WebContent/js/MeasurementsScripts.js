var charts = {
	glucose_primary: null,
	glucose_secondary: null,
	bloodPressure_primary: null,
	bloodPressure_secondary: null,
	calorie_primary: null,
	calorie_secondary: null,
	exercise_primary: null,
	exercise_secondary: null,
	sleep_primary: null,
	sleep_secondary: null,
	weight_primary: null,
	weight_secondary: null
}

var measurementParts = {
	glucose: ['glucose'],
	bloodPressure: ['systolicPressure', 'diastolicPressure'],
	calorie: ['calories'],
	exercise: ['duration'],
	sleep: ['duration'],
	weight: ['weight']
}

var units = {
	glucose: 'mg/dL',
	bloodPressure: 'mm Hg',
	calorie: 'calories',
	exercise: 'minutes',
	sleep: 'minutes',
	weight: 'kilograms'
}

var cumulativeMeasurements = ['calorie', 'exercise', 'sleep'];

var selectedRows = [];

$(document).ready(function() {
	// hide some content on load
	$('.add_measurement_section').hide();
	$('.edit_measurement_section').hide();
	
	// add listeners for page navigation buttons (i.e. for jumping to a measurement)
	$('#page-nav button').click(function(event) {
		window.location.assign('#' + $(this).attr('name'));
	});
	
	// add listener for submit and cancel buttons
	$('.add_measurement_section').submit(addMeasurement);
	$('.cancelMeasurement').click(cancelMeasurement);
	
	// grab measurement data from server and create tables
	var table_bloodPressure = $('#bloodPressure_table').DataTable(tableOptions('bloodPressure', [ ['systolicPressure', 'Systolic Pressure'], ['diastolicPressure', 'Diastolic Pressure'] ]));
	var table_glucose = $('#glucose_table').DataTable(tableOptions('glucose', [ ['glucose', 'Glucose (mg/dL)'] ]));
	var table_calories = $('#calorie_table').DataTable(tableOptions('calorie', [ ['calories', 'Calories'] ]));
	var table_execrise = $('#exercise_table').DataTable(tableOptions('exercise', [ ['duration', 'Exercise (min)'] ]));
	var table_sleep = $('#sleep_table').DataTable(tableOptions('sleep', [ ['duration', 'Sleep (min)', ] ]));
	var table_weight = $('#weight_table').DataTable(tableOptions('weight', [ ['weight', 'Weight (kg)'] ]));
	
	// create add/edit/delete measurement buttons
	table_bloodPressure.button(0, null).container().addClass('btn-group-justified');
	table_glucose.button(0, null).container().addClass('btn-group-justified');
	table_calories.button(0, null).container().addClass('btn-group-justified');
	table_execrise.button(0, null).container().addClass('btn-group-justified');
	table_sleep.button(0, null).container().addClass('btn-group-justified');
	table_weight.button(0, null).container().addClass('btn-group-justified');
	
	// add selection handlers for table rows
	table_bloodPressure.on('select', row_clicked);
	table_glucose.on('select', row_clicked);
	table_calories.on('select', row_clicked);
	table_execrise.on('select', row_clicked);
	table_sleep.on('select', row_clicked);
	table_weight.on('select', row_clicked);
	
	// grab measurement data from server and create charts
	createCharts('glucose');
	createCharts('bloodPressure');
	createCharts('calorie');
	createCharts('exercise');
	createCharts('sleep');
	createCharts('weight');
	
	// assign handlers for chart date range buttons 
	$('.btn-change-chart').click(viewNewChart);
});

function addMeasurement(event) {
	var measType = $(this).attr('id').split('_')[1];
	
	// collect data unique to each measurement
	var measData = {};
	for (var i = 0; i < measurementParts[measType].length; i++) {
		var partName = measurementParts[measType][i];
		measData[partName] = $('#' +partName+ '_' +measType+ '_add').val();
	}
	if (measType === 'exercise')
		measData.type = $('#type_exercise_add').val();
	
	// collect data common to each measurement
	measData.date = $('#date_' +measType+ '_add').val();
	measData.time = $('#time_' +measType+ '_add').val();
	measData.notes = $('#notes_' +measType+ '_add').val();
	measData.userName = $('#userName_' +measType+ '_add').val();
	measData.json = true;

	// send add request to server
	$.ajax({
		url: 'measurements_add_' +measType,
		data: measData,
		dataType: 'json',
		method: 'POST',
		success: function(response) {
			if (response.result) {
				// add row and highlight it for a few seconds
				var newRow = $('#' +measType+ '_table').DataTable().row.add(measData).draw();
				$(newRow.node()).addClass('success');
				setTimeout(function() { $(newRow.node()).removeClass('success'); }, 3000);
				
				// refresh charts
				$('#' +measType+ '_charts_primary_column .active').click();
				$('#' +measType+ '_charts_secondary_column .active').click();
				
			}
			else
				alert('add failed: ' +response.error);
		},
		error: function() { alert('error: check values and try again.'); }
	});
	
	event.preventDefault();
}

function row_clicked(e, dt, type, indexes) {
	if (type !== 'row')
		return;
	
	var measType = $(dt.table(0).node()).attr('id').split('_')[0];
	
	// store selected row data in global variable
	selectedRows = [];
	for (var i = 0; i < indexes.length; i++)
		selectedRows.push(dt.row(indexes[i]).data());
	
	// show edit/delete buttons
	dt.button($('#' +measType+ '_delete')).node().show();
	if (indexes.length > 1)
		dt.button($('#' +measType+ '_edit')).node().hide();
	else
		dt.button($('#' +measType+ '_edit')).node().show();
	
	// if edit form is visible, fill the edit form with data from the currently selected measurement
	if (indexes.length == 1 && $('#edit_' + measType + '_section').is(':visible')) {
		var row = selectedRows[0];
		for (var key in row)
			$('#' +key+ '_' +measType+ '_edit').val(row[key]);
		$('#oldDateTime_' + measType).val(row.date + ' ' + row.time);
	}
}

function tableOptions(measType, dataAndTitle) {
	// create columns array and all common columns
	var columns = [
        { data: 'date', title: 'Date' },
        { data: 'time', title: 'Time' },
	    { data: 'notes', title: 'Notes' }
    ];
	var orderIndex = (measType == 'bloodPressure') ? 2 : 1;

	// add remaining columns
	for (var i = dataAndTitle.length-1; i >= 0; i--)
		columns.unshift({ data: dataAndTitle[i][0], title: dataAndTitle[i][1] });
	
	return {
		ajax: { url: '/na_project/measurements_get_' +measType+ '_all' , dataSrc: '' },
		columns: columns,
		order: [[orderIndex, 'desc']],
		scrollY: '35vh',
		scrollCollapse: true,
		paging: false,
		select: true,
		dom: 'ftB',
		buttons: {
			name: 'add_edit_delete',
			buttons: [
				{
					name: measType+ '_add',
					text: 'Add',
					init: function (dt, node, config) {
						node.attr('id', measType+ '_add');
						node.prepend('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;');
					},
					action: function (e, dt, node, config) {
						showFormSection(measType, 'add', dt); // show add form
						if ($(window).height() < 400)
							window.location.assign('#add_' + measType + '_section');
					}
				},
	            {
					name: measType+ '_edit',
	            	extend: 'selectedSingle',
	            	text: 'Edit',
	            	init: function (dt, node, config) {
						node.hide().attr('id', measType+ '_edit');
						node.prepend('<span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;');
					},
	            	action: function (e, dt, node, config) {
	            		// fill the edit form with data from the currently selected measurement
	            		var row = selectedRows[0];
	            		for (var key in row)
	            			$('#' +key+ '_' +measType+ '_edit').val(row[key]);
	            		$('#oldDateTime_' + measType).val(row.date + ' ' + row.time);
	            		
	            		// show the edit form
	            		showFormSection(measType, 'edit', dt);
	            		if ($(window).height() < 400)
	            			window.location.assign('#edit_' + measType + '_section');
	            	}
	            },
	            {
	            	name: measType+ '_delete',
	            	extend: 'selected',
	            	text: 'Delete',
	            	init: function (dt, node, config) {
						node.hide().attr('id', measType+ '_delete').addClass('btn-danger');
						node.prepend('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;');
					},
	            	action: function (e, dt, node, config) {
	            		if (window.confirm('Are you sure you want to delete the selected measurement(s)?')) {
	            			// send delete request(s) to measurements controller
	            			for (var i = 0; i < selectedRows.length; i++) {
	            				window.location.assign(
            						'measurements_delete_' + measType +
            						'_' + selectedRows[i].date + ' ' + selectedRows[i].time
        						);
	            			}
	            		}
	            	}
	            }
            ]
		}
	};
}

/* creates a primary and secondary chart for the specified measurement type.
 * defaults to individual and monthly views respectively for primary and secondary charts, respectively */
function createCharts(measType) {
	var measNames = measurementParts[measType];
	
	// create two charts, a primary chart w/ individual view, and a secondary chart w/ monthly view
	for (var i = 0; i < 2; i++) {
		var per = (i == 0) ? 'individual' : 'month';
		
		$.ajax({
			'url': 'measurements_get_' +measType+ '_' +per,
			'dataType': 'json',
			'async': false,
			'success': function(response) {
				var data = [];
				var xValPropName; // the name of the property containing the x-value
				var idSuffix;
				var title;
				var subtitle;
				
				if (per === 'individual') {
					xValPropName = 'dateAndTime';
					idSuffix = 'primary';
					title = 'Individual Entries';
					subtitle = 'Over Past Month';
				} else if (per === 'month') {
					xValPropName = 'month';
					idSuffix = 'secondary';
					title = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Monthly Averages' : 'Monthly Totals';
					subtitle = 'Over Past Year';
				} else {
					alert('error');
				}
				
				// create a chart series for each measurement part (e.g. systolic/diastolic pressures)
				for (var j = 0; j < measNames.length; j++) {
					var currentData = [];
					for (var k = 0; k < response.length; k++)
						currentData.push( [ response[k][xValPropName], parseFloat(response[k][measNames[j]]) ] );
					data.push(currentData);
				}
				
				// create chart
				var chartOptions = createChartOptions(measType, title, data, measNames, per, subtitle, idSuffix);
				charts[measType+ '_' +idSuffix] = new Highcharts.Chart(chartOptions);
			},
			'error': function() { alert('Error retreiving measurements'); }
		});
	}
}

function createChartOptions(measType, title, data, name, per, subtitle, idSuffix) {
	var series = [];
	var numOfSeriesData = data.length;
	var xValue;
//	var minY = 100000;
//	var maxY = 0;
//	var formatStr;
	
	for (var j = 0; j < numOfSeriesData; j++) {
//		for (var k = 0; k < data[j].length; k++) {
//			if (data[j][k][1] < minY)
//				minY = data[j][k][1];
//			if (data[j][k][1] > maxY)
//				maxY = data[j][k][1];
//		}
//		if (data[j][1] < minY)
//			minY = data[j][1];
//		if (data[j][1] > maxY)
//			maxY = data[j][1];
		series.push( {
			name: name[j],
			data: data[j]
		} );
	}
//	Date.prototype.getWeek = function() {
//	    var onejan = new Date(this.getFullYear(),0,1);
//	    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
//	} 
//	var min;
//	var max;
//	var now = new Date();
//	var thirtyDaysAgo = new Date(now.valueOf() - (1000 * 60 * 60 * 24 * 30));
//	thirtyDaysAgo = thirtyDaysAgo.getFullYear()+ '-' +thirtyDaysAgo.getMonth()+ '-' +thirtyDaysAgo.getDate();
//	var fiftyTwoWeeksAgo = new Date(now.valueOf() - (1000 * 60 * 60 * 24 * 7 * 52));
//	fiftyTwoWeeksAgo = fiftyTwoWeeksAgo.getFullYear()+ '-' +fiftyTwoWeeksAgo.getWeek();
//	var twelveMonthsAgo = new Date(now.valueOf() - (1000 * 60 * 60 * 24 * 356));
//	twelveMonthsAgo = twelveMonthsAgo.getFullYear()+ '-' +twelveMonthsAgo.getMonth();
//	var fiveYearsAgo = new Date(now.valueOf() - (1000 * 60 * 60 * 24 * 356 * 5));
//	fiveYearsAgo = fiveYearsAgo.getFullYear();
//	switch (per) {
//		case 'individual': min = lastMonth(); max = todaysEnd();
//		case 'day': min = thirtyDaysAgo; max = now.getFullYear()+ '-' +now.getMonth()+ '-' +now.getDate();
//		case 'week': min = fiftyTwoWeeksAgo; max = now.getFullYear()+ '-' +now.getWeek();
//		case 'month': min = twelveMonthsAgo; max = now.getFullYear()+ '-' +now.getMonth();
//		case 'year': min = fiveYearsAgo; max = now.getFullYear();
//	}
	
	var dayValue = function(value) {
		var pieces = value.split('-');
		return monthNumToShortName(parseInt(pieces[1]), false) + ' ' + pieces[2];
	}
	
	var weekValue = function(value, isFirst) {
		var result = '';
		var pieces = value.split('-');
		if (isFirst || pieces[1] == 1)
			result += '(' +pieces[0]+ ') ';
		return result+ 'Week ' + (parseInt(pieces[1])+1);
	}
	
	var monthValue = function (value, isFirst) {
		var result = '';
		var pieces = value.split('-');
		if (isFirst || pieces[1] == 1)
			result += '(' +pieces[0]+ ') ';
		return result + monthNumToShortName(parseInt(pieces[1]), false);
	}
	
	var chartOptions =
		{
			chart: {
				renderTo: measType+ '_chart_' +idSuffix,
				type: 'line'
			},
			title: { text: title },
			subtitle: { text: subtitle },
			xAxis: {
				type: 'category',
//				min: min,
//				max: max,
				labels: {
					formatter: function() {
						switch (per) {
							case 'all':
							case 'individual':
								var date = new Date(this.value);
								return monthNumToShortName(date.getMonth(), true)+ ' ' +date.getDate();
							case 'day': // example result: Aug 17
								return dayValue(this.value);
							case 'week': // example results: Week 41  or  (2015) Week 1
								return weekValue(this.value, this.isFirst);
							case 'month': // example result: Sep  or  (2014) Nov
								return monthValue(this.value, this.isFirst);
							case 'year': // example result: 2015
								return this.value;
						}
					}
				}
			},
			yAxis: {
				title: { text: units[measType] }
//				min: minY,
//				max: maxY
			},
			series: series,
			tooltip: {
				formatter: function() {
					var resultStr;
					var firstLine;
					var secondLine = '';
					var firstLineHeader = '<span style="font-size: smaller;">';
					var firstLineBody;
					var firstLineFooter = '</span>';
					var secondLineHeader = '<br /><span style="font-size: smaller;">';
					var secondLineBody;
					var secondLineFooter = '</span>';
					if (per === 'all' || per === 'individual') {
						var date = new Date(this.key);
						firstLineBody = date.toDateString();
						secondLineBody = dateTo12HourLocalTimeString(date);
					} else if (per === 'day')
						firstLineBody = dayValue(this.key);
					else if (per === 'week')
						firstLineBody = weekValue(this.key, false);
					else if (per === 'month')
						firstLineBody = monthValue(this.key, false);
					else if (per === 'year')
						firstLineBody = this.key;
					
					firstLine = firstLineHeader + firstLineBody + firstLineFooter;
					if (per === 'all' || per === 'individual')
						secondLine += secondLineHeader + secondLineBody + secondLineFooter;
					
					resultStr = firstLine + secondLine;
					resultStr +=
						'<br /><span style="color: ' +this.series.color+ '">\u25CF</span> ' +
						this.series.name+ ': <strong>' +this.y+ ' ' +units[measType]+ '</strong>';
					
					if (measType === 'exercise' || measType === 'sleep')
						resultStr += ' (' +(this.y / 60).toFixed(2)+ ' hours)';

				    return resultStr;
				}
			}
		}
	
	return chartOptions;
}

function viewNewChart() {
	// deactivate/activate associated buttons
	$(this).parent().parent().find('.active').removeClass('active');
	$(this).addClass('active');
	
	// collect information on the chart being changed
	var id_pieces = $(this).attr('id').split('_');
	var measType = id_pieces[0];
	var chartType = id_pieces[1];
	var primOrSec = id_pieces[4];
	var measNames = measurementParts[measType];
	
	// grab new chart data from server
	$.ajax({
		'url': 'measurements_get_' +measType+ '_' +chartType,
		'dataType': 'json',
		'async': false,
		'success': function(response) {
			var data = [];
			var xValue;
			var title;
			var subtitle;
			
			switch (chartType) {
				case 'individual':
					xValue = 'dateAndTime';
					title = 'Individual Entries';
					subtitle = 'Over Past Month' ;
					break;
				case 'day':
					xValue = chartType;
					title = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Daily Averages' : 'Daily Totals';
					subtitle = 'Over Past Month';
					break;
				case 'week':
					xValue = chartType;
					title = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Weekly Averages' : 'Weekly Totals';
					subtitle = 'Over Past Year';
					break;
				case 'month':
					xValue = chartType;
					title = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Monthly Averages' : 'Monthly Totals';
					subtitle = 'Over Past Year';
					break;
				case 'year':
					xValue = chartType;
					title = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Yearly Averages' : 'Yearly Totals';
					subtitle = 'Over Past 5 Years';
					break;
				default:
					title = '';
					subtitle = '';
					xValue = chartType
			}
			
			// create a chart series for each measurement part (e.g. systolic/diastolic pressures)
			for (var i = 0; i < measNames.length; i++) {
				var currentData = [];
				for (var j = 0; j < response.length; j++)
					currentData.push( [ response[j][xValue], parseFloat(response[j][measNames[i]]) ] );
				data.push(currentData);
			}
			
			// replace chart
			var chartOptions = createChartOptions(measType, title, data, measNames, chartType, subtitle, primOrSec);
			charts[measType+ '_' +primOrSec].destroy();
			charts[measType+ '_' +primOrSec] = new Highcharts.Chart(chartOptions);
		},
		'error': function() { alert('Error retreiving measurements'); }
	});
}

function monthNumToShortName(num, isZeroBased) {
	if (isZeroBased)
		num++;
	switch (num) {
		case 1: return 'Jan';
		case 2: return 'Feb';
		case 3: return 'Mar';
		case 4: return 'Apr';
		case 5: return 'May';
		case 6: return 'Jun';
		case 7: return 'Jul';
		case 8: return 'Aug';
		case 9: return 'Sep';
		case 10: return 'Oct';
		case 11: return 'Nov';
		case 12: return 'Dec';
		default: return 'error';
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

// a cancel button for an add or edit form was clicked
function cancelMeasurement() {
	// determine which measurement and which form (add/edit) to hide
	var btnID_pieces = $(this).attr('id').split('_');
	var form_type = btnID_pieces[1];
	var meas_type = btnID_pieces[2];
	
	// hide form and jump to the associated measurements table
	hideFormSection(meas_type, form_type);
}

function hideFormSection(meas_type, form_type) {
	
	// hide form
	$('#' + form_type + '_' + meas_type + '_section').hide();
	$('#view_' + meas_type + '_section').removeClass('col-sm-8');
	$('#view_' + meas_type + '_section').addClass('col-sm-12');
	
	// deactivate button
	$('#' + meas_type + '_' + form_type).removeClass('active');
}

//show the specified measurement form section for the associated measurement type and jump to it
function showFormSection(meas_type, form_type, dt) {
	
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
	$('#' +meas_type+ '_' +form_type).addClass('active');
}


