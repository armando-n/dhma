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

<div id="page-nav" class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm-12">
                <p class="buttonGroupLabel">Click to jump to a measurement:</p>
            </div>
        </div>
        <div class="row">
            <!--  I LEFT OF HERE LAST NIGHT!!!!!!!!! -->
            <div class="jump-to col-xs-4 col-sm-2">
                <button type="button" name="glucose" class="btn btn-default btn-block">Glucose</button>
            </div>
            <div class="jump-to col-xs-4 col-sm-2">
                <button type="button" name="bloodPressure" class="btn btn-default btn-block">Blood Pressure</button>
            </div>
            <div class="jump-to col-xs-4 col-sm-2">
                <button type="button" name="calories" class="btn btn-default btn-block">Calories</button>
            </div>
            <div class="jump-to col-xs-4 col-sm-2">
                <button type="button" name="exercise" class="btn btn-default btn-block">Exercise</button>
            </div>
            <div class="jump-to col-xs-4 col-sm-2">
                <button type="button" name="sleep" class="btn btn-default btn-block">Sleep</button>
            </div>
            <div class="jump-to col-xs-4 col-sm-2">
                <button type="button" name="weight" class="btn btn-default btn-block">Weight</button>
            </div>
        </div>
    </div>
</div>

<section class="row">
    <div class="col-sm-12">
        <?php BloodPressureMeasurementsView::showBody(); ?>
    </div>
</section>
        
<?php
        if (isset($_SESSION)) {
            unset($_SESSION['measurements']);
            unset($_SESSION['styles']);
            unset($_SESSION['scripts']);
        }
    }
    
}
?>

<?php //     public static function add() {
//         HeaderView::show("Add Measurements");
        
//         if (!isset($_SESSION) || !isset($_SESSION['profile'])):
            ?><!-- <p>Error: unable to add measurements. Profile data is missing.</p> --><?php
//             return;
//         endif;
        
//         $measurements = $_SESSION['measurements'];
        ?>
        <!-- 
<section>
    <form action="measurements_add_bloodPressure" method="post">
        <fieldset>
            <legend>Blood Pressure</legend> -->
            <!-- Pattern attribute and specific error reporting absent to avoid hints that weaken security -->
<!--             Systolic Pressure <input type="text" name="systolicPressure" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Diastolic Pressure <input type="text" name="diastolicPressure" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" required="required" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" required="required" tabindex="4" title="H:M" /><br />
            Notes <input type="text" name="notes" size="30" maxlength="4" tabindex="5" /><br />
        </fieldset>
        <div>
            <input type="submit" tabindex="6" />
            <input type="hidden" name="userName" value="<?php//$_SESSION['profile']->getUserName()?>" tabindex="7" />
        </div>
    </form>
</section>
-->
        <?php
    //    FooterView::show();
    //}
//}
?>