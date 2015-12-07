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
    
        <div class="panel panel-primary">
        
            <div class="panel-heading">
                <ul id="measurements_tabs" class="nav nav-tabs nav-justified" role="tablist">
                	<li class="measurement-tab active" role="presentation"><a class="measurement-tab" href="#glucose" id="glucose_tab_btn" aria-controls="glucose" role="tab">Glucose</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#bloodPressure" id="bloodPressure_tab_btn" aria-controls="bloodPressure" role="tab">Blood Pressure</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#weight" id="weight_tab_btn" aria-controls="weight" role="tab">Weight</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#calories" id="calorie_tab_btn" aria-controls="calories" role="tab">Calories</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#exercise" id="exercise_tab_btn" aria-controls="exercise" role="tab">Exercise</a></li>
                	<li class="measurement-tab" role="presentation"><a class="measurement-tab" href="#sleep" id="sleep_tab_btn" aria-controls="sleep" role="tab">Sleep</a></li>
                </ul>
            </div>

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