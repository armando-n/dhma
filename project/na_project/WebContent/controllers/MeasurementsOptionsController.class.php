<?php

class MeasurementsOptionsController {
    
    public static function run() {
        if (!isset($_SESSION))
            return array('success' => false, 'error', 'session data not found');

        if (!isset($_SESSION['profile']))
            return array('success' => false, 'error', 'you must log in before you can retrieve or modify you options.');

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

        // create new options objct from input
        $options = new MeasurementsOptions($_POST);
        if ($options->getErrorCount() > 0)
            return array('success' => false, 'error' => array_shift($options->getErrors()));
        
        // make sure options with the specified name for the specified user does not already exist
        $returnArray = MeasurementsOptionsDB::getOptions($options->getUserName(), $options->getOptionsName());
        if ($returnArray['success'])
            return array('success' => false, 'error' => 'options with the specified name already exists');

        // add options to the database and return the success/failure result array
        return MeasurementsOptionsDB::addOptions($options);
    }
    
    private static function edit() {
        if (!isset($_POST) || empty($_POST))
            return array('success' => false, 'error' => 'post data missing');
        if (!isset($_POST['oldOptionsName']) || empty($_POST['oldOptionsName']))
            return array('success' => false, 'error' => 'old options name missing');
        $_POST['userName'] = $_SESSION['profile']->getUserName();

        // make sure options with old name exists    
        $returnArray = MeasurementsOptionsDB::getOptions($_POST['userName'], $_POST['oldOptionsName']);
        if (!$returnArray['success'])
            return $returnArray;

        // create new options object from input
        $newOptions = new MeasurementsOptions($_POST);
        if ($newOptions->getErrorCount() > 0)
            return array('success' => false, 'error' => array_shift($newOptions->getErrors()));

        // update the database and return the success/failure result array
        return MeasurementsOptionsDB::editOptions($returnArray, $newOptions);
    }
    
    private static function delete() {
        if (!isset($_POST) || empty($_POST))
            return array('success' => false, 'error' => 'post data missing');
        if (!isset($_POST['optionsName']) || empty($_POST['optionsName']))
            return array('success' => false, 'error' => 'options name missing');
        
        // delete options from the database and return the success/failure result array
        return MeasurementsOptionsDB::deleteOptions($_SESSION['profile']->getUserName(), $_POST['optionsName']);
    }
    
    private static function get() {
        return MeasurementsOptionsDB::getOptionsFor($_SESSION['profile']->getUserName());
    }
    
}

?>