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
        HeaderView::show("Your Past Measurements");
        MeasurementsView::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        if (!isset($_SESSION) || !isset($_SESSION['measurements'])):
            ?><p>Error: unable to show measurements. Data is missing.</p><?php
            return;
        endif;
        $measurements = $_SESSION['measurements'];
        ?>

<div class="row">
    <div class="col-xs-12">
    
        <!-- Options button and panel -->
        <div class="row">
            <div class="col-sm-12">

                <button type="button" id="options_btn" class="btn btn-default" data-toggle="collapse" data-target="#options" aria-expanded="false" aria-controls="options">
                    <span class="glyphicon glyphicon-cog"></span>
                </button>

            </div>
        </div>
        <section class="row">
            <div class="col-sm-12">
                <div id="options" class="collapse">
                    <div class="well well-lg">
            
                        <form action="signup_post" id="signupForm" enctype="multipart/form-data" method="post" role="form" class="form-horizontal">
                            <div class="row">
                                <fieldset class="col-sm-4">
                                    <legend>General Options</legend>
                                    
                                    <!-- General Options -->
                                    <div class="form-group">
                                        <label for="options_units">Units</label>
                                        <select id="options_units" name="units" class="form-control">
                                            <option>mg/dL</option>
                                            <option>mM</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="options_timeFormat">Time Format</label>
                                        <select id="options_timeFormat" name="timeFormat" class="form-control">
                                            <option>12 hour</option>
                                            <option>24 hour</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="options_tooltips" name="tooltips" />Show Tooltips
                                            </label>
                                        </div>
                                    </div>
                                
                                </fieldset>
                                <fieldset class="col-sm-4">
                                    <legend>Table Options</legend>
                                
                                    <!-- Table Options -->
                                    <div class="form-group">
                                        <div id="columns_dropdown" class="dropdown" data-toggle="tooltip" title="Choose the columns you want shown in the table">
                                            <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                <span id="columns_dropdown_label">Show/Hide Columns</span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="columns_dropdown_label">
                                                <li><a href="#" id="systolicPressure_dropdown_btn">Systolic</a></li>
                                                <li><a href="#" id="diastolicPressure_dropdown_btn">Diastlic</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#" id="date_dropdown_btn">Date</a></li>
                                                <li><a href="#" id="time_dropdown_btn">Time</a></li>
                                                <li><a href="#" id="notes_dropdown_btn">Notes</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="options_numRows">Rows per page</label>
                                        <input type="text" id="options_numRows" name="numRows" value="10" class="form-control" size="5" maxlength="5" pattern="^[0-9]+$" title="Enter a positive number of 5 digits or less" />
                                    </div>
                                    
                                </fieldset>
                                <fieldset class="col-sm-4">
                                    <legend>Chart Options</legend>
                                
                                    <!-- Chart Options -->
                                    <div class="form-group">
                                        <label for="startDate_options">Start Date</label>
                                        <div class="input-group date date-picker">
                                            <input type="text" id="startDate_options" name="startDate" class="form-control" title="mm/dd/yyyy or mm-dd-yyyy" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="endDate_options">End Date</label>
                                        <div class="input-group date date-picker">
                                            <input type="text" id="endDate_options" name="endDate" class="form-control" title="mm/dd/yyyy or mm-dd-yyyy" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="options_firstChart" name="firstChart" />Show chart
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="options_secondChart" name="secondChart" />Show a second chart
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="options_lastYear" name="lastYear" />Show same time last year
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="options_dailyAverages" name="dailyAverages" />Show daily averages
                                            </label>
                                        </div>
                                    </div>
                                    
                                </fieldset>
                            </div>
                        </form>
                
                    </div>
                </div>
            </div>
        </section>
    
        <div class="panel panel-primary">
            
            <!-- dropdown menu for extra small screens -->
            <div id="measurements_dropdown" class="dropdown" data-toggle="tooltip" title="Switch to a different measurement tracker">
                <button type="button" class="dropdown-toggle btn btn-primary btn-block" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span id="measurements_dropdown_label">Glucose</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="measurements_dropdown_label">
                    <li><a href="#glucose" id="glucose_dropdown_btn">Glucose</a></li>
                    <li><a href="#bloodPressure" id="bloodPressure_dropdown_btn">Blood Pressure</a></li>
                    <li><a href="#weight" id="weight_dropdown_btn">Weight</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#calories" id="calories_dropdown_btn">Calories</a></li>
                    <li><a href="#exercise" id="exercise_dropdown_btn">Exercise</a></li>
                    <li><a href="#sleep" id="sleep_dropdown_btn">Sleep</a></li>
                </ul>
            </div>
        
            <!-- tabs for small screens and larger -->
            <div id="measurements_nav" class="panel-heading collapse navbar-collapse">
                <ul id="measurements_tabs" class="nav nav-tabs nav-justified" role="tablist">
                	<li class="measurement-tab active" role="presentation"><a class="measurement-tab" href="#glucose" id="glucose_tab_btn" aria-controls="glucose" role="tab" data-toggle="tooltip" title="Switch to glucose tracker">Glucose</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#bloodPressure" id="bloodPressure_tab_btn" aria-controls="bloodPressure" role="tab" data-toggle="tooltip" title="Switch to blood pressure tracker">Blood Pressure</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#weight" id="weight_tab_btn" aria-controls="weight" role="tab" data-toggle="tooltip" title="Switch to weight tracker">Weight</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#calories" id="calories_tab_btn" aria-controls="calories" role="tab" data-toggle="tooltip" title="Switch to calorie tracker">Calories</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#exercise" id="exercise_tab_btn" aria-controls="exercise" role="tab" data-toggle="tooltip" title="Switch to exercise tracker">Exercise</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#sleep" id="sleep_tab_btn" aria-controls="sleep" role="tab" data-toggle="tooltip" title="Switch to sleep tracker">Sleep</a></li>
                </ul>
            </div>

            <!-- Main content -->
            <div class="tab-content">
                <section role="tabpanel" id="glucose" class="row tab-pane active">
                    <div class="col-sm-12">
                            <?php GlucoseMeasurementsView::showBody(); ?>
                    </div>
                </section>
                
                <section role="tabpanel" id="bloodPressure" class="row tab-pane">
                    <div class="col-sm-12">
                        <?php BloodPressureMeasurementsView::showBody(); ?>
                    </div>
                </section>
                
                <section role="tabpanel" id="calories" class="row tab-pane">
                    <div class="col-sm-12">
                        <?php CalorieMeasurementsView::showBody(); ?>
                    </div>
                </section>
                
                <section role="tabpanel" id="exercise" class="row tab-pane">
                    <div class="col-sm-12">
                        <?php ExerciseMeasurementsView::showBody(); ?>
                    </div>
                </section>
                
                <section role="tabpanel" id="sleep" class="row tab-pane">
                    <div class="col-sm-12">
                        <?php SleepMeasurementsView::showBody(); ?>
                    </div>
                </section>
                
                <section role="tabpanel" id="weight" class="row tab-pane">
                    <div class="col-sm-12">
                        <?php WeightMeasurementsView::showBody(); ?>
                    </div>
                </section>
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