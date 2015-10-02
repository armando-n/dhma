drop database if exists dhma;
create database dhma;
use dhma;

drop table if exists Users;
create table Users(
    userID          integer primary key auto_increment,
    userName        varchar(50) unique not null,
    password        varchar(255) not null
);

drop table if exists UserProfiles;
create table UserProfiles(
    profileID       integer primary key auto_increment,
    firstName       varchar(50),
    lastName        varchar(50),
    email           varchar(50),
    phone           varchar(15),
    gender          varchar(6),
    dob             datetime,
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
    foreign key (userID) references Users (userID) on delete cascade
);

drop table if exists CalorieMeasurements;
create table CalorieMeasurements(
    calorieID           integer primary key auto_increment,
    calories            integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade
);

drop table if exists ExerciseMeasurements;
create table ExerciseMeasurements(
    exerciseID          integer primary key auto_increment,
    duration            integer not null,
    type                varchar(100) not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade
);

drop table if exists GlucoseMeasurements;
create table GlucoseMeasurements(
    glucoseID           integer primary key auto_increment,
    glucose             integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade
);

drop table if exists SleepMeasurements;
create table SleepMeasurements(
    sleepID             integer primary key auto_increment,
    duration            integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade
);

drop table if exists WeightMeasurements;
create table WeightMeasurements(
    weightID            integer primary key auto_increment,
    weight              double not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade
);