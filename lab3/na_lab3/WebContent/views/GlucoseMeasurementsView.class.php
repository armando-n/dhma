<?php
class GlucoseMeasurementsView {
    
    public static function show() {
        HeaderView::show("Glucose Measurements");
        GlucoseMeasurementsView::showBody();
        FooterView::show();
    }
    
    public static function edit() {
        HeaderView::show("Glucose Measurements | Edit");
        GlucoseMeasurementsView::editBody();
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
    <h2><a id="glucose">Glucose Measurements</a></h2>
    <?php
        if (!isset($measurements["glucose"]) || empty($measurements["glucose"])):
            ?><p>No glucose measurements to show yet</p><?php
        else: ?>
    <table>
        <tr>
            <th>Glucose (mg/dL)</th>
            <th>Date / Time</th>
            <th>Notes</th>
            <th colspan="2">Actions</th>
        </tr><?php
            foreach ($measurements["glucose"] as $glucose):
                ?>
        <tr>
                <td><?=$glucose->getMeasurement()?></td>
                <td><?=$glucose->getDate() . ' / ' . $glucose->getTime()?></td>
                <td><?=$glucose->getNotes()?></td>
                <td>
                	<form action="measurements_edit_show_glucose_<?=$glucose->getDateTime()->format('Y-m-d H-i')?>" method="post">
                		<input type="submit" value="Edit" />
                	</form>
            	</td>
            	<td>
                	<form action="measurements_delete_<?=$glucose->getDateTime()->format('Y-m-d H-i')?>" method="post">
                		<input type="submit" value="Delete" disabled="disabled" />
                	</form>
            	</td>
        </tr>
<?php   endforeach; ?>
    </table>
    <hr />
<?php   endif; ?>
    <form action="measurements_add_glucose" method="post">
    	<fieldset>
    		<legend>Add a measurement</legend>
        	Glucose Levels <input type="text" name="glucose" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" required="required" tabindex="2" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" required="required" tabindex="3" title="H:M" /><br />
            Notes <input type="text" name="notes" size="30" maxlength="50" tabindex="4" /><br />
        	<input type="submit" value="Add" tabindex="5" />
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="6" />
    	</fieldset>
    </form>
</section>

        <?php
        unset($_SESSION['measurements']['glucose']);
    }
    
    public static function editBody() {
        if (!isset($_SESSION) || !isset($_SESSION['measurement']) || !isset($_SESSION['profile'])):
            ?><p>Error: unable to show measurements. Data is missing.</p><?php
            return;
        endif;
        
        $measurement = $_SESSION['measurement'];
        ?>
        
<section>
    <h2><a id="glucose">Edit Glucose Measurement</a></h2>
 	<form action="measurements_edit_post_glucose" method="post">
    	<fieldset>
    		<legend>Edit Measurement</legend>
        	Glucose Levels <input type="text" name="glucose" value="<?=$measurement->getMeasurement()?>" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" /><br />
            Date <input type="date" name="date" value="<?=$measurement->getDate()?>" required="required" tabindex="2" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
            Time <input type="time" name="time" value="<?=$measurement->getTime()?>" required="required" tabindex="3" title="H:M" /><br />
            Notes <input type="text" name="notes" value="<?=$measurement->getNotes()?>" size="30" maxlength="50" tabindex="4" /><br />
        	<input type="submit" value="Save Changes" tabindex="5" />
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="6" />
    	</fieldset>
    </form>
</section>
        
        <?php
    }
    
}
?>