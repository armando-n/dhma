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
        
<section id="bloodPressure" class="row">
    <div id="view_bloodPressure_section" class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2></h2><?php
                if (!isset($measurements["bloodPressure"]) || empty($measurements["bloodPressure"])):
                        ?><p>No blood pressure measurements to show yet</p><?php
                else:
                    $i = 0; ?>
                <table class="table table-striped table-hover table-condensed table-responsive">
                    <caption>Blood Pressure</caption>
                    <thead>
                        <tr>
                            <th>Systolic</th>
                            <th>Diastolic</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody><?php
                    foreach ($measurements["bloodPressure"] as $bloodPressure): ?>
                        <tr id="bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d_H-i')?>" class="measurementRow">
                            <td id="systolicPressure_bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d_H-i')?>"><?=$bloodPressure->getSystolicPressure()?></td>
                            <td id="diastolicPressure_bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d_H-i')?>"><?=$bloodPressure->getDiastolicPressure()?></td>
                            <td id="date_bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d_H-i')?>"><?=$bloodPressure->getDate()?></td>
                            <td id="time_bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d_H-i')?>"><?=$bloodPressure->getTime()?></td>
                            <td id="notes_bloodPressure_<?=$bloodPressure->getDateTime()->format('Y-m-d_H-i')?>"><?=$bloodPressure->getNotes()?></td>
                        </tr><?php
                        $i++;
                    endforeach; ?>
                    </tbody>
                </table><?php
                endif; ?>
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-12">
            
                <div class="btn-group btn-group-justified" role="group">
                
                    <div class="btn-group" role="group">
                        <button type="button" id="add_bloodPressure_btn" class="addMeasurement btn btn-default">
                            <span class="glyphicon glyphicon-plus"></span>
                            &nbsp;Add
                        </button>
                    </div>
                    
                    <div class="btn-group" role="group">
                        <button type="button" id="edit_bloodPressure_btn" class="editMeasurement btn btn-default">
                            <span class="glyphicon glyphicon-pencil"></span>
                            &nbsp;Edit
                        </button>
                    </div>
                    
                    <div class="btn-group" role="group">
                        <button type="button" id="delete_bloodPressure_btn" class="deleteMeasurement btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                            &nbsp;Delete
                        </button>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
    <div id="add_bloodPressure_section" class="add_measurement_section col-sm-4">
        <hr />
        <form action="measurements_add_bloodPressure" method="post" class="form-horizontal">
        	<fieldset>
        		<legend>Add Blood Pressure Measurement</legend>
                <div class="form-group">
                    <label for="sPressureAdd" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Systolic Pressure</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="sPressureAdd" name="systolicPressure" class="form-control" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="dPressureAdd" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Diastolic Pressure</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="dPressureAdd" name="diastolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="pressDateAdd" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="date" id="pressDateAdd" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="pressTimeAdd" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="pressTimeAdd" name="time" required="required" class="form-control" tabindex="4" title="H:M" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="pressNotesAdd" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="pressNotesAdd" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                    <div class="btn-group btn-group-justified" role="group">
                        <div class="btn-group" role="group">
                            <button type="submit" id="submitAdd" class="btn btn-primary" tabindex="6">
                                <span class="glyphicon glyphicon-plus"></span>
                                &nbsp;Submit
                            </button>
                        </div>
                        
                        <div class="btn-group" role="group">
                            <button type="button" id="cancel_add_bloodPressure" class="cancelMeasurement btn btn-default">
                                <span class="glyphicon glyphicon-remove"></span>
                                &nbsp;Cancel
                            </button>
                        </div>
                    </div>
                    
                </div>
        	</fieldset>
        </form>
    </div>
    
    <div id="edit_bloodPressure_section" class="edit_measurement_section col-sm-4">
        <hr />
        <form action="measurements_edit_post_bloodPressure" method="post" class="form-horizontal">
            <fieldset>
                <legend>Edit Blood Pressure Measurement</legend>
                <div class="form-group">
                    <label for="systolicPressure_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Systolic Pressure</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="systolicPressure_bloodPressure_edit" name="systolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="diastolicPressure_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Diastolic Pressure</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="diastolicPressure_bloodPressure_edit" name="diastolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="date" id="date_bloodPressure_edit" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="time_bloodPressure_edit" name="time" required="required" class="form-control" tabindex="4" title="H:M" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_bloodPressure_edit" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                    <input type="hidden" id="oldDateTime" name="oldDateTime" value="" />
                    <div class="btn-group btn-group-justified" role="group">
                        <div class="btn-group" role="group">
                            <button type="submit" id="submitEdit" class="btn btn-primary" tabindex="6">
                                <span class="glyphicon glyphicon-ok"></span>
                                &nbsp;Save
                            </button>
                        </div>
                        
                        <div class="btn-group" role="group">
                            <button type="button" id="cancel_edit_bloodPressure" class="cancelMeasurement btn btn-default">
                                <span class="glyphicon glyphicon-remove"></span>
                                &nbsp;Cancel
                            </button>
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
        
<section class="row">
    <div class="col-sm-12">
        <h2><a id="something">Edit Blood Pressure Measurement</a></h2>
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
                <a href="measurements_show_all" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-remove"></span>
                    &nbsp;Cancel
                </a>
                <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" tabindex="7" />
        	</fieldset>
        </form>
    </div>
</section>
        
        <?php
    }
    
}
?>