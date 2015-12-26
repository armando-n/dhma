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
	var measurementTypes = ['bloodPressure', 'glucose', 'calorie', 'exercise', 'sleep', 'weight'];
	
	// hide some content on load
	$('.add_measurement_section').hide();
	$('.edit_measurement_section').hide();
	
	// add listener for add/save/cancel buttons
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
	
	// request data from server and create tables
	$.each(measurementTypes, function(index, measType) {
		var table = $('#' +measType+ '_table').DataTable(tableOptions(measType));
		table.button(0, null).container().addClass('btn-group-justified');
		table.on('select', row_clicked);
		table.on('deselect', row_deselected);
	});
	
	// request data from server and create charts
	$.each(measurementTypes, function(index, measType) { createCharts(measType); } );
	
	// assign handlers for chart date range buttons 
	$('.btn-change-chart').click(viewNewChart);
	
	// add listeners for tab buttons (i.e. for switching to a measurement)
	$('#measurements_tabs a, #measurements_dropdown li a').click(tab_clicked);
	
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
	
	// add tooltips
	$('.btn-group [data-toggle="tooltip"]').tooltip( { container: 'body' } );
	$('body').tooltip( { selector: '.dynamic-tooltip' } );
	$('#measurements_dropdown').tooltip();
	$('#measurements_nav [data-toggle="tooltip"]').tooltip();
	
	// hide all but one units select tag
	$('#units_form-group select:gt(0)').hide();
	
	// switch visible unit select tag according to the measurement type selected
	$('#options_units_measurementType').change(unitsMeasType_selected);
	
	// a unit of measure was selected; switch display of tables/charts accordingly
	$('#units_form-group select').change(units_selected);
	
});

function unitsMeasType_selected() {
	var measTypeSelected = displayNameToAttributeName($(this).val());
	if (measTypeSelected === 'calories')
		measTypeSelected = 'calorie';

	// hide currently displayed units select tag
	$('#units_form-group select').hide();
	
	// show the units select tag for the selected measurement type
	$('#options_units_' +measTypeSelected).show();
}

// takes a name like "Blood Pressure" and converts it to its attribute-name-friendly "bloodPressure"
function displayNameToAttributeName(displayName) {
	var str = displayName.charAt(0).toLowerCase() + displayName.substr(1);
	return str.replace(/ /, '');
}

// takes an attribute-name-friendly name like "bloodPressure" and converts it to "Blood Pressure"  
function attributeNameToDisplayName(attrName) {
	var result = attrName.replace(/([a-z])([A-Z])/, "$1 $2");
	result = result.charAt(0).toUpperCase() + result.substr(1);
	return result;
}

function units_selected() {
	var unitsSelected = $(this).val();
	var measType = $(this).attr('id').split('_')[2];
	var table = $('#' +measType+ '_table').DataTable();
	var primaryChart = charts[measType+ '_primary'];
	var secondaryChart = charts[measType+ '_secondary'];
	var displayUnits = $('#options_units_' +measType).val();
	
	// update table rows
	table.rows().invalidate().draw();
	
	// update table column header(s)
	$.each(measurementParts[measType], function(index, partName) {
		$(table.column(index).header()).text(attributeNameToDisplayName(partName)+ ' (' +unitsSelected+ ')');
	});
	
	// update charts
	var updateChart = function(chart) {
		for (var i = 0; i < chart.series.length; i++) {
			var series = chart.series[i];
			for (var j = 0; j < series.data.length; j++) {
				var point = series.data[j];
				var yVal = point.y;
				var oldVal = point.old;
				
				if (point.units !== displayUnits) {
					if (point.old !== null) {
						yVal = point.old;
						oldVal = null;
					}
					else {
						oldVal = yVal;
						yVal = convertUnits(yVal, point.units, displayUnits);
					}
				}
				
				point.update({
					name: point.name,
					y: yVal,
					units: displayUnits,
					notes: point.notes,
					old: oldVal
				}, false);
			}
		}
		chart.yAxis[0].setTitle({ text: displayUnits }, false);
		chart.redraw();
	}
	updateChart(primaryChart);
	updateChart(secondaryChart);
	
	// update add/edit forms
	$('#add_' +measType+ '_section .units-addon').text(displayUnits);
	$('#edit_' +measType+ '_section .units-addon').text(displayUnits);
}

function tab_clicked(event) {
	var measType = $(this).attr('id').split('_')[0];
	var upperMeasType = measType.replace(/([a-z])([A-Z])/g, function(match, p1, p2) { return [p1, p2].join(' '); } );
	upperMeasType = upperMeasType.replace(/^[a-z]/, function (match) { return match.toUpperCase(); } );

	// show tab, change dropdown label, and deselect menu item
	$(this).tab('show');
	$('#measurements_dropdown_label').text(upperMeasType);
	$('#measurements_dropdown li').removeClass('active');
	
	// update units selection options to current measurement
	$('#options_units_measurementType').val(attributeNameToDisplayName(measType)).change();
	
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
}

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
	measData.units = $('#options_units_' +measType).val();
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
	measData.units = $('#options_units_' +measType).val();
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

