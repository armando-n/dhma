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
	
	// add listener for submit and cancel buttons
	$('.add_measurement_section').submit(addMeasurement);
	$('.edit_measurement_section').submit(editMeasurement);
	$('.cancelMeasurement').click(cancelMeasurement);
	
	// add date/time pickers for add/edit forms
	$('.date-picker').datetimepicker( {
		format: 'YYYY-MM-DD',
		defaultDate: Date.now(),
		showTodayButton: true
	} );
	$('.time-picker').datetimepicker( {
		format: 'hh:mm a',
		defaultDate: Date.now()
	} );
	
	// grab measurement data from server and create tables
	var table_bloodPressure = $('#bloodPressure_table').DataTable(tableOptions('bloodPressure', [ ['systolicPressure', 'Systolic Pressure'], ['diastolicPressure', 'Diastolic Pressure'] ]));
	var table_glucose = $('#glucose_table').DataTable(tableOptions('glucose', [ ['glucose', 'Glucose (mg/dL)'] ]));
	var table_calories = $('#calorie_table').DataTable(tableOptions('calorie', [ ['calories', 'Calories'] ]));
	var table_execrise = $('#exercise_table').DataTable(tableOptions('exercise', [ ['duration', 'Exercise (min)'] ]));
	var table_sleep = $('#sleep_table').DataTable(tableOptions('sleep', [ ['duration', 'Sleep (min)', ] ]));
	var table_weight = $('#weight_table').DataTable(tableOptions('weight', [ ['weight', 'Weight (kg)'] ]));
	
	// make add/edit/delete buttons justified
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
	
	// add deselection handlers for table rows to hide edit and delete buttons
	var row_deselected = function(e, dt, type, indexes) {
		var measType = $(this).attr('id').split('_')[0];
		dt.button($('#' +measType+ '_edit')).node().hide();
		dt.button($('#' +measType+ '_delete')).node().hide();
	};
	table_bloodPressure.on('deselect', row_deselected);
	table_glucose.on('deselect', row_deselected);
	table_calories.on('deselect', row_deselected);
	table_execrise.on('deselect', row_deselected);
	table_sleep.on('deselect', row_deselected);
	table_weight.on('deselect', row_deselected);
	
	// grab measurement data from server and create charts
	createCharts('glucose');
	createCharts('bloodPressure');
	createCharts('calorie');
	createCharts('exercise');
	createCharts('sleep');
	createCharts('weight');
	
	// assign handlers for chart date range buttons 
	$('.btn-change-chart').click(viewNewChart);
	
	// add listeners for tab buttons (i.e. for switching to a measurement)
	$('#measurements_tabs a, #measurements_dropdown li a').click(function (event) {
		var measType = $(this).attr('id').split('_')[0];
		var upperMeasType = measType.replace(/([a-z])([A-Z])/g, function(match, p1, p2) { return [p1, p2].join(' '); } );
		upperMeasType = upperMeasType.replace(/^[a-z]/, function (match) { return match.toUpperCase(); } );

		// show tab, change dropdown label, and deselect menu item
		$(this).tab('show');
		$('#measurements_dropdown_label').text(upperMeasType);
		$('#measurements_dropdown li').removeClass('active');
		
		if (measType === 'calories')
			measType = 'calorie';
		
		// update tabs appearance (in case dropdown triggered this event)
		$('#measurements_tabs .active').removeClass('active');
		$('#' +measType+ '_tab_btn').parent().addClass('active');
		
		// redraw charts and table to avoid overflow and column alignment issues
		charts[measType+ '_primary'].reflow();
		charts[measType+ '_secondary'].reflow();
		$('#' +measType+ '_table').DataTable().draw();
		
		event.preventDefault();
	});
	
	// add change listeners for forms
	var doneToCancel = function() {
		var id_pieces = $(this).attr('id').split('_');
		var measType = id_pieces[1];
		var addOrEdit = id_pieces[2];
		
		if ($('#cancel_' +addOrEdit+ '_' +measType+ '_text').text() === 'Done')
			$('#cancel_' +addOrEdit+ '_' +measType+ '_text').text('Cancel');
	};
	$('.add_measurement_section .form-control').change(doneToCancel);
	$('.edit_measurement_section .form-control').change(doneToCancel);
	
});

