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
    
    public static function showBody($preset) {
        if (!isset($_SESSION) || !isset($_SESSION['profile'])):
            ?><p>Error: Sorry, I was unable to find your data</p><?php
            return;
        endif;
        ?>

<section class="row">
    <div id="bloodPressure_table_section" class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <table id="bloodPressure_table" class="table table-striped table-hover table-condensed table-responsive measurement-table">
                    <!-- DataTable is inserted here -->
                </table>
            </div>
        </div>
    </div>
    <div id="add_bloodPressure_section" class="add_measurement_section col-sm-4">
        <hr />
        <form action="measurements_add_bloodPressure" method="post" class="form-horizontal">
        	<fieldset>
        		<legend>Add Blood Pressure Measurement</legend>
                <div class="form-group">
                    <label for="systolicPressure_bloodPressure_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Systolic Pressure</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="systolicPressure_bloodPressure_add" name="systolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getBloodPressureUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="diastolicPressure_bloodPressure_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Diastolic Pressure</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="diastolicPressure_bloodPressure_add" name="diastolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getBloodPressureUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_bloodPressure_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                            <input type="text" id="date_bloodPressure_add" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_bloodPressure_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                            <input type="text" id="time_bloodPressure_add" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_bloodPressure_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_bloodPressure_add" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" id="userName_bloodPressure_add" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitAdd" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    &nbsp;Add
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_add_bloodPressure" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_add_bloodPressure_text">Cancel</span>
                                </button>
                            </div>
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
                        <div class="input-group">
                            <input type="text" id="systolicPressure_bloodPressure_edit" name="systolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getBloodPressureUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="diastolicPressure_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Diastolic Pressure</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="diastolicPressure_bloodPressure_edit" name="diastolicPressure" class="form-control" size="10" required="required" maxlength="4" tabindex="2" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getBloodPressureUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                            <input type="text" id="date_bloodPressure_edit" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                            <input type="text" id="time_bloodPressure_edit" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_bloodPressure_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_bloodPressure_edit" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        <input type="hidden" id="oldDateTime_bloodPressure" name="oldDateTime" value="" class="oldDateTime" />
                        
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
                                    &nbsp;<span id="cancel_edit_bloodPressure_text">Cancel</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </fieldset>
        </form>
    </div>
    
</section>

<!-- Charts Section -->
<section id="bloodPressure_charts_row" class="row">
    <div id="firstChart_bloodPressure" class="col-sm-6 firstChart">
        <!-- Primary Chart -->
    </div>
    <div id="secondChart_bloodPressure" class="col-sm-6 secondChart">
        <!-- Secondary Chart -->
    </div>
</section>

<!-- </div> -->
        <?php
        unset($_SESSION['measurements']['bloodPressure']);
    }
    
}
?>