function row_deselected(e, dt, type, indexes) {
	var measType = $(this).attr('id').split('_')[0];
	dt.button($('#' +measType+ '_edit')).node().hide();
	dt.button($('#' +measType+ '_delete')).node().hide();
}

function tableOptions(measType) {
	
	// create columns array and all common columns
	var columns = [
        { data: 'date', title: 'Date' },
        { data: 'time', title: 'Time' },
	    { data: 'notes', title: 'Notes' },
	    { data: 'units', title: 'Units', visible: false}
    ];
	var orderIndex = (measType == 'bloodPressure') ? 2 : 1;
	var propNames = measurementParts[measType];

	// add remaining columns
	for (var i = propNames.length-1; i >= 0; i--) {
		columns.unshift(
			{
				data: propNames[i],
				title: attributeNameToDisplayName(propNames[i]) + ' (' +$('#options_units_' +measType).val()+ ')',
				render: function(data, type, fullRow, meta) {
					var result = data;
					if (type === 'display') {
						var displayUnits = $('#options_units_' +measType).val();
						if (displayUnits !== fullRow.units) // if necessary, convert data to the units specified in the current measurements options preset
							result = convertUnits(data, fullRow.units, displayUnits);
					}
					return result;
				}
			
			}
		);
	}
	
	// create and return table options object
	return {
		ajax: { url: '/na_project/measurements_get_' +measType+ '_all' , dataSrc: '' },
		columns: columns,
		order: [[orderIndex, 'desc'], [orderIndex+1, 'desc']],
		scrollY: '35vh',
		scrollCollapse: true,
		lengthChange: false,
		processing: true,
		pagingType: 'numbers',
		select: { style: 'single' },
		dom: 
			"<'row'<'col-sm-6'><'col-sm-6'f>>" +   // sets filter (search) box in upper right
			"<'row'<'col-sm-12'tr>>" +             // table and processing message
			"<'row'<'col-sm-5'i><'col-sm-7'p>>" +  // page info and pagination controls in buttom left and right, respectively
			"<'row'<'col-sm-12'B>>",               // set add/edit/delete buttons as bottom row
		createdRow: function (row, data, dataIndex) {
			// add a tooltip to the row
			$(row).attr('data-toggle', 'tooltip').attr('title', 'Rows can be selected for editing/deletion').addClass('dynamic-tooltip');
		},
		initComplete: function (settings, json) {
			$('#view_' +measType+ '_section th').each(function (index, element) {
				$(element).attr('data-toggle', 'tooltip').attr('data-placement', 'bottom').attr('title', 'Click to sort by this column');
				
//				if (index < propNames_colHeaders.len)
//					$(element).text($(element).text() + '(' + )
				
//				$(element).addClass('dynamic-tooltip'); // TODO figure out why this doesn't work
			});
		},
		buttons: table_addEditDeleteButtons_options(measType)
	};
}

// takes a value and converts it from oldUnits to newUnits
function convertUnits(value, oldUnits, newUnits) {
	switch(oldUnits) {
		// glucose units
		case 'mg/dL':
			if (newUnits === 'mM')
				return parseFloat((value * 0.0555).toFixed(2));
			break;
		case 'mM':
			if (newUnits === 'mg/dL')
				return parseFloat((value * 18.0182).toFixed(2));
			break;
			
		// blood pressure units
		case 'mm Hg':
			return value;
			
		// weight units
		case 'lbs':
			if (newUnits === 'kg')
				return parseFloat((value * 0.45359237).toFixed(2));
			break;
		case 'kg':
			if (newUnits === 'lbs')
				return parseFloat((value * 2.20462262185).toFixed(2));
			break;
			
		// calorie units
		case 'calories':
			return value;
			
		// exercise/sleep units
		case 'minutes':
			if (newUnits === 'hours')
				return parseFloat((value / 60).toFixed(2));
			if (newUnits === 'hours:minutes')
				return '' + Math.floor(value/60) + ':' + (value%60);
			break;
		case 'hours':
			if (newUnits === 'minutes')
				return Math.floor(value*60);
			if (newUnits === 'hours:minutes')
				return '' +Math.floor(value)+ ':' +((value%1).toFixed(2) * 60);
			break;
		case 'hours:minutes':
			var piece = value.split(':');
			var hours = pieces[0];
			var minutes = pieces[1];
			if (newUnits === 'minutes')
				return hours * 60 + minutes;
			if (newUnits === 'hours')
				return parseInt(hours) + (minutes / 60).toFixed(2);
			break;
		default:
			console.log('error: unrecognized unit specified for conversion: ' +oldUnits);
	}
}

