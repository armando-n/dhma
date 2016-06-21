<?php
class MeasurementsView{

    public static function show() {
        $_SESSION['styles'][] = '../lib/datatables/datatables.css';
        $_SESSION['styles'][] = 'bootstrap-datetimepicker.min.css';
        $_SESSION['styles'][] = 'MeasurementsStyles.css';
        $_SESSION['libraries'][] = 'highcharts/highcharts.js';
        $_SESSION['libraries'][] = 'datatables/datatables.js';
        $_SESSION['scripts'][] = 'moment-with-locales.js';
        $_SESSION['scripts'][] = 'bootstrap-datetimepicker.min.js';
        $_SESSION['scripts'][] = 'MeasurementsScripts.js';

        if (isset($_SESSION['profile']) && $_SESSION['profile']->getTheme() === 'dark')
            $_SESSION['libraries'][] = 'highcharts/dark-unica.js';
        HeaderView::show();
        MeasurementsView::showBody();
        FooterView::show();
    }

    public static function showBody() {
        if (!isset($_SESSION) || !isset($_SESSION['allMeasurementsOptions']) || !isset($_SESSION['activeMeasurementsOptions'])):
            ?> <p>Error: unable to show measurements. Data is missing.</p><?php
            return;
        endif;

        // Note that some options are retrieved directly in the options area. only selects, checkboxes, and glyphicon stuff is retrieved here

        // html attribute strings required to activate/select/check/mark active options
        $msmtActive = ' active';
        $msmtActiveClass = ' class="active"';
        $optionSelected = ' selected="selected"';
        $optionChecked = ' checked="checked"';
        $optionOk = ' class="glyphicon glyphicon-ok"';

        $allOptions = $_SESSION['allMeasurementsOptions'];
        $activeOptions = $_SESSION['activeMeasurementsOptions'];
        $activeOptionsName = $activeOptions->getOptionsName();
        $activeMeasurement = $activeOptions->getActiveMeasurement();

        // options that require an active class to be added
        $glucoseActive = ($activeMeasurement === 'glucose') ? $msmtActive : '';
        $bloodPressureActive = ($activeMeasurement === 'bloodPressure') ? $msmtActive : '';
        $weightActive =  ($activeMeasurement === 'weight') ? $msmtActive : '';
        $caloriesActive = ($activeMeasurement === 'calories') ? $msmtActive : '';
        $exerciseActive = ($activeMeasurement === 'exercise') ? $msmtActive : '';
        $sleepActive = ($activeMeasurement === 'sleep') ? $msmtActive : '';
        foreach (array('firstChart', 'secondChart') as $whichChart) {
            foreach (array('individual', 'daily', 'weekly', 'monthly', 'yearly') as $timePeriod) {
                // example: $firstChart_daily = ($activeOptions->getFirstChartType() === 'daily') ? $msmtActive : '';
                ${$whichChart.'_'.$timePeriod} = (call_user_func(array($activeOptions, 'get'.ucfirst($whichChart).'Type')) === $timePeriod) ? $msmtActive : '';
            }
        }
        $glucoseDropdownActive = ($activeMeasurement === 'glucose') ? $msmtActiveClass : '';
        $bloodPressureDropdownActive = ($activeMeasurement === 'bloodPressure') ? $msmtActiveClass : '';
        $weightDropdownActive =  ($activeMeasurement === 'weight') ? $msmtActiveClass : '';
        $caloriesDropdownActive = ($activeMeasurement === 'calories') ? $msmtActiveClass : '';
        $exerciseDropdownActive = ($activeMeasurement === 'exercise') ? $msmtActiveClass : '';
        $sleepDropdownActive = ($activeMeasurement === 'sleep') ? $msmtActiveClass : '';

        // options that use select/option tags are retrieved here
        $timeFormat_12hour = ($activeOptions->getTimeFormat() === '12 hour') ? $optionSelected : '';
        $timeFormat_24hour = ($activeOptions->getTimeFormat() === '24 hour') ? $optionSelected : '';
        $durationFormat_minutes = ($activeOptions->getDurationFormat() === 'minutes') ? $optionSelected : '';
        $durationFormat_hours = ($activeOptions->getDurationFormat() === 'hours') ? $optionSelected : '';
        $durationFormat_hoursMinutes = ($activeOptions->getDurationFormat() === 'hours:minutes') ? $optionSelected : '';

        // options that use checkboxes or radio buttons are retrieved here
        $showTooltips = $activeOptions->getShowTooltips() ? $optionChecked : '';
        $showTable = $activeOptions->getShowTable() ? $optionChecked : '';
        $showFirstChart = $activeOptions->getShowFirstChart() ? $optionChecked : '';
        $showSecondChart = $activeOptions->getShowSecondChart() ? $optionChecked : '';
        $chartLastYear = $activeOptions->getChartLastYear() ? $optionChecked : '';
        $chartGroupDays = $activeOptions->getChartGroupDays() ? $optionChecked : '';
        $weightUnits_lbs = ($activeOptions->getWeightUnits() === 'lbs') ? $optionChecked : '';
        $weightUnits_kg = ($activeOptions->getWeightUnits() === 'kg') ? $optionChecked : '';
        $glucoseUnits_mgdL = ($activeOptions->getGlucoseUnits() === 'mg/dL') ? $optionChecked : '';
        $glucoseUnits_mM = ($activeOptions->getGlucoseUnits() === 'mM') ? $optionChecked : '';

        // options that use an ok glyphicon (check mark icon) are retrieved here
        $showSecondaryCols = $activeOptions->getShowSecondaryCols() ? $optionOk : '';
        $showDateCol = $activeOptions->getShowDateCol() ? $optionOk : '';
        $showTimeCol = $activeOptions->getShowTimeCol() ? $optionOk : '';
        $showNotesCol = $activeOptions->getShowNotesCol() ? $optionOk : '';
        ?>

<div class="row">
    <div class="col-xs-12">

        <!-- Options button and panel -->
        <div class="row">
            <div class="col-sm-12">

                <button type="button" id="options_btn" class="btn btn-default tooltip-help" data-toggle="collapse" data-target="#options" aria-expanded="false" aria-controls="options" title="Options">
                    <span class="glyphicon glyphicon-cog"></span>
                </button>

            </div>
        </div>
        <section class="row">
            <div class="col-sm-12">
                <div id="options" class="collapse">
                    <div class="well well-lg">

                        <form action="meausrementsOptions_edit" id="measurementsOptionsForm" method="post" role="form" class="form-horizontal">
                            <div class="row">
                                <fieldset class="col-sm-4 col-md-3">
                                    <legend>General Options</legend>

                                    <!-- General Options -->

                                    <div class="form-group">
                                        <div class="checkbox col-sm-12">
                                            <label class="tooltip-help" data-toggle="tooltip" title="Show/hide these tooltips">
                                                <input type="checkbox" id="options_showTooltips" name="showTooltips"<?=$showTooltips?> />Show Help Tooltips
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-default btn-block tooltip-help" data-toggle="modal" data-target="#unitsOptions_modal" title="Choose the units to use for applicable measurements">
                                                Choose Units
                                            </button>

                                            <div id="unitsOptions_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelldby="unitsOptions_label">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h4 id="unitsOptions_label" class="modal-title">Units of Measurement</h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <p>Only those types of measurements that have multiple choices of units are shown.</p>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-xs-12">

                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-4 col-lg-3">Glucose:</label>
                                                                        <div class="radio col-md-8 col-lg-9" id="options_glucoseUnits">
                                                                            <div class="row">
                                                                                <div class="col-xs-4 col-xs-offset-2">
                                                                                    <label class="tooltip-help" title="milligrams per deciliter">
                                                                                        <input type="radio" id="options_units_glucose_mgdL" name="glucoseUnits" value="mg/dL"<?=$glucoseUnits_mgdL?> />mg/dL
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-xs-4 col-xs-offset-1">
                                                                                    <label class="tooltip-help" title="millimolar (millimoles per litre)">
                                                                                        <input type="radio" id="options_units_glucose_mM" name="glucoseUnits" value="mM"<?=$glucoseUnits_mM?> />mM
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">

                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-4 col-lg-3">Weight:</label>
                                                                        <div class="radio col-md-8 col-lg-9" id="options_weightUnits">
                                                                            <div class="row">
                                                                                <div class="col-xs-4 col-xs-offset-2">
                                                                                    <label class="tooltip-help" title="pounds">
                                                                                        <input type="radio" id="options_units_weight_lbs" name="weightUnits" value="lbs"<?=$weightUnits_lbs?> />lbs
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-xs-4 col-xs-offset-1">
                                                                                    <label class="tooltip-help" title="kilograms">
                                                                                        <input type="radio" id="options_units_weight_kg" name="weightUnits" value="kg"<?=$weightUnits_kg?> />kg
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" id="saveUnitsChanges_btn" class="btn btn-primary" data-dismiss="modal">Save Changes</button>
                                                            <button type="button" id="cancelUnitsChanges_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group select-form-group">
                                        <label for="options_timeFormat" class="control-label col-xs-5 col-sm-6">Time Format:</label>
                                        <div class="col-xs-7 col-sm-6">
                                            <select id="options_timeFormat" name="timeFormat" class="form-control tooltip-help" data-toggle="tooltip" title="Choose between 12 hour (e.g. 2:00 pm) or 24 hour (e.g. 14:00) format for displaying time">
                                                <option<?=$timeFormat_12hour?>>12 hour</option>
                                                <option<?=$timeFormat_24hour?>>24 hour</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label for="options_durationFormat">Duration Format</label>
                                        <select id="options_durationFormat" name="durationFormat" class="form-control">
                                            <option<?php//$durationFormat_minutes?>>minutes</option>
                                            <option<?php//$durationFormat_hours?>>hours</option>
                                            <option<?php//$durationFormat_hoursMinutes?>>hours:minutes</option>
                                        </select>
                                    </div>
                                     -->

                                </fieldset>
                                <fieldset class="col-sm-4 col-md-3">
                                    <legend>Table Options</legend>

                                    <!-- Table Options -->
                                    <div class="form-group">
                                        <div class="checkbox col-sm-12">
                                            <label class="tooltip-help" data-toggle="tooltip" title="Show/hide the table of measurement entries. The table is where entries can be added/edited/deleted">
                                                <input type="checkbox" id="options_showTable" name="showTable"<?=$showTable?> />Show table
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div id="columns_dropdown" class="dropdown tooltip-help col-sm-12" data-toggle="tooltip" title="Choose the columns you want shown in the table">
                                            <button type="button" class="dropdown-toggle btn btn-default btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                <span id="columns_dropdown_label">Show/Hide Columns</span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="columns_dropdown_label">
                                                <li><a href="#" id="colvis_type" class="col-visibility-exercise"><span<?=$showSecondaryCols?>></span><span id="colvis_type_text">Type</span></a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#" id="colvis_date"><span<?=$showDateCol?>></span><span id="colvis_date_text">Date</span></a></li>
                                                <li><a href="#" id="colvis_time"><span<?=$showTimeCol?>></span><span id="colvis_time_text">Time</span></a></li>
                                                <li><a href="#" id="colvis_notes"><span<?=$showNotesCol?>></span><span id="colvis_notes_text">Notes</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="options_numRows" class="control-label col-xs-5 col-sm-8">Rows per page:</label>
                                        <div class="col-xs-7 col-sm-4">
                                            <input type="text" id="options_numRows" name="numRows" value="<?=$activeOptions->getNumRows()?>" class="form-control tooltip-help" size="5" maxlength="5" pattern="^[0-9]+$" data-toggle="tooltip" title="Enter the number of entries/rows to show per page in the table" />
                                        </div>
                                    </div>

                                </fieldset>
                                <fieldset class="col-sm-4 col-md-6">
                                    <legend>Chart Options</legend>

                                    <div class="form-group">
                                        <div class="checkbox col-xs-6">
                                            <label class="tooltip-help" data-toggle="tooltip" title="Show a chart">
                                                <input type="checkbox" id="options_showFirstChart" name="showFirstChart"<?=$showFirstChart?> />1<sup>st</sup> chart
                                            </label>
                                        </div>
                                        <div class="checkbox col-xs-6" id="options_showSecondChart_checkbox">
                                            <label class="tooltip-help" data-toggle="tooltip" title="Show a second chart, which is only available for larger screen sizes">
                                                <input type="checkbox" id="options_showSecondChart" name="showSecondChart"<?=$showSecondChart?> />2<sup>nd</sup> chart
                                            </label>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="options_lastYear" name="lastYear"<?php//$chartLastYear?> />Show last year
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="options_dailyAverages" name="dailyAverages"<?php//$chartGroupDays?> />Group days
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                     -->

                                    <!-- tabs for each chart's settings -->
                                    <div>
                                        <ul id="chartsOptions_tabs" class="nav nav-tabs nav-justified" role="tablist">
                                            <li class="active" role="presentation">
                                                <a class="tooltip-help" href="#firstChartOptions" id="firstChartOptions_tab" aria-controls="firstChartOptions" role="tab" data-toggle="tab" title="Settings for the first chart">1<sup>st</sup> Chart</a>
                                            </li>
                                            <li role="presentation">
                                                <a class="tooltip-help" href="#secondChartOptions" id="secondChartOptions_tab" aria-controls="secondChartOptions" role="tab" data-toggle="tab" title="Settings for a second chart, which is only available for larger screen sizes">2<sup>nd</sup> Chart</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="tab-content">
                                        <!-- First Chart's Settings -->
                                        <section role="tabpanel" id="firstChartOptions" class="tab-pane active">
                                            <div class="form-group">
                                                <label for="options_firstChart_startDate" class="control-label col-xs-5 col-md-4 col-md-offset-2">Start Date:</label>
                                                <div id="first_chart_start_datePicker" class="input-group date tooltip-help col-xs-7 col-md-4" title="Change the start date for the first chart (yyyy-mm-dd format)">
                                                    <input type="text" id="options_firstChart_startDate" name="firstChartStartDate" class="form-control" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="options_firstChart_endDate" class="control-label col-xs-5 col-md-4 col-md-offset-2">End Date:</label>
                                                <div id="first_chart_end_datePicker" class="input-group date tooltip-help col-xs-7 col-md-4" title="Change the end date for the first chart (yyyy-mm-dd format)">
                                                    <input type="text" id="options_firstChart_endDate" name="firstChartEndDate" class="form-control" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-xs-8 col-xs-offset-2">
                                                    <button id="first_chart_update_btn" type="button" class="btn btn-default btn-block updateCharts-btn tooltip-help" data-toggle="tooltip" title="Update the first chart to match the dates selected above">
                                                        <span>Update Chart</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </section>

                                        <!-- Second Chart's Settings -->
                                        <section role="tabpanel" id="secondChartOptions" class="tab-pane">
                                            <div class="form-group">
                                                <label for="options_secondChart_startDate" class="control-label col-xs-5 col-md-4 col-md-offset-2">Start Date:</label>
                                                <div id="second_chart_start_datePicker" class="input-group date tooltip-help col-xs-7 col-md-4" title="Change the start date for the second chart">
                                                    <input type="text" id="options_secondChart_startDate" name="secondChartStartDate" class="form-control" title="mm/dd/yyyy or mm-dd-yyyy" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="options_secondChart_endDate" class="control-label col-xs-5 col-md-4 col-md-offset-2">End Date:</label>
                                                <div id="second_chart_end_datePicker" class="input-group date tooltip-help col-xs-7 col-md-4" title="Change the end date for the second chart">
                                                    <input type="text" id="options_secondChart_endDate" name="secondChartEndDate" class="form-control" title="mm/dd/yyyy or mm-dd-yyyy" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-xs-8 col-xs-offset-2">
                                                    <button id="second_chart_update_btn" type="button" class="btn btn-default btn-block updateCharts-btn tooltip-help" data-toggle="tooltip" title="Update the second chart to match the dates selected above">
                                                        <span>Update Chart</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </section>
                                    </div> <!-- / tab content -->
                                </fieldset> <!-- / Chart Options -->

                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="#" id="closeOptions_btn">Close Options</a>
                                        <!-- <button type="button" id="closeOptions_btn">
                                            <span aria-hidden="true">Close Options</span>
                                        </button> -->
                                    </div>
                                </div>

                                    <section class="hidden-data">
                                        <div id="userName"><?=$_SESSION['profile']->getUserName()?></div>
                                        <div id="activeMeasurement"><?=$activeOptions->getActiveMeasurement()?></div>
                                        <div id="bloodPressureUnits"><?=$activeOptions->getBloodPressureUnits()?></div>
                                        <div id="calorieUnits"><?=$activeOptions->getCalorieUnits()?></div>
                                        <div id="exerciseUnits"><?=$activeOptions->getExerciseUnits()?></div>
                                        <div id="glucoseUnits"><?=$activeOptions->getGlucoseUnits()?></div>
                                        <div id="sleepUnits"><?=$activeOptions->getSleepUnits()?></div>
                                        <div id="weightUnits"><?=$activeOptions->getWeightUnits()?></div>
                                        <div id="timeFormat"><?=$activeOptions->getTimeFormat()?></div>
                                        <div id="durationFormat"><?=$activeOptions->getDurationFormat()?></div>
                                        <div id="showTooltips"><?php echo ($activeOptions->getShowTooltips() === true ? 'true' : 'false'); ?></div>
                                        <div id="showSecondaryCols"><?php echo ($activeOptions->getShowSecondaryCols() === true ? 'true' : 'false'); ?></div>
                                        <div id="showDateCol"><?php echo ($activeOptions->getShowDateCol() === true ? 'true' : 'false'); ?></div>
                                        <div id="showTimeCol"><?php echo ($activeOptions->getShowTimeCol() === true ? 'true' : 'false'); ?></div>
                                        <div id="showNotesCol"><?php echo ($activeOptions->getShowNotesCol() === true ? 'true' : 'false'); ?></div>
                                        <div id="numRows"><?=$activeOptions->getNumRows()?></div>
                                        <div id="showTable"><?php echo ($activeOptions->getShowTable() === true ? 'true' : 'false'); ?></div>
                                        <div id="showFirstChart"><?php echo ($activeOptions->getShowFirstChart() === true ? 'true' : 'false'); ?></div>
                                        <div id="showSecondChart"><?php echo ($activeOptions->getShowSecondChart() === true ? 'true' : 'false'); ?></div>
                                        <div id="firstChartType"><?=$activeOptions->getFirstChartType()?></div>
                                        <div id="secondChartType"><?=$activeOptions->getSecondChartType()?></div>
                                        <div id="chartLastYear"><?php echo ($activeOptions->getChartLastYear() === true ? 'true' : 'false'); ?></div>
                                        <div id="chartGroupDays"><?php echo ($activeOptions->getChartGroupDays() === true ? 'true' : 'false'); ?></div>
                                        <div id="individual_bloodPressure_chartStart"><?=$activeOptions->getIndividualBloodPressureChartStart()?></div>
                                        <div id="individual_bloodPressure_chartEnd"><?=$activeOptions->getIndividualBloodPressureChartEnd()?></div>
                                        <div id="daily_bloodPressure_chartStart"><?=$activeOptions->getDailyBloodPressureChartStart()?></div>
                                        <div id="daily_bloodPressure_chartEnd"><?=$activeOptions->getDailyBloodPressureChartEnd()?></div>
                                        <div id="weekly_bloodPressure_chartStart"><?=$activeOptions->getWeeklyBloodPressureChartStart()?></div>
                                        <div id="weekly_bloodPressure_chartEnd"><?=$activeOptions->getWeeklyBloodPressureChartEnd()?></div>
                                        <div id="monthly_bloodPressure_chartStart"><?=$activeOptions->getMonthlyBloodPressureChartStart()?></div>
                                        <div id="monthly_bloodPressure_chartEnd"><?=$activeOptions->getMonthlyBloodPressureChartEnd()?></div>
                                        <div id="yearly_bloodPressure_chartStart"><?=$activeOptions->getYearlyBloodPressureChartStart()?></div>
                                        <div id="yearly_bloodPressure_chartEnd"><?=$activeOptions->getYearlyBloodPressureChartEnd()?></div>
                                        <div id="individual_calories_chartStart"><?=$activeOptions->getIndividualCaloriesChartStart()?></div>
                                        <div id="individual_calories_chartEnd"><?=$activeOptions->getIndividualCaloriesChartEnd()?></div>
                                        <div id="daily_calories_chartStart"><?=$activeOptions->getDailyCaloriesChartStart()?></div>
                                        <div id="daily_calories_chartEnd"><?=$activeOptions->getDailyCaloriesChartEnd()?></div>
                                        <div id="weekly_calories_chartStart"><?=$activeOptions->getWeeklyCaloriesChartStart()?></div>
                                        <div id="weekly_calories_chartEnd"><?=$activeOptions->getWeeklyCaloriesChartEnd()?></div>
                                        <div id="monthly_calories_chartStart"><?=$activeOptions->getMonthlyCaloriesChartStart()?></div>
                                        <div id="monthly_calories_chartEnd"><?=$activeOptions->getMonthlyCaloriesChartEnd()?></div>
                                        <div id="yearly_calories_chartStart"><?=$activeOptions->getYearlyCaloriesChartStart()?></div>
                                        <div id="yearly_calories_chartEnd"><?=$activeOptions->getYearlyCaloriesChartEnd()?></div>
                                        <div id="individual_exercise_chartStart"><?=$activeOptions->getIndividualExerciseChartStart()?></div>
                                        <div id="individual_exercise_chartEnd"><?=$activeOptions->getIndividualExerciseChartEnd()?></div>
                                        <div id="daily_exercise_chartStart"><?=$activeOptions->getDailyExerciseChartStart()?></div>
                                        <div id="daily_exercise_chartEnd"><?=$activeOptions->getDailyExerciseChartEnd()?></div>
                                        <div id="weekly_exercise_chartStart"><?=$activeOptions->getWeeklyExerciseChartStart()?></div>
                                        <div id="weekly_exercise_chartEnd"><?=$activeOptions->getWeeklyExerciseChartEnd()?></div>
                                        <div id="monthly_exercise_chartStart"><?=$activeOptions->getMonthlyExerciseChartStart()?></div>
                                        <div id="monthly_exercise_chartEnd"><?=$activeOptions->getMonthlyExerciseChartEnd()?></div>
                                        <div id="yearly_exercise_chartStart"><?=$activeOptions->getYearlyExerciseChartStart()?></div>
                                        <div id="yearly_exercise_chartEnd"><?=$activeOptions->getYearlyExerciseChartEnd()?></div>
                                        <div id="individual_glucose_chartStart"><?=$activeOptions->getIndividualGlucoseChartStart()?></div>
                                        <div id="individual_glucose_chartEnd"><?=$activeOptions->getIndividualGlucoseChartEnd()?></div>
                                        <div id="daily_glucose_chartStart"><?=$activeOptions->getDailyGlucoseChartStart()?></div>
                                        <div id="daily_glucose_chartEnd"><?=$activeOptions->getDailyGlucoseChartEnd()?></div>
                                        <div id="weekly_glucose_chartStart"><?=$activeOptions->getWeeklyGlucoseChartStart()?></div>
                                        <div id="weekly_glucose_chartEnd"><?=$activeOptions->getWeeklyGlucoseChartEnd()?></div>
                                        <div id="monthly_glucose_chartStart"><?=$activeOptions->getMonthlyGlucoseChartStart()?></div>
                                        <div id="monthly_glucose_chartEnd"><?=$activeOptions->getMonthlyGlucoseChartEnd()?></div>
                                        <div id="yearly_glucose_chartStart"><?=$activeOptions->getYearlyGlucoseChartStart()?></div>
                                        <div id="yearly_glucose_chartEnd"><?=$activeOptions->getYearlyGlucoseChartEnd()?></div>
                                        <div id="individual_sleep_chartStart"><?=$activeOptions->getIndividualSleepChartStart()?></div>
                                        <div id="individual_sleep_chartEnd"><?=$activeOptions->getIndividualSleepChartEnd()?></div>
                                        <div id="daily_sleep_chartStart"><?=$activeOptions->getDailySleepChartStart()?></div>
                                        <div id="daily_sleep_chartEnd"><?=$activeOptions->getDailySleepChartEnd()?></div>
                                        <div id="weekly_sleep_chartStart"><?=$activeOptions->getWeeklySleepChartStart()?></div>
                                        <div id="weekly_sleep_chartEnd"><?=$activeOptions->getWeeklySleepChartEnd()?></div>
                                        <div id="monthly_sleep_chartStart"><?=$activeOptions->getMonthlySleepChartStart()?></div>
                                        <div id="monthly_sleep_chartEnd"><?=$activeOptions->getMonthlySleepChartEnd()?></div>
                                        <div id="yearly_sleep_chartStart"><?=$activeOptions->getYearlySleepChartStart()?></div>
                                        <div id="yearly_sleep_chartEnd"><?=$activeOptions->getYearlySleepChartEnd()?></div>
                                        <div id="individual_weight_chartStart"><?=$activeOptions->getIndividualWeightChartStart()?></div>
                                        <div id="individual_weight_chartEnd"><?=$activeOptions->getIndividualWeightChartEnd()?></div>
                                        <div id="daily_weight_chartStart"><?=$activeOptions->getDailyWeightChartStart()?></div>
                                        <div id="daily_weight_chartEnd"><?=$activeOptions->getDailyWeightChartEnd()?></div>
                                        <div id="weekly_weight_chartStart"><?=$activeOptions->getWeeklyWeightChartStart()?></div>
                                        <div id="weekly_weight_chartEnd"><?=$activeOptions->getWeeklyWeightChartEnd()?></div>
                                        <div id="monthly_weight_chartStart"><?=$activeOptions->getMonthlyWeightChartStart()?></div>
                                        <div id="monthly_weight_chartEnd"><?=$activeOptions->getMonthlyWeightChartEnd()?></div>
                                        <div id="yearly_weight_chartStart"><?=$activeOptions->getYearlyWeightChartStart()?></div>
                                        <div id="yearly_weight_chartEnd"><?=$activeOptions->getYearlyWeightChartEnd()?></div>
                                    </section>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <!-- dropdown menu for extra small screens -->
                    <div id="measurements_dropdown" class="dropdown tooltip-help" data-toggle="tooltip" title="Switch to a different measurement tracker">
                        <button type="button" class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span id="measurements_dropdown_label"><?php echo ucfirst($activeMeasurement); ?></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="measurements_dropdown_label">
                            <li<?=$glucoseDropdownActive?>><a href="#glucose" id="glucose_dropdown_btn">Glucose</a></li>
                            <li<?=$bloodPressureDropdownActive?>><a href="#bloodPressure" id="bloodPressure_dropdown_btn">Blood Pressure</a></li>
                            <li<?=$weightDropdownActive?>><a href="#weight" id="weight_dropdown_btn">Weight</a></li>
                            <li role="separator" class="divider"></li>
                            <li<?=$caloriesDropdownActive?>><a href="#calories" id="calories_dropdown_btn">Calories</a></li>
                            <li<?=$exerciseDropdownActive?>><a href="#exercise" id="exercise_dropdown_btn">Exercise</a></li>
                            <li<?=$sleepDropdownActive?>><a href="#sleep" id="sleep_dropdown_btn">Sleep</a></li>
                        </ul>
                    </div>

                    <!-- tabs for small screens and larger -->
                    <div id="measurements_nav" class="panel-heading collapse navbar-collapse">
                        <ul id="measurements_tabs" class="nav nav-tabs nav-justified" role="tablist">
                            <li class="measurement-tab<?=$glucoseActive?>" role="presentation"><a class="measurement-tab tooltip-help" href="#glucose" id="glucose_tab_btn" aria-controls="glucose" role="tab" data-toggle="tooltip" title="Switch to glucose tracker">Glucose</a></li>
                            <li class="measurement-tab<?=$bloodPressureActive?>" role="presentation"><a class="measurement-tab tooltip-help" href="#bloodPressure" id="bloodPressure_tab_btn" aria-controls="bloodPressure" role="tab" data-toggle="tooltip" title="Switch to blood pressure tracker">Blood Pressure</a></li>
                            <li class="measurement-tab<?=$weightActive?>" role="presentation"><a class="measurement-tab tooltip-help" href="#weight" id="weight_tab_btn" aria-controls="weight" role="tab" data-toggle="tooltip" title="Switch to weight tracker">Weight</a></li>
                            <li class="measurement-tab<?=$caloriesActive?>" role="presentation"><a class="measurement-tab tooltip-help" href="#calories" id="calories_tab_btn" aria-controls="calories" role="tab" data-toggle="tooltip" title="Switch to calorie tracker">Calories</a></li>
                            <li class="measurement-tab<?=$exerciseActive?>" role="presentation"><a class="measurement-tab tooltip-help" href="#exercise" id="exercise_tab_btn" aria-controls="exercise" role="tab" data-toggle="tooltip" title="Switch to exercise tracker">Exercise</a></li>
                            <li class="measurement-tab<?=$sleepActive?>" role="presentation"><a class="measurement-tab tooltip-help" href="#sleep" id="sleep_tab_btn" aria-controls="sleep" role="tab" data-toggle="tooltip" title="Switch to sleep tracker">Sleep</a></li>
                        </ul>
                    </div>

                    <!-- Main content -->
                    <div id="measurement_sections" class="tab-content panel-body">
                        <section role="tabpanel" id="glucose" class="col-sm-12 tab-pane<?=$glucoseActive?>">
                            <?php GlucoseMeasurementsView::showBody($activeOptions); ?>
                        </section>

                        <section role="tabpanel" id="bloodPressure" class="col-sm-12 tab-pane<?=$bloodPressureActive?>">
                            <?php BloodPressureMeasurementsView::showBody($activeOptions); ?>
                        </section>

                        <section role="tabpanel" id="calories" class="col-sm-12 tab-pane<?=$caloriesActive?>">
                            <?php CalorieMeasurementsView::showBody($activeOptions); ?>
                        </section>

                        <section role="tabpanel" id="exercise" class="col-sm-12 tab-pane<?=$exerciseActive?>">
                            <?php ExerciseMeasurementsView::showBody($activeOptions); ?>
                        </section>

                        <section role="tabpanel" id="sleep" class="col-sm-12 tab-pane<?=$sleepActive?>">
                            <?php SleepMeasurementsView::showBody($activeOptions); ?>
                        </section>

                        <section role="tabpanel" id="weight" class="col-sm-12 tab-pane<?=$weightActive?>">
                            <?php WeightMeasurementsView::showBody($activeOptions); ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div id="first_chartType_btns" class="btn-group btn-group-justified" role="group">
                    <div class="btn-group" role="group">
                        <button type="button" id="first_individual_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$firstChart_individual?>" data-toggle="tooltip" title="Show a chart of individual entries">
                            Individual
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="first_daily_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$firstChart_daily?>" data-toggle="tooltip" title="Show a chart of daily averages/totals">
                            Daily
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="first_weekly_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$firstChart_weekly?>" data-toggle="tooltip" title="Show a chart of weekly averages/totals">
                            Weekly
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="first_monthly_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$firstChart_monthly?>" data-toggle="tooltip" title="Show a chart of monthly averages/totals">
                            Monthly
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="first_yearly_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$firstChart_yearly?>" data-toggle="tooltip" title="Show a chart of yearly averages/totals">
                            Yearly
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div id="second_chartType_btns" class="btn-group btn-group-justified" role="group">
                    <div class="btn-group" role="group">
                        <button type="button" id="second_individual_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$secondChart_individual?>" data-toggle="tooltip" title="Show a chart of individual entries">
                            Individual
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="second_daily_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$secondChart_daily?>" data-toggle="tooltip" title="Show a chart of daily averages/totals">
                            Daily
                        </button>
                    </div><div class="btn-group" role="group">
                        <button type="button" id="second_weekly_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$secondChart_weekly?>" data-toggle="tooltip" title="Show a chart of weekly averages/totals">
                            Weekly
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="second_monthly_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$secondChart_monthly?>" data-toggle="tooltip" title="Show a monthly of daily averages/totals">
                            Monthly
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="second_yearly_chart_btn" class="btn btn-primary btn-change-chart tooltip-help<?=$secondChart_yearly?>" data-toggle="tooltip" title="Show a chart of yearly averages/totals">
                            Yearly
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
        if (isset($_SESSION)) {
            unset($_SESSION['measurements']);
            unset($_SESSION['styles']);
            unset($_SESSION['scripts']);
        }
    }

}