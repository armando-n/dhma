(function() {
var measurementParts = {
	glucose: ['glucose'],
	bloodPressure: ['systolicPressure', 'diastolicPressure'],
	calorie: ['calories'],
	exercise: ['duration'],
	sleep: ['duration'],
	weight: ['weight']
};

var units = {
	glucose: 'mg/dL',
	bloodPressure: 'mm Hg',
	calorie: 'calories',
	exercise: 'minutes',
	sleep: 'minutes',
	weight: 'kilograms'
};

var measurementTypes = ['bloodPressure', 'glucose', 'calorie', 'exercise', 'sleep', 'weight'];

var smallScreen_limit = 975;

$(document).ready(initializePage);

var utils = (function() {
        /** Takes a value and converts it from oldUnits to newUnits.
         * @param value The value to convert.
         * @param oldUnits The units the value is currently represented in.
         * @param newUnits The units to convert the value to. */
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
                    var pieces = value.split(':');
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
        };

        /** Takes an integer and converts it to its corresponding month short-hand.
         * isZeroBased determines whether to start with Jan at 0 (true) or 1 (false). */
        function convertToShortMonth(num, isZeroBased) {
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

        /** Takes a Date object and returns the local time in a string in the currently selected timeFormat option, e.g.: 2:06 pm (CST) or 14:06 (CST) */
        function convertDateToTimeString(date) {
            var pieces = date.toTimeString().split(' ');

            var timeZone = '(';
            $.each(pieces.slice(2), function(index, string) { timeZone += (index == 0) ? string[1] : string[0]; } );
            timeZone += ')';
            var time = pieces[0];
            var timePieces = time.split(':');
            var hour = timePieces[0];
            var minute = timePieces[1];

            if (options.get('timeFormat') === '12 hour')
                return convert24To12HourTime(hour+ ':' +minute)+ ' ' +timeZone;

            return hour+ ':' +minute+ ' ' +timeZone;
        }

        /** Converts a string in 24-hour format (HH:MM or H:MM) to 12-hour format (H:MM pm  or HH:MM pm) */
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

        /** Converts a string in 12-hour format (HH:MM pm or H:MM pm) to 24-hour format (HH:MM) */
        function convert12To24HourTime(timeStr) {
            if (timeStr.search(/^\d\d:\d\d$/) != -1)
                return timeStr;
            var pieces = timeStr.split(' ');
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

        /** Takes a name like "Blood Pressure" and converts it to its attribute-name-friendly "bloodPressure" */
        function convertDisplayToAttrName(displayName) {
            var str = displayName.charAt(0).toLowerCase() + displayName.substr(1);
            return str.replace(/ /, '');
        }

        /** Takes an attribute-name-friendly name like "bloodPressure" and converts it to "Blood Pressure".
         * Note that this currently only works for one-word or two-word property names */
        function convertAttrToDisplayName(attrName) {
            var result = attrName.replace(/([a-z])([A-Z])/, "$1 $2");
            result = result.charAt(0).toUpperCase() + result.substr(1);
            return result;
        }

        /** Returns the given string with the first letter capitalized. */
        function capitalizeFirstLetter(str) {
            return str.charAt(0).toUpperCase()+str.substring(1);
        }

        return {
            /** convertUnits(value, oldUnits, newUnits) : Takes a value and converts it from oldUnits to newUnits. */
            convertUnits: convertUnits,
            /** toShortMonth(num, isZeroBased) : Takes an integer and converts it to its corresponding month short-hand.
              * isZeroBased determines whether to start with Jan at 0 (true) or 1 (false). */
            toShortMonth: convertToShortMonth,
            /** toTimeString(date) : Takes a Date object and returns the local time in a string in the currently selected timeFormat option, e.g.: 2:06 pm (CST) or 14:06 (CST) */
            toTimeString: convertDateToTimeString,
            /** to12Hour(timeStr) : Converts a string in 24-hour format (HH:MM or H:MM) to 12-hour format (H:MM pm  or HH:MM pm) */
            to12Hour: convert24To12HourTime,
            /** to24Hour(timeStr) : Converts a string in 12-hour format (HH:MM pm or H:MM pm) to 24-hour format (HH:MM) */
            to24Hour: convert12To24HourTime,
            /** Takes a name like "Blood Pressure" and converts it to its attribute-name-friendly "bloodPressure" */
            toAttrName: convertDisplayToAttrName,
            /** Takes an attribute-name-friendly name like "bloodPressure" and converts it to "Blood Pressure".
             * Note that this currently only works for one-word or two-word property names */
            toDisplayName: convertAttrToDisplayName,
            /** Returns the given string with the first letter capitalized. */
            capFirst: capitalizeFirstLetter
        };
})(); // end utils module

var options = (function() {

    var measurementsOptions = {
        activeMeasurement: null,
        bloodPressureUnits: null,
        calorieUnits: null,
        exerciseUnits: null,
        glucoseUnits: null,
        sleepUnits: null,
        weightUnits: null,
        timeFormat: null,
        durationFormat: null,
        showTooltips: null,
        showSecondaryCols: null,
        showDateCol: null,
        showTimeCol: null,
        showNotesCol: null,
        numRows: null,
        showTable: null,
        tableSize: null,
        chartPlacement: null,
        showFirstChart: null,
        showSecondChart: null,
        firstChartType: null,
        secondChartType: null,
        chartLastYear: null,
        chartGroupDays: null,
        individualBloodPressureChartStart: null,
        individualBloodPressureChartEnd: null,
        dailyBloodPressureChartStart: null,
        dailyBloodPressureChartEnd: null,
        weeklyBloodPressureChartStart: null,
        weeklyBloodPressureChartEnd: null,
        monthlyBloodPressureChartStart: null,
        monthlyBloodPressureChartEnd: null,
        yearlyBloodPressureChartStart: null,
        yearlyBloodPressureChartEnd: null,
        individualCaloriesChartStart: null,
        individualCaloriesChartEnd: null,
        dailyCaloriesChartStart: null,
        dailyCaloriesChartEnd: null,
        weeklyCaloriesChartStart: null,
        weeklyCaloriesChartEnd: null,
        monthlyCaloriesChartStart: null,
        monthlyCaloriesChartEnd: null,
        yearlyCaloriesChartStart: null,
        yearlyCaloriesChartEnd: null,
        individualExerciseChartStart: null,
        individualExerciseChartEnd: null,
        dailyExerciseChartStart: null,
        dailyExerciseChartEnd: null,
        weeklyExerciseChartStart: null,
        weeklyExerciseChartEnd: null,
        monthlyExerciseChartStart: null,
        monthlyExerciseChartEnd: null,
        yearlyExerciseChartStart: null,
        yearlyExerciseChartEnd: null,
        individualGlucoseChartStart: null,
        individualGlucoseChartEnd: null,
        dailyGlucoseChartStart: null,
        dailyGlucoseChartEnd: null,
        weeklyGlucoseChartStart: null,
        weeklyGlucoseChartEnd: null,
        monthlyGlucoseChartStart: null,
        monthlyGlucoseChartEnd: null,
        yearlyGlucoseChartStart: null,
        yearlyGlucoseChartEnd: null,
        individualSleepChartStart: null,
        individualSleepChartEnd: null,
        dailySleepChartStart: null,
        dailySleepChartEnd: null,
        weeklySleepChartStart: null,
        weeklySleepChartEnd: null,
        monthlySleepChartStart: null,
        monthlySleepChartEnd: null,
        yearlySleepChartStart: null,
        yearlySleepChartEnd: null,
        individualWeightChartStart: null,
        individualWeightChartEnd: null,
        dailyWeightChartStart: null,
        dailyWeightChartEnd: null,
        weeklyWeightChartStart: null,
        weeklyWeightChartEnd: null,
        monthlyWeightChartStart: null,
        monthlyWeightChartEnd: null,
        yearlyWeightChartStart: null,
        yearlyWeightChartEnd: null
    };

    var datepickers = {
        first_chart_start_datePicker: null,
        second_chart_start_datePicker: null,
        first_chart_end_datePicker: null,
        second_chart_end_datePicker: null
    }

    /** Accesses the current active measurements options by name. */
    function get(optionName) {
        return measurementsOptions[optionName];
    }

    /** Convenience function for accessing a chart date range option. measType is like 'bloodPressure', 'exercise', etc.
     * chartType is something like 'individual', 'weekly', etc.  whichDate must be either 'start' or 'end'.  */
    function getChartDate(measType, chartType, whichDate) {
        measType = (measType === 'calorie') ? 'calories' : measType;
        return measurementsOptions[chartType+utils.capFirst(measType)+'Chart'+utils.capFirst(whichDate)];
    }

    /** @param {String} optionName The name of some option, e.g. 'showFirstChart' or 'showTooltips'. */
    function getControlFor(optionName) {
        return $('#options_'+optionName);
    }

    function getUnits(measType) {
        return measurementsOptions[measType+'Units'];
    }

    /** @param {String} whichChart Must be either 'first' or 'second'.
     *  @param {String} startOrEnd Must be either 'start' or 'end'. */
    function getChartDatePicker(whichChart, startOrEnd) {
        return datepickers[whichChart+'_chart_'+startOrEnd+'_datePicker'];
    }

    function setChartDatePicker(whichChart, startOrEnd, newDatePicker) {
        datepickers[whichChart+'_chart_'+startOrEnd+'_datePicker'] = newDatePicker;
    }

    /** Sets the specified option to the specified value */
    function set(optionName, value) {
        measurementsOptions[optionName] = value;
    }

    /** Sets the specified chart to the specified value. */
    function setChartDate(measType, chartType, whichDate, value) {
        measType = (measType === 'calorie') ? 'calories' : measType;
        measurementsOptions[chartType+utils.capFirst(measType)+'Chart'+utils.capFirst(whichDate)] = value;
    }

    function initialize() {
        loadOptions();
        initChartDateRangePickers();

        // set listeners for options controls
        $('#chartsOptions_tabs a').click(chartSettingsTab_clicked);                             // chart options tabs
        $('#saveUnitsChanges_btn').click(saveUnitsChanges);                                // save changes to units
        $("#unitsOptions_modal button[data-dismiss='modal']").click(cancelUnitsChanges);   // units modal canceled/closed without saving
        $('#options_timeFormat').change(timeFormat_selected);                              // time format
        $('#options_showTooltips').change(showTooltips_clicked);                           // show tooltips
        $('#options_showTable').change(showTable_clicked);                                 // show table
        $('#columns_dropdown li a').click(columnVisibility_clicked);                       // column visibility
        $('#options_numRows').change(numRows_changed);                                          // num rows
        $('#options_showFirstChart').click(showFirstChart_clicked);                        // show first chart
        $('#options_showSecondChart').click(showSecondChart_clicked);                      // show second chart
        $('#closeOptions_btn').click(function() { $('#options_btn').click(); return false; });  // close options
    }

    function loadOptions() {
        set('activeMeasurement', $('#activeMeasurement').text());
        set('bloodPressureUnits', $('#bloodPressureUnits').text());
        set('calorieUnits', $('#calorieUnits').text());
        set('exerciseUnits', $('#exerciseUnits').text());
        set('glucoseUnits', $('#glucoseUnits').text());
        set('sleepUnits', $('#sleepUnits').text());
        set('weightUnits', $('#weightUnits').text());
        set('timeFormat', $('#timeFormat').text());
        set('durationFormat', $('#durationFormat').text());
        set('showTooltips', $('#showTooltips').text() === 'true' ? true : false);
        set('showSecondaryCols', $('#showSecondaryCols').text() === 'true' ? true : false);
        set('showDateCol', $('#showDateCol').text() === 'true' ? true : false);
        set('showTimeCol', $('#showTimeCol').text() === 'true' ? true : false);
        set('showNotesCol', $('#showNotesCol').text() === 'true' ? true : false);
        set('numRows', $('#numRows').text());
        set('showTable', $('#showTable').text() === 'true' ? true : false);
        set('tableSize', $('#tableSize').text());
        set('chartPlacement', $('#chartPlacement').text());
        set('showFirstChart', $('#showFirstChart').text() === 'true' ? true : false);
        set('showSecondChart', $('#showSecondChart').text() === 'true' ? true : false);
        set('firstChartType', $('#firstChartType').text());
        set('secondChartType', $('#secondChartType').text());
        set('chartLastYear', $('#chartLastYear').text() === 'true' ? true : false);
        set('chartGroupDays', $('#chartGroupDays').text() === 'true' ? true : false);
        set('individualBloodPressureChartStart', $('#individual_bloodPressure_chartStart').text());
        set('individualBloodPressureChartEnd', $('#individual_bloodPressure_chartEnd').text());
        set('dailyBloodPressureChartStart', $('#daily_bloodPressure_chartStart').text());
        set('dailyBloodPressureChartEnd', $('#daily_bloodPressure_chartEnd').text());
        set('weeklyBloodPressureChartStart', $('#weekly_bloodPressure_chartStart').text());
        set('weeklyBloodPressureChartEnd', $('#weekly_bloodPressure_chartEnd').text());
        set('monthlyBloodPressureChartStart', $('#monthly_bloodPressure_chartStart').text());
        set('monthlyBloodPressureChartEnd', $('#monthly_bloodPressure_chartEnd').text());
        set('yearlyBloodPressureChartStart', $('#yearly_bloodPressure_chartStart').text());
        set('yearlyBloodPressureChartEnd', $('#yearly_bloodPressure_chartEnd').text());
        set('individualCaloriesChartStart', $('#individual_calories_chartStart').text());
        set('individualCaloriesChartEnd', $('#individual_calories_chartEnd').text());
        set('dailyCaloriesChartStart', $('#daily_calories_chartStart').text());
        set('dailyCaloriesChartEnd', $('#daily_calories_chartEnd').text());
        set('weeklyCaloriesChartStart', $('#weekly_calories_chartStart').text());
        set('weeklyCaloriesChartEnd', $('#weekly_calories_chartEnd').text());
        set('monthlyCaloriesChartStart', $('#monthly_calories_chartStart').text());
        set('monthlyCaloriesChartEnd', $('#monthly_calories_chartEnd').text());
        set('yearlyCaloriesChartStart', $('#yearly_calories_chartStart').text());
        set('yearlyCaloriesChartEnd', $('#yearly_calories_chartEnd').text());
        set('individualExerciseChartStart', $('#individual_exercise_chartStart').text());
        set('individualExerciseChartEnd', $('#individual_exercise_chartEnd').text());
        set('dailyExerciseChartStart', $('#daily_exercise_chartStart').text());
        set('dailyExerciseChartEnd', $('#daily_exercise_chartEnd').text());
        set('weeklyExerciseChartStart', $('#weekly_exercise_chartStart').text());
        set('weeklyExerciseChartEnd', $('#weekly_exercise_chartEnd').text());
        set('monthlyExerciseChartStart', $('#monthly_exercise_chartStart').text());
        set('monthlyExerciseChartEnd', $('#monthly_exercise_chartEnd').text());
        set('yearlyExerciseChartStart', $('#yearly_exercise_chartStart').text());
        set('yearlyExerciseChartEnd', $('#yearly_exercise_chartEnd').text());
        set('individualGlucoseChartStart', $('#individual_glucose_chartStart').text());
        set('individualGlucoseChartEnd', $('#individual_glucose_chartEnd').text());
        set('dailyGlucoseChartStart', $('#daily_glucose_chartStart').text());
        set('dailyGlucoseChartEnd', $('#daily_glucose_chartEnd').text());
        set('weeklyGlucoseChartStart', $('#weekly_glucose_chartStart').text());
        set('weeklyGlucoseChartEnd', $('#weekly_glucose_chartEnd').text());
        set('monthlyGlucoseChartStart', $('#monthly_glucose_chartStart').text());
        set('monthlyGlucoseChartEnd', $('#monthly_glucose_chartEnd').text());
        set('yearlyGlucoseChartStart', $('#yearly_glucose_chartStart').text());
        set('yearlyGlucoseChartEnd', $('#yearly_glucose_chartEnd').text());
        set('individualSleepChartStart', $('#individual_sleep_chartStart').text());
        set('individualSleepChartEnd', $('#individual_sleep_chartEnd').text());
        set('dailySleepChartStart', $('#daily_sleep_chartStart').text());
        set('dailySleepChartEnd', $('#daily_sleep_chartEnd').text());
        set('weeklySleepChartStart', $('#weekly_sleep_chartStart').text());
        set('weeklySleepChartEnd', $('#weekly_sleep_chartEnd').text());
        set('monthlySleepChartStart', $('#monthly_sleep_chartStart').text());
        set('monthlySleepChartEnd', $('#monthly_sleep_chartEnd').text());
        set('yearlySleepChartStart', $('#yearly_sleep_chartStart').text());
        set('yearlySleepChartEnd', $('#yearly_sleep_chartEnd').text());
        set('individualWeightChartStart', $('#individual_weight_chartStart').text());
        set('individualWeightChartEnd', $('#individual_weight_chartEnd').text());
        set('dailyWeightChartStart', $('#daily_weight_chartStart').text());
        set('dailyWeightChartEnd', $('#daily_weight_chartEnd').text());
        set('weeklyWeightChartStart', $('#weekly_weight_chartStart').text());
        set('weeklyWeightChartEnd', $('#weekly_weight_chartEnd').text());
        set('monthlyWeightChartStart', $('#monthly_weight_chartStart').text());
        set('monthlyWeightChartEnd', $('#monthly_weight_chartEnd').text());
        set('yearlyWeightChartStart', $('#yearly_weight_chartStart').text());
        set('yearlyWeightChartEnd', $('#yearly_weight_chartEnd').text());
    }

    function initChartDateRangePickers() {
        // add date pickers for chart options
        $('#first_chart_start_datePicker').datetimepicker( {
            format: 'YYYY-MM-DD',
            defaultDate: getChartDate(get('activeMeasurement'), get('firstChartType'), 'start'),
            focusOnShow: false,
        } );
        $('#second_chart_start_datePicker').datetimepicker( {
            format: 'YYYY-MM-DD',
            defaultDate: getChartDate(get('activeMeasurement'), get('secondChartType'), 'start'),
            focusOnShow: false,
        } );
        $('#first_chart_end_datePicker').datetimepicker( {
            format: 'YYYY-MM-DD',
            useCurrent: false, // important due to some issue with the API
            defaultDate: getChartDate(get('activeMeasurement'), get('firstChartType'), 'end'),
            focusOnShow: false,
            showTodayButton: true
        } );
        $('#second_chart_end_datePicker').datetimepicker( {
            format: 'YYYY-MM-DD',
            useCurrent: false, // important due to some issue with the API
            defaultDate: getChartDate(get('activeMeasurement'), get('secondChartType'), 'end'),
            focusOnShow: false,
            showTodayButton: true
        } );

        // set chart date-picker module fields
        setChartDatePicker('first', 'start', $('#first_chart_start_datePicker').data("DateTimePicker"));
        setChartDatePicker('first', 'end', $('#first_chart_end_datePicker').data("DateTimePicker"));
        setChartDatePicker('second', 'start', $('#second_chart_start_datePicker').data("DateTimePicker"));
        setChartDatePicker('second', 'end', $('#second_chart_end_datePicker').data("DateTimePicker"));

        // initialize min/max limits on chart date-pickers (min/max options didn't seem to work, so doing it here instead)
        getChartDatePicker('first', 'start').maxDate(getChartDatePicker('first', 'end').date());
        getChartDatePicker('first', 'end').minDate(getChartDatePicker('first', 'start').date());
        getChartDatePicker('second', 'start').maxDate(getChartDatePicker('second', 'end').date());
        getChartDatePicker('second', 'end').minDate(getChartDatePicker('second', 'start').date());

        // set listeners for chart date-pickers and their update charts btn (on base object, not DateTimePicker data)
        $('.updateCharts-btn').click(updateCharts_clicked);
        $('#first_chart_start_datePicker').on('dp.change', datePicker_changed).on('dp.show', datePicker_clicked);
        $('#second_chart_start_datePicker').on('dp.change', datePicker_changed).on('dp.show', datePicker_clicked);
        $('#first_chart_end_datePicker').on('dp.change', datePicker_changed).on('dp.show', datePicker_clicked);
        $('#second_chart_end_datePicker').on('dp.change', datePicker_changed).on('dp.show', datePicker_clicked);
    }

    /** Disables the 2nd chart checkbox, 2nd chart tab, and unchecks the 2nd chart checkbox
     *  according to the specified parameters.
     * @param  {Boolean} checkboxDisable If true, the 2nd chart checkbox will be disabled.
     * @param  {Boolean} tabDisable If true, the 2nd chart tab will be disabled.
     * @param  {Boolean} checkboxUncheck If true, the 2nd chart checkbox will be unchecked. */
    function disableSecondChartOptions(checkboxDisable, tabDisable, checkboxUncheck) {
        if (checkboxDisable) // disable showSecondChart checkbox
            getControlFor('showSecondChart').prop('disabled', true).parent().addClass('disabled');

        if (tabDisable) { // click 1st chart tab and disable second chart tab
            $('#chartsOptions_tabs a[href="#firstChartOptions"]').click();
            $('#chartsOptions_tabs a[href="#secondChartOptions"]').removeAttr('data-toggle').addClass('disabled').off('click', chartSettingsTab_clicked);
        }

        if (checkboxUncheck) // uncheck 2nd chart checkbox
            getControlFor('showSecondChart').prop('checked', false);
    }

    /** Enables the checkbox and tab for the second chart options according to the given boolean values.
     * @param  {Boolean} checkboxEnable 2nd Chart checkbox is enabled if true.
     * @param  {Boolean} tabEnable 2nd Chart options tab is enabled if true. */
    function enableSecondChartOptions(checkboxEnable, tabEnable) {
        if (checkboxEnable) // enable showSecondChart checkbox
            getControlFor('showSecondChart').prop('disabled', false).parent().removeClass('disabled');

        if (tabEnable) // enable 2nd chart tab
            $('#chartsOptions_tabs a[href="#secondChartOptions"]').attr('data-toggle', 'tab').removeClass('disabled').on('click', chartSettingsTab_clicked);
    }

    function showTable_clicked(event) {
        var activeMeasurement = get('activeMeasurement');
        if (activeMeasurement === 'calories')
            activeMeasurement = 'calorie';
        set('showTable', ! get('showTable'));
        tables.toggleTables();
        forms.hide(activeMeasurement, 'add');
        forms.hide(activeMeasurement, 'edit');
        storeChanges();
    }

    function numRows_changed() {
        var newNumRows = getControlFor('numRows').val();
        set('numRows', newNumRows);
        tables.setNumRows(newNumRows);
        storeChanges();
    }

    /** Updates start/end date-pickers in options well for the specified chart. */
    function updateChartDatePickers(measType, chartType, whichChart) {
        var oldStartDate = getChartDatePicker(whichChart, 'start').date().format('YYYY-MM-DD');
        var oldEndDate   = getChartDatePicker(whichChart, 'end').date().format('YYYY-MM-DD');
        var newStartDate = getChartDate(measType, chartType, 'start');
        var newEndDate   = getChartDate(measType, chartType, 'end');

        /* clear min/max on date pickers if necessary so no conflicts arise when setting the new dates.
         * (clearing unnecessarily results in date-picker change event not firing, failing to apply min/max) */
        if (oldEndDate !== newEndDate)
            getChartDatePicker(whichChart, 'start').maxDate(false);
        if (oldStartDate !== newStartDate)
            getChartDatePicker(whichChart, 'end').minDate(false);

        // update dates in options area
        getChartDatePicker(whichChart, 'start').date(newStartDate);
        getChartDatePicker(whichChart, 'end').date(newEndDate);
    }

    function datePicker_clicked() {
        var pieces = $(this).attr('id').split('_');
        var whichChart = pieces[0];
        var whichDate = pieces[2];
        var otherDate = (whichDate === 'start') ? 'end' : 'start';

        // hide linked date picker
        getChartDatePicker(whichChart, otherDate).hide();
    }

    // called when a chart date-picker date is selected options
    function datePicker_changed() {
        var pieces = $(this).attr('id').split('_');
        var whichChart = pieces[0];
        var chosenDate = pieces[2];
        var dateToLimit =  (chosenDate === 'start') ? 'end' : 'start';
        var minOrMax = (chosenDate === 'start') ? 'min' : 'max';

        // update linked date picker's limits
        getChartDatePicker(whichChart, dateToLimit)[minOrMax+'Date'](getChartDatePicker(whichChart, chosenDate).date());
    }

    function showFirstChart_clicked() {
        var activeMeasurement = get('activeMeasurement');
        if (activeMeasurement === 'calories')
            activeMeasurement = 'calorie';

        // showFirstChart is being checked
        if (getControlFor('showFirstChart').is(':checked')) {
            set('showFirstChart', true);
            charts.showFirstCharts();
            if ($(window).width() >= smallScreen_limit)
                enableSecondChartOptions(true, false);

        }
        // showFirstChart is being unchecked
        else {
            set('showFirstChart', false);
            set('showSecondChart', false);
            charts.hideBothCharts(); // this also causes the second charts to be hidden
            disableSecondChartOptions(true, true, true);
        }

        storeChanges();
    } // end showFirstChart_clicked

    function showSecondChart_clicked() {
        var activeMeasurement = get('activeMeasurement');
        if (activeMeasurement === 'calories')
            activeMeasurement = 'calorie';

        // showSecondChart is being checked
        if (getControlFor('showSecondChart').is(':checked')) {
            set('showSecondChart', true);
            charts.showSecondCharts();
            enableSecondChartOptions(false, true);
        }
        // showSecondChart is being unchecked
        else {
            set('showSecondChart', false);
            charts.hideSecondCharts();
            disableSecondChartOptions(false, true, false);
        }

        storeChanges();
    } // end showSecondChart_clicked

    // called by the update chart button in options, finalizing any date range changes
    function updateCharts_clicked() {
        var whichChart = $(this).attr('id').split('_')[0]; // update charts button ID example: first_chart_update_btn
        var measType = get('activeMeasurement');
        var startDate = $('#options_'+whichChart+'Chart_startDate').val();       // appropriate text input box controlled by a date picker
        var endDate = $('#options_'+whichChart+'Chart_endDate').val();           // appropriate text input box controlled by a date picker
        measType = (measType === 'calories') ? 'calorie' : measType;
        var avgOrTotal = ($.inArray(measType, ['calorie', 'sleep', 'exercise']) === -1) ? 'Averages' : 'Totals';
        var chartType = get(whichChart+'ChartType');

        // store updated start and end data for this chart
        var measTypeAdjusted = (measType === 'calorie') ? 'calories' : measType;
        setChartDate(measType, chartType, 'start', startDate);
        setChartDate(measType, chartType, 'end', endDate);

        charts.create(measType, chartType, whichChart);
        storeChanges();
    }

    /** Stores the current session's options in the server database. */
    function storeChanges() {
        // add some constants to some options until those features get fully implemented
        measurementsOptions.optionsName = 'Session';
        measurementsOptions.oldOptionsName = 'Session';
        measurementsOptions.isActive = true;
        measurementsOptions.showSecondaryCols = true;
        measurementsOptions.tableSize = 35;
        measurementsOptions.chartPlacement = 'bottom';

        // send add request to server
        $.ajax({
            url: 'measurementsOptions_edit',
            data: measurementsOptions,
            dataType: 'json',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    if (response.data.rowsAffected < 1)
                        return;
                }
                else
                    alert('Changes to options storing failed: ' +response.error);
            },
            error: function() { alert('Error: invalid response when attempting to store changes.'); }
        });
    }

    // called when save changes button is clicked in units modal
    function saveUnitsChanges() {
        var changedMeasurements = [];

        // determine glucose unit selection and update hidden data in the DOM if necessary
        var selectedGlucoseUnits = $('#options_glucoseUnits input:checked').val();
        if (selectedGlucoseUnits !== get('glucoseUnits')) {
            set('glucoseUnits', selectedGlucoseUnits);
            changedMeasurements.push('glucose');
        }

        // determine weight unit selection and update hidden data in the DOM if necessary
        var selectedWeightUnits = $('#options_weightUnits input:checked').val();
        if (selectedWeightUnits !== get('weightUnits')) {
            set('weightUnits', selectedWeightUnits);
            changedMeasurements.push('weight');
        }

        // if any changes were made then update charts/tables/forms and store the changes
        if (changedMeasurements.length > 0) {
            tables.refresh(changedMeasurements);
            charts.updateUnits(changedMeasurements);
            forms.refresh(changedMeasurements);
            storeChanges();
        }
    }

    // called when cancel or X button is clicked in units modal
    function cancelUnitsChanges() {
        var glucoseUnits = get('glucoseUnits');
        var weightUnits = get('weightUnits');

        // reset glucose units radio buttons to original selection
        if (glucoseUnits === 'mg/dL')
            $('#options_units_glucose_mgdL').prop('checked', true);
        else if (glucoseUnits === 'mM')
            $('#options_units_glucose_mM').prop('checked', true);

        // reset weight units radio buttons to original selection
        if (weightUnits === 'lbs')
            $('#options_units_weight_lbs').prop('checked', true);
        else if (weightUnits === 'kg')
            $('#options_units_weight_kg').prop('checked', true);
    }

    // a column visibility dropdown menu item was clicked
    function columnVisibility_clicked(event) { // TODO This only works for date/time/notes columns. Secondary columns not yet implemented.
        var colName = $(this).attr('id').split('_')[1];
        var iconSpan = $('span:first', this);
        if (iconSpan.hasClass('glyphicon')) {
            // hide checkmark icon and specified table column
            set('show'+utils.capFirst(colName)+'Col', false);
            iconSpan.removeClass('glyphicon glyphicon-ok');
            $('#colvis_'+colName+'_text').css('margin-left', '1.6em');
            tables.hideColumn(colName);
        } else {
            // show checkmark icon and specified table column
            set('show'+utils.capFirst(colName)+'Col', true);
            iconSpan.addClass('glyphicon glyphicon-ok');
            $('#colvis_'+colName+'_text').css('margin-left', '0em');
            tables.showColumn(colName);
        }

        storeChanges();
        event.preventDefault();
    }

    function showTooltips_clicked() {
        if (document.getElementById('options_showTooltips').checked) {
            set('showTooltips', true);
            $('.tooltip-help').tooltip('enable');
        }
        else {
            set('showTooltips', false);
            $('.tooltip-help').tooltip('disable');
        }

        storeChanges();
    }

    // a time format (12/24-hour) was selected; switch display of tables/charts/forms accordingly
    function timeFormat_selected() {
        set('timeFormat', $(this).val());
        tables.refresh(measurementTypes);
        forms.refresh(measurementTypes);
        storeChanges();
    }

    function chartSettingsTab_clicked(event) {
        $(this).tab('show');
        event.preventDefault();
    }

    return {
        /** init() */
        init: initialize,
        /** get(optionName) : Accesses the current active measurements options by name. */
        get: get,
        getUnits: getUnits,
        /** getChartDate(measType, chartType, whichDate) :
         * Convenience function for accessing a chart date range option. measType is like 'bloodPressure', 'exercise', etc.
         * chartType is something like 'individual', 'weekly', etc.  whichDate must be either 'start' or 'end'.  */
        getChartDate: getChartDate,
        /** getControlFor(optionName) : Returns a jQuery object for the DOM element controlling the specified option.
         * @param {String} optionName The name of some option, e.g. 'showFirstChart' or 'showTooltips'. */
        getControlFor: getControlFor,
        /** set(optionName, value) : Sets the specified option to the specified value */
        set: set,
        /** save() : Stores the current session's options in the server database. */
        save: storeChanges,
        /** disableSecondChartOptions(checkboxDisable, tabDisable, checkboxUncheck) :
         *  Disables the 2nd chart checkbox, 2nd chart tab, and unchecks the 2nd chart checkbox
         *  according to the specified parameters.
         * @param  {Boolean} checkboxDisable If true, the 2nd chart checkbox will be disabled.
         * @param  {Boolean} tabDisable If true, the 2nd chart tab will be disabled.
         * @param  {Boolean} checkboxUncheck If true, the 2nd chart checkbox will be unchecked. */
        disableSecondChartOptions: disableSecondChartOptions,
        /** enableSecondChartOptions(checkboxEnable, tabEnable) :
         *  Enables the checkbox and tab for the second chart options according to the given boolean values.
         * @param  {Boolean} checkboxEnable 2nd Chart checkbox is enabled if true.
         * @param  {Boolean} tabEnable 2nd Chart options tab is enabled if true. */
        enableSecondChartOptions: enableSecondChartOptions,
        /** updateChartDatePickers(measType, chartType, whichChart) : Updates start/end date-pickers in options well for the specified chart. */
        updateChartDatePickers: updateChartDatePickers,
        /**
         * getChartDatePicker(whichChart, startOrEnd) : Returns the start/end date-picker for the first/second chart.
         *  @param {String} whichChart Must be either 'first' or 'second'.
         *  @param {String} startOrEnd Must be either 'start' or 'end'. */
        getChartDatePicker: getChartDatePicker
    };

})(); // end options module

var tables = (function() {

    var selectedRows = [];

    function initialize() {
        // request data from server and create initial+ tables
        $.each(measurementTypes, function(index, measType) {
            setTable(measType, tableOptions(measType));
        });

        // show/hide table according to loaded measurements options
        if (! options.get('showTable'))
            $.each(measurementTypes, function(index, measType) { $('#'+measType+'_table_section').hide(); } );
    }

    /** Returns the DataTables table object for the specified measurement type. */
    function getTable(measType) {
        return $('#'+measType+'_table').DataTable();
    }

    function setTable(measType, tableOptionsObject) {
        var table = $('#'+measType+'_table').DataTable(tableOptionsObject);
            table.button(0, null).container().addClass('btn-group-justified');
            table.on('select', row_clicked);
            table.on('deselect', row_deselected);
            table.page.len(options.get('numRows')); // shouldn't be necessary, but initial pageLength option seems buggy in DataTables API
    }

    /** buttonType must be 'add', 'edit', or 'delete' */
    function getButton(dt, measType, buttonType) {
        return dt.button($('#'+measType+'_'+buttonType)).node();
    }

    function setNumRows(num) {
        $.each(measurementTypes, function(index, measType) {
            getTable(measType).page.len(num).draw();
        });
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
        getButton(dt, measType, 'delete').show();
        if (indexes.length > 1)
            getButton(dt, measType, 'edit').hide();
        else
            getButton(dt, measType, 'edit').show();

        // if edit form is visible, fill the edit form with data from the currently selected measurement
        if (indexes.length == 1 && forms.isFormVisible(measType, 'edit')) {
            var displayUnits = options.getUnits(measType);
            var nonMeasParts = ['date', 'time', 'notes', 'type'];
            var row = selectedRows[0].data(); // this is the original object returned from the server to create the row
            var cellValue;
            for (var key in row) {
                if (displayUnits !== row.units && $.inArray(key, nonMeasParts) === -1)
                    cellValue = utils.convertUnits(row[key], row.units, displayUnits);
                else
                    cellValue = row[key];
                forms.set(measType, 'edit', key, cellValue);
            }
            forms.setTime(measType, 'edit', row.time, row.date);
        }

        // if add form is visible, hide it
        if (forms.isFormVisible(measType, 'add'))
            forms.hide(measType, 'add');
    }

    function row_deselected(e, dt, type, indexes) {
        var measType = $(this).attr('id').split('_')[0];
        getButton(dt, measType, 'edit').hide();
        getButton(dt, measType, 'delete').hide();
        if (selectedRows.length === 0)
            forms.hide(measType, 'edit');
    }

    // returns the table options object for the specified type of measurement (e.g. for bloodPressure, or exercise, etc.)
    function tableOptions(measType) {

        // determine current column visibility options
        var dateVisible = options.get('showDateCol'); // $('#colvis_date span:first').hasClass('glyphicon');
        var timeVisible = options.get('showTimeCol'); // $('#colvis_time span:first').hasClass('glyphicon');
        var notesVisible = options.get('showNotesCol'); // $('#colvis_notes span:first').hasClass('glyphicon');

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
                if (type === 'display' && options.get('timeFormat') === '12 hour')
                    return utils.to12Hour(data);
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
            columns.unshift( { name: 'type', data: 'type', title: 'Type', visible: (options.get('showSecondaryCols') === true) } );
        for (var i = propNames.length-1; i >= 0; i--) {
            columns.unshift({
                name: propNames[i],
                data: propNames[i],
                title: utils.toDisplayName(propNames[i]) + ' (' +options.getUnits(measType)+ ')',
                render: function(data, type, fullRow, meta) {
                    // for display purposes, it may be necessary to convert data to the units specified in the current measurements options preset
                    if (type === 'display') {
                        var displayUnits = options.getUnits(measType);
                        if (displayUnits !== fullRow.units)
                            return utils.convertUnits(data, fullRow.units, displayUnits);
                    }
                    return data;
                }
            });
        }

        // request data from server, then create and return table options object
        return {
            ajax: { url: '/dhma/measurements_get_'+measType+'_all', dataSrc: '' },
            columns: columns,
            order: [[orderIndex, 'desc'], [orderIndex+1, 'desc']], // order descending by date then by time
            scrollY: '35vh',
            scrollCollapse: true,
            lengthChange: false,
            processing: true,
            paging: true,
            pagingType: 'numbers',
            pageLength: options.get('numRows'),
            select: { style: 'single' },
            dom:
                "<'row'<'col-sm-6'><'col-sm-6'f>>" +   // sets filter (search) box in upper right
                "<'row'<'col-sm-12'B>>" +              // set add/edit/delete buttons as top row
                "<'row'<'col-sm-12'tr>>" +             // table and processing message
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",   // page info and pagination controls in buttom left and right, respectively
            createdRow: function (row, data, dataIndex) { // add a tooltip to the row
                $(row).attr('data-toggle', 'tooltip').attr('title', 'Rows can be selected for editing/deletion').addClass('dynamic-tooltip tooltip-help');
                if (! $('#options_showTooltips').is(':checked'))
                    $(row).tooltip('disable');
            },
            initComplete: function (settings, json) { // when the table is finished loading, add tooltips to column headers
                $('#'+measType+'_table_section th').each(function (index, element) {
                    $(element).attr('data-toggle', 'tooltip').attr('data-placement', 'bottom').attr('title', 'Sort by this column');
                });
            },
            buttons: table_addEditDeleteButtons_options(measType) // creates the add/edit/delete buttons for the table
        };
    }

    function table_addEditDeleteButtons_options(measType) {
        return {
                name: 'add_edit_delete',
                buttons: [
                    { // add button
                        name: measType+'_add',
                        text: 'Add',
                        init: function (dt, node, config) {
                            node.attr('id', measType+'_add');
                            node.addClass('addMeasurement_btn');
                            node.prepend('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;');
                        },
                        action: function (e, dt, node, config) {
                            forms.show(measType, 'add');
                            if (selectedRows.length > 0) {

                                // deselect rows
                                $.each(selectedRows, function(index, row) { row.deselect(); } );

                                // hide edit and delete buttons
                                getButton(dt, measType, 'edit').hide();
                                getButton(dt, measType, 'delete').hide();
                            }

                            // change Done button to Cancel button
                            if ($('#cancel_add_'+measType+'_text').text() == 'Done')
                                $('#cancel_add_'+measType+'_text').text('Cancel');

                            // fill in date/time fields of add form with current date and time and clear the first field
                            forms.setTime(measType, 'add', new Date());
                            $('#'+measurementParts[measType][0]+'_'+measType+'_add').val('');

                            // scroll to first field of form on extra-small screens
                            if ($(window).width() < smallScreen_limit)
                                $('html, body').animate( { scrollTop: $('#add_'+measType+'_section').offset().top }, 200);
                            else
                                $('#' +measurementParts[measType][0]+'_'+measType+'_add').focus();
                        }
                    },

                    { // edit button
                        name: measType+ '_edit',
                        extend: 'selectedSingle',
                        text: 'Edit',
                        init: function (dt, node, config) {
                            node.hide().attr('id', measType+ '_edit');
                            node.prepend('<span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;');
                        },
                        action: function (e, dt, node, config) {
                            // fill the edit form with data from the currently selected measurement
                            var displayUnits = options.getUnits(measType);
                            var nonMeasParts = ['date', 'time', 'notes', 'type'];
                            var row = selectedRows[0].data(); // this is the original object returned from the server to create the row
                            var cellValue;
                            for (var key in row) {
                                if (displayUnits !== row.units && $.inArray(key, nonMeasParts) === -1)
                                    cellValue = utils.convertUnits(row[key], row.units, displayUnits);
                                else
                                    cellValue = row[key];
                                forms.set(measType, 'edit', key, cellValue);
                            }
                            forms.setTime(measType, 'edit', row.time, row.date);

                            // show the edit form and scroll to first field on extra-small screens
                            forms.show(measType, 'edit');
                            if ($(window).width() < smallScreen_limit)
                                $('html, body').animate( { scrollTop: $('#edit_'+measType+'_section').offset().top }, 200);
                            else
                                $('#'+measurementParts[measType][0]+'_'+measType+'_edit').focus();
                        }
                    },

                    { // delete button
                        name: measType+'_delete',
                        extend: 'selected',
                        text: 'Delete',
                        init: function(dt, node, config) {
                            node.hide().attr('id', measType+'_delete').addClass('btn-danger');
                            node.prepend('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;');
                        },
                        action: function(e, dt, node, config) {
                            if (window.confirm('Are you sure you want to delete the selected measurement(s)?')) {
                                var measType = node.attr('id').split('_')[0];

                                // send delete request to measurements controller
                                $.ajax( {
                                    url: 'measurements_delete_'+measType+'_'+
                                        getSelectedRow().date+' '+
                                        getSelectedRow().time.replace(':', '-'),
                                    data: { json: true },
                                    dataType: 'json',
                                    method: 'POST',
                                    success: function(response) {
                                        if (response.result) {
                                            var targetRows = getSelectedRows();
                                            clearSelectedRows();

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
                                                    dt.button($('#'+measType+'_edit')).node().hide();
                                                    dt.button($('#'+measType+'_delete')).node().hide();
                                                }
                                            }, 100);

                                            // refresh charts
                                            charts.refresh('first');
                                            charts.refresh('second');
                                        }
                                        else
                                            alert('delete failed: '+response.error);
                                    },
                                    error: function() { alert('error'); }
                                } );
                            }
                        }
                    }
                ]
            };
    } // end table_addEditDeleteButtons_options

    function toggleTables() {
        $.each(measurementTypes, function(index, measType) { $('#'+measType+'_table_section').toggle(); });
        var activeMeasurement = options.get('activeMeasurement');
        if ($('#'+activeMeasurement+'_table_section').is(':visible'))
            $('#'+activeMeasurement+'_table').DataTable().draw(); // avoids column alignment issues on show
    }

    /** Hides the specified column from all applicable tables. */
    function hideColumn(colName) {
        if ($.inArray(colName, ['date', 'time', 'notes']) !== -1) {
            $.each(measurementTypes, function(index, measType) {
                getTable(measType).column(colName+':name').visible(false);
            });
        }
        else
            $('.measurement-table:visible').DataTable().column(colName+':name').visible(false);
    }

    /** Shows the specified column in the currently visible table. */
    function showColumn(colName) { // TODO this is how it's been for a long time, but I'm not sure if it makes sense
        $('.measurement-table:visible').DataTable().column(colName+':name').visible(true);
    }

    /** Updates the specified measurement types' tables to the currently selected units option, as well as the currently selected time format. */
    function refreshTables(changedMeasurements) {
        $.each(changedMeasurements, function(index, measType) {
            var table = getTable(measType);
            table.rows().invalidate().draw();                              // update table rows
            $.each(measurementParts[measType], function(index, partName) { // update appropriate table column header(s)
                $(table.column(index).header()).text(utils.toDisplayName(partName)+' ('+options.getUnits(measType)+')');
            });
        });
    }

    function refreshAllTables() {
        refreshTables(measurementTypes);
    }

    /** Adds the row data to the table of the specified measurement type. The new row
     * is highlighted green for a few seconds.
     * @param {String} measType Must be something like 'bloodPressure' or 'exercise'.
     * @param {Object} rowData Contains data returned from the server about the row to add. */
    function addRow(measType, rowData) {
        var newRow = getTable(measType).row.add(rowData).draw();
        $(newRow.node()).addClass('success');
        setTimeout(function() { $(newRow.node()).removeClass('success'); }, 3000);
    }

    /** Edits the currently selected row, replacing its data with newRowData.
     * @param  {Object} newRowData Contains key-value pairs for the new row data. */
    function editSelection(newRowData) {
        var row = selectedRows[0];
        row.data(newRowData);
        $(row.node()).addClass('success black-text');
        setTimeout(function() { $(row.node()).removeClass('success black-text'); }, 3000);
    }

    /** redraw(measType) : Redraws the table of the specified measurement type. */
    function redrawTable(measType) {
        getTable(measType).draw();
    }

    function getSelectedRow()    { return selectedRows[0].data(); }
    function getSelectedRows()   { return selectedRows;           }
    function clearSelectedRows() { selectedRows = [];             }

    return {
        init: initialize,
        /** setNumRows(num) : Sets the number of rows per page of the table to the specified integer num. */
        setNumRows: setNumRows,
        toggleTables: toggleTables,
        /** refresh(changedMeasurements) : Updates the specified measurement types' tables to the currently selected units option, as well as the currently selected time format.
         * changedMeasurements must be an array of measurement types, e.g. 'bloodPressure', 'exercise', etc. */
        refresh: refreshTables,
        /** redraw(measType) : Redraws the table of the specified measurement type. */
        redraw: redrawTable,
        /** hideColumn(colName) : Hides the specified column from all applicable tables. */
        hideColumn: hideColumn,
        /** showColumn(colName) : Shows the specified column in the currently visible table. */
        showColumn: showColumn,
        /** addRow(measType, rowData) : Adds the row data to the table of the specified measurement type.
         *  The new row is highlighted green for a few seconds.
         * @param {String} measType Must be something like 'bloodPressure' or 'exercise'.
         * @param {Object} rowData Contains data returned from the server about the row to add. */
        addRow: addRow,
        /** editSelection(newRowData) : Edits the currently selected row, replacing its data with newRowData.
         * @param  {Object} newRowData Contains key-value pairs for the new row data. */
        editSelection: editSelection,
        getSelectedRow: getSelectedRow,
        getSelectedRows: getSelectedRows,
        clearSelectedRows: clearSelectedRows
    };

})(); // end table module

