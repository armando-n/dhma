drop database if exists na_lab5db;
create database na_lab5db;
use na_lab5db;

drop table if exists Users;
create table Users(
    userID          integer primary key auto_increment,
    userName        varchar(50) unique not null,
    password        varchar(255) not null,
    isAdministrator   boolean not null default false,
    dateCreated     timestamp default CURRENT_TIMESTAMP
);

drop table if exists UserProfiles;
create table UserProfiles(
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
);

drop table if exists BloodPressureMeasurements;
create table BloodPressureMeasurements(
    bpID                integer primary key auto_increment,
    systolicPressure    integer not null,
    diastolicPressure   integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists CalorieMeasurements;
create table CalorieMeasurements(
    calorieID           integer primary key auto_increment,
    calories            integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists ExerciseMeasurements;
create table ExerciseMeasurements(
    exerciseID          integer primary key auto_increment,
    duration            integer not null,
    type                varchar(100) not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists GlucoseMeasurements;
create table GlucoseMeasurements(
    glucoseID           integer primary key auto_increment,
    glucose             integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists SleepMeasurements;
create table SleepMeasurements(
    sleepID             integer primary key auto_increment,
    duration            integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists WeightMeasurements;
create table WeightMeasurements(
    weightID            integer primary key auto_increment,
    weight              double not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

-- test data

insert into Users (userName, password) values ("armando-n", "pass123");
insert into Users (userName, password) values ("robbins", "pass456");
insert into Users (userName, password) values ("john-s", "pass123");
insert into Users (userName, password) values ("bob", "pass456");
insert into Users (userName, password) values ("sarahk", "pass789");
insert into Users (userName, password) values ("whatup", "whatup");

-- Users/UserProfiles data
insert into UserProfiles (firstName, lastName, email, phone, gender,
    dob, country, picture, facebook, theme, accentColor, isProfilePublic,
    isPicturePublic, sendReminders, stayLoggedIn, userID)
    values ("Armando", "Navarro", "fdf786@my.utsa.edu", "210-555-2170", "male",
        "1983-11-02", "United States of America", "armando-n.png", null, "light",
        "#0088BB", true, true, false, false, 1);
insert into UserProfiles (firstName, lastName, email, phone, gender,
    dob, country, picture, facebook, theme, accentColor, isProfilePublic,
    isPicturePublic, sendReminders, stayLoggedIn, userID)
    values ("Robin", "Scherbatsky", "robbins@email.com", "210-555-1593", "female",
        "1980-02-22", "United States of America", "robbins.jpg",
        "http://www.facebook.com/robbins", "light", "#0088BB", true, true,
        true, true, 2);
insert into UserProfiles (firstName, lastName, email, phone, gender,
    dob, country, picture, facebook, theme, accentColor, isProfilePublic,
    isPicturePublic, sendReminders, stayLoggedIn, userID)
    values ("John", "Smith", "johns@email.com", "314-555-1260", "male",
        null, "United States of America", "john-s.jpg", null, "dark",
        "#BB0000", false, false, true, true, 3);
insert into UserProfiles (firstName, lastName, email, phone, gender,
    dob, country, picture, facebook, theme, accentColor, isProfilePublic,
    isPicturePublic, sendReminders, stayLoggedIn, userID)
    values ("Bob", "Roberts", "bobrob@email.com", "450-555-1253", "male",
        "1973-01-12", "United States of America", "bob.jpg", null, "light",
        "#44DD88", true, false, false, true, 4);
insert into UserProfiles (firstName, lastName, email, phone, gender,
    dob, country, picture, facebook, theme, accentColor, isProfilePublic,
    isPicturePublic, sendReminders, stayLoggedIn, userID)
    values ("Sarah", "Kinberg", "sarahk@email.com", "512-555-4826", "female",
        "1987-08-24", "United States of America", "sarahk.jpg", null, "light",
        "#0088BB", true, true, false, false, 5);
insert into UserProfiles (firstName, lastName, email, phone, gender,
    dob, country, picture, facebook, theme, accentColor, isProfilePublic,
    isPicturePublic, sendReminders, stayLoggedIn, userID)
    values ("Jason", "McMann", "jason@email.com", "341-555-3856", "male",
        "1983-10-12", "United States of America", "whatup.jpg", null, "dark",
        "#0088BB", true, true, false, false, 6);
        
-- blood pressure measurements data
insert into BloodPressureMeasurements (systolicPressure, diastolicPressure,
    dateAndTime, notes, userID)
    values (120, 80, "2015-09-27 14:00:00", null, 1);
insert into BloodPressureMeasurements (systolicPressure, diastolicPressure,
    dateAndTime, notes, userID)
    values (110, 90, "2015-09-26 14:05:00", null, 1);
insert into BloodPressureMeasurements (systolicPressure, diastolicPressure,
    dateAndTime, notes, userID)
    values (115, 95, "2015-09-25 14:02:00", "good day", 1);
insert into BloodPressureMeasurements (systolicPressure, diastolicPressure,
    dateAndTime, notes, userID)
    values (125, 78, "2015-09-24 14:00:00", null, 1);

-- glucose measurements data
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID)
    values (95, "2015-09-27 08:15:00", "good day", 1);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID)
    values (120, "2015-09-26 08:22:00", null, 1);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID)
    values (110, "2015-09-25 08:15:00", null, 1);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID)
    values (112, "2015-09-24 08:12:00", null, 1);
    
-- calories data
insert into CalorieMeasurements (calories, dateAndTime, notes, userID)
    values (1800, "2015-09-27 21:00:00", "special occasion", 1);
insert into CalorieMeasurements (calories, dateAndTime, notes, userID)
    values (1540, "2015-09-26 21:03:00", null, 1);
insert into CalorieMeasurements (calories, dateAndTime, notes, userID)
    values (1620, "2015-09-25 21:01:00", null, 1);
insert into CalorieMeasurements (calories, dateAndTime, notes, userID)
    values (1460, "2015-09-24 21:00:00", null, 1);
    
-- exercise data
insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID)
    values (60, "running", "2015-09-27 20:00:00", null, 1);
insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID)
    values (56, "running", "2015-09-26 20:02:00", null, 1);
insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID)
    values (40, "running", "2015-09-25 20:05:00", "bad day", 1);
insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID)
    values (58, "running", "2015-09-24 20:00:00", null, 1);
    
-- sleep data
insert into SleepMeasurements (duration, dateAndTime, notes, userID)
    values (480, "2015-09-27 22:00:00", "good sleep", 1);
insert into SleepMeasurements (duration, dateAndTime, notes, userID)
    values (460, "2015-09-26 22:12:00", null, 1);
insert into SleepMeasurements (duration, dateAndTime, notes, userID)
    values (464, "2015-09-25 22:35:00", null, 1);
insert into SleepMeasurements (duration, dateAndTime, notes, userID)
    values (472, "2015-09-24 22:02:00", null, 1);
    
-- weight data
insert into WeightMeasurements (weight, dateAndTime, notes, userID)
    values (140.5, "2015-09-27 20:45:00", null, 1);
insert into WeightMeasurements (weight, dateAndTime, notes, userID)
    values (139.5, "2015-09-26 20:50:00", null, 1);
insert into WeightMeasurements (weight, dateAndTime, notes, userID)
    values (140, "2015-09-25 20:28:00", null, 1);
insert into WeightMeasurements (weight, dateAndTime, notes, userID)
    values (141, "2015-09-24 20:46:00", "big meal", 1);
