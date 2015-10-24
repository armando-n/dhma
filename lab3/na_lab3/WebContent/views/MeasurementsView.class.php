<?php 
class MeasurementsView{
    
    public static function show() {
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
<section>
    <h2>Add Measurements</h2>
    <ul>
        <li><a href="measurements_add_bloodPressure">Blood Pressure</a></li>
        <li><a href="measurements_add_calories">Calories</a></li>
        <li><a href="measurements_add_exercise">Exercise</a></li>
        <li><a href="measurements_add_glucose">Glucose</a></li>
        <li><a href="measurements_add_sleep">Sleep</a></li>
        <li><a href="measurements_add_weight">Weight</a></li>
    </ul>
</section>

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
        if (!isset($measurements["glucose"]) || empty($measurements["glucose"])):
            ?>
    <p>No glucose measurements to show yet</p>
<?php
        else:
            ?>
    <table>
        <tr>
            <th>Gluclose Levels</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["glucose"] as $glucose):
                ?>

        <tr>
            <td><?=$glucose->getMeasurement()?></td>
            <td><?=$glucose->getDate() . ' / ' . $glucose->getTime()?></td>
        </tr>
<?php
            endforeach;
            ?>
    </table><?php
        endif;
        ?>
</section>

<section>
    <h2><a id="bloodPressure">Blood Pressure Measurements</a></h2>
    <?php
        if (!isset($measurements["bloodPressure"]) || empty($measurements["bloodPressure"])):
            ?><p>No blood pressure measurements to show yet</p>
<?php
        else:
            ?>
    <table>
        <tr>
            <th>Blood Pressure (systolic / diastolic)</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["bloodPressure"] as $bloodPressure):
                ?>
            
        <tr>
            <td><?=$bloodPressure->getMeasurement()?></td>
            <td><?=$bloodPressure->getDate() . ' / ' . $bloodPressure->getTime()?></td>
        </tr>
<?php
            endforeach;
            ?>
    </table><?php
        endif;
    ?>
</section>

<section>
    <h2><a id="calories">Calorie Measurements</a></h2>
    <?php
        if (!isset($measurements["calories"]) || empty($measurements["calories"])):
            ?><p>No calorie measurements to show yet</p>
<?php
        else:
            ?>
    <table>
        <tr>
            <th>Calories Consumed</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["calories"] as $calories):
                ?>
            
        <tr>
            <td><?=$calories->getMeasurement()?></td>
            <td><?=$calories->getDate() . ' / ' . $calories->getTime()?></td>
        </tr>
<?php
            endforeach;
            ?>
    </table><?php
        endif;
        ?>
</section>

<section>
    <h2><a id="exercise">Exercise Measurements</a></h2>
    <?php
        if (!isset($measurements["exercise"]) || empty($measurements["exercise"])):
            ?><p>No exercise to show yet</p>
<?php
        else:
        ?>
    <table>
        <tr>
            <th>Duration (mins)</th>
            <th>Type</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["exercise"] as $exercise):
                ?>
            
        <tr>
            <td><?=$exercise->getDuration()?></td>
            <td><?=$exercise->getType()?></td>
            <td><?=$exercise->getDate() . ' / ' . $exercise->getTime()?></td>
        </tr>
<?php
            endforeach;
            ?>
    </table><?php
        endif;
        ?>
</section>

<section>
    <h2><a id="sleep">Sleep Measurements</a></h2>
    <?php
        if (!isset($measurements["sleep"]) || empty($measurements["sleep"])):
            ?><p>No sleep to show yet</p>
<?php
        else:
        ?>
    <table>
        <tr>
            <th>Sleep Duration (minutes)</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["sleep"] as $sleep):
                ?>
            
        <tr>
            <td><?=$sleep->getMeasurement()?></td>
            <td><?=$sleep->getDate() . ' / ' . $sleep->getTime()?></td>
        </tr>
<?php
            endforeach;
            ?>
    </table><?php
        endif;
        ?>
</section>

<section>
    <h2><a id="weight">Weight Measurements</a></h2>
    <?php
        if (!isset($measurements["weight"]) || empty($measurements["weight"])):
            ?><p>No weight measurements to show yet</p>
<?php
        else:
        ?>
    <table>
        <tr>
            <th>Weight</th>
            <th>Date / Time</th>
        </tr><?php
            foreach ($measurements["weight"] as $weight):
                ?>
            
        <tr>
            <td><?=$weight->getMeasurement()?></td>
            <td><?=$weight->getDate() . ' / ' . $weight->getTime()?></td>
        </tr>
<?php
            endforeach;
            ?>
    </table><?php
        endif;
        ?>
</section>
        
<?php
        if (isset($_SESSION))
            unset($_SESSION['measurements']);
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