function editMeasurement(event) {
	var measType = $(this).attr('id').split('_')[1];
	
	// collect data unique to each measurement
	var measData = {};
	for (var i = 0; i < measurementParts[measType].length; i++) {
		var partName = measurementParts[measType][i];
		measData[partName] = $('#' +partName+ '_' +measType+ '_edit').val().trim();
	}
	if (measType === 'exercise')
		measData.type = $('#type_exercise_edit').val().trim();
	
	// collect data common to each measurement
	measData.date = $('#date_' +measType+ '_edit').val().trim();
	measData.time = TwelveHourTimeStringTo24HourTimeString($('#time_' +measType+ '_edit').val().trim());
	measData.notes = $('#notes_' +measType+ '_edit').val().trim();
	measData.userName = $('#userName_' +measType+ '_add').val().trim();
	measData.oldDateTime = $('#oldDateTime_' +measType).val().trim();
	measData.json = true;

	// send add request to server
	$.ajax({
		url: 'measurements_edit_post_' +measType,
		data: measData,
		dataType: 'json',
		method: 'POST',
		success: function(response) {
			if (response.result) {
				
				// edit row and highlight it for a few seconds
				var targetRow = selectedRows[0]; 
				targetRow.data(measData);
				$(targetRow.node()).addClass('success black-text');
				setTimeout(function() { $(targetRow.node()).removeClass('success black-text'); }, 3000);
				
				// update old date time (it may have been changed)
				$('#oldDateTime_' +measType).val(measData.date+ ' ' +measData.time);
				
				// refresh charts
				$('#' +measType+ '_charts_primary_column .active').click();
				$('#' +measType+ '_charts_secondary_column .active').click();
				
				// change Cancel button to Done button
				$('#cancel_edit_' +measType+ '_text').text('Done');
				
				// put focus in first field of form
				$('#' +measurementParts[measType][0]+ '_' +measType+ '_edit').focus();
			}
			else
				alert('edit failed: check input for errors and try again.');
		},
		error: function() { alert('error: check values and try again.'); }
	});
	
	event.preventDefault();
}

function deleteMeasurement(e, dt, node, config) {
	if (window.confirm('Are you sure you want to delete the selected measurement(s)?')) {
		var measType = node.attr('id').split('_')[0];
		
		// send delete request to measurements controller
		$.ajax( {
			url: 'measurements_delete_' +measType+ '_' +
				selectedRows[0].data().date + ' '+
				selectedRows[0].data().time.replace(':', '-'),
			data: { json: true },
			dataType: 'json',
			method: 'POST',
			success: function(response) {
				if (response.result) {
					var targetRows = selectedRows.slice();
					selectedRows = [];
					
					// highlight rows for a few seconds and remove them
					$.each(targetRows, function (index, row) {
						row.deselect();
						$(row.node()).addClass('deletedRow');
					} );
					setTimeout(function() {
						$.each(targetRows, function (index, row) { row.remove(); } );
						dt.draw();
						if (selectedRows.length == 0) {
							// hide edit and delete buttons
							dt.button($('#' +measType+ '_edit')).node().hide();
							dt.button($('#' +measType+ '_delete')).node().hide();
						}
					}, 100);
					
					// refresh charts
					$('#' +measType+ '_charts_primary_column .active').click();
					$('#' +measType+ '_charts_secondary_column .active').click();
				}
				else
					alert('delete failed: ' +response.error);
			},
			error: function() { alert('error'); }
		} );
	}
}

function addMeasurement(event) {
	var measType = $(this).attr('id').split('_')[1];
	
	// collect data unique to each measurement
	var measData = {};
	for (var i = 0; i < measurementParts[measType].length; i++) {
		var partName = measurementParts[measType][i];
		measData[partName] = $('#' +partName+ '_' +measType+ '_add').val().trim();
	}
	if (measType === 'exercise')
		measData.type = $('#type_exercise_add').val().trim();
	
	// collect data common to each measurement
	measData.date = $('#date_' +measType+ '_add').val().trim();
	measData.time = TwelveHourTimeStringTo24HourTimeString($('#time_' +measType+ '_add').val().trim());
	measData.notes = $('#notes_' +measType+ '_add').val().trim();
	measData.userName = $('#userName_' +measType+ '_add').val().trim();
	measData.json = true;

	// send add request to server and process response
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
				
				// change Cancel button to Done button
				$('#cancel_add_' +measType+ '_text').text('Done');
				
				// clear and put focus in first field of form
				$('#' +measurementParts[measType][0]+ '_' +measType+ '_add').val('').focus();
			}
			else
				alert('add failed: check input for errors and try again: ' +response.error);
		},
		error: function() { alert('error: check values and try again.'); }
	});
	
	event.preventDefault();
}

