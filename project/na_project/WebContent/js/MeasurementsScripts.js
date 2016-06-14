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

var timePeriodStrings = {
	individual: {
		period: 'individual',
		xProperty: 'dateAndTime',
		title: 'Individual Entries'
	},
	daily: {
		period: 'day',
		xProperty: 'day',
		title: 'Daily'
	},
	weekly: {
		period: 'week',
		xProperty: 'week',
		title: 'Weekly'
	},
	monthly: {
		period: 'month',
		xProperty: 'month',
		title: 'Weekly'
	},
	yearly: {
		period: 'year',
		xProperty: 'year',
		title: 'Yearly'
	}
}

var cumulativeMeasurements = ['calorie', 'exercise', 'sleep'];

var selectedRows = [];

var measurementTypes = ['bloodPressure', 'glucose', 'calorie', 'exercise', 'sleep', 'weight'];

$(document).ready(function() {
	// hide some content on load
	$('.add_measurement_section').hide();
	$('.edit_measurement_section').hide();
	$('.col-visibility-exercise').hide();
	
	// add listener for add/save/cancel buttons
	$('.add_measurement_section').submit(addMeasurement);
	$('.edit_measurement_section').submit(editMeasurement);
	$('.cancelMeasurement').click(cancelMeasurement);
	
	var firstChartStartID = '#'+$('#firstChartType').text()+'_'+$('#activeMeasurement').text()+'_chartStart';
	var firstChartEndID = '#'+$('#firstChartType').text()+'_'+$('#activeMeasurement').text()+'_chartEnd';
	var secondChartStartID = '#'+$('#secondChartType').text()+'_'+$('#activeMeasurement').text()+'_chartStart';
	var secondChartEndID = '#'+$('#secondChartType').text()+'_'+$('#activeMeasurement').text()+'_chartEnd';
	
	// add date pickers for chart options
	$('#startDate-picker_primary').datetimepicker( {
		format: 'YYYY-MM-DD',
		defaultDate: $(firstChartStartID).text(),
//		maxDate: $(firstChartEnd).text() // I'm not sure why setting min/max dates doesn't work here...API bug?
	} );
	$('#startDate-picker_secondary').datetimepicker( {
		format: 'YYYY-MM-DD',
		defaultDate: $(secondChartStartID).text(),
//		maxDate: new Date($(secondChartEnd).text())
	} );
	$('#endDate-picker_primary').datetimepicker( {
		format: 'YYYY-MM-DD',
		useCurrent: false, // important due to some issue with the API
		defaultDate: $(firstChartEndID).text(),
//		minDate: $(firstChartStart).text(),
		showTodayButton: true
	} );
	$('#endDate-picker_secondary').datetimepicker( {
		format: 'YYYY-MM-DD',
		useCurrent: false, // important due to some issue with the API
		defaultDate: $(secondChartEndID).text(),
//		minDate: $(secondChartStart).text(),
		showTodayButton: true
	} );
	var firstStartDatePicker = $('#startDate-picker_primary').data("DateTimePicker");
	var firstEndDatePicker = $('#endDate-picker_primary').data("DateTimePicker");
	var secondStartDatePicker = $('#startDate-picker_secondary').data("DateTimePicker");
	var secondEndDatePicker = $('#endDate-picker_secondary').data("DateTimePicker");
	firstStartDatePicker.maxDate(firstEndDatePicker.date());
	firstEndDatePicker.minDate(firstStartDatePicker.date());
	secondStartDatePicker.maxDate(secondEndDatePicker.date());
	secondEndDatePicker.minDate(secondStartDatePicker.date());
	
	// add date/time pickers for add/edit forms
	$('.add_measurement_section .date-picker').datetimepicker( {
		format: 'YYYY-MM-DD',
		defaultDate: Date.now(),
		showTodayButton: true
	} );
	$('.add_measurement_section .time-picker').datetimepicker( {
		format: 'h:mm a',
		defaultDate: Date.now()
	} );
	$('.edit_measurement_section .date-picker').datetimepicker( {
		format: 'YYYY-MM-DD',
		defaultDate: Date.now(),
		showTodayButton: true
	} );
	$('.edit_measurement_section .time-picker').datetimepicker( {
		format: 'h:mm a',
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
	
	// show/hide table according to loaded measurements options
	if (!$('#options_showTable').is(':checked'))
		$.each(measurementTypes, function(index, measType) { $('#' +measType+ '_table_section').hide(); } );
	
	// show/hide charts according to loaded measurements options, and disable showSecondChart checkbox if needed
	if (!$('#options_showFirstChart').is(':checked')) {
		$.each(measurementTypes, function(index, measType) {
			$('#'+measType+'_charts_primary_section').hide();
			$('#'+measType+'_charts_secondary_section').hide();
		});
		$('#options_showSecondChart').prop('disabled', true);
		$('#options_showSecondChart').parent().addClass('disabled');
	}
	else if (!$('#options_showSecondChart').is(':checked')) {
		$.each(measurementTypes, function(index, measType) {
			$('#'+measType+'_charts_secondary_section').hide();
			$('#'+measType+'_charts_primary_section').removeClass('col-md-6').addClass('col-md-12');
		});
	}
	
	// assign handlers for chart date range buttons 
	$('.btn-change-chart').click(viewNewChart);
	
	// add listeners for tab buttons (i.e. for switching to a measurement)
	$('#measurements_tabs a, #measurements_dropdown li a').click(tab_clicked);
	
	// add listeners for chart settings tabs
	$('#chartsOptions_tabs a').click(chartSettingsTab_clicked);
	
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
	
	setOptionsChangedListeners();
});

function setOptionsChangedListeners() {
	
	// units changes saved
	$('#saveUnitsChanges_btn').click(saveUnitsChanges);
	
	// units modal canceled/closed without saving
	$("#unitsOptions_modal button[data-dismiss='modal']").click(cancelUnitsChanges);
	
	// time format
	$('#options_timeFormat').change(timeFormat_selected);
	
	// show tooltips
	$('#options_showTooltips').change(showTooltips_clicked);
	
	// show table
	$('#options_showTable').change(function (event) {
		$.each(measurementTypes, function(index, measType) { $('#'+measType+'_table_section').toggle(); });
		var activeMeasurement = $('#activeMeasurement').text();
		if ($('#'+activeMeasurement+'_table_section').is(':visible'))
			$('#' +activeMeasurement+ '_table').DataTable().draw(); // avoids column alignment issues on show
		options_changed(); // store changes
	});
	
	// column visibility
	$('#columns_dropdown li a').click(columnVisibility_clicked);
	
	// num rows
	$('#options_numRows').change(function() {
		$('.measurement-table').each(function(index, element) { $(element).DataTable().page.len($('#options_numRows').val()).draw(); });
		options_changed(); // store changes
	});
	
	// show first chart
	$('#options_showFirstChart').click(showFirstChart_clicked);
	
	// show second chart
	$('#options_showSecondChart').click(showSecondChart_clicked);
	
	// update charts button clicked
	$('.updateCharts-btn').click(chartRange_update);
	
	// chart date pickers changed
	$('#startDate-picker_primary').on('dp.change', chartStartDate_picked);
	$('#startDate-picker_secondary').on('dp.change', chartStartDate_picked);
	$('#endDate-picker_primary').on('dp.change', chartEndDate_picked);
	$('#endDate-picker_secondary').on('dp.change', chartEndDate_picked);
}

// fires when any option is changed, storing the change in the server
function options_changed() {
	var optionsData = {};
	var moddedActMeasurement = $('#activeMeasurement').text();
	if (moddedActMeasurement === 'calories')
		moddedActMeasurement = 'calorie';
	optionsData.userName = $('#userName').text();
	optionsData.optionsName = 'Session';
	optionsData.oldOptionsName = 'Session';
	optionsData.isActive = true;
	optionsData.activeMeasurement = $('#activeMeasurement').text();
	optionsData.bloodPressureUnits = $('#bloodPressureUnits').text();
	optionsData.calorieUnits = $('#calorieUnits').text();
	optionsData.exerciseUnits = $('#exerciseUnits').text();
	optionsData.glucoseUnits = $('#glucoseUnits').text();
	optionsData.sleepUnits = $('#sleepUnits').text();
	optionsData.weightUnits = $('#weightUnits').text();
	optionsData.timeFormat = $('#options_timeFormat').val();
	optionsData.durationFormat = $('#durationFormat').text();
	optionsData.showTooltips = $('#options_showTooltips').is(':checked') ? true : false;
	optionsData.showSecondaryCols = true // TODO change this when fully implemented
	optionsData.showDateCol = $('#colvis_date span:first').hasClass('glyphicon') ? true : false;
	optionsData.showTimeCol = $('#colvis_time span:first').hasClass('glyphicon') ? true : false;
	optionsData.showNotesCol = $('#colvis_notes span:first').hasClass('glyphicon') ? true : false;
	optionsData.numRows = $('#options_numRows').val();
	optionsData.showTable = $('#options_showTable').is(':checked') ? true : false;
	optionsData.tableSize = 35; // TODO change this when fully implemeneted
	optionsData.chartPlacement = 'bottom'; // TODO change this when fully implemented
	optionsData.showFirstChart = $('#options_showFirstChart').is(':checked') ? true : false;
	optionsData.showSecondChart = $('#options_showSecondChart').is(':checked') ? true : false;
	optionsData.firstChartType = $('#' +moddedActMeasurement+ '_charts_primary_section .btn-change-chart.active').attr('id').split('_')[1]; // based on active chart type button
	optionsData.secondChartType = $('#' +moddedActMeasurement+ '_charts_secondary_section .btn-change-chart.active').attr('id').split('_')[1]; // based on active chart type button
	optionsData.chartLastYear = $('#options_chartLastYear').is(':checked') ? true : false;
	optionsData.chartGroupDays = $('#options_chartGroupDays').is(':checked') ? true : false;
	optionsData.individualBloodPressureChartStart = $('#individual_bloodPressure_chartStart').text();
	optionsData.individualBloodPressureChartEnd = $('#individual_bloodPressure_chartEnd').text();
	optionsData.dailyBloodPressureChartStart = $('#daily_bloodPressure_chartStart').text();
	optionsData.dailyBloodPressureChartEnd = $('#daily_bloodPressure_chartEnd').text();
	optionsData.weeklyBloodPressureChartStart = $('#weekly_bloodPressure_chartStart').text();
	optionsData.weeklyBloodPressureChartEnd = $('#weekly_bloodPressure_chartEnd').text();
	optionsData.monthlyBloodPressureChartStart = $('#monthly_bloodPressure_chartStart').text();
	optionsData.monthlyBloodPressureChartEnd = $('#monthly_bloodPressure_chartEnd').text();
	optionsData.yearlyBloodPressureChartStart = $('#yearly_bloodPressure_chartStart').text();
	optionsData.yearlyBloodPressureChartEnd = $('#yearly_bloodPressure_chartEnd').text();
	optionsData.individualCaloriesChartStart = $('#individual_calories_chartStart').text();
	optionsData.individualCaloriesChartEnd = $('#individual_calories_chartEnd').text();
	optionsData.dailyCaloriesChartStart = $('#daily_calories_chartStart').text();
	optionsData.dailyCaloriesChartEnd = $('#daily_calories_chartEnd').text();
	optionsData.weeklyCaloriesChartStart = $('#weekly_calories_chartStart').text();
	optionsData.weeklyCaloriesChartEnd = $('#weekly_calories_chartEnd').text();
	optionsData.monthlyCaloriesChartStart = $('#monthly_calories_chartStart').text();
	optionsData.monthlyCaloriesChartEnd = $('#monthly_calories_chartEnd').text();
	optionsData.yearlyCaloriesChartStart = $('#yearly_calories_chartStart').text();
	optionsData.yearlyCaloriesChartEnd = $('#yearly_calories_chartEnd').text();
	optionsData.individualExerciseChartStart = $('#individual_exercise_chartStart').text();
	optionsData.individualExerciseChartEnd = $('#individual_exercise_chartEnd').text();
	optionsData.dailyExerciseChartStart = $('#daily_exercise_chartStart').text();
	optionsData.dailyExerciseChartEnd = $('#daily_exercise_chartEnd').text();
	optionsData.weeklyExerciseChartStart = $('#weekly_exercise_chartStart').text();
	optionsData.weeklyExerciseChartEnd = $('#weekly_exercise_chartEnd').text();
	optionsData.monthlyExerciseChartStart = $('#monthly_exercise_chartStart').text();
	optionsData.monthlyExerciseChartEnd = $('#monthly_exercise_chartEnd').text();
	optionsData.yearlyExerciseChartStart = $('#yearly_exercise_chartStart').text();
	optionsData.yearlyExerciseChartEnd = $('#yearly_exercise_chartEnd').text();
	optionsData.individualGlucoseChartStart = $('#individual_glucose_chartStart').text();
	optionsData.individualGlucoseChartEnd = $('#individual_glucose_chartEnd').text();
	optionsData.dailyGlucoseChartStart = $('#daily_glucose_chartStart').text();
	optionsData.dailyGlucoseChartEnd = $('#daily_glucose_chartEnd').text();
	optionsData.weeklyGlucoseChartStart = $('#weekly_glucose_chartStart').text();
	optionsData.weeklyGlucoseChartEnd = $('#weekly_glucose_chartEnd').text();
	optionsData.monthlyGlucoseChartStart = $('#monthly_glucose_chartStart').text();
	optionsData.monthlyGlucoseChartEnd = $('#monthly_glucose_chartEnd').text();
	optionsData.yearlyGlucoseChartStart = $('#yearly_glucose_chartStart').text();
	optionsData.yearlyGlucoseChartEnd = $('#yearly_glucose_chartEnd').text();
	optionsData.individualSleepChartStart = $('#individual_sleep_chartStart').text();
	optionsData.individualSleepChartEnd = $('#individual_sleep_chartEnd').text();
	optionsData.dailySleepChartStart = $('#daily_sleep_chartStart').text();
	optionsData.dailySleepChartEnd = $('#daily_sleep_chartEnd').text();
	optionsData.weeklySleepChartStart = $('#weekly_sleep_chartStart').text();
	optionsData.weeklySleepChartEnd = $('#weekly_sleep_chartEnd').text();
	optionsData.monthlySleepChartStart = $('#monthly_sleep_chartStart').text();
	optionsData.monthlySleepChartEnd = $('#monthly_sleep_chartEnd').text();
	optionsData.yearlySleepChartStart = $('#yearly_sleep_chartStart').text();
	optionsData.yearlySleepChartEnd = $('#yearly_sleep_chartEnd').text();
	optionsData.individualWeightChartStart = $('#individual_weight_chartStart').text();
	optionsData.individualWeightChartEnd = $('#individual_weight_chartEnd').text();
	optionsData.dailyWeightChartStart = $('#daily_weight_chartStart').text();
	optionsData.dailyWeightChartEnd = $('#daily_weight_chartEnd').text();
	optionsData.weeklyWeightChartStart = $('#weekly_weight_chartStart').text();
	optionsData.weeklyWeightChartEnd = $('#weekly_weight_chartEnd').text();
	optionsData.monthlyWeightChartStart = $('#monthly_weight_chartStart').text();
	optionsData.monthlyWeightChartEnd = $('#monthly_weight_chartEnd').text();
	optionsData.yearlyWeightChartStart = $('#yearly_weight_chartStart').text();
	optionsData.yearlyWeightChartEnd = $('#yearly_weight_chartEnd').text();
	
	// send add request to server
	$.ajax({
		url: 'measurementsOptions_edit',
		data: optionsData,
		dataType: 'json',
		method: 'POST',
		success: function(response) {
			if (response.success) {
				if (response.data.rowsAffected < 1) {
					alert('Stored options not affected. Either options name not found, or stored option was already set to current selection.');
					return;
				}
//				alert('Options change successfully stored.');
			}
			else
				alert('Changes to options storing failed: ' +response.error);
		},
		error: function() { alert('Error: invalid response when attempting to store changes.'); }
	});
}

function showFirstChart_clicked() {
	var activeMeasurement = $('#activeMeasurement').text();
	if (activeMeasurement === 'calories')
		activeMeasurement = 'calorie';
	var firstChart_tab = $('#chartsOptions_tabs a[href="#firstChartOptions"]');
	var secondChart_tab = $('#chartsOptions_tabs a[href="#secondChartOptions"]');
	var secondChart_checkbox = $('#options_showSecondChart');
	
	// showFirstChart is being unchecked
	if (! $('#options_showFirstChart').is(':checked')) {
		
		// hide all first charts
		$.each(measurementTypes, function(index, measType) {
			$('#'+measType+'_charts_primary_section').hide();        
		});
		
		firstChart_tab.click();
		
		// disable second chart tab
		secondChart_tab.addClass('disabled');
		secondChart_tab.off('click', chartSettingsTab_clicked);
		secondChart_tab.removeAttr('data-toggle');
		
		// disable showSecondChart checkbox
		secondChart_checkbox.prop('disabled', true);        
		secondChart_checkbox.parent().addClass('disabled');
		
		// hide second charts if visible and uncheck second chart checkbox
		if ($('#'+activeMeasurement+'_charts_secondary_section').is(':visible')) {
			$.each(measurementTypes, function(index, measType) {
				$('#'+measType+'_charts_secondary_section').hide();
			});
			secondChart_checkbox.prop('checked', false);
		}
	}
	
	// showFirstChart is being checked
	else {
		// show all first charts, stretching first charts if needed
		$.each(measurementTypes, function(index, measType) {
			var firstChart = $('#'+measType+'_charts_primary_section');
			firstChart.show();
			if (firstChart.hasClass('col-md-6'))
				firstChart.removeClass('col-md-6').addClass('col-md-12');
		});
		
		// enable second chart tab
		secondChart_tab.removeClass('disabled');
		secondChart_tab.on('click', chartSettingsTab_clicked);
		secondChart_tab.attr('data-toggle', 'tab');
		
		// enable showSecondChart checkbox
		secondChart_checkbox.prop('disabled', false);
		secondChart_checkbox.parent().removeClass('disabled');
		
		charts[activeMeasurement+'_primary'].reflow();
	}
	
	options_changed();
}

function showSecondChart_clicked() {
	var activeMeasurement = $('#activeMeasurement').text();
	if (activeMeasurement === 'calories')
		activeMeasurement = 'calorie';
	
	// showSecondChart is being unchecked
	if (! $('#options_showSecondChart').is(':checked')) {
		$.each(measurementTypes, function(index, measType) {
			$('#'+measType+'_charts_secondary_section').hide();                                       // hide all second charts
			$('#'+measType+'_charts_primary_section').removeClass('col-md-6').addClass('col-md-12'); // stretch all first charts
		});
	}
	
	// showSecondChart is being checked
	else {
		$.each(measurementTypes, function(index, measType) {
			$('#'+measType+'_charts_secondary_section').show();                                       // show all second charts
			$('#'+measType+'_charts_primary_section').removeClass('col-md-12').addClass('col-md-6');  // shrink all first charts
		});
		charts[activeMeasurement+'_secondary'].reflow();
	}
	charts[activeMeasurement+'_primary'].reflow();
	
	options_changed();
}

// called when save changes button is clicked in units modal
function saveUnitsChanges() {
	var changedUnits = [];
	
	// determine glucose unit selection and update hidden data in the DOM if necessary
	var selectedGlucoseUnits = $('#options_glucoseUnits input:checked').val();
	if (selectedGlucoseUnits !== $('#glucoseUnits').text()) {
		$('#glucoseUnits').text(selectedGlucoseUnits);
		changedUnits.push('glucose');
	}
	
	// determine weight unit selection and update hidden data in the DOM if necessary
	var selectedWeightUnits = $('#options_weightUnits input:checked').val();
	if (selectedWeightUnits !== $('#weightUnits').text()) {
		$('#weightUnits').text(selectedWeightUnits);
		changedUnits.push('weight');
	}
	
	// update charts/tables/forms
	$.each(changedUnits, function(index, measType) { unitsModified(measType); });
	
	// store changes if something was changed
	if (changedUnits.length > 0)
		options_changed();
}

// called when cancel or X buttons are clicked in units modal
function cancelUnitsChanges() {
	var glucoseUnits = $('#glucoseUnits').text();
	var weightUnits = $('#weightUnits').text();
	
	// reset glucose units radio buttons to original selection
	if (glucoseUnits === 'mg/dL')
		$('#options_units_glucose_mgdL').prop('checked', true);
	else if (glucoseUnits === 'mM')
		$('#options_units_glucose_mM').prop('checked', true);
	else
		alert('Unable to revert glucose units in units dialog.');
	
	// reset weight units radio buttons to original selection
	if (weightUnits === 'lbs')
		$('#options_units_weight_lbs').prop('checked', true);
	else if (weightUnits === 'kg')
		$('#options_units_weight_kg').prop('checked', true);
	else
		alert('Unable to revert weight units in units dialog.');
}

// a column visibility dropdown menu item was clicked
function columnVisibility_clicked(event) {
	event.preventDefault();
	var colName = $(this).attr('id').split('_')[1];
	var iconSpan = $('span:first', this);
	if (iconSpan.hasClass('glyphicon')) {
		// hide checkmark icon
		iconSpan.removeClass('glyphicon glyphicon-ok');
		$('#colvis_' +colName+ '_text').css('margin-left', '1.6em');
		
		// hide column in all tables if it is common, or in visible table only otherwise
		if ($.inArray(colName, ['date', 'time', 'notes']) !== -1)
			$('.measurement-table').each(function(index, element) {
				$(element).DataTable().column(colName+ ':name').visible(false);
			});
		else
			$('.measurement-table:visible').DataTable().column(colName+ ':name').visible(false);
	} else {
		// show checkmark icon and show column
		iconSpan.addClass('glyphicon glyphicon-ok');
		$('#colvis_' +colName+ '_text').css('margin-left', '0em');
		$('.measurement-table:visible').DataTable().column(colName+ ':name').visible(true);
	}
	
	// store changes
	options_changed();
}

function showTooltips_clicked() {
	if (document.getElementById('options_showTooltips').checked)
		$('.tooltip-help').tooltip('enable');
	else
		$('.tooltip-help').tooltip('disable');
	
	// store changes
	options_changed();
}

// a time format (12/24-hour) was selected; switch display of tables/charts/forms accordingly
function timeFormat_selected() {
	var timeFormatSelected = $(this).val();
	
	// update table rows
	for (var i = 0; i < measurementTypes.length; i++)
		$('#' +measurementTypes[i]+ '_table').DataTable().rows().invalidate().draw();
	
	// update time picker in add/edit forms
	$('.time-picker').each(function(index, element) {
		if (timeFormatSelected === '12 hour')
			$(element).data("DateTimePicker").format('h:mm a');
		else
			$(element).data("DateTimePicker").format('HH:mm');
	});
	
	// store changes
	options_changed();
}

// takes a name like "Blood Pressure" and converts it to its attribute-name-friendly "bloodPressure"
function displayNameToAttributeName(displayName) {
	var str = displayName.charAt(0).toLowerCase() + displayName.substr(1);
	return str.replace(/ /, '');
}

// takes an attribute-name-friendly name like "bloodPressure" and converts it to "Blood Pressure"
// note that this currently only works for one-word or two-word property names
function attributeNameToDisplayName(attrName) {
	var result = attrName.replace(/([a-z])([A-Z])/, "$1 $2");
	result = result.charAt(0).toUpperCase() + result.substr(1);
	return result;
}

// a unit of measure was selected; switch display of tables/charts/forms accordingly
function unitsModified(measType) {
	var table = $('#' +measType+ '_table').DataTable();
	var primaryChart = charts[measType+ '_primary'];
	var secondaryChart = charts[measType+ '_secondary'];
	var newUnits = $('#'+measType+'Units').text();
	
	// update table rows
	table.rows().invalidate().draw();
	
	// update table column header(s)
	$.each(measurementParts[measType], function(index, partName) {
		$(table.column(index).header()).text(attributeNameToDisplayName(partName)+ ' (' +newUnits+ ')');
	});
	
	// function to update a single chart
	var updateChart = function(chart) {
		for (var i = 0; i < chart.series.length; i++) {
			var series = chart.series[i];
			for (var j = 0; j < series.data.length; j++) {
				var point = series.data[j];
				var yVal = point.y;
				var oldVal = point.old;
				
				// I think this only works for measurements with two types of units
				if (point.units !== newUnits) {
					if (point.old !== null) {
						yVal = point.old;
						oldVal = null; // TODO change this to a proper swap???
					}
					else {
						oldVal = yVal;
						yVal = convertUnits(yVal, point.units, newUnits);
					}
				}
				
				point.update({
					name: point.name,
					y: yVal,
					units: newUnits,
					notes: point.notes,
					old: oldVal
				}, false);
			}
		}
		chart.yAxis[0].setTitle({ text: newUnits }, false);
		chart.redraw();
	}
	updateChart(primaryChart);
	updateChart(secondaryChart);
	
	// update add/edit forms
	$('#add_' +measType+ '_section .units-addon').text(newUnits);
	$('#edit_' +measType+ '_section .units-addon').text(newUnits);
}

function chartSettingsTab_clicked(event) {
	event.preventDefault();
	$(this).tab('show');
}

function tab_clicked(event) {
	var measType = $(this).attr('id').split('_')[0];
	
	// change something like 'bloodPressure' to 'Blood Pressure', or 'exercise' to 'Exericse'
	var upperMeasType = measType.replace(/([a-z])([A-Z])/g, function(match, p1, p2) { return [p1, p2].join(' '); } );
	upperMeasType = upperMeasType.replace(/^[a-z]/, function (match) { return match.toUpperCase(); } );

	// show tab, change dropdown label, and deselect menu item
	$(this).tab('show');
	$('#measurements_dropdown_label').text(upperMeasType);
	$('#measurements_dropdown li').removeClass('active');
	
	// update tabs appearance (in case dropdown triggered this event)
	$('#measurements_tabs .active').removeClass('active');
	$('#' +measType+ '_tab_btn').parent().addClass('active');
	
	// update current option values stored in hidden DOM data
	$('#activeMeasurement').text(measType);
	
	if (measType === 'calories')
		measType = 'calorie';
	
	// update secondary column visibility options // TODO this used to be for showExerciseType option, which has been changed to showSecondaryCols option
	if (measType === 'exercise')
		$('.col-visibility-exercise').show();
	else
		$('.col-visibility-exercise').hide();
	
	// redraw charts and table to avoid overflow and column alignment issues
	charts[measType+ '_primary'].reflow();
	charts[measType+ '_secondary'].reflow();
	$('#' +measType+ '_table').DataTable().draw();
	
	options_changed();
	
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
	measData.time = convert12To24HourTime($('#time_' +measType+ '_edit').val().trim());
	measData.notes = $('#notes_' +measType+ '_edit').val().trim();
	measData.userName = $('#userName_' +measType+ '_add').val().trim();
	measData.oldDateTime = $('#oldDateTime_' +measType).val().trim();
	measData.units = $('#'+measType+'Units').text();
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
				$('#' +measType+ '_charts_primary_section .active').click();
				$('#' +measType+ '_charts_secondary_section .active').click();
				
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
					$('#' +measType+ '_charts_primary_section .active').click();
					$('#' +measType+ '_charts_secondary_section .active').click();
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
	measData.time = convert12To24HourTime($('#time_' +measType+ '_add').val().trim());
	measData.notes = $('#notes_' +measType+ '_add').val().trim();
	measData.userName = $('#userName_' +measType+ '_add').val().trim();
	measData.units = $('#'+measType+'Units').text();
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
				$('#' +measType+ '_charts_primary_section .active').click();
				$('#' +measType+ '_charts_secondary_section .active').click();
				
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
		var row = selectedRows[0].data(); // this is the original object returned from the server to create the row
		for (var key in row)
			$('#' +key+ '_' +measType+ '_edit').val(row[key]);
		$('#edit_' +measType+ '_section .time-picker').data('DateTimePicker').date(row.time);
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

// returns the table options object for the specified type of measurement (e.g. for bloodPressure, or exercise, etc.)
function tableOptions(measType) {
	
	// determine current column visibility options
	var dateVisible = $('#colvis_date span:first').hasClass('glyphicon');
	var timeVisible = $('#colvis_time span:first').hasClass('glyphicon');
	var notesVisible = $('#colvis_notes span:first').hasClass('glyphicon');
	
	// add/remove margins from column visibilty menu items as needed
	var addMargin = '1.6em';
	var noMargin = '0em';
	$('#colvis_date_text').css('margin-left', (dateVisible ? noMargin : addMargin));
	$('#colvis_time_text').css('margin-left', (timeVisible ? noMargin : addMargin));
	$('#colvis_notes_text').css('margin-left', (notesVisible ? noMargin : addMargin));
	
	// create columns array and add all common columns
	var columns = [
        { name: 'date', data: 'date', title: 'Date', visible: dateVisible },
        { name: 'time', data: 'time', title: 'Time', visible: timeVisible, render: function(data, type, fullRow, meta) {
        	if (type === 'display' && $('#options_timeFormat').val() === '12 hour')
    			return convert24To12HourTime(data);
        	else
        		return data;
        } },
	    { name: 'notes', data: 'notes', title: 'Notes', visible: notesVisible },
	    { name: 'units', data: 'units', title: 'Units', visible: false}
    ];
	var orderIndex = (measType == 'bloodPressure' || measType == 'exercise') ? 2 : 1; // index of col for ordering
	var propNames = measurementParts[measType]; // the actual measurement properties name(s) (e.g. systolicPressure/diastolicPresssure, duration, etc.)

	// add remaining columns
	if (measType === 'exercise') // for exercise, the type of exercise (running/aerobics/etc.) may or may not be hidden, so check options
		columns.unshift( { name: 'type', data: 'type', title: 'Type', visible: ($('#colvis_type span:first').hasClass('glyphicon') === true) } );
	for (var i = propNames.length-1; i >= 0; i--) {
		columns.unshift({
			name: propNames[i],
			data: propNames[i],
			title: attributeNameToDisplayName(propNames[i]) + ' (' +$('#'+measType+'Units').text()+ ')',
			render: function(data, type, fullRow, meta) {
				// for display purposes, it may be necessary to convert data to the units specified in the current measurements options preset
				if (type === 'display') {
					var displayUnits = $('#'+measType+'Units').text();
					if (displayUnits !== fullRow.units)
						return convertUnits(data, fullRow.units, displayUnits);
				}
				return data;
			}			
		});
	}
	
	// request data from server, then create and return table options object
	return {
		ajax: { url: '/na_project/measurements_get_' +measType+ '_all' , dataSrc: '' },
		columns: columns,
		order: [[orderIndex, 'desc'], [orderIndex+1, 'desc']], // order descending by date then by time
		scrollY: '35vh',
		scrollCollapse: true,
		lengthChange: false,
		processing: true,
		pagingType: 'numbers',
		pageLength: $('#options_numRows').val(),
		select: { style: 'single' },
		dom: 
			"<'row'<'col-sm-6'><'col-sm-6'f>>" +   // sets filter (search) box in upper right
			"<'row'<'col-sm-12'tr>>" +             // table and processing message
			"<'row'<'col-sm-5'i><'col-sm-7'p>>" +  // page info and pagination controls in buttom left and right, respectively
			"<'row'<'col-sm-12'B>>",               // set add/edit/delete buttons as bottom row
		createdRow: function (row, data, dataIndex) { // add a tooltip to the row
			$(row).attr('data-toggle', 'tooltip').attr('title', 'Rows can be selected for editing/deletion').addClass('dynamic-tooltip tooltip-help');
		},
		initComplete: function (settings, json) { // when the table is finished loading, add tooltips to column headers
			$('#' +measType+ '_table_section th').each(function (index, element) {
				$(element).attr('data-toggle', 'tooltip').attr('data-placement', 'bottom').attr('title', 'Click to sort by this column');
				
//				if (index < propNames_colHeaders.len)
//					$(element).text($(element).text() + '(' + )
				
//				$(element).addClass('dynamic-tooltip'); // TODO figure out why this doesn't work
			});
		},
		buttons: table_addEditDeleteButtons_options(measType) // creates the add/edit/delete buttons for the table
	};
}

// timeStr should be a string in HH:MM or H:MM format, with 0 <= HH <= 24
function convert24To12HourTime(timeStr) {
	
	// timeStr is already in 12 hour format, so simply return it
	if (timeStr.search(/^\d+:\d\d [ap]m$/) != -1)
		return timeStr;
	
	// break down the 24 hour timeStr
	var pieces = timeStr.split(':');
	var hours = parseInt(pieces[0]);
	var minutes = parseInt(pieces[1]);
	var amOrPm = 'am';
	
	// build the 12 hour formatted string
	if (hours >= 12) {
		amOrPm = 'pm';
		if (hours > 12)
			hours = hours % 12;
	}
	else if (hours === 0)
		hours = 12;
	if (minutes < 10)
		minutes = '0' +minutes;
	
	return hours+ ':' +minutes+ ' ' +amOrPm;
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
			break;
			
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
			break;
			
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
						node.attr('data-toggle', 'tooltip').attr('title', 'Show a form for adding a new ' +measType+ ' entry.').addClass('tooltip-help');
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
						
						// fill in date/time fields of add form with current date and time
						$('#add_' +measType+ '_section .time-picker').data('DateTimePicker').date(new Date());
						
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
						node.attr('data-toggle', 'tooltip').attr('title', 'Show a form for editing the selected ' +measType+ ' entry.').addClass('tooltip-help');
						node.prepend('<span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;');
					},
	            	action: function (e, dt, node, config) {
	            		// fill the edit form with data from the currently selected measurement
	            		var row = selectedRows[0].data(); // this is the original object returned from the server to create the row
	            		for (var key in row)
	            			$('#' +key+ '_' +measType+ '_edit').val(row[key]);
	            		$('#edit_' +measType+ '_section .time-picker').data('DateTimePicker').date(row.time);
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
						node.attr('data-toggle', 'tooltip').attr('title', 'Delete the selected ' +measType+ ' entry.').addClass('tooltip-help');
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
	var measTypeAdjusted = (measType === 'calorie') ? 'calories' : measType;
	var firstTimePeriod = $('#firstChartType').text();
	var secondTimePeriod = $('#secondChartType').text();
	var firstStartDate = $('#' + firstTimePeriod +'_'+ measTypeAdjusted +'_'+ 'chartStart').text();
	var firstEndDate = $('#' + firstTimePeriod +'_'+ measTypeAdjusted +'_'+ 'chartEnd').text();
	var secondStartDate = $('#' + secondTimePeriod +'_'+ measTypeAdjusted +'_'+ 'chartStart').text();
	var secondEndDate = $('#' + secondTimePeriod +'_'+ measTypeAdjusted +'_'+ 'chartEnd').text();
	
	// create two charts, a primary chart w/ individual view, and a secondary chart w/ monthly view
	createChart(measType, firstTimePeriod, firstStartDate, firstEndDate, 'primary', 'Over Past Month');
	createChart(measType, secondTimePeriod, secondStartDate, secondEndDate, 'secondary', 'Over Past Year');
}

// create a chart with the specified properties
function createChart(measType, timePeriods, startDate, endDate, primOrSec, subtitle) {
	var avgOrTotal = '';
	if (timePeriods !== 'individual')
		avgOrTotal = ($.inArray(measType, cumulativeMeasurements) === -1) ? ' Averages' : ' Totals';
	
	$.ajax({
		'url': 'measurements_get_' +measType+ '_' +timePeriodStrings[timePeriods]['period']+ '_' +startDate+ '_' +endDate,
		'dataType': 'json',
		'success': function(response) {
			var measNames = measurementParts[measType];
			var displayUnits = $('#'+measType+'Units').text();
			var data = [];
			var partName;
			
			// create a chart series for each measurement part (e.g. systolic/diastolic pressures)
			for (var i = 0; i < measNames.length; i++) {
				partName = measNames[i];
				data.push( {
					name: partName,
					data: createChartSeries(response, timePeriodStrings[timePeriods]['xProperty'], partName, displayUnits)
				} );
			}
			
			// create chart
			var chartOptions = createChart_Options(measType, timePeriodStrings[timePeriods]['title']+avgOrTotal, data, measNames, timePeriodStrings[timePeriods]['period'], subtitle, primOrSec);
			if (charts[measType+ '_' +primOrSec] !== null)
				charts[measType+ '_' +primOrSec].destroy();
			charts[measType+ '_' +primOrSec] = new Highcharts.Chart(chartOptions);
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
				title: { text: $('#'+measType+'Units').text() }
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
					var displayUnits = $('#'+measType+'Units').text();

					if (per === 'all' || per === 'individual') {
						var date = new Date(this.key);
						firstLineBody = date.toDateString();
						secondLineBody = dateToLocalTimeString(date);
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
						if (displayUnits === 'minutes') {
							resultStr += '<br />equivalent to: (' +(this.y / 60).toFixed(2)+ ' hours) or (' +(this.y/60/24).toFixed(2)+ ' days)';
						}
						else if (displayUnits === 'hours')
							resultStr += ' (' +(this.y / 24).toFixed(2)+ ' days)';
					}

				    return resultStr;
				}
			}
		}
	
	return chartOptions;
}

// called by the update chart button in options
function chartRange_update() {
	var primOrSec = $(this).attr('id').split('_')[1]; // update charts button ID example: updateCharts_primary
	var measType = $('#activeMeasurement').text();
	var startDate = $('#options_startDate_' +primOrSec+ '-chart').val();       // appropriate text input box controlled by a date picker
	var endDate = $('#options_endDate_' +primOrSec+ '-chart').val();           // appropriate text input box controlled by a date picker
	measType = (measType === 'calories') ? 'calorie' : measType;
	var avgOrTotal = ($.inArray(measType, cumulativeMeasurements) === -1) ? 'Averages' : 'Totals';
	var timePeriods = $('#' +measType+ '_charts_' +primOrSec+ '_section .btn-change-chart.active').attr('id').split('_')[1];
	
	// store updated start and end data for this chart in the DOM
	var measTypeAdjusted = (measType === 'calorie') ? 'calories' : measType;
	$('#' + timePeriods +'_'+ measTypeAdjusted +'_'+ 'chartStart').text(startDate);
	$('#' + timePeriods +'_'+ measTypeAdjusted +'_'+ 'chartEnd').text(endDate);
	
	// create the new chart
	createChart(measType, timePeriods, startDate, endDate, primOrSec, 'Over Past Month');
	
	// store changes
	options_changed();
}

// called by the chart selection buttons below each chart
function viewNewChart(event) {
	// deactivate/activate associated buttons
	$(this).parent().parent().find('.active').removeClass('active');
	$(this).addClass('active');
	
	// collect information on the new chart to load
	var id_pieces = $(this).attr('id').split('_');
	var measType = id_pieces[0];
	var chartType = id_pieces[1];
	var primOrSec = id_pieces[4];
	var measTypeAdjusted = (measType === 'calorie') ? 'calories' : measType;
	var startDate = $('#' + chartType +'_'+ measTypeAdjusted +'_chartStart').text();
	var endDate = $('#' + chartType +'_'+ measTypeAdjusted +'_chartEnd').text();
	
	// clear min/max on date pickers so no conflicts arise when setting the new dates
	$('#startDate-picker_'+primOrSec).data("DateTimePicker").maxDate(false);
	$('#endDate-picker_'+primOrSec).data("DateTimePicker").minDate(false);
	
	// update dates in options area
	$('#startDate-picker_' +primOrSec).data('DateTimePicker').date(startDate);
	$('#endDate-picker_' +primOrSec).data('DateTimePicker').date(endDate);
	
	// create the new chart
	createChart(measType, chartType, startDate, endDate, primOrSec, 'Over Past Month');
}

// called when a chart date range date picker date changed in options
function chartStartDate_picked() {
	// update linked date picker's limits
	var primOrSec = $(this).attr('id').split('_')[1];
	$('#endDate-picker_'+primOrSec).data("DateTimePicker").minDate($('#startDate-picker_'+primOrSec).data("DateTimePicker").date());
}

// called when a chart date range date picker date changed in options
function chartEndDate_picked() {
	// update linked date picker's limits
	var primOrSec = $(this).attr('id').split('_')[1];
	$('#startDate-picker_'+primOrSec).data("DateTimePicker").maxDate($('#endDate-picker_'+primOrSec).data("DateTimePicker").date());
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

// takes a Date object and returns a time string in a 12-hour or 24-hour format, e.g.: 2:06 pm (CST) 
function dateToLocalTimeString(date) {
	var pieces = date.toTimeString().split(' ');
	
	var timeZone = '(';
	$.each(pieces.slice(2), function(index, string) { timeZone += (index == 0) ? string[1] : string[0]; } );
	timeZone += ')';
	var time = pieces[0];
	var timePieces = time.split(':');
	var hour = timePieces[0];
	var minute = timePieces[1];
	
	if ($('#options_timeFormat').val() === '12 hour')
		return convert24To12HourTime(hour+ ':' +minute)+ ' ' +timeZone;
	
	return hour+ ':' +minute+ ' ' +timeZone;
}

// takes a string in 12-hour format and returns a string in 24-hour format, e.g. 14:02
function convert12To24HourTime(timeString) {
	if (timeString.search(/^\d\d:\d\d$/) != -1)
		return timeString;
	var pieces = timeString.split(' ');
	var numbers = pieces[0];
	var amOrPm = pieces[1];
	var numberPieces = pieces[0].split(':');
	var hour = parseInt(numberPieces[0]);
	var minute = parseInt(numberPieces[1]);
	
	if (amOrPm === 'pm' && hour != 12)
		hour += 12;
	else if (amOrPm === 'am' && hour == 12)
		hour = 0;
	
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
	$('#' + meas_type + '_table_section').removeClass('col-sm-8');
	$('#' + meas_type + '_table_section').addClass('col-sm-12');
	
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
	$('#' + meas_type + '_table_section').removeClass('col-sm-12');
	$('#' + meas_type + '_table_section').addClass('col-sm-8');
	
	// activate button
	$('#' +meas_type+ '_' +form_type).addClass('active');
}


