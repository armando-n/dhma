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
    
    public static function showBody($preset) {
        if (!isset($_SESSION) || !isset($_SESSION['profile'])):
            ?><p>Error: Sorry, I was unable to find your data</p><?php
            return;
        endif;
        ?>
        
<section class="row">
    <div id="sleep_table_section" class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <table id="sleep_table" class="table table-striped table-hover table-condensed table-responsive measurement-table">
                    <!-- DataTable is inserted here -->
                </table>
            </div>
        </div>
    </div>
    <div id="add_sleep_section" class="add_measurement_section col-sm-4">
        <hr />
        <form action="measurements_add_sleep" method="post" class="form-horizontal">
        	<fieldset>
        		<legend>Add Sleep Measurement</legend>
                <div class="form-group">
                    <label for="duration_sleep_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Duration</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="duration_sleep_add" name="duration" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getSleepUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_sleep_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type="text" id="date_sleep_add" name="date" required="required" class="form-control" tabindex="3" title="yyyy-mm-dd" />
                            <span class="input-group-btn">
                        		<label for="sleep_add_date_now" class=".sr-only">Set date to today</label>
                        		<button type="button" class="btn btn-default tooltip-help today-btn" id="sleep_add_date_now" data-placement="left" title="Set the date to today">today</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_sleep_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                            <input type="text" id="time_sleep_add" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-btn">
                        		<label for="sleep_add_time_now" class=".sr-only">Set time to now</label>
                        		<button type="button" class="btn btn-default tooltip-help now-btn" id="sleep_add_time_now" data-placement="left" title="Set the time to now">now</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_sleep_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_sleep_add" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" id="userName_sleep_add" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitAdd" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    &nbsp;Add
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_add_sleep" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_add_sleep_text">Cancel</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
        	</fieldset>
        </form>
    </div>
    
    <div id="edit_sleep_section" class="edit_measurement_section col-sm-4">
        <hr />
        <form action="measurements_edit_post_sleep" method="post" class="form-horizontal">
            <fieldset>
                <legend>Edit Sleep Measurement</legend>
                <div class="form-group">
                    <label for="duration_sleep_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Duration</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="duration_sleep_edit" name="duration" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getSleepUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_sleep_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type="text" id="date_sleep_edit" name="date" required="required" class="form-control" tabindex="3" title="yyyy-mm-dd" />
                            <span class="input-group-btn">
                        		<label for="sleep_edit_date_now" class=".sr-only">Set date to today</label>
                        		<button type="button" class="btn btn-default tooltip-help today-btn" id="sleep_edit_date_now" data-placement="left" title="Set the date to today">today</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_sleep_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                        	<span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                            <input type="text" id="time_sleep_edit" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-btn">
                        		<label for="sleep_edit_time_now" class=".sr-only">Set time to now</label>
                        		<button type="button" class="btn btn-default tooltip-help now-btn" id="sleep_edit_time_now" data-placement="left" title="Set the time to now">now</button>
                        	</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_sleep_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_sleep_edit" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        <input type="hidden" id="oldDateTime_sleep" name="oldDateTime" value="" class="oldDateTime" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitEdit" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    &nbsp;Save
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_edit_sleep" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_edit_sleep_text">Cancel</span>
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
<section id="sleep_charts_row" class="row">
    <div id="firstChart_sleep" class="col-sm-6">
        <!-- Primary Chart -->
    </div>
    <div id="secondChart_sleep" class="col-sm-6">
        <!-- Secondary Chart -->
    </div>
</section>

<!-- </div> -->
        <?php
        unset($_SESSION['measurements']['sleep']);
    }
    
}
?>