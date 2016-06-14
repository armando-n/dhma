<?php
class CalorieMeasurementsView {
    
    public static function show() {
        HeaderView::show("Calorie Measurements");
        CalorieMeasurementsView::showBody();
        FooterView::show();
    }
    
    public static function edit() {
        HeaderView::show("Calorie Measurements | Edit");
        CalorieMeasurementsView::editBody();
        FooterView::show();
    }
    
    public static function showBody($preset) {
        if (!isset($_SESSION) || !isset($_SESSION['profile'])):
            ?><p>Error: Sorry, I was unable to find your data</p><?php
            return;
        endif;
        ?>
        
<section class="row">
    <div id="calorie_table_section" class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <table id="calorie_table" class="table table-striped table-hover table-condensed table-responsive measurement-table">
                    <!-- DataTable is inserted here -->
                </table>
            </div>
        </div>
    </div>
    <div id="add_calorie_section" class="add_measurement_section col-sm-4">
        <hr />
        <form action="measurements_add_calories" method="post" class="form-horizontal">
        	<fieldset>
        		<legend>Add Calorie Measurement</legend>
                <div class="form-group">
                    <label for="calories_calorie_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Calories</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="calories_calorie_add" name="calories" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getCalorieUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_calorie_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                            <input type="text" id="date_calorie_add" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_calorie_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                            <input type="text" id="time_calorie_add" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_calorie_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_calorie_add" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" id="userName_calorie_add" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitAdd" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    &nbsp;Add
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_add_calorie" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_add_calorie_text">Cancel</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
        	</fieldset>
        </form>
    </div>
    
    <div id="edit_calorie_section" class="edit_measurement_section col-sm-4">
        <hr />
        <form action="measurements_edit_post_calories" method="post" class="form-horizontal">
            <fieldset>
                <legend>Edit Calorie Measurement</legend>
                <div class="form-group">
                    <label for="calories_calorie_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Calories</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group">
                            <input type="text" id="calories_calorie_edit" name="calories" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                            <span class="input-group-addon units-addon">
                                <?=$preset->getCalorieUnits()?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_calorie_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                            <input type="text" id="date_calorie_edit" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_calorie_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                            <input type="text" id="time_calorie_edit" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_calorie_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_calorie_edit" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        <input type="hidden" id="oldDateTime_calorie" name="oldDateTime" value="" class="oldDateTime" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitEdit" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    &nbsp;Save
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_edit_calorie" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_edit_calorie_text">Cancel</span>
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
<section id="calorie_charts_row" class="row">
    <div id="calorie_charts_primary_section" class="col-sm-12 col-md-6">
        <div class="row">
            <div id="calorie_chart_primary" class="col-sm-12">
                <!-- Primary Chart -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 btn-group btn-group-justified" role="group">
            
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_individual_chart_btn_primary" class="btn btn-default btn-change-chart tooltip-help active" data-toggle="tooltip" title="Show a chart of individual calorie entries">
                        Individual
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_daily_chart_btn_primary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of daily calorie totals">
                        Daily
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_weekly_chart_btn_primary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of weekly calorie totals">
                        Weekly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_monthly_chart_btn_primary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of monthly calorie totals">
                        Monthly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_yearly_chart_btn_primary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of yearly calorie totals">
                        Yearly
                    </button>
                </div>
                
            </div>
        </div>    
    </div>
    
    <div id="calorie_charts_secondary_section" class="col-sm-12 col-md-6 chart-secondary">
        <div class="row">
            <div id="calorie_chart_secondary" class="col-sm-12">
                <!-- Secondary Chart -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 btn-group btn-group-justified" role="group">
            
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_individual_chart_btn_secondary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of individual calorie entries">
                        Individual
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_daily_chart_btn_secondary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of daily calorie totals">
                        Daily
                    </button>
                </div><div class="btn-group" role="group">
                    <button type="button" id="calorie_weekly_chart_btn_secondary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of weekly calorie totals">
                        Weekly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_monthly_chart_btn_secondary" class="btn btn-default btn-change-chart tooltip-help active" data-toggle="tooltip" title="Show a chart of monthly calorie totals">
                        Monthly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="calorie_yearly_chart_btn_secondary" class="btn btn-default btn-change-chart tooltip-help" data-toggle="tooltip" title="Show a chart of yearly calorie totals">
                        Yearly
                    </button>
                </div>
                
            </div>
        </div>
    </div>
</section>

<!-- </div> -->
        <?php
        unset($_SESSION['measurements']['calories']);
    }
    
}
?>