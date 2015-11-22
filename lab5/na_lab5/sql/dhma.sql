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

-- Users/UserProfiles data
insert into Users (userName, password) values ("armando-n", "pass123");
insert into Users (userName, password) values ("robbins", "pass456");
insert into Users (userName, password) values ("john-s", "pass123");
insert into Users (userName, password) values ("bob", "pass456");
insert into Users (userName, password) values ("sarahk", "pass789");
insert into Users (userName, password) values ("whatup", "whatup");
insert into Users (userName, password, isAdministrator) values ("admin", "admin", true);

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
insert into UserProfiles(email, userID) values ('admin@email.com', 7);

-- measurement data
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (112, "2015-11-21 02:01", null, 1), (108, "2015-11-20 04:37", null, 1), (102, "2015-11-19 10:44", null, 1), (108, "2015-11-18 07:52", null, 1), (107, "2015-11-17 09:38", null, 1), (114, "2015-11-16 09:31", null, 1); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (121, 81, "2015-11-21 04:19", null, 1), (115, 85, "2015-11-20 14:27", null, 1), (109, 81, "2015-11-19 16:10", null, 1), (119, 85, "2015-11-18 01:11", null, 1), (112, 88, "2015-11-17 19:43", null, 1), (125, 90, "2015-11-16 12:29", null, 1); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1673, "2015-11-21 05:19", null, 1), (1517, "2015-11-20 02:09", null, 1), (1575, "2015-11-19 01:46", null, 1), (1652, "2015-11-18 00:50", null, 1), (1584, "2015-11-17 11:34", null, 1), (1535, "2015-11-16 13:21", null, 1); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (41, "weights", "2015-11-21 20:27", null, 1), (43, "running", "2015-11-20 00:16", null, 1), (51, "weights", "2015-11-19 23:47", null, 1), (47, "running", "2015-11-18 23:27", null, 1), (34, "weights", "2015-11-17 22:14", null, 1), (40, "running", "2015-11-16 03:36", null, 1); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (394, "2015-11-21 06:28", null, 1), (459, "2015-11-20 22:51", null, 1), (409, "2015-11-19 12:51", null, 1), (460, "2015-11-18 08:28", null, 1), (463, "2015-11-17 00:06", null, 1), (525, "2015-11-16 04:31", null, 1); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (143, "2015-11-21 18:14", null, 1), (146, "2015-11-20 09:33", null, 1), (147, "2015-11-19 22:08", null, 1), (141, "2015-11-18 16:47", null, 1), (148, "2015-11-17 04:44", null, 1), (144, "2015-11-16 12:28", null, 1);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (108, "2015-11-21 21:22", null, 2), (106, "2015-11-20 12:03", null, 2), (100, "2015-11-19 09:53", null, 2), (111, "2015-11-18 13:25", null, 2), (114, "2015-11-17 06:04", null, 2), (108, "2015-11-16 03:12", null, 2); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (108, 88, "2015-11-21 17:52", null, 2), (120, 80, "2015-11-20 04:57", null, 2), (105, 90, "2015-11-19 16:41", null, 2), (125, 84, "2015-11-18 18:46", null, 2), (122, 87, "2015-11-17 12:25", null, 2), (116, 82, "2015-11-16 14:22", null, 2); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1316, "2015-11-21 19:02", null, 2), (1607, "2015-11-20 04:23", null, 2), (1372, "2015-11-19 13:20", null, 2), (1327, "2015-11-18 22:41", null, 2), (1506, "2015-11-17 17:05", null, 2), (1106, "2015-11-16 12:56", null, 2); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (60, "weights", "2015-11-21 18:57", null, 2), (26, "running", "2015-11-20 16:15", null, 2), (58, "weights", "2015-11-19 04:47", null, 2), (45, "weights", "2015-11-18 13:03", null, 2), (39, "running", "2015-11-17 16:00", null, 2), (20, "weights", "2015-11-16 21:06", null, 2); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (455, "2015-11-21 09:00", null, 2), (419, "2015-11-20 08:55", null, 2), (434, "2015-11-19 03:40", null, 2), (472, "2015-11-18 22:30", null, 2), (377, "2015-11-17 04:54", null, 2), (526, "2015-11-16 17:47", null, 2); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (141, "2015-11-21 07:06", null, 2), (141, "2015-11-20 02:11", null, 2), (143, "2015-11-19 15:31", null, 2), (144, "2015-11-18 03:49", null, 2), (147, "2015-11-17 16:29", null, 2), (148, "2015-11-16 17:47", null, 2);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (115, "2015-11-21 17:39", null, 3), (99, "2015-11-20 17:24", null, 3), (105, "2015-11-19 22:19", null, 3), (109, "2015-11-18 12:27", null, 3), (113, "2015-11-17 19:34", null, 3), (110, "2015-11-16 22:27", null, 3); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (106, 83, "2015-11-21 15:52", null, 3), (121, 93, "2015-11-20 13:46", null, 3), (106, 82, "2015-11-19 19:22", null, 3), (121, 81, "2015-11-18 23:23", null, 3), (111, 82, "2015-11-17 03:03", null, 3), (113, 83, "2015-11-16 22:04", null, 3); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1386, "2015-11-21 20:23", null, 3), (1503, "2015-11-20 08:59", null, 3), (1182, "2015-11-19 13:22", null, 3), (1148, "2015-11-18 06:57", null, 3), (1330, "2015-11-17 02:22", null, 3), (1460, "2015-11-16 17:31", null, 3); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (39, "weights", "2015-11-21 06:19", null, 3), (32, "weights", "2015-11-20 07:58", null, 3), (59, "running", "2015-11-19 16:25", null, 3), (56, "running", "2015-11-18 19:09", null, 3), (28, "running", "2015-11-17 11:08", null, 3), (47, "running", "2015-11-16 01:50", null, 3); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (407, "2015-11-21 22:22", null, 3), (399, "2015-11-20 12:18", null, 3), (497, "2015-11-19 18:37", null, 3), (518, "2015-11-18 01:24", null, 3), (385, "2015-11-17 08:40", null, 3), (480, "2015-11-16 03:59", null, 3); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (143, "2015-11-21 11:15", null, 3), (144, "2015-11-20 13:19", null, 3), (145, "2015-11-19 01:00", null, 3), (141, "2015-11-18 15:24", null, 3), (141, "2015-11-17 11:37", null, 3), (146, "2015-11-16 17:12", null, 3);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (96, "2015-11-21 21:10", null, 4), (112, "2015-11-20 02:56", null, 4), (101, "2015-11-19 09:16", null, 4), (108, "2015-11-18 10:14", null, 4), (109, "2015-11-17 06:25", null, 4), (103, "2015-11-16 23:29", null, 4); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (118, 85, "2015-11-21 05:32", null, 4), (124, 80, "2015-11-20 23:08", null, 4), (116, 95, "2015-11-19 09:29", null, 4), (114, 81, "2015-11-18 23:19", null, 4), (125, 93, "2015-11-17 22:52", null, 4), (123, 83, "2015-11-16 18:49", null, 4); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1432, "2015-11-21 16:38", null, 4), (1358, "2015-11-20 20:12", null, 4), (1157, "2015-11-19 09:06", null, 4), (1264, "2015-11-18 05:55", null, 4), (1205, "2015-11-17 19:40", null, 4), (1547, "2015-11-16 09:21", null, 4); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (37, "running", "2015-11-21 13:49", null, 4), (28, "running", "2015-11-20 02:40", null, 4), (21, "weights", "2015-11-19 19:55", null, 4), (45, "weights", "2015-11-18 01:00", null, 4), (58, "running", "2015-11-17 15:53", null, 4), (41, "running", "2015-11-16 23:03", null, 4); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (476, "2015-11-21 04:49", null, 4), (446, "2015-11-20 18:13", null, 4), (463, "2015-11-19 14:59", null, 4), (460, "2015-11-18 01:26", null, 4), (533, "2015-11-17 20:38", null, 4), (434, "2015-11-16 17:22", null, 4); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (141, "2015-11-21 12:55", null, 4), (147, "2015-11-20 22:11", null, 4), (145, "2015-11-19 08:49", null, 4), (144, "2015-11-18 10:47", null, 4), (141, "2015-11-17 17:25", null, 4), (142, "2015-11-16 20:00", null, 4);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (99, "2015-11-21 14:08", null, 5), (110, "2015-11-20 20:53", null, 5), (114, "2015-11-19 22:59", null, 5), (114, "2015-11-18 13:00", null, 5), (109, "2015-11-17 22:51", null, 5), (115, "2015-11-16 13:35", null, 5); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (108, 84, "2015-11-21 16:08", null, 5), (112, 93, "2015-11-20 15:18", null, 5), (121, 84, "2015-11-19 13:17", null, 5), (109, 86, "2015-11-18 00:49", null, 5), (106, 92, "2015-11-17 02:06", null, 5), (108, 84, "2015-11-16 09:53", null, 5); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1606, "2015-11-21 11:02", null, 5), (1321, "2015-11-20 22:18", null, 5), (1447, "2015-11-19 06:48", null, 5), (1225, "2015-11-18 02:48", null, 5), (1282, "2015-11-17 04:15", null, 5), (1517, "2015-11-16 17:40", null, 5); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (19, "running", "2015-11-21 23:40", null, 5), (25, "weights", "2015-11-20 09:38", null, 5), (48, "running", "2015-11-19 18:31", null, 5), (24, "weights", "2015-11-18 12:50", null, 5), (49, "weights", "2015-11-17 09:30", null, 5), (59, "weights", "2015-11-16 19:01", null, 5); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (528, "2015-11-21 07:36", null, 5), (424, "2015-11-20 05:57", null, 5), (377, "2015-11-19 23:00", null, 5), (525, "2015-11-18 03:49", null, 5), (504, "2015-11-17 21:05", null, 5), (430, "2015-11-16 17:10", null, 5); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (146, "2015-11-21 15:22", null, 5), (143, "2015-11-20 12:03", null, 5), (147, "2015-11-19 20:13", null, 5), (145, "2015-11-18 17:13", null, 5), (141, "2015-11-17 17:06", null, 5), (146, "2015-11-16 06:24", null, 5);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (107, "2015-11-21 02:49", null, 6), (97, "2015-11-20 06:32", null, 6), (108, "2015-11-19 20:44", null, 6), (111, "2015-11-18 03:01", null, 6), (96, "2015-11-17 00:07", null, 6), (108, "2015-11-16 21:59", null, 6); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (124, 82, "2015-11-21 07:55", null, 6), (114, 88, "2015-11-20 20:31", null, 6), (124, 84, "2015-11-19 16:01", null, 6), (111, 85, "2015-11-18 07:06", null, 6), (124, 82, "2015-11-17 22:02", null, 6), (116, 91, "2015-11-16 01:30", null, 6); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1455, "2015-11-21 12:50", null, 6), (1542, "2015-11-20 20:30", null, 6), (1600, "2015-11-19 10:43", null, 6), (1182, "2015-11-18 03:51", null, 6), (1110, "2015-11-17 09:22", null, 6), (1520, "2015-11-16 01:44", null, 6); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (42, "running", "2015-11-21 18:08", null, 6), (34, "running", "2015-11-20 09:04", null, 6), (58, "running", "2015-11-19 19:30", null, 6), (50, "running", "2015-11-18 09:54", null, 6), (59, "running", "2015-11-17 23:14", null, 6), (20, "weights", "2015-11-16 21:59", null, 6); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (425, "2015-11-21 16:58", null, 6), (378, "2015-11-20 06:44", null, 6), (465, "2015-11-19 01:54", null, 6), (396, "2015-11-18 13:49", null, 6), (524, "2015-11-17 18:13", null, 6), (516, "2015-11-16 08:39", null, 6); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (144, "2015-11-21 03:50", null, 6), (145, "2015-11-20 14:10", null, 6), (144, "2015-11-19 19:28", null, 6), (147, "2015-11-18 19:58", null, 6), (143, "2015-11-17 17:56", null, 6), (143, "2015-11-16 07:41", null, 6);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (105, "2015-11-21 15:27", null, 7), (100, "2015-11-20 14:06", null, 7), (110, "2015-11-19 09:45", null, 7), (103, "2015-11-18 10:31", null, 7), (97, "2015-11-17 18:29", null, 7), (109, "2015-11-16 06:57", null, 7); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (108, 84, "2015-11-21 07:08", null, 7), (111, 85, "2015-11-20 20:03", null, 7), (111, 85, "2015-11-19 21:55", null, 7), (105, 82, "2015-11-18 03:27", null, 7), (119, 85, "2015-11-17 15:53", null, 7), (110, 92, "2015-11-16 23:55", null, 7); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1131, "2015-11-21 01:17", null, 7), (1570, "2015-11-20 21:04", null, 7), (1167, "2015-11-19 04:06", null, 7), (1284, "2015-11-18 13:21", null, 7), (1440, "2015-11-17 17:15", null, 7), (1104, "2015-11-16 14:47", null, 7); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (26, "running", "2015-11-21 02:28", null, 7), (22, "running", "2015-11-20 08:15", null, 7), (18, "running", "2015-11-19 04:06", null, 7), (42, "running", "2015-11-18 00:28", null, 7), (52, "running", "2015-11-17 15:21", null, 7), (29, "running", "2015-11-16 10:11", null, 7); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (394, "2015-11-21 10:10", null, 7), (534, "2015-11-20 01:50", null, 7), (409, "2015-11-19 03:57", null, 7), (481, "2015-11-18 13:41", null, 7), (459, "2015-11-17 16:20", null, 7), (375, "2015-11-16 21:04", null, 7); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (144, "2015-11-21 06:34", null, 7), (145, "2015-11-20 08:46", null, 7), (146, "2015-11-19 09:47", null, 7), (144, "2015-11-18 21:16", null, 7), (140, "2015-11-17 22:11", null, 7), (143, "2015-11-16 04:04", null, 7);

/*
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
*/