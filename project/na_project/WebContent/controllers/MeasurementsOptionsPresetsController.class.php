<?php

class MeasurementsOptionsPresetsController {
    
    public static function run() {
        if (!isset($_SESSION))
            return array('success' => false, 'error', 'session data not found');

        if (!isset($_SESSION['profile']))
            return array('success' => false, 'error', 'you must log in before you can retrieve or modify you options presets.');

        if (!isset($_SESSION['action']))
            return array('success' => false, 'error', 'unrecognized command');

        switch ($_SESSION['action']) {
            case 'add'      : $result = self::add();
            case 'edit'     : $result = self::edit();
            case 'delete'   : $result = self::delete();
            case 'get'      : $result = self::get();
            default         : $result = array('success' => false, 'error' => 'unrecognized command');
        }
        
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    
    private static function add() {
        if (!isset($_POST) || empty($_POST))
            return array('success' => false, 'error' => 'post data missing');
        $_POST['userName'] = $_SESSION['profile']->getUserName();

        // create new preset objct from input
        $preset = new MeasurementsOptionsPreset($_POST);
        if ($preset->getErrorCount() > 0)
            return array('success' => false, 'error' => array_shift($preset->getErrors()));
        
        // make sure a preset with the specified name for the specified user does not already exist
        $returnArray = MeasurementsOptionsPresetsDB::getPreset($preset->getUserName(), $preset->getPresetName());
        if ($returnArray['success'])
            return array('success' => false, 'error' => 'a preset with the specified name already exists');

        // add preset to the database and return the success/failure result array
        return MeasurementsOptionsPresetsDB::addPreset($preset);
    }
    
    private static function edit() {
        if (!isset($_POST) || empty($_POST))
            return array('success' => false, 'error' => 'post data missing');
        if (!isset($_POST['oldPresetName']) || empty($_POST['oldPresetName']))
            return array('success' => false, 'error' => 'old preset name missing');
        $_POST['userName'] = $_SESSION['profile']->getUserName();

        // make sure preset with old name exists    
        $returnArray = MeasurementsOptionsPresetsDB::getPreset($_POST['userName'], $_POST['oldPresetName']);
        if (!$returnArray['success'])
            return $returnArray;

        // create new preset object from input
        $newPreset = new MeasurementsOptionsPreset($_POST);
        if ($newPreset->getErrorCount() > 0)
            return array('success' => false, 'error' => array_shift($newPreset->getErrors()));

        // update the database and return the success/failure result array
        return MeasurementsOptionsPresetsDB::editPreset($returnArray, $newPreset);
    }
    
    private static function delete() {
        if (!isset($_POST) || empty($_POST))
            return array('success' => false, 'error' => 'post data missing');
        if (!isset($_POST['presetName']) || empty($_POST['presetName']))
            return array('success' => false, 'error' => 'preset name missing');
        
        // delete preset from the database and return the success/failure result array
        return MeasurementsOptionsPresetsDB::deletePreset($_SESSION['profile']->getUserName(), $_POST['presetName']);
    }
    
    private static function get() {
        return MeasurementsOptionsPresetsDB::getPresetsFor($_SESSION['profile']->getUserName());
    }
    
}

?>