function row_clicked(e, dt, type, indexes) {
	if (type !== 'row')
		return;
	
	var measType = $(dt.table(0).node()).attr('id').split('_')[0];
	
	// store selected row (DataTables API object) in global variable
	selectedRows = [];
	for (var i = 0; i < indexes.length; i++)
		selectedRows.push(dt.row(indexes[i]));
	
	// show edit/delete buttons
	dt.button($('#' +measType+ '_delete')).node().show();
	if (indexes.length > 1)
		dt.button($('#' +measType+ '_edit')).node().hide();
	else
		dt.button($('#' +measType+ '_edit')).node().show();
	
	// if edit form is visible, fill the edit form with data from the currently selected measurement
	if (indexes.length == 1 && $('#edit_' +measType+ '_section').is(':visible')) {
		var row = selectedRows[0].data();
		for (var key in row)
			$('#' +key+ '_' +measType+ '_edit').val(row[key]);
		$('#oldDateTime_' + measType).val(row.date + ' ' + row.time);
	}
	
	// if add form is visible, hide it
	if ($('#add_' +measType+ '_section').is(':visible'))
		hideFormSection(measType, 'add');
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
		order: [[orderIndex, 'desc'], [orderIndex+1, 'desc']],
		scrollY: '35vh',
		scrollCollapse: true,
		paging: false,
		select: { style: 'single' },
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
						if (selectedRows.length > 0) {
							
							// deselect rows
							$.each(selectedRows, function(index, row) { row.deselect(); } );
							
							// hide edit and delete buttons
							dt.button($('#' +measType+ '_edit')).node().hide();
							dt.button($('#' +measType+ '_delete')).node().hide();
						}
						
						// change Done button to Cancel button
						if ($('#cancel_add_' +measType+ '_text').text() == 'Done')
							$('#cancel_add_' +measType+ '_text').text('Cancel');
						
						// fill in date/time fields with current date and time
						var now = new Date();
						$('#date_' +measType+ '_add').val(now.getFullYear()+ '-' +(now.getMonth()+1)+ '-' +now.getDate());
						$('#time_' +measType+ '_add').val(now.getHours()+ ':' +now.getMinutes());
						
						// clear and put focus in first field of form
						$('#' +measurementParts[measType][0]+ '_' +measType+ '_add').val('').focus();
						
						// jump to form if on a very small screen
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
	            		var row = selectedRows[0].data();
	            		for (var key in row)
	            			$('#' +key+ '_' +measType+ '_edit').val(row[key]);
	            		$('#oldDateTime_' + measType).val(row.date + ' ' + row.time);
	            		
	            		// show the edit form and put focus on first field
	            		showFormSection(measType, 'edit', dt);
	            		$('#' +measurementParts[measType][0]+ '_' +measType+ '_edit').focus();
	            		
	            		// jump to form if on a very small screen
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
	            	action: deleteMeasurement
	            }
            ]
		}
	};
}

/* creates a primary and secondary chart for the specified measurement type.
 * defaults to individual and monthly views for primary and secondary charts, respectively */
function createCharts(measType) {
	var measNames = measurementParts[measType];
	var avgOrTotal = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Averages' : 'Totals';
	
	// create two charts, a primary chart w/ individual view, and a secondary chart w/ monthly view
	createChart(measType, 'individual', 'dateAndTime', 'primary', 'Individual Entries', 'Over Past Month');
	createChart(measType, 'month', 'month', 'secondary', 'Monthly ' +avgOrTotal, 'Over Past Year');
}

// create a chart with the specified properties
function createChart(measType, chartType, xValPropertyName, idSuffix, title, subtitle) {
	var measNames = measurementParts[measType];
	
	$.ajax({
		'url': 'measurements_get_' +measType+ '_' +chartType,
		'dataType': 'json',
		'success': function(response) {
			var data = [];
			
			// create a chart series for each measurement part (e.g. systolic/diastolic pressures)
			for (var i = 0; i < measNames.length; i++) {
				var currentData = [];
				for (var j = 0; j < response.length; j++)
					currentData.push( [ response[j][xValPropertyName], parseFloat(response[j][measNames[i]]) ] );
				data.push(currentData);
			}
			
			// create chart
			var chartOptions = createChart_Options(measType, title, data, measNames, chartType, subtitle, idSuffix);
			charts[measType+ '_' +idSuffix] = new Highcharts.Chart(chartOptions);
		},
		'error': function() { alert('Error retreiving measurements'); }
	});
}

//function addTabListener() {
//	$('#measurements_tabs a').click(function (event) {
//		event.preventDefault();
//		$(this).tab('show');
//	});
//}

function createChart_Options(measType, title, data, name, per, subtitle, idSuffix) {
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
		var year;
		var month;
		var result = '';
		var pieces = value.split('-');
		
		year = pieces[0];
		month = pieces[1];
		
		if (isFirst || month == 1)
			result += '(' +year+ ') ';
		return result + monthNumToShortName(parseInt(month), false);
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
			var chartOptions = createChart_Options(measType, title, data, measNames, chartType, subtitle, primOrSec);
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
	hour = (hour == 0) ? 12 : hour;
	
	return hour+ ':' +minute+ ' ' +amOrPm+ ' ' +timeZone
}

// takes a string in 12-hour format and returns a string in 24-hour format
function TwelveHourTimeStringTo24HourTimeString(timeString) {
	var pieces = timeString.split(' ');
	var numbers = pieces[0];
	var amOrPm = pieces[1];
	var numberPieces = pieces[0].split(':');
	var hour = parseInt(numberPieces[0]);
	var minute = parseInt(numberPieces[1]);
	
	if (amOrPm === 'pm' && hour != 12)
		hour += 12;
	
	if (hour < 10)
		hour = '0' + hour;
	if (minute < 10)
		minute = '0' + minute;
	
	return hour+ ':' +minute;
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

// hides the form for the given measurement type (glucose/etc.) and form type (add/edit)
function hideFormSection(meas_type, form_type) {
	
	// hide form
	$('#' + form_type + '_' + meas_type + '_section').hide();
	$('#view_' + meas_type + '_section').removeClass('col-sm-8');
	$('#view_' + meas_type + '_section').addClass('col-sm-12');
	
	// deactivate button
	$('#' + meas_type + '_' + form_type).removeClass('active');
}

// show the specified measurement form section for the associated measurement type and jump to it
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


