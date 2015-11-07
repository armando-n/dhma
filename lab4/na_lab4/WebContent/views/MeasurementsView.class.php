<?php 
class MeasurementsView{
    
    public static function show() {
        if (!isset($_SESSION['styles']))
            $_SESSION['styles'] = array();
        if (!isset($_SESSION['scripts']))
            $_SESSION['scripts'] = array();
        $_SESSION['styles'][] = 'MeasurementsStyles.css';
        $_SESSION['scripts'][] = 'MeasurementsScripts.js';
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
<section class="row">
    <div class="col-xs-6">
        <div class="dropdown">
            <button id="buttonAdd" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add Measurement
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="buttonAdd">
            	<li><a href="measurements_show_glucose">Glucose</a></li>
            	<li><a href="measurements_show_bloodPressure">Blood Pressure</a></li>
            	<li><a href="measurements_show_calories">Calories</a></li>
            	<li><a href="measurements_show_exercise">Exercise</a></li>
            	<li><a href="measurements_show_sleep">Sleep</a></li>
            	<li><a href="measurements_show_weight">Weight</a></li>
            </ul>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="dropdown">
            <button id="buttonJump" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Jump to Measurement
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="buttonJump">
                <li><a href="#glucose">Glucose</a></li>
                <li><a href="#bloodPressure">Blood Pressure</a></li>
                <li><a href="#calories">Calories</a></li>
                <li><a href="#exercise">Exercise</a></li>
                <li><a href="#sleep">Sleep</a></li>
                <li><a href="#weight">Weight</a></li>
            </ul>
        </div>
    </div>
</section>
        
<section class="row">
    <div class="col-sm-12">
        <h2 class="buttonGroupLabel">Add a measurement</h2>
        <div class="btn-group">
            <a href="measurements_show_glucose" class="btn btn-default">Glucose</a>
            <a href="measurements_show_bloodPressure" class="btn btn-default">Blood Pressure</a>
            <a href="measurements_show_calories" class="btn btn-default">Calories</a>
            <a href="measurements_show_exercise" class="btn btn-default">Exercise</a>
            <a href="measurements_show_sleep" class="btn btn-default">Sleep</a>
            <a href="measurements_show_weight" class="btn btn-default">Weight</a>
        </div>
    </div>
</section>

<nav id="page-nav" class="row">
    <div class="col-sm-12">
        <h2 class="buttonGroupLabel">Jump to a measurement</h2>
        <div class="btn-group">
            <a href="#glucose" class="btn btn-default">Glucose</a>
            <a href="#bloodPressure" class="btn btn-default">Blood Pressure</a>
            <a href="#calories" class="btn btn-default">Calories</a>
            <a href="#exercise" class="btn btn-default">Exercise</a>
            <a href="#sleep" class="btn btn-default">Sleep</a>
            <a href="#weight" class="btn btn-default">Weight</a>
        </div>
    </div>
</nav>

<hr />

<section class="row">
    <div class="col-sm-12"><?php
        BloodPressureMeasurementsView::showBody();
    ?>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <a id="glucose"></a><?php
        if (!isset($measurements["glucose"]) || empty($measurements["glucose"])): ?>
        <p>No glucose measurements to show yet</p><?php
        else: ?>
        
        <table class="table table-striped">
            <caption>Glucose Measurements</caption>
            <thead>
                <tr>
                    <th>Gluclose Levels</th>
                    <th>Date / Time</th>
                </tr>
            </thead>
            
            <tbody><?php
            foreach ($measurements["glucose"] as $glucose): ?>
                <tr>
                    <td><?=$glucose->getMeasurement()?></td>
                    <td><?=$glucose->getDate() . ' / ' . $glucose->getTime()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
            
        </table><?php
        endif; ?>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <a id="bloodPressure"></a><?php
        if (!isset($measurements["bloodPressure"]) || empty($measurements["bloodPressure"])):
            ?><p>No blood pressure measurements to show yet</p><?php
        else: ?>
        
        <table class="table table-striped">
            <caption>Blood Pressure Measurements</caption>
            <thead>
                <tr>
                    <th>Blood Pressure (systolic / diastolic)</th>
                    <th>Date / Time</th>
                </tr>
            </thead>
            
            <tbody><?php
            foreach ($measurements["bloodPressure"] as $bloodPressure): ?>
                <tr>
                    <td><?=$bloodPressure->getMeasurement()?></td>
                    <td><?=$bloodPressure->getDate() . ' / ' . $bloodPressure->getTime()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
            
        </table><?php
        endif; ?>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <a id="calories"></a><?php
        if (!isset($measurements["calories"]) || empty($measurements["calories"])):
            ?><p>No calorie measurements to show yet</p><?php
        else: ?>
        
        <table class="table table-striped">
            <caption>Calorie Measurements</caption>
            <thead>
                <tr>
                    <th>Calories Consumed</th>
                    <th>Date / Time</th>
                </tr>
            </thead>
            
            <tbody><?php
            foreach ($measurements["calories"] as $calories): ?>
                <tr>
                    <td><?=$calories->getMeasurement()?></td>
                    <td><?=$calories->getDate() . ' / ' . $calories->getTime()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
            
        </table><?php
        endif; ?>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <a id="exercise"></a><?php
        if (!isset($measurements["exercise"]) || empty($measurements["exercise"])): ?>
        <p>No exercise to show yet</p><?php
        else: ?>
        
        <table class="table table-striped">
            <caption>Exercise Measurements</caption>
            <thead>
                <tr>
                    <th>Duration (mins)</th>
                    <th>Type</th>
                    <th>Date / Time</th>
                </tr>
            </thead>
            
            <tbody><?php
            foreach ($measurements["exercise"] as $exercise): ?>
                <tr>
                    <td><?=$exercise->getDuration()?></td>
                    <td><?=$exercise->getType()?></td>
                    <td><?=$exercise->getDate() . ' / ' . $exercise->getTime()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
            
        </table><?php
        endif; ?>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <a id="sleep"></a><?php
        if (!isset($measurements["sleep"]) || empty($measurements["sleep"])):
            ?><p>No sleep to show yet</p><?php
        else: ?>

        <table class="table table-striped">
            <caption>Sleep Measurements</caption>
            <thead>
                <tr>
                    <th>Sleep Duration (minutes)</th>
                    <th>Date / Time</th>
                </tr>
            </thead>
        
            <tbody><?php
            foreach ($measurements["sleep"] as $sleep): ?>
                <tr>
                    <td><?=$sleep->getMeasurement()?></td>
                    <td><?=$sleep->getDate() . ' / ' . $sleep->getTime()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
            
        </table><?php
        endif; ?>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <a id="weight"></a><?php
        if (!isset($measurements["weight"]) || empty($measurements["weight"])):
            ?><p>No weight measurements to show yet</p><?php
        else: ?>
        
        <table class="table table-striped">
            <caption>Weight Measurements</caption>
            
            <thead>
                <tr>
                    <th>Weight</th>
                    <th>Date / Time</th>
                </tr>
            </thead>
            
            <tbody><?php
            foreach ($measurements["weight"] as $weight): ?>
                <tr>
                    <td><?=$weight->getMeasurement()?></td>
                    <td><?=$weight->getDate() . ' / ' . $weight->getTime()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
            
        </table><?php
        endif; ?>
    </div>
</section>
        
<?php
        if (isset($_SESSION)) {
            unset($_SESSION['measurements']);
            unset($_SESSION['styles']);
            unset($_SESSION['scripts']);
        }
    }
    
    public static function add() {
        HeaderView::show("Add Measurements");
        
        if (!isset($_SESSION) || !isset($_SESSION['profile'])):
            ?><p>Error: unable to add measurements. Profile data is missing.</p><?php
            return;
        endif;
        
//         $measurements = $_SESSION['measurements'];
        ?>
        
<section>
    <form action="measurements_add_bloodPressure" method="post">
        <fieldset>
            <legend>Blood Pressure</legend>
            <!-- Pattern attribute and specific error reporting absent to avoid hints that weaken security -->
            Systolic Pressure <input type="text" name="systolicPressure" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Diastolic Pressure <input type="text" name="diastolicPressure" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" required="required" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" required="required" tabindex="4" title="H:M" /><br />
            Notes <input type="text" name="notes" size="30" maxlength="4" tabindex="5" /><br />
        </fieldset>
        <div>
            <input type="submit" tabindex="6" />
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="7" />
        </div>
    </form>
</section>
        
        <?php
        FooterView::show();
    }
}
?>