<?php
class SleepMeasurementsView {
    
    public static function show() {
        HeaderView::show("Sleep Measurements");
        SleepMeasurementsView::showBody();
        FooterView::show();
    }
    
    public static function edit() {
        HeaderView::show("Sleep Measurements | Edit");
        SleepMeasurementsView::editBody();
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
    <h2><a id="sleep">Sleep Measurements</a></h2>
    <?php
        if (!isset($measurements["sleep"]) || empty($measurements["sleep"])):
            ?><p>No sleep measurements to show yet</p><?php
        else: ?>
    <table>
        <tr>
            <th>Duration (mins)</th>
            <th>Date / Time</th>
            <th>Notes</th>
            <th colspan="2">Actions</th>
        </tr><?php
            foreach ($measurements["sleep"] as $sleep):
                ?>
        <tr>
                <td><?=$sleep->getMeasurement()?></td>
                <td><?=$sleep->getDate() . ' / ' . $sleep->getTime()?></td>
                <td><?=$sleep->getNotes()?></td>
                <td>
                	<form action="measurements_edit_show_sleep_<?=$sleep->getDateTime()->format('Y-m-d H-i')?>" method="post">
                		<input class="btn btn-primary btn-sm" type="submit" value="Edit" />
                	</form>
            	</td>
            	<td>
                	<form action="measurements_delete_<?=$sleep->getDateTime()->format('Y-m-d H-i')?>" method="post">
                		<input class="btn btn-danger btn-sm" type="submit" value="Delete" disabled="disabled" />
                	</form>
            	</td>
        </tr>
<?php   endforeach; ?>
    </table>
    <hr />
<?php   endif; ?>
    <form action="measurements_add_sleep" method="post">
    	<fieldset>
    		<legend>Add a measurement</legend>
        	Duration <input type="text" name="duration" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" required="required" tabindex="2" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" required="required" tabindex="3" title="H:M" /><br />
            Notes <input type="text" name="notes" size="30" maxlength="50" tabindex="4" /><br />
        	<input type="submit" class="btn btn-primary" value="Add" tabindex="5" />
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="6" />
    	</fieldset>
    </form>
</section>

        <?php
        unset($_SESSION['measurements']['sleep']);
    }
    
    public static function editBody() {
        if (!isset($_SESSION) || !isset($_SESSION['measurement']) || !isset($_SESSION['profile'])):
            ?><p>Error: unable to show measurements. Data is missing.</p><?php
            return;
        endif;
        
        $measurement = $_SESSION['measurement'];
        ?>
        
<section>
    <h2><a id="sleep">Edit Sleep Measurement</a></h2>
 	<form action="measurements_edit_post_sleep" method="post">
    	<fieldset>
    		<legend>Edit Measurement</legend>
        	Duration <input type="text" name="duration" value="<?=$measurement->getMeasurement()?>" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" value="<?=$measurement->getDate()?>" required="required" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" value="<?=$measurement->getTime()?>" required="required" tabindex="4" title="H:M" /><br />
            Notes <input type="text" name="notes" value="<?=$measurement->getNotes()?>" size="30" maxlength="50" tabindex="5" /><br />
        	<input type="submit" class="btn btn-primary" value="Save Changes" tabindex="6" />
            <a href="measurements_show_sleep" class="btn btn-default btn-sm">Cancel</a>
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="7" />
    	</fieldset>
    </form>
</section>
        
        <?php
    }
    
}
?>