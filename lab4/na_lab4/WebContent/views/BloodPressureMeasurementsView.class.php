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
        
<section class="row">
    <div class="col-sm-12">
        <h2><a id="bloodPressure">Blood Pressure Measurements</a></h2><?php
        if (!isset($measurements["bloodPressure"]) || empty($measurements["bloodPressure"])):
                ?><p>No blood pressure measurements to show yet</p><?php
        else: ?>
        <table class="table table-striped table-hover table-condensed table-responsive">
            <thead>
                <tr>
                    <th>Blood Pressure (systolic / diastolic)</th>
                    <th>Date / Time</th>
                    <th>Notes</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody><?php
            foreach ($measurements["bloodPressure"] as $bloodPressure): ?>
                <tr>
                    <td><?=$bloodPressure->getMeasurement()?></td>
                    <td><?=$bloodPressure->getDate() . ' / ' . $bloodPressure->getTime()?></td>
                    <td><?=$bloodPressure->getNotes()?></td>
                    <td>
                    	<form action="measurements_edit_show_bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d H-i')?>" method="post">
                    		<label for="submitEdit" class="btn btn-primary btn-sm">
                                <span class="glyphicon glyphicon-pencil"></span>
                                &nbsp;Edit
                            </label>
                            <input type="submit" id="submitEdit" class="hidden" value="Edit" />
                    	</form>
                	</td>
                	<td>
                    	<form action="measurements_delete_<?=$bloodPressure->getDateTime()->format('Y-m-d H-i')?>" method="post">
                            <label for="submitDelete" class="btn btn-danger btn-sm disabled">
                                <span class="glyphicon glyphicon-trash"></span>
                                &nbsp;Delete
                            </label>
                    		<input type="submit" id="submitDelete" class="hidden" value="Delete" disabled="disabled" />
                    	</form>
                	</td>
                </tr><?php
            endforeach; ?>
            </tbody>
        </table><?php
        endif; ?>
    </div>
</section>
<section class="row">
    <div class="col-sm-12">
        <hr />
        <form action="measurements_add_bloodPressure" method="post" class="form-horizontal">
        	<fieldset>
        		<legend>Add a measurement</legend>
                <div class="form-group">
                    <label for="sPressureAdd" class="control-label col-md-2">Systolic Pressure</label>
                    <div class="col-md-10">
                        <input type="text" id="sPressureAdd" name="systolicPressure" class="form-control" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="dPressureAdd" class="control-label col-md-2">Diastolic Pressure</label>
                    <div class="col-md-10">
                        <input type="text" id="dPressureAdd" name="diastolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="pressDateAdd" class="control-label col-md-2">Date</label>
                    <div class="col-md-10">
                        <input type="date" id="pressDateAdd" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="pressTimeAdd" class="control-label col-md-2">Time</label>
                    <div class="col-md-10">
                        <input type="time" id="timeAdd" name="time" required="required" class="form-control" tabindex="4" title="H:M" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="pressNotesAdd" class="control-label col-md-2">Notes</label>
                    <div class="col-md-10">
                        <input type="text" id="pressNotesAdd" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                    <div class="btn-group btn-group-justified" role="group">
                        <div class="btn-group" role="group">
                            <button type="submit" id="submitAdd" class="btn btn-primary" tabindex="6">
                                <span class="glyphicon glyphicon-plus"></span>
                                &nbsp;Add
                            </button>
                        </div>
                        
                        <div class="btn-group" role="group">
                            <a href="measurements_show_all" class="btn btn-default" tabindex="7">
                                <span class="glyphicon glyphicon-remove"></span>
                                &nbsp;Cancel
                            </a>
                        </div>
                    </div>
                    
                </div>
        	</fieldset>
        </form>
    </div>
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
            <label for="submitSave" class="btn btn-primary">
                <span class="glyphicon glyphicon-ok"></span>
                &nbsp;Save Changes
            </label>
        	<input type="submit" id="submitSave" class="hidden" value="Save Changes" tabindex="6" />
            <a href="measurements_show_bloodPressure" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-remove"></span>
                &nbsp;Cancel
            </a>
            <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="7" />
    	</fieldset>
    </form>
</section>
        
        <?php
    }
    
}
?>