function table_addEditDeleteButtons_options(measType) {
	return {
			name: 'add_edit_delete',
			buttons: [
				{ // add button
					name: measType+ '_add',
					text: 'Add',
					init: function (dt, node, config) {
						node.attr('id', measType+ '_add');
						node.attr('data-toggle', 'tooltip').attr('title', 'Show a form for adding a new ' +measType+ ' entry.');
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
					}
				},
				
	            { // edit button
					name: measType+ '_edit',
	            	extend: 'selectedSingle',
	            	text: 'Edit',
	            	init: function (dt, node, config) {
						node.hide().attr('id', measType+ '_edit');
						node.attr('data-toggle', 'tooltip').attr('title', 'Show a form for editing the selected ' +measType+ ' entry.');
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
	            	}
	            },
	            
	            { // delete button
	            	name: measType+ '_delete',
	            	extend: 'selected',
	            	text: 'Delete',
	            	init: function (dt, node, config) {
						node.hide().attr('id', measType+ '_delete').addClass('btn-danger');
						node.attr('data-toggle', 'tooltip').attr('title', 'Delete the selected ' +measType+ ' entry.');
						node.prepend('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;');
					},
	            	action: deleteMeasurement
	            }
	        ]
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
	$.ajax({
		'url': 'measurements_get_' +measType+ '_' +chartType,
		'dataType': 'json',
		'success': function(response) {
			var measNames = measurementParts[measType];
			var displayUnits = $('#options_units_' +measType).val();
			var data = [];
			var partName;
			
			// create a chart series for each measurement part (e.g. systolic/diastolic pressures)
			for (var i = 0; i < measNames.length; i++) {
				partName = measNames[i];
				data.push( {
					name: partName,
					data: createChartSeries(response, xValPropertyName, partName, displayUnits)
				} );
			}
			
			// create chart
			var chartOptions = createChart_Options(measType, title, data, measNames, chartType, subtitle, idSuffix);
			if (charts[measType+ '_' +idSuffix] !== null)
				charts[measType+ '_' +idSuffix].destroy();
			charts[measType+ '_' +idSuffix] = new Highcharts.Chart(chartOptions);
		},
		'error': function() { alert('Error retreiving measurements'); }
	});
}

function createChartSeries(response, xValPropertyName, partName, displayUnits) {
	var currentSeries = [];
	
	for (var j = 0; j < response.length; j++) {
		var point = response[j];
		var yVal = parseFloat(point[partName]);
		var oldVal = null;
		
		if (point.units !== displayUnits) {
			oldVal = yVal;
			yVal = convertUnits(yVal, point.units, displayUnits);
		}

		currentSeries.push({
			name: point[xValPropertyName],
			y: yVal,
			units: displayUnits,
			notes: point.notes,
			old: oldVal
		});
	}
	
	return currentSeries;
}

function createChart_Options(measType, title, data, name, per, subtitle, idSuffix) {
	var series = [];
	var numOfSeriesData = data.length;
	var xValue;
//	var minY = 100000;
//	var maxY = 0;
//	var formatStr;
	
	
	
//	for (var j = 0; j < numOfSeriesData; j++) {	
		
		
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
		
		
//		series.push( {
//			name: name[j],
//			data: data[j]
//		} );
//	}
	
	
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
				title: { text: $('#options_units_' +measType).val() }
//				min: minY,
//				max: maxY
			},
			series: data,
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
					var displayUnits = $('#options_units_' +measType).val();

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
						this.series.name+ ': <strong>' +this.y+ ' ' +displayUnits+ '</strong>';
					
					if (measType === 'exercise' || measType === 'sleep') {
						if (displayUnits === 'minutes')
							resultStr += ' (' +(this.y / 60).toFixed(2)+ ' hours)';
						else if (displayUnits === 'hours')
							resultStr += ' (' +(this.y / 24).toFixed(2)+ ' days)';
					}

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
	var avgOrTotal = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Averages' : 'Totals';
	
	// create the new chart
	switch (chartType) {
		case 'individual':
			createChart(measType, chartType, 'dateAndTime', primOrSec, 'Individual Entries', 'Over Past Month'); break;
		case 'day':
			createChart(measType, chartType, chartType, primOrSec, 'Daily ' +avgOrTotal, 'Over Past Month'); break;
		case 'week':
			createChart(measType, chartType, chartType, primOrSec, 'Weekly ' +avgOrTotal, 'Over Past Year'); break;
		case 'month':
			createChart(measType, chartType, chartType, primOrSec, 'Monthly ' +avgOrTotal, 'Over Past Year'); break;
		case 'year':
			createChart(measType, chartType, chartType, primOrSec, 'Yearly ' +avgOrTotal, 'Over Past 5 Years'); break;
		default:
			createChart(measType, chartType, chartType, primOrSec, '', '');
	}
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


