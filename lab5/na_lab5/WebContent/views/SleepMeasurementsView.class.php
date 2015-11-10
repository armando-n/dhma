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
        
<section id="sleep" class="row">
    <div id="view_sleep_section" class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2></h2><?php
                if (!isset($measurements["sleep"]) || empty($measurements["sleep"])):
                        ?><p>No sleep measurements to show yet</p><?php
                else:
                    $i = 0; ?>
                <table class="table table-striped table-hover table-condensed table-responsive">
                    <caption>Sleep</caption>
                    <thead>
                        <tr>
                            <th>Duration (mins)</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody><?php
                    foreach ($measurements["sleep"] as $sleep): ?>
                        <tr id="sleep_<?=$sleep->getDateTime()->format('Y-m-d_H-i')?>" class="measurementRow">
                            <td id="duration_sleep_<?=$sleep->getDateTime()->format('Y-m-d_H-i')?>"><?=$sleep->getMeasurement()?></td>
                            <td id="date_sleep_<?=$sleep->getDateTime()->format('Y-m-d_H-i')?>"><?=$sleep->getDate()?></td>
                            <td id="time_sleep_<?=$sleep->getDateTime()->format('Y-m-d_H-i')?>"><?=$sleep->getTime()?></td>
                            <td id="notes_sleep_<?=$sleep->getDateTime()->format('Y-m-d_H-i')?>"><?=$sleep->getNotes()?></td>
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
                        <button type="button" id="add_sleep_btn" class="addMeasurement btn btn-default">
                            <span class="glyphicon glyphicon-plus"></span>
                            &nbsp;Add
                        </button>
                    </div>
                    
                    <div class="btn-group" role="group">
                        <button type="button" id="edit_sleep_btn" class="editMeasurement btn btn-default">
                            <span class="glyphicon glyphicon-pencil"></span>
                            &nbsp;Edit
                        </button>
                    </div>
                    
                    <div class="btn-group" role="group">
                        <button type="button" id="delete_sleep_btn" class="deleteMeasurement btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                            &nbsp;Delete
                        </button>
                    </div>
                    
                </div>
                
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
                        <input type="text" id="duration_sleep_add" name="duration" class="form-control" size="10" autofocus="autofocus" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_sleep_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="date" id="date_sleep_add" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_sleep_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="time_sleep_add" name="time" required="required" class="form-control" tabindex="4" title="H:M" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_sleep_add" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_sleep_add" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" /><br />
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
                            <button type="button" id="cancel_add_sleep" class="cancelMeasurement btn btn-default">
                                <span class="glyphicon glyphicon-remove"></span>
                                &nbsp;Cancel
                            </button>
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
                        <input type="text" id="duration_sleep_edit" name="duration" class="form-control" size="10" required="required" maxlength="4" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_sleep_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Date</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="date" id="date_sleep_edit" name="date" required="required" class="form-control" tabindex="3" title="mm/dd/yyyy or mm-dd-yyyy" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_sleep_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Time</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="time_sleep_edit" name="time" required="required" class="form-control" tabindex="4" title="H:M" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes_sleep_edit" class="control-label meas-label col-xs-3 col-sm-12 col-md-4">Notes</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="notes_sleep_edit" name="notes" class="form-control" size="30" maxlength="50" tabindex="5" /><br />
                    </div>
                </div>
                <div class="form-group">
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
        unset($_SESSION['measurements']['sleep']);
    }
    
}
?>