<?php
class ExerciseMeasurementsView {
    
    public static function show() {
        HeaderView::show("Exercise Measurements");
        ExerciseMeasurementsView::showBody();
        FooterView::show();
    }
    
    public static function edit() {
        HeaderView::show("Exercise Measurements | Edit");
        ExerciseMeasurementsView::editBody();
        FooterView::show();
    }
    
    public static function showBody($preset) {
        if (!isset($_SESSION) || !isset($_SESSION['profile'])):
            ?><p>Error: Sorry, I was unable to find your data</p><?php
            return;
        endif;
        ?>
        
<section class="row">
    <div id="exercise_table_section" class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <table id="exercise_table" class="table table-striped table-hover table-condensed table-responsive measurement-table">
                    <!-- DataTable is inserted here -->
                </table>
            </div>
        </div>
    </div>
    <div id="add_exercise_section" class="add_measurement_section col-sm-4">
        <hr />
        <form action="measurements_add_exercise" method="post" class="form-horizontal">
        	<fieldset>
        		<legend>Add Exercise Measurement</legend>
                <div class="form-group">
                    <label for="duration_exercise_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Duration</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="duration_exercise_add" name="duration" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getExerciseUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="type_exercise_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Type</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="type_exercise_add" name="type" class="form-control" size="10" required="required" maxlength="255" tabindex="2" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_exercise_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type="text" id="date_exercise_add" name="date" required="required" class="form-control" tabindex="3" title="yyyy-mm-dd" />
                            <span class="input-group-btn">
                        		<label for="exercise_add_date_now" class=".sr-only">Set date to today</label>
                        		<button type="button" class="btn btn-default tooltip-help today-btn" id="exercise_add_date_now" data-placement="left" title="Set the date to today">today</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_exercise_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                            <input type="text" id="time_exercise_add" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-btn">
                        		<label for="exercise_add_time_now" class=".sr-only">Set time to now</label>
                        		<button type="button" class="btn btn-default tooltip-help now-btn" id="exercise_add_time_now" data-placement="left" title="Set the time to now">now</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_exercise_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_exercise_add" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" id="userName_exercise_add" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitAdd" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    &nbsp;Add
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_add_exercise" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_add_exercise_text">Cancel</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
        	</fieldset>
        </form>
    </div>
    
    <div id="edit_exercise_section" class="edit_measurement_section col-sm-4">
        <hr />
        <form action="measurements_edit_post_exercise" method="post" class="form-horizontal">
            <fieldset>
                <legend>Edit Exercise Measurement</legend>
                <div class="form-group">
                    <label for="duration_exercise_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Duration</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="duration_exercise_edit" name="duration" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getExerciseUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="type_exercise_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Type</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="type_exercise_edit" name="type" class="form-control" size="10" required="required" maxlength="255" tabindex="2" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_exercise_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type="text" id="date_exercise_edit" name="date" required="required" class="form-control" tabindex="3" title="yyyy-mm-dd" />
                            <span class="input-group-btn">
                        		<label for="exercise_edit_date_now" class=".sr-only">Set date to today</label>
                        		<button type="button" class="btn btn-default tooltip-help today-btn" id="exercise_edit_date_now" data-placement="left" title="Set the date to today">today</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_exercise_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                            <input type="text" id="time_exercise_edit" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-btn">
                        		<label for="exercise_edit_time_now" class=".sr-only">Set time to now</label>
                        		<button type="button" class="btn btn-default tooltip-help now-btn" id="exercise_edit_time_now" data-placement="left" title="Set the time to now">now</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_exercise_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_exercise_edit" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        <input type="hidden" id="oldDateTime_exercise" name="oldDateTime" value="" class="oldDateTime" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitEdit" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    &nbsp;Save
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_edit_exercise" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_edit_exercise_text">Cancel</span>
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
<section id="exercise_charts_row" class="row">
    <div id="firstChart_exercise" class="col-sm-6 firstChart">
        <!-- Primary Chart -->
    </div>
    <div id="secondChart_exercise" class="col-sm-6 secondChart">
        <!-- Secondary Chart -->
    </div>
</section>

<!-- </div> -->
        <?php
        unset($_SESSION['measurements']['exercise']);
    }
    
}
?>