var charts = (function() {

    var cumulativeMeasurements = ['calorie', 'exercise', 'sleep'];

    var chartTypeStrings = {
        individual: {
            type: 'individual',
            xProperty: 'dateAndTime',
            title: 'Individual Entries'
        },
        daily: {
            type: 'day',
            xProperty: 'day',
            title: 'Daily'
        },
        weekly: {
            type: 'week',
            xProperty: 'week',
            title: 'Weekly'
        },
        monthly: {
            type: 'month',
            xProperty: 'month',
            title: 'Monthly'
        },
        yearly: {
            type: 'year',
            xProperty: 'year',
            title: 'Yearly'
        }
    };

    var chartObjs = {
        glucose_firstChart: null,
        glucose_secondChart: null,
        bloodPressure_firstChart: null,
        bloodPressure_secondChart: null,
        calorie_firstChart: null,
        calorie_secondChart: null,
        exercise_firstChart: null,
        exercise_secondChart: null,
        sleep_firstChart: null,
        sleep_secondChart: null,
        weight_firstChart: null,
        weight_secondChart: null,
    };

    function initialize() {
        // creates first and second charts for all measurement types using the current options for chartType and start/end dates.
        $.each(measurementTypes, function(index, measType) {
            createChart(measType, options.get('firstChartType'), 'first');
            createChart(measType, options.get('secondChartType'), 'second');
        });

        // show/hide charts according to loaded measurements options and current screen size, and disable showSecondChart checkbox if needed
        if (! options.get('showFirstChart')) {
            hideBothCharts();
            options.disableSecondChartOptions(true, true, false);
        }
        else if (! options.get('showSecondChart') || $(window).width() < smallScreen_limit) {
            hideSecondCharts();
            options.disableSecondChartOptions(false, true, false);
        }
        if ($(window).width() < smallScreen_limit)
            options.disableSecondChartOptions(true, true, false);

        // make chart date subtitles clickable to allow easy start/end date editing
        $('#measurement_sections').on('click', '.first-chart-start-date', chartSubtitle_clicked);
        $('#measurement_sections').on('click', '.first-chart-end-date', chartSubtitle_clicked);
        $('#measurement_sections').on('click', '.second-chart-start-date', chartSubtitle_clicked);
        $('#measurement_sections').on('click', '.second-chart-end-date', chartSubtitle_clicked);

        // assign handlers for chart type buttons
        $('.btn-change-chart').click(viewNewChart);
        
        $('#refreshCharts').click(refreshAll);
    }

    /** Returns the actual Charts object for the first/second chart of the specified measurement type.
     * measType is something like 'bloodPressure' or 'exercise', etc.
     * whichChart must be either 'first' or 'second', since each measurement type has two possible charts. */
    function getChart(measType, whichChart) {
        if (measType === 'calories')
            measType = 'calorie';
        return chartObjs[measType+'_'+whichChart+'Chart'];
    }

    /** Creates a chart from the given options, replaces any existing chart for the specified measurement type and 1st/2nd position.
     * @param {String} measType Must be something like 'bloodPressure' or 'exercise'.
     * @param {String} whichChart Must be either 'first' or 'second'.
     * @param {Object} newChartOptions Must be an object containing options for the HighCharts.Chart object initialization. */
    function setChart(measType, whichChart, newChartOptions) {
        if (measType === 'calories')
            measType = 'calorie';
        if (getChart(measType, whichChart) !== null)
            getChart(measType, whichChart).destroy();

        chartObjs[measType+'_'+whichChart+'Chart'] = new Highcharts.Chart(newChartOptions);
    }

    function getChartParent(measType, whichChart) {
        return $('#'+whichChart+'Chart_'+measType);
    }

    /** Returns the DOM element containing all buttons for the specified chart.
     * whichCharts must be either 'first' or 'second'. */
    function getChartButtons(whichCharts) {
        return $('#'+whichCharts+'_chartType_btns');
    }

    /** Create a chart with the specified properties.
     * @param measType The type of measurement, e.g. 'bloodPressure', 'exercise', etc.
     * @param chartType A.K.A. chartType, e.g. 'individual', 'weekly', etc.
     * @param whichChart Either 'first' or 'second' */
    function createChart(measType, chartType, whichChart) {
        var avgOrTotal = '';
        if (chartType !== 'individual')
            avgOrTotal = ($.inArray(measType, cumulativeMeasurements) === -1) ? ' Averages' : ' Totals';

        if (measType === 'calorie')
            measType = 'calories';

        var startDate = options.getChartDate(measType, chartType, 'start');
        var endDate = options.getChartDate(measType, chartType, 'end');

        if (measType === 'calories')
            measType = 'calorie';

        $.ajax({
            'url': 'measurements_get_'+measType+'_'+chartTypeStrings[chartType]['type']+'_'+startDate+'_'+endDate,
            'dataType': 'json',
            'success': function(response) {
                var measNames = measurementParts[measType];
                var data = [];
                var partName;

                // create a chart series for each measurement part (e.g. systolic/diastolic pressures)
                for (var i = 0; i < measNames.length; i++) {
                    partName = measNames[i];
                    data.push( {
                        name: partName,
                        data: chartSeries(response, chartTypeStrings[chartType]['xProperty'], partName, options.getUnits(measType))
                    } );
                }

                // create chart
                var title = chartTypeStrings[chartType]['title']+avgOrTotal;
                var subtitle = '<span class="link-text '+whichChart+'-chart-start-date tooltip-help dynamic-tooltip" data-toggle="tooltip" data-placement="left" title="Modify start date">'+startDate+'</span> to <span class="link-text '+whichChart+'-chart-end-date tooltip-help dynamic-tooltip" data-toggle="tooltip" data-placement="right" title="Modify end date">'+endDate+'</span>';
                var chartOpts = chartOptions(measType, title, data, chartTypeStrings[chartType]['type'], subtitle, whichChart);
                setChart(measType, whichChart, chartOpts);
            },
            'error': function() { alert('Error retreiving measurements'); }
        });
    } // end createChart

    function chartSeries(response, xValPropertyName, partName, displayUnits) {
        var currentSeries = [];

        for (var j = 0; j < response.length; j++) {
            var point = response[j];
            var yVal = parseFloat(point[partName]);
            var oldVal = null;

            if (point.units !== displayUnits) {
                oldVal = yVal;
                yVal = utils.convertUnits(yVal, point.units, displayUnits);
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

    function chartOptions(measType, title, data, per, subtitle, whichChart) {
        var series = [];
        var xValue;

        if (whichChart === 'first')
            whichChart = 'firstChart';
        if (whichChart === 'second')
            whichChart = 'secondChart';

        var chartOptions =
            {
                chart: {
                    renderTo: whichChart+'_'+measType,
                    type: 'line'
                },
                title: { text: title },
                subtitle: { text: subtitle, useHTML: true },
                credits: { enabled: false },
                xAxis: {
                    type: 'category',
                    labels: {
                        formatter: function() {
                            switch (per) {
                                case 'all':
                                case 'individual':
                                    var date = new Date(this.value);
                                    return utils.toShortMonth(date.getMonth(), true)+ ' ' +date.getDate();
                                case 'day': // example result: Aug 17
                                    return dayValue(this.value);
                                case 'week': // example results: Week 41 or (2015) Week 1
                                    return weekValue(this.value, this.isFirst);
                                case 'month': // example result: Sep or (2014) Nov
                                    return monthValue(this.value, this.isFirst);
                                case 'year': // example result: 2015
                                    return this.value;
                            }
                        }
                    }
                },
                yAxis: {
                    title: { text: options.getUnits(measType) }
                },
                series: data,
                legend: { labelFormatter: function() { return this.name.replace(/([a-z])([A-Z])/, "$1 $2").toLowerCase(); } },
                tooltip: {
                    shared: (measType === 'bloodPressure'),
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
                        var displayUnits = options.getUnits(measType);
                        var key = (measType === 'bloodPressure') ? this.points[0].key : this.key;

                        if (per === 'all' || per === 'individual') {
                            var date = new Date(key);
                            firstLineBody = date.toDateString();
                            secondLineBody = utils.toTimeString(date);
                        } else if (per === 'day')
                            firstLineBody = dayValue(key);
                        else if (per === 'week')
                            firstLineBody = weekValue(key, false);
                        else if (per === 'month')
                            firstLineBody = monthValue(key, false);
                        else if (per === 'year')
                            firstLineBody = key;

                        firstLine = firstLineHeader + firstLineBody + firstLineFooter;
                        if (per === 'all' || per === 'individual')
                            secondLine += secondLineHeader + secondLineBody + secondLineFooter;

                        resultStr = firstLine + secondLine;
                        if (measType === 'bloodPressure') {
                            $.each(this.points, function() {
                                resultStr +=
                                    '<br /><span style="color: ' +this.series.color+ '">\u25CF</span> ' +
                                    this.series.name.replace(/([a-z])([A-Z])/, "$1 $2").toLowerCase()+ ': <strong>' +this.y+ ' ' +displayUnits+ '</strong>';
                            });
                        } else {
                            resultStr +=
                                '<br /><span style="color: ' +this.series.color+ '">\u25CF</span> ' +
                                this.series.name+ ': <strong>' +this.y+ ' ' +displayUnits+ '</strong>';
                        }

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
            };

            return chartOptions;
    }

    /** Goes through the chart's points for the specified chart types and converts any values that are not already in the units specified.
     * changedMeasurements is an array of measurement type names, e.g. 'bloodPressure', 'exercise', etc. */
    function updateUnits(changedMeasurements) {
        var updateChart = function(chart, newUnits) {
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
                            yVal = utils.convertUnits(yVal, point.units, newUnits);
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
        };
        $.each(changedMeasurements, function(index, measType) {
            updateChart(getChart(measType, 'first'), options.getUnits(measType));
            updateChart(getChart(measType, 'second'), options.getUnits(measType));
        });
    }

    // called by the chart selection buttons below each chart
    function viewNewChart(event) {
        // collect information on the new chart to load
        var id_pieces = $(this).attr('id').split('_');
        var measType = options.get('activeMeasurement');
        var whichChart = id_pieces[0];
        var chartType = id_pieces[1];
        var oldChartType = getChartButtons(whichChart).find('.active').attr('id').split('_')[1];

        // deactivate/activate associated buttons
        getChartButtons(whichChart).find('.active').removeClass('active');
        $(this).addClass('active');

        // update/save chart type options and chart date pickers
        options.set(whichChart+'ChartType', chartType);
        options.updateChartDatePickers(measType, chartType, whichChart);
        options.save();

        // create new chart for the active measurement
        createChart(measType, chartType, whichChart);
    }

    function chartSubtitle_clicked() {
        var pieces = $(this).attr('class').split(' ')[1].split('-');
        var whichChart = pieces[0];
        var startOrEnd = pieces[2];

        // function to scroll-animate to charts tab and then open the correct date-picker
        var scrollAndClickDatePicker = function() {
            $('html, body').animate( { scrollTop: $("#firstChartOptions_tab").offset().top }, 200);
            setTimeout(function() {
                $('#'+whichChart+'ChartOptions_tab').click();
                options.getChartDatePicker(whichChart, startOrEnd).show();
            }, 200);
        };

        // open options well if necessary and open the correct date-picker
        if ($('#options').is(':visible'))
            scrollAndClickDatePicker();
        else {
            $('#options_btn').click();
            setTimeout(scrollAndClickDatePicker, 300);
        }
    }

    /** Hides all first and second charts as well, since the second chart is not allowed if the first chart isn't visible. */
    function hideBothCharts() {
        $.each(measurementTypes, function(index, measType) {
            getChartParent(measType, 'first').hide();
        });
        getChartButtons('first').hide();

        if (isVisible('second'))
            hideSecondCharts();
    }

    function showFirstCharts() {
        var activeMeasurement = options.get('activeMeasurement');
        if (activeMeasurement === 'calories')
            activeMeasurement = 'calorie';

        // show all first charts
        $.each(measurementTypes, function(index, measType) {
            getChartParent(measType, 'first').show();
        });
        getChartButtons('first').show();

        getChart(activeMeasurement, 'first').reflow();
    }

    function hideSecondCharts() {
        var activeMeasurement = options.get('activeMeasurement');
        if (activeMeasurement === 'calories')
            activeMeasurement = 'calorie';

        // hide all second charts and stretch all first charts
        $.each(measurementTypes, function(index, measType) {
            getChartParent(measType, 'second').hide();
            getChartParent(measType, 'first').removeClass('col-sm-6').addClass('col-sm-12');
            // getChartParent(measType, 'first').hide();
        });
        getChartButtons('second').hide();
        getChartButtons('first').parent().removeClass('col-sm-6').addClass('col-sm-12');
        // getChartButtons('first').hide();

        if (getChart(activeMeasurement, 'first') !== null)
            getChart(activeMeasurement, 'first').reflow();
    }

    function showSecondCharts() {
        // show all second charts and shrink all first charts
        $.each(measurementTypes, function(index, measType) {
            getChartParent(measType, 'second').show();
            getChartParent(measType, 'first').removeClass('col-sm-12').addClass('col-sm-6');
        });
        getChartButtons('second').show();
        getChartButtons('first').parent().removeClass('col-sm-12').addClass('col-sm-6');
        getChart(options.get('activeMeasurement'), 'second').reflow();
        getChart(options.get('activeMeasurement'), 'first').reflow();
    }

    /** whichCharts must be either 'first' or 'second'. */
    function isVisible(whichCharts) {
        var activeMeasurement = options.get('activeMeasurement');
        if (activeMeasurement === 'calories')
            activeMeasurement = 'calorie';
        return $('#'+whichCharts+'Chart_'+activeMeasurement).is(':visible');
    }

    /** Converts a date string in YYYY-MM-DD format to something like Aug 17 */
    function dayValue(value) {
        var pieces = value.split('-');
        return utils.toShortMonth(parseInt(pieces[1]), false)+' '+pieces[2];
    }

    /** Converts a date week string in YYYY-WW format to something like Week 41.
     * The year is added to the beginning in parenthesis if isFirst is true. */
    function weekValue(value, isFirst) {
        var result = '';
        var pieces = value.split('-');
        if (isFirst || pieces[1] == 1)
            result += '('+pieces[0]+') ';
        return result+'Week '+(parseInt(pieces[1])+1);
    }

    /** Converts a date month string in YYYY-MM format to something like Sep.
     * The year is added to the beginning in parenthesis if isFirst is true. */
    function monthValue(value, isFirst) {
        var year;
        var month;
        var result = '';
        var pieces = value.split('-');

        year = pieces[0];
        month = pieces[1];

        if (isFirst || month == 1)
            result += '('+year+') ';
        return result + utils.toShortMonth(parseInt(month), false);
    }

    function refreshChart(whichChart) {
        createChart(
            options.get('activeMeasurement'),
            getChartButtons(whichChart).find('.active').attr('id').split('_')[1],
            whichChart
        );
    }
    
    function refreshAll() {
        $.each(measurementTypes, function(index, measType) {
            createChart(measType, options.get('firstChartType'), 'first');
            createChart(measType, options.get('secondChartType'), 'second');
        });
    }

    return {
        /** get(measType, whichChart) : Returns the actual Charts object for the first/second chart of the specified measurement type.
         * measType is something like 'bloodPressure' or 'exercise', etc.
         * whichChart must be either 'first' or 'second', since each measurement type has two possible charts. */
        get: getChart,
        /** init() : Creates first and second charts for all measurement types using the current options for chartType and start/end dates. */
        init: initialize,
        /** create(measType, chartType, whichChart) : Create a chart with the specified properties.
         * chartType A.K.A. chartType examples: 'individual', 'weekly', etc. whichChart is either 'firstChart' or 'secondChart' */
        create: createChart,
        /** isVisible(whichCharts) : Returns true if the specified charts are visible, false otherwise. whichCharts must be either 'first' or 'second'. */
        isVisible: isVisible,
        /** Hides first charts for all measurement types, as well as all second charts.. */
        hideBothCharts: hideBothCharts,
        /** Shows first charts for all measurement types. */
        showFirstCharts: showFirstCharts,
        /** Hides second charts for all measurement types. */
        hideSecondCharts: hideSecondCharts,
        /** Shows second charts for all measurements types. */
        showSecondCharts: showSecondCharts,
        /** refresh(changedMeasurements) : Goes through the chart's points for the specified chart types and converts any values that are not already in the units specified.
         * changedMeasurements must be an array of measurement type names, e.g. 'bloodPressure', 'exercise', etc. */
        updateUnits: updateUnits,
        refresh: refreshChart,
        refreshAll: refreshAll
    };
})(); // end charts module

var forms = (function() {

    function initialize() {
        // hide add/edit forms and secondary cols option on load
        $('.add_measurement_section').hide();
        $('.edit_measurement_section').hide();
        $('.col-visibility-exercise').hide();

        // add listener for add/save submit actions and cancel button
        $('.add_measurement_section').submit(addMeasurement);
        $('.edit_measurement_section').submit(editMeasurement);
        $('.cancelMeasurement').click(cancelMeasurement);

        // add date/time pickers for add/edit forms
        $('.date-picker').datetimepicker( {
            format: 'YYYY-MM-DD',
            defaultDate: Date.now(),
            showTodayButton: true,
            focusOnShow: false,
        } );
        $('.time-picker').datetimepicker( {
            format: 'h:mm a',
            defaultDate: Date.now(),
            focusOnShow: false,
        } );

        // add today/now buttons to add/edit date/time form fields
        $('#measurement_sections .today-btn, #measurement_sections .now-btn').click(function() {
            var pieces = $(this).attr('id').split('_');
            var measType = pieces[0];
            var addOrEdit = pieces[1];
            var dateOrTime = pieces[2];
            $('#'+addOrEdit+'_'+measType+'_section .'+dateOrTime+'-picker').data('DateTimePicker').date(new Date());
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
    }

    function getVal(measType, formType, key) {
        return $('#'+key+'_'+measType+'_'+formType).val();
    }

    function setVal(measType, formType, key, value) {
        $('#'+key+'_'+measType+'_'+formType).val(value);
    }

    /** formType must be either 'add' or 'edit'.  updateOldDateTime must be a boolean value. */
    function setTime(measType, formType, time, date) {
        $('#'+formType+'_'+measType+'_section .time-picker').data('DateTimePicker').date(time);
        if (typeof date !== 'undefined')
            $('#oldDateTime_'+measType).val(date+' '+time);
    }

    function addMeasurement(event) {
        var measType = $(this).attr('id').split('_')[1];

        // collect data unique to each measurement
        var measData = {};
        for (var i = 0; i < measurementParts[measType].length; i++) {
            var partName = measurementParts[measType][i];
            measData[partName] = $('#'+partName+'_'+measType+'_add').val().trim();
        }
        if (measType === 'exercise')
            measData.type = $('#type_exercise_add').val().trim();

        // collect data common to each measurement
        measData.date = $('#date_'+measType+'_add').val().trim();
        measData.time = utils.to24Hour($('#time_'+measType+'_add').val().trim());
        measData.notes = $('#notes_'+measType+'_add').val().trim();
        measData.userName = $('#userName_'+measType+'_add').val().trim();
        measData.units = options.getUnits(measType);
        measData.json = true;

        // send add request to server and process response
        $.ajax({
            url: 'measurements_add_'+measType,
            data: measData,
            dataType: 'json',
            method: 'POST',
            success: function(response) {
                if (response.result) {
                    // add row to the table and refresh charts
                    tables.addRow(measType, measData);
                    charts.refresh('first');
                    charts.refresh('second');

                    // change Cancel button to Done button
                    $('#cancel_add_'+measType+'_text').text('Done');

                    // clear and put focus in first field of form
                    $('#'+measurementParts[measType][0]+'_'+measType+'_add').val('').focus();
                }
                else
                    alert('Add failed: '+response.error);
            },
            error: function() { alert('Error: unable to communiate with server. Try again later.'); }
        });

        event.preventDefault();
    }

    function editMeasurement(event) {
        var measType = $(this).attr('id').split('_')[1];

        // collect data unique to each measurement
        var measData = {};
        for (var i = 0; i < measurementParts[measType].length; i++) {
            var partName = measurementParts[measType][i];
            measData[partName] = $('#'+partName+'_'+measType+'_edit').val().trim();
        }
        if (measType === 'exercise')
            measData.type = $('#type_exercise_edit').val().trim();

        // collect data common to each measurement
        measData.date = $('#date_'+measType+'_edit').val().trim();
        measData.time = utils.to24Hour($('#time_'+measType+'_edit').val().trim());
        measData.notes = $('#notes_'+measType+'_edit').val().trim();
        measData.userName = $('#userName_'+measType+'_add').val().trim();
        measData.oldDateTime = $('#oldDateTime_'+measType).val().trim();
        measData.units = options.getUnits(measType);
        measData.json = true;

        // send edit request to server
        $.ajax({
            url: 'measurements_edit_post_'+measType,
            data: measData,
            dataType: 'json',
            method: 'POST',
            success: function(response) {
                if (response.result) {

                    // edit row and highlight it for a few seconds
                    tables.editSelection(measData);

                    // update old date time (it may have been changed)
                    $('#oldDateTime_'+measType).val(measData.date+' '+measData.time);

                    // refresh charts
                    charts.refresh('first');
                    charts.refresh('second');

                    // change Cancel button to Done button
                    $('#cancel_edit_'+measType+'_text').text('Done');

                    // put focus in first field of form
                    $('#'+measurementParts[measType][0]+'_'+measType+'_edit').focus();
                }
                else
                    alert('Edit failed: '+response.error);
            },
            error: function() { alert('Error: unable to communiate with server. Try again later.'); }
        });

        event.preventDefault();
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
    function hideFormSection(measType, formType) {

        // hide form
        $('#'+formType+'_'+measType+'_section').hide();
        $('#'+measType+'_table_section').removeClass('col-sm-8');
        $('#'+measType+'_table_section').addClass('col-sm-12');

        // deactivate button
        $('#'+measType+'_'+formType).removeClass('active');
    }

    // show the specified measurement form section for the associated measurement type and jump to it
    function showFormSection(measType, formType) {

        // hide other section if it is visible, and deactivate its corresponding button
        if (formType == 'add' && isFormVisible(measType, 'edit'))
            hideFormSection(measType, 'edit');
        else if (formType == 'edit' && isFormVisible(measType, 'add'))
            hideFormSection(measType, 'add');

        // show form
        $('#'+formType+'_'+measType+'_section').show();
        $('#'+measType+'_table_section').removeClass('col-sm-12');
        $('#'+measType+'_table_section').addClass('col-sm-8');

        // activate button
        $('#'+measType+'_'+formType).addClass('active');
    }

    /** formType must be either 'add' or 'edit' */
    function isFormVisible(measType, formType) {
        return $('#'+formType+'_'+measType+'_section').is(':visible');
    }

    /** Updates the forms for the specified measurement type to the current units option selection.
     * changedMeasurements must be an array of measurement types, e.g. 'bloodPressure', 'exercise', etc.  */
    function refreshForms(changedMeasurements) {
        $.each(changedMeasurements, function(index, measType) {
            var newUnits = options.getUnits(measType);
            $('#add_'+measType+'_section .units-addon').text(newUnits);
            $('#edit_'+measType+'_section .units-addon').text(newUnits);
            $('#add_'+measType+'_section .time-picker').data('DateTimePicker').format( options.get('timeFormat') === '12 hour' ? 'h:mm a' : 'HH:mm' );
            $('#edit_'+measType+'_section .time-picker').data('DateTimePicker').format( options.get('timeFormat') === '12 hour' ? 'h:mm a' : 'HH:mm' );
        });
    }

    return {
        init: initialize,
        /** refresh(changedMeasurements) : Updates the specified measurement type forms to the current units option selection.
         * changedMeasurements must be an array of measurement types, e.g. 'bloodPressure', 'exercise', etc.  */
        refresh: refreshForms,
        isFormVisible: isFormVisible,
        /** get(measType, formType, key) : Returns the value of the specified form key for the specified measurement. */
        get: getVal,
        /** set(measType, formType, key, value) : Sets the value of the specified form key for the specified measurement. */
        set: setVal,
        /** hide(measType, formType) : formType must be either 'add' or 'edit'. */
        hide: hideFormSection,
        /** show(measType, formType) : formType must be either 'add' or 'edit'. */
        show: showFormSection,
        /** setTime(measType, formType, newTime) : formType must be either 'add' or 'edit'. */
        setTime: setTime
    };

})(); // end forms module

function window_resized() {
	// small size screens
	if ($(window).width() < smallScreen_limit) {
		if (! options.getControlFor('showSecondChart').is(':disabled'))
			options.disableSecondChartOptions(true, true, false);
		if (charts.isVisible('first'))
            charts.hideSecondCharts();
	}

	// medium and large screen sizes
	else {
		if (options.getControlFor('showSecondChart').is(':disabled'))
			options.enableSecondChartOptions(true, true);
		if (options.getControlFor('showSecondChart').is(':checked') && ! charts.isVisible('second'))
            charts.showSecondCharts();
	}
}

function tab_clicked(event) {
	var measType = $(this).attr('id').split('_')[0];

	// show tab, change dropdown label, deselect menu item and select correct menu item
	$(this).tab('show');
	$('#measurements_dropdown_label').text(utils.toDisplayName(measType));
	$('#measurements_dropdown li').removeClass('active');
	$('#'+measType+'_dropdown_btn').parent().addClass('active');

	// update tabs appearance (in case dropdown triggered this event)
	$('#measurements_tabs .active').removeClass('active');
	$('#'+measType+'_tab_btn').parent().addClass('active');

	// update activeMeasurement option value
	options.set('activeMeasurement', measType);

	// update chart date-pickers
	options.updateChartDatePickers(measType, options.get('firstChartType'), 'first');
	options.updateChartDatePickers(measType, options.get('secondChartType'), 'second');

	if (measType === 'calories')
		measType = 'calorie';

	// update secondary column visibility options // TODO this used to be for showExerciseType option, which has been changed to showSecondaryCols option
	if (measType === 'exercise')
		$('.col-visibility-exercise').show();
	else
		$('.col-visibility-exercise').hide();

	// update the charts for the new active measurement if necessary
	if (charts.get(measType, 'first').options.title.text.split(' ')[0].toLowerCase() !== options.get('firstChartType'))
		charts.create(measType, options.get('firstChartType'), 'first');
	if (charts.get(measType, 'second').options.title.text.split(' ')[0].toLowerCase() !== options.get('secondChartType'))
		charts.create(measType, options.get('secondChartType'), 'second');

	// redraw charts and table to avoid overflow and column alignment issues
	charts.get(measType, 'first').reflow();
	charts.get(measType, 'second').reflow();
    tables.redraw(measType);

	options.save();

	event.preventDefault();
}

function initializePage() {
    options.init(); // read data from DOM and initialize options
    tables.init();  // request data from server and create tables
    charts.init();  // request data from server and create charts
    forms.init();   // initialize add/edit forms

    // add listeners for measurement tab buttons (i.e. for switching to a measurement)
    $('#measurements_tabs a, #measurements_dropdown li a').click(tab_clicked);

    // add tooltips
    $('body').tooltip( { selector: '.dynamic-tooltip' } );
    if (! $('#options_showTooltips').is(':checked'))
        $('.tooltip-help').tooltip('disable');
    else
        $('.tooltip-help').tooltip('enable');

    // modify layout for screens of different sizes
    $(window).resize(window_resized);
}

})();
