<?php

class MeasurementsOptionsController {
    
    public static function run() {
        try {
            if (!isset($_SESSION))
                throw new MissingArgumentException('Session data not found');
    
            if (!isset($_SESSION['profile']))
                throw new MissingArgumentException('You must log in before you can retrieve or modify your options');
    
            if (!isset($_SESSION['action']))
                throw new MissingArgumentException('Command not found');
    
            switch ($_SESSION['action']) {
                case 'add'      : $resultData = self::add();
                case 'edit'     : $resultData = self::edit();
                case 'delete'   : $resultData = self::delete();
                case 'get'      : $resultData = self::get();
                default         : throw new InvalidArgumentException('Unrecognized command');
            }
            
            echo json_encode(array('success' => true, 'data' => $resultData), JSON_PRETTY_PRINT);
            
        } catch (RuntimeException $e) {
            echo json_encode(array('success' => false, 'error' => 'Database config file not found'), JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            echo json_encode(array('success' => false, 'error' => $e->getMessage()), JSON_PRETTY_PRINT);
        } catch (MissingArgumentException $e) {
            echo json_encode(array('success' => false, 'error' => $e->getMessage()), JSON_PRETTY_PRINT);
        } catch (InvalidArgumentException $e) {
            echo json_encode(array('success' => false, 'error' => $e->getMessage()), JSON_PRETTY_PRINT);
        } catch (UserNotFoundException $e) {
            echo json_encode(array('success' => false, 'error' => $e->getMessage()), JSON_PRETTY_PRINT);
        } catch (NotFoundException $e) {
            echo json_encode(array('success' => false, 'error' => $e->getMessage()), JSON_PRETTY_PRINT);
        }
    }
    
    private static function add() {
        if (!isset($_POST) || empty($_POST))
            throw new MissingArgumentException('Post data missing');
        $_POST['userName'] = $_SESSION['profile']->getUserName();

        // create new options object from input
        $options = new MeasurementsOptions($_POST);
        if ($options->getErrorCount() > 0)
            throw new InvalidArgumentException(array_shift($options->getErrors()));
        
        // make sure options with the specified name for the specified user does not already exist
        try {
            $existingOptions = MeasurementsOptionsDB::getOptions($options->getUserName(), $options->getOptionsName());
            throw new AlreadyExistsException('Options with the specified name already exists');
        }
        catch (NotFoundException $e) { } // note that we WANT a NotFoundException to be thrown, so that the AlreadyExistsException is not thrown
        
        // add options to the database and return the result data object that contains the assigned options ID
        return MeasurementsOptionsDB::addOptions($options);
    }
    
    private static function edit() {
        if (!isset($_POST) || empty($_POST))
            throw new MissingArgumentException('Post data missing');
        if (!isset($_POST['oldOptionsName']) || empty($_POST['oldOptionsName']))
            throw new MissingArgumentException('Old options name missing');
        $_POST['userName'] = $_SESSION['profile']->getUserName();

        // make sure options with old name exists (an exception will be thrown if options are not found)
        $oldOptions = MeasurementsOptionsDB::getOptions($_POST['userName'], $_POST['oldOptionsName']);

        // create new options object from input
        $newOptions = new MeasurementsOptions($_POST);
        if ($newOptions->getErrorCount() > 0)
            throw new InvalidArgumentException(array_shift($newOptions->getErrors()));

        // update the database and return the success/failure result array
        return MeasurementsOptionsDB::editOptions($oldOptions, $newOptions);
    }
    
    private static function delete() {
        if (!isset($_POST) || empty($_POST))
            throw new MissingArgumentException('Post data missing');
        if (!isset($_POST['optionsName']) || empty($_POST['optionsName']))
            throw new MissingArgumentException('Options name missing');
        
        // delete options from the database and return the success/failure result array
        return MeasurementsOptionsDB::deleteOptions($_SESSION['profile']->getUserName(), $_POST['optionsName']);
    }
    
    // Returns an array of MeasurementsOptions objects, one for each set of options associated with the logged in user.
    private static function get() {
        return MeasurementsOptionsDB::getOptionsFor($_SESSION['profile']->getUserName());
    }
    
}

?>