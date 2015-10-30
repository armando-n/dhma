<?php
class BloodPressureMeasurementsView {
    
    public static function show() {
        HeaderView::show("Blood Pressure Measurements");
        BloodPressureMeasurementsView::showBody();
        FooterView::show();
    }
    
    public static function edit() {
        HeaderView::show("Blood Pressure Measurements | Edit");
        BloodPressureMeasurementsView::editBody();
        FooterView::show();
    }
    
    public static function showBody() {
        if (!isset($_SESSION) || !isset($_SESSION['measurements']) || !isset($_SESSION['profile'])):
            ?><p>Error: measurements not found</p><?php
            return;
        endif;
        $measurements = $_SESSION['measurements'];
        ?>
        
<section>
    <h2><a id="bloodPressure">Blood Pressure Measurements</a></h2>
    <?php
        if (!isset($measurements["bloodPressure"]) || empty($measurements["bloodPressure"])):
            ?><p>No blood pressure measurements to show yet</p><?php
        else: ?>
    <table>
        <tr>
            <th>Blood Pressure (systolic / diastolic)</th>
            <th>Date / Time</th>
            <th>Notes</th>
            <th colspan="2">Actions</th>
        </tr><?php
            foreach ($measurements["bloodPressure"] as $bloodPressure):
                ?>
        <tr>
                <td><?=$bloodPressure->getMeasurement()?></td>
                <td><?=$bloodPressure->getDate() . ' / ' . $bloodPressure->getTime()?></td>
                <td><?=$bloodPressure->getNotes()?></td>
                <td>
                	<form action="measurements_edit_show_bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d H-i')?>" method="post">
                		<input type="submit" value="Edit" />
                	</form>
            	</td>
            	<td>
                	<form action="measurements_delete_<?=$bloodPressure->getDateTime()->format('Y-m-d H-i')?>" method="post">
                		<input type="submit" value="Delete" disabled="disabled" />
                	</form>
            	</td>
        </tr>
<?php   endforeach; ?>
    </table>
    <hr />
<?php   endif; ?>
    <form action="measurements_add_bloodPressure" method="post">
    	<fieldset>
    		<legend>Add a measurement</legend>
        	Systolic Pressure <input type="text" name="systolicPressure" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Diastolic Pressure <input type="text" name="diastolicPressure" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" required="required" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" required="required" tabindex="4" title="H:M" /><br />
            Notes <input type="text" name="notes" size="30" maxlength="50" tabindex="5" /><br />
        	<input type="submit" value="Add" tabindex="6" />
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="7" />
    	</fieldset>
    </form>
</section>

        <?php
        unset($_SESSION['measurements']['bloodPressure']);
    }
    
    public static function editBody() {
        if (!isset($_SESSION) || !isset($_SESSION['measurement']) || !isset($_SESSION['profile'])):
            ?><p>Error: unable to show measurements. Data is missing.</p><?php
            return;
        endif;
        
        $measurement = $_SESSION['measurement'];
        ?>
        
<section>
    <h2><a id="bloodPressure">Edit Blood Pressure Measurement</a></h2>
 	<form action="measurements_edit_post_bloodPressure" method="post">
    	<fieldset>
    		<legend>Edit Measurement</legend>
        	Systolic Pressure <input type="text" name="systolicPressure" value="<?=$measurement->getSystolicPressure()?>" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Diastolic Pressure <input type="text" name="diastolicPressure" value="<?=$measurement->getDiastolicPressure()?>" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" value="<?=$measurement->getDate()?>" required="required" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" value="<?=$measurement->getTime()?>" required="required" tabindex="4" title="H:M" /><br />
            Notes <input type="text" name="notes" value="<?=$measurement->getNotes()?>" size="30" maxlength="50" tabindex="5" /><br />
        	<input type="submit" value="Save Changes" tabindex="6" />
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="7" />
    	</fieldset>
    </form>
</section>
        
        <?php
    }
    
}
?>