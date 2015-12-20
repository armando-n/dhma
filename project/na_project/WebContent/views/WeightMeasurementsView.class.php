<?php
class WeightMeasurementsView {
    
    public static function show() {
        HeaderView::show("Weight Measurements");
        WeightMeasurementsView::showBody();
        FooterView::show();
    }
    
    public static function edit() {
        HeaderView::show("Weight Measurements | Edit");
        WeightMeasurementsView::editBody();
        FooterView::show();
    }
    
    public static function showBody() {
        if (!isset($_SESSION) || !isset($_SESSION['measurements']) || !isset($_SESSION['profile'])):
            ?><p>Error: measurements not found</p><?php
            return;
        endif;
        $measurements = $_SESSION['measurements'];
        ?>
<!-- <div class="panel panel-primary"> -->
<!--     <div class="panel-heading"><h2>Weight</h2></div> -->
        
<section class="row">
    <div id="view_weight_section" class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <table id="weight_table" class="table table-striped table-hover table-condensed table-responsive">
                    <!-- DataTable is inserted here -->
                </table>
            </div>
        </div>
    </div>
    <div id="add_weight_section" class="add_measurement_section col-sm-4">
        <hr />
        <form action="measurements_add_weight" method="post" class="form-horizontal">
        	<fieldset>
        		<legend>Add Weight Measurement</legend>
                <div class="form-group">
                    <label for="weight_weight_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Weight</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="weight_weight_add" name="weight" class="form-control" size="10" required="required" maxlength="10" tabindex="1" pattern="^((\d+)|(\d*\.\d+))$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_weight_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                            <input type="text" id="date_weight_add" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_weight_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                            <input type="text" id="time_weight_add" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_weight_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_weight_add" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" id="userName_weight_add" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitAdd" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    &nbsp;Add
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_add_weight" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_add_weight_text">Cancel</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
        	</fieldset>
        </form>
    </div>
    
    <div id="edit_weight_section" class="edit_measurement_section col-sm-4">
        <hr />
        <form action="measurements_edit_post_weight" method="post" class="form-horizontal">
            <fieldset>
                <legend>Edit Weight Measurement</legend>
                <div class="form-group">
                    <label for="weight_weight_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Weight</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="weight_weight_edit" name="weight" class="form-control" size="10" required="required" maxlength="10" tabindex="1" pattern="^((\d+)|(\d*\.\d+))$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_weight_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date date-picker">
                            <input type="text" id="date_weight_edit" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_weight_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <div class="input-group date time-picker">
                            <input type="text" id="time_weight_edit" name="time" required="required" class="form-control" tabindex="4" title="H:M" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_weight_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_weight_edit" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" name="userName" value="<?=$_SESSION['profile']->getUserName()?>" />
                        <input type="hidden" id="oldDateTime_weight" name="oldDateTime" value="" class="oldDateTime" />
                        
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <button type="submit" id="submitEdit" class="btn btn-primary" tabindex="6">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    &nbsp;Save
                                </button>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <button type="button" id="cancel_edit_weight" class="cancelMeasurement btn btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    &nbsp;<span id="cancel_edit_weight_text">Cancel</span>
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
<section id="weight_charts_row" class="row">
    <div id="weight_charts_primary_column" class="col-sm-12 col-md-6">
        <div class="row">
            <div id="weight_chart_primary" class="col-sm-12">
                <!-- Primary Chart -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 btn-group btn-group-justified" role="group">
            
                <div class="btn-group" role="group">
                    <button type="button" id="weight_individual_chart_btn_primary" class="btn btn-default btn-change-chart active" data-toggle="tooltip" title="Show a chart of individual weight entries">
                        Individual
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="weight_day_chart_btn_primary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of daily weight averages">
                        Daily
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="weight_week_chart_btn_primary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of weekly weight averages">
                        Weekly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="weight_month_chart_btn_primary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of monthly weight averages">
                        Monthly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="weight_year_chart_btn_primary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of yearly weight averages">
                        Yearly
                    </button>
                </div>
                
            </div>
        </div>    
    </div>
    
    <div id="weight_charts_secondary_column" class="col-sm-12 col-md-6 chart-secondary">
        <div class="row">
            <div id="weight_chart_secondary" class="col-sm-12">
                <!-- Secondary Chart -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 btn-group btn-group-justified" role="group">
            
                <div class="btn-group" role="group">
                    <button type="button" id="weight_individual_chart_btn_secondary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of individual weight entries">
                        Individual
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="weight_day_chart_btn_secondary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of daily weight averages">
                        Daily
                    </button>
                </div><div class="btn-group" role="group">
                    <button type="button" id="weight_week_chart_btn_secondary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of weekly weight averages">
                        Weekly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="weight_month_chart_btn_secondary" class="btn btn-default btn-change-chart active" data-toggle="tooltip" title="Show a chart of monthly weight averages">
                        Monthly
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" id="weight_year_chart_btn_secondary" class="btn btn-default btn-change-chart" data-toggle="tooltip" title="Show a chart of yearly weight averages">
                        Yearly
                    </button>
                </div>
                
            </div>
        </div>
    </div>
</section>

<!-- </div> -->

        <?php
        unset($_SESSION['measurements']['weight']);
    }
    
}
?>