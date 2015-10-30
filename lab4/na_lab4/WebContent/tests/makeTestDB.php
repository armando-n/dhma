<?php
// creates a database named $dbName for testing and returns connection
function makeTestDB($dbName) {
    if (strcasecmp($dbName, 'dhma') == 0)
        throw new Exception("Error: cannot overwite dhma database");
    
    try {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION['dbName'] = '';
        $_SESSION['configFile'] = 'myConfig.ini';
        $db = Database::getDB();
        
        $stmt = $db->prepare("drop database if exists $dbName");
        $stmt->execute();
        
        $stmt = $db->prepare("create database $dbName");
        $stmt->execute();
        
        $stmt = $db->prepare("use $dbName");
        $stmt->execute();
        
        $stmt = $db->prepare(
            "create table Users(
                userID          integer primary key auto_increment,
                userName        varchar(50) unique not null,
                password        varchar(255) not null,
                dateCreated     timestamp default CURRENT_TIMESTAMP
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare("insert into Users (userName, password) values (:un, :pw)");
        $stmt->execute(array(":un" => "armando-n",  ":pw" => "pass123"));
        $stmt->execute(array(":un" => "robin",    ":pw" => "pass456"));
        $stmt->execute(array(":un" => "john-s",     ":pw" => "pass123"));
        $stmt->execute(array(":un" => "bob",        ":pw" => "pass456"));
        $stmt->execute(array(":un" => "sarahk",     ":pw" => "pass789"));
        
        $stmt = $db->prepare(
            "create table UserProfiles(
                profileID       integer primary key auto_increment,
                firstName       varchar(50),
                lastName        varchar(50),
                email           varchar(50),
                phone           varchar(15),
                gender          varchar(6),
                dob             date,
                country         varchar(50),
                picture         varchar(50),
                facebook        varchar(50),
                theme           varchar(5),
                accentColor     char(7),
                isProfilePublic boolean,
                isPicturePublic boolean,
                sendReminders   boolean,
                stayLoggedIn    boolean,
                userID          integer not null,
                foreign key (userID) references Users (userID) on delete cascade
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare(
            "insert into UserProfiles (firstName, lastName, email, phone,
                gender, dob, country, picture, facebook, theme, accentColor,
                isProfilePublic, isPicturePublic, sendReminders, stayLoggedIn,
                userID)
                values (:firstName, :lastName, :email, :phone, :gender, :dob,
                    :country, :picture, :facebook, :theme, :accentColor,
                    :isProfilePublic, :isPicturePublic, :sendReminders,
                    :stayLoggedIn, :userID)"
        );
        $stmt->execute(array(
                ":firstName" => "Armando",
                ":lastName" => "Navarro",
                ":email" => "fdf786@my.utsa.edu",
                ":phone" => "210-555-2170",
                ":gender" => "male",
                ":dob" => "1983-11-02",
                ":country" => "United States of America",
                ":picture" => "armando-n.png",
                ":facebook" => null,
                ":theme" => "dark",
                ":accentColor" => "#0088BB",
                ":isProfilePublic" => true,
                ":isPicturePublic" => true,
                ":sendReminders" => false,
                ":stayLoggedIn" => false,
                ":userID" => 1
        ));
        $stmt->execute(array(
                ":firstName" => "Robin",
                ":lastName" => "Scherbatsky",
                ":email" => "robin@email.com",
                ":phone" => "210-555-1593",
                ":gender" => "female",
                ":dob" => "1980-02-22",
                ":country" => "United States of America",
                ":picture" => "robin.png",
                ":facebook" => "http://www.facebook.com/robin",
                ":theme" => "light",
                ":accentColor" => "#0088BB",
                ":isProfilePublic" => true,
                ":isPicturePublic" => true,
                ":sendReminders" => true,
                ":stayLoggedIn" => true,
                ":userID" => 2
        ));
        $stmt->execute(array(
                ":firstName" => "John",
                ":lastName" => "Smith",
                ":email" => "johns@email.com",
                ":phone" => "314-555-1260",
                ":gender" => "male",
                ":dob" => null,
                ":country" => "United States of America",
                ":picture" => "johns.png",
                ":facebook" => null,
                ":theme" => "dark",
                ":accentColor" => "#BB0000",
                ":isProfilePublic" => false,
                ":isPicturePublic" => false,
                ":sendReminders" => true,
                ":stayLoggedIn" => true,
                ":userID" => 3
        ));
        $stmt->execute(array(
                ":firstName" => "Bob",
                ":lastName" => "Roberts",
                ":email" => "bobrob@email.com",
                ":phone" => "450-555-1253",
                ":gender" => "male",
                ":dob" => "1973-01-12",
                ":country" => "United States of America",
                ":picture" => "bob.png",
                ":facebook" => null,
                ":theme" => "light",
                ":accentColor" => "#44DD88",
                ":isProfilePublic" => true,
                ":isPicturePublic" => false,
                ":sendReminders" => false,
                ":stayLoggedIn" => true,
                ":userID" => 4
        ));
        $stmt->execute(array(
                ":firstName" => "Sarah",
                ":lastName" => "Kinberg",
                ":email" => "sarahk@email.com",
                ":phone" => "512-555-4826",
                ":gender" => "female",
                ":dob" => "1987-08-24",
                ":country" => "United States of America",
                ":picture" => "sarahk.png",
                ":facebook" => null,
                ":theme" => "dark",
                ":accentColor" => "#0088BB",
                ":isProfilePublic" => true,
                ":isPicturePublic" => true,
                ":sendReminders" => false,
                ":stayLoggedIn" => false,
                ":userID" => 5
        ));
        
        $stmt = $db->prepare(
            "create table BloodPressureMeasurements(
                bpID                integer primary key auto_increment,
                systolicPressure    integer not null,
                diastolicPressure   integer not null,
                dateAndTime         datetime not null,
                notes               varchar(255),
                userID              integer not null,
                foreign key (userID) references Users (userID) on delete cascade
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare(
            "insert into BloodPressureMeasurements (systolicPressure,
                diastolicPressure, dateAndTime, notes, userID)
                values (:systolicPressure, :diastolicPressure, :dateAndTime,
                    :notes, :userID)"
        );
        $stmt->execute(array(
            ":systolicPressure" => 120, ":diastolicPressure" => 80,
            ":dateAndTime" => "2015-09-27 14:00:00", ":notes" => null,
            ":userID" => 1
        ));
        $stmt->execute(array(
                ":systolicPressure" => 110, ":diastolicPressure" => 90,
                ":dateAndTime" => "2015-09-26 14:05:00", ":notes" => null,
                ":userID" => 1
        ));
        $stmt->execute(array(
                ":systolicPressure" => 115, ":diastolicPressure" => 95,
                ":dateAndTime" => "2015-09-25 14:02:00", ":notes" => "good day",
                ":userID" => 1
        ));
        $stmt->execute(array(
                ":systolicPressure" => 128, ":diastolicPressure" => 78,
                ":dateAndTime" => "2015-09-24 14:00:00", ":notes" => null,
                ":userID" => 1
        ));
        
        $stmt = $db->prepare(
            "create table GlucoseMeasurements (
                glucoseID           integer primary key auto_increment,
                glucose             integer not null,
                dateAndTime         datetime not null,
                notes               varchar(255),
                userID              integer not null,
                foreign key (userID) references Users (userID) on delete cascade
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare(
            "insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID)
                values (:glucose, :dateAndTime, :notes, :userID)"
        );
        $stmt->execute(array(
            ":glucose" => 95, ":dateAndTime" => "2015-09-27 08:15:00",
            ":notes" => "good day", ":userID" => 1
        ));
        $stmt->execute(array(
                ":glucose" => 120, ":dateAndTime" => "2015-09-26 08:22:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":glucose" => 110, ":dateAndTime" => "2015-09-25 08:15:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":glucose" => 112, ":dateAndTime" => "2015-09-24 08:12:00",
                ":notes" => null, ":userID" => 1
        ));
        
        $stmt = $db->prepare(
            "create table CalorieMeasurements (
                calorieID           integer primary key auto_increment,
                calories            integer not null,
                dateAndTime         datetime not null,
                notes               varchar(255),
                userID              integer not null,
                foreign key (userID) references Users (userID) on delete cascade
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare(
            "insert into CalorieMeasurements (calories, dateAndTime, notes, userID)
                values (:calories, :dateAndTime, :notes, :userID)"
        );
        $stmt->execute(array(
            ":calories" => 1800, ":dateAndTime" => "2015-09-27 21:00:00",
            ":notes" => "special occasion", ":userID" => 1
        ));
        $stmt->execute(array(
                ":calories" => 1540, ":dateAndTime" => "2015-09-26 21:03:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":calories" => 1620, ":dateAndTime" => "2015-09-25 21:01:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":calories" => 1460, ":dateAndTime" => "2015-09-24 21:00:00",
                ":notes" => null, ":userID" => 1
        ));
        
        $stmt = $db->prepare(
            "create table ExerciseMeasurements (
                exerciseID          integer primary key auto_increment,
                duration            integer not null,
                type                varchar(100) not null,
                dateAndTime         datetime not null,
                notes               varchar(255),
                userID              integer not null,
                foreign key (userID) references Users (userID) on delete cascade
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare(
            "insert into ExerciseMeasurements (duration, type, dateAndTime,
                notes, userID)
                values (:duration, :type, :dateAndTime, :notes, :userID)"
        );
        $stmt->execute(array(
            ":duration" => 60, ":type" => "running",
            ":dateAndTime" => "2015-09-27 20:00:00", ":notes" => null,
            ":userID" => 1
        ));
        $stmt->execute(array(
                ":duration" => 56, ":type" => "running",
                ":dateAndTime" => "2015-09-26 20:02:00", ":notes" => null,
                ":userID" => 1
        ));
        $stmt->execute(array(
                ":duration" => 40, ":type" => "running",
                ":dateAndTime" => "2015-09-25 20:05:00", ":notes" => "bad day",
                ":userID" => 1
        ));
        $stmt->execute(array(
                ":duration" => 58, ":type" => "running",
                ":dateAndTime" => "2015-09-24 20:00:00", ":notes" => null,
                ":userID" => 1
        ));
        
        $stmt = $db->prepare(
            "create table SleepMeasurements (
                sleepID             integer primary key auto_increment,
                duration            integer not null,
                dateAndTime         datetime not null,
                notes               varchar(255),
                userID              integer not null,
                foreign key (userID) references Users (userID) on delete cascade
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare(
            "insert into SleepMeasurements (duration, dateAndTime, notes, userID)
                values (:duration, :dateAndTime, :notes, :userID)"
        );
        $stmt->execute(array(
                ":duration" => 480, ":dateAndTime" => "2015-09-27 22:00:00",
                ":notes" => "good sleep", ":userID" => 1
        ));
        $stmt->execute(array(
                ":duration" => 460, ":dateAndTime" => "2015-09-26 22:12:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":duration" => 464, ":dateAndTime" => "2015-09-25 22:35:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":duration" => 472, ":dateAndTime" => "2015-09-24 22:02:00",
                ":notes" => null, ":userID" => 1
        ));
        
        $stmt = $db->prepare(
            "create table WeightMeasurements (
                weightID            integer primary key auto_increment,
                weight              double not null,
                dateAndTime         datetime not null,
                notes               varchar(255),
                userID              integer not null,
                foreign key (userID) references Users (userID) on delete cascade
            )"
        );
        $stmt->execute();
        
        $stmt = $db->prepare(
                "insert into WeightMeasurements (weight, dateAndTime, notes, userID)
                values (:weight, :dateAndTime, :notes, :userID)"
        );
        $stmt->execute(array(
                ":weight" => 140.5, ":dateAndTime" => "2015-09-27 20:45:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":weight" => 139.5, ":dateAndTime" => "2015-09-26 20:50:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":weight" => 140, ":dateAndTime" => "2015-09-25 20:28:00",
                ":notes" => null, ":userID" => 1
        ));
        $stmt->execute(array(
                ":weight" => 141, ":dateAndTime" => "2015-09-24 20:46:00",
                ":notes" => "big meal", ":userID" => 1
        ));
        
    } catch (PDOException $e) {
        echo $e->getMessage(); // not final error handling
    }
}
?>