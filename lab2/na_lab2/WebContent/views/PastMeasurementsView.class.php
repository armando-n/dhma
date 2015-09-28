<?php 
class PastMeasurementsView{
    
    public static function show($measurements = null) {
        HeaderView::show("Your Past Measurements", true);
        PastMeasurementsView::showBody($measurements);
        FooterView::show(true);
    }
    
    public static function showBody($measurements) {
        if (is_null($measurements)) {
            ?><p>Error: unable to show measurements. Data is missing.</p><?php
            return;
        }
        ?>
<nav id="page-nav">
    <h2>Jump to a measurement</h2>
    <ul>
        <li><a href="#glucose">Glucose</a></li>
        <li><a href="#bloodPressure">Blood Pressure</a></li>
        <li><a href="#calories">Calories</a></li>
        <li><a href="#exercise">Exercise</a></li>
        <li><a href="#sleep">Sleep</a></li>
        <li><a href="#weight">Weight</a></li>
    </ul>
</nav>

<section>
    <h2><a id="glucose">Glucose Measurements</a></h2>
<?php
        if (!isset($measurements["glucose"]) || empty($measurements["glucose"])) {
            ?>
    <p>No glucose measurements to show yet</p>
<?php
        } else {
            ?>
    <table>
        <tr>
            <th>Gluclose Levels</th>
            <th>Units</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["glucose"] as $glucose) {
                ?>

        <tr>
            <td><?=$glucose->getMeasurement()?></td>
            <td><?=$glucose->getUnits()?></td>
            <td><?=$glucose->getDate() . ' / ' . $glucose->getTime()?></td>
        </tr>
<?php
            }
            ?>
    </table><?php
        }
        ?>
</section>

<section>
    <h2><a id="bloodPressure">Blood Pressure Measurements</a></h2>
    <?php
        if (!isset($measurements["bloodPressure"]) || empty($measurements["bloodPressure"])) {
            ?><p>No blood pressure measurements to show yet</p>
<?php
        } else {
            ?>
    <table>
        <tr>
            <th>Blood Pressure (systolic / diastolic)</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["bloodPressure"] as $bloodPressure) {
                ?>
            
        <tr>
            <td><?=$bloodPressure->getMeasurement()?></td>
            <td><?=$bloodPressure->getDate() . ' / ' . $bloodPressure->getTime()?></td>
        </tr>
<?php
            }
            ?>
    </table><?php
        }
    ?>
</section>

<section>
    <h2><a id="calories">Calorie Measurements</a></h2>
    <?php
        if (!isset($measurements["calories"]) || empty($measurements["calories"])) {
            ?><p>No calorie measurements to show yet</p>
<?php
        } else {
            ?>
    <table>
        <tr>
            <th>Calories Consumed</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["calories"] as $calories) {
                ?>
            
        <tr>
            <td><?=$calories->getMeasurement()?></td>
            <td><?=$calories->getDate() . ' / ' . $calories->getTime()?></td>
        </tr>
<?php
            }
            ?>
    </table><?php
        }
        ?>
</section>

<section>
    <h2><a id="exercise">Exercise Measurements</a></h2>
    <?php
        if (!isset($measurements["exercise"]) || empty($measurements["exercise"])) {
            ?><p>No exercise to show yet</p>
<?php
        } else {
        ?>
    <table>
        <tr>
            <th>Duration (mins)</th>
            <th>Type</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["exercise"] as $exercise) {
                ?>
            
        <tr>
            <td><?=$exercise->getDuration()?></td>
            <td><?=$exercise->getType()?></td>
            <td><?=$exercise->getDate() . ' / ' . $exercise->getTime()?></td>
        </tr>
<?php
            }
            ?>
    </table><?php
        }
        ?>
</section>

<section>
    <h2><a id="sleep">Sleep Measurements</a></h2>
    <?php
        if (!isset($measurements["sleep"]) || empty($measurements["sleep"])) {
            ?><p>No sleep to show yet</p>
<?php
        } else {
        ?>
    <table>
        <tr>
            <th>Sleep Duration (minutes)</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["sleep"] as $sleep) {
                ?>
            
        <tr>
            <td><?=$sleep->getMeasurement()?></td>
            <td><?=$sleep->getDate() . ' / ' . $sleep->getTime()?></td>
        </tr>
<?php
            }
            ?>
    </table><?php
        }
        ?>
</section>

<section>
    <h2><a id="weight">Weight Measurements</a></h2>
    <?php
        if (!isset($measurements["weight"]) || empty($measurements["weight"])) {
            ?><p>No weight measurements to show yet</p>
<?php
        } else {
        ?>
    <table>
        <tr>
            <th>Weight</th>
            <th>Units</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["weight"] as $weight) {
                ?>
            
        <tr>
            <td><?=$weight->getMeasurement()?></td>
            <td><?=$weight->getUnits()?></td>
            <td><?=$weight->getDate() . ' / ' . $weight->getTime()?></td>
        </tr>
<?php
            }
            ?>
    </table><?php
        }
        ?>
</section>
        
<?php
    }
}
?>