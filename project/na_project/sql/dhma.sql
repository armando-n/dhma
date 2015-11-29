drop database if exists na_projectdb;
create database na_projectdb;
use na_projectdb;

drop table if exists Users;
create table Users(
    userID          integer primary key auto_increment,
    userName        varchar(50) unique not null,
    password        varchar(255) not null,
    isAdministrator boolean not null default false,
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
    picture         varchar(50) not null default 'profile_default.png',
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

-- User data (passwords are hashes for 'pass123', except admin password is 'admin')
insert into Users (userName, password) values
    ('armando-n', '$2y$10$Xvd13JJMs0aNuXI3DeCDQOmSOPmdBuYzxuc8pTrTiDz80GwL2VrWO'),
    ('robbins', '$2y$10$o0oZQSAgFCIjwdJ5yZ5s7uAtg3i5J7jOU.oUOFTLM0ENe7hKVc8pe'),
    ('john-s', '$2y$10$YsgDH7ayR07IUObGbuLWlO57CVIfACO5T0C4Y9gUyfGXakZZGRFtu'),
    ('bob', '$2y$10$OLf1V4sBXJXVwmay2JuwSe.lFx.Ch9tuAnVnIcJCzcH.nui05ZRd2'),
    ('sarahk', '$2y$10$53fpNRPHq7v.PSTNesgkxuZ3DfJG3cO.qmovlV1r8B4/QErxgv7ym'),
    ('whatup', '$2y$10$tQWN1Uh0Y8eBtE1hRwaqPO3HkNIOC8k75EV/CAGAvgQltG6o5JJrC'),
    ('delete-me-1', '$2y$10$tDIy3lCQbSy.IHDy5HmSouroejYV.0.vLWuXBZj1HPDLQspyrSRwi'),
    ('delete-me-2', '$2y$10$J3T8PHNfo5XeF0la8I2Rgei0FrSJkQ8nMbt2wsFVPp7UIOYgXALVu'),
    ('delete-me-3', '$2y$10$NbL.oq.o/k0TpW7cccc9bO1PLvZ/1MZASkuJwMidTZaBiSfpMokJi'),
    ('admin', '$2y$10$D7IJ76T54m8EcNL4UwhYLO.N1xXoGnYijwhJ9TCksQNMTJNvC6aUq');
update Users set isAdministrator = true where userName = 'admin';

-- UserProfile data
insert into UserProfiles (firstName, lastName, email, phone, gender, dob, country, picture, facebook, theme, accentColor, isProfilePublic,  isPicturePublic, sendReminders, stayLoggedIn, userID)
    values
        ("Armando", "Navarro", "fdf786@my.utsa.edu", "210-555-2170", "male", "1983-11-02", "United States of America", "armando-n.png", null, "light", "#0088BB", true, true, false, false, 1),
        ("Robin", "Scherbatsky", "robbins@email.com", "210-555-1593", "female", "1980-02-22", "United States of America", "robbins.jpg", "http://www.facebook.com/robbins", "light", "#0088BB", true, true, true, true, 2),
        ("John", "Smith", "johns@email.com", "314-555-1260", "male", null, "United States of America", "john-s.jpg", null, "dark", "#BB0000", false, false, true, true, 3),
        ("Bob", "Roberts", "bobrob@email.com", "450-555-1253", "male", "1973-01-12", "United States of America", "bob.jpg", null, "light", "#44DD88", true, false, false, true, 4),
        ("Sarah", "Kinberg", "sarahk@email.com", "512-555-4826", "female", "1987-08-24", "United States of America", "sarahk.jpg", null, "light", "#0088BB", true, true, false, false, 5),
        ("Jason", "McMann", "jason@email.com", "341-555-3856", "male", "1983-10-12", "United States of America", "whatup.jpg", null, "dark", "#0088BB", true, true, false, false, 6);
insert into UserProfiles(email, userID)
    values
        ('deleteme1@email.com', 7),
        ('deleteme2@email.com', 8),
        ('deleteme3@email.com', 9);
insert into UserProfiles(email, userID) values ('admin@email.com', 10);

-- measurement data is in separate file 'measurement_inserts.sql' which is generated by /resources/InsertsGenerator.php
