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

-- measurement data
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (102, "2015-11-22 21:46", null, 1), (98, "2015-11-21 17:08", null, 1), (110, "2015-11-20 18:32", null, 1), (97, "2015-11-19 13:17", null, 1), (114, "2015-11-18 01:03", null, 1), (109, "2015-11-17 11:39", null, 1); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (117, 86, "2015-11-22 07:08", null, 1), (125, 87, "2015-11-21 22:14", null, 1), (116, 84, "2015-11-20 13:09", null, 1), (108, 93, "2015-11-19 16:37", null, 1), (109, 90, "2015-11-18 11:51", null, 1), (115, 90, "2015-11-17 10:34", null, 1); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1312, "2015-11-22 06:46", null, 1), (1694, "2015-11-21 15:30", null, 1), (1698, "2015-11-20 19:42", null, 1), (1617, "2015-11-19 05:17", null, 1), (1326, "2015-11-18 06:03", null, 1), (1177, "2015-11-17 00:12", null, 1); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (41, "running", "2015-11-22 10:38", null, 1), (33, "running", "2015-11-21 00:02", null, 1), (41, "running", "2015-11-20 14:22", null, 1), (49, "weights", "2015-11-19 12:06", null, 1), (60, "weights", "2015-11-18 14:13", null, 1), (55, "weights", "2015-11-17 04:10", null, 1); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (456, "2015-11-22 07:10", null, 1), (531, "2015-11-21 12:53", null, 1), (515, "2015-11-20 23:58", null, 1), (501, "2015-11-19 02:17", null, 1), (444, "2015-11-18 17:13", null, 1), (413, "2015-11-17 01:55", null, 1); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (140, "2015-11-22 23:25", null, 1), (140, "2015-11-21 01:50", null, 1), (145, "2015-11-20 11:55", null, 1), (147, "2015-11-19 03:47", null, 1), (140, "2015-11-18 09:13", null, 1), (142, "2015-11-17 08:30", null, 1);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (103, "2015-11-22 12:04", null, 2), (108, "2015-11-21 18:54", null, 2), (97, "2015-11-20 19:54", null, 2), (96, "2015-11-19 18:25", null, 2), (114, "2015-11-18 06:57", null, 2), (105, "2015-11-17 11:10", null, 2); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (125, 87, "2015-11-22 12:02", null, 2), (118, 84, "2015-11-21 18:35", null, 2), (112, 87, "2015-11-20 06:00", null, 2), (123, 94, "2015-11-19 14:02", null, 2), (119, 83, "2015-11-18 21:54", null, 2), (115, 83, "2015-11-17 17:18", null, 2); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1537, "2015-11-22 10:11", null, 2), (1389, "2015-11-21 04:50", null, 2), (1626, "2015-11-20 03:14", null, 2), (1649, "2015-11-19 21:29", null, 2), (1530, "2015-11-18 14:47", null, 2), (1254, "2015-11-17 23:23", null, 2); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (47, "weights", "2015-11-22 19:13", null, 2), (25, "running", "2015-11-21 15:36", null, 2), (35, "weights", "2015-11-20 06:28", null, 2), (57, "running", "2015-11-19 01:18", null, 2), (52, "running", "2015-11-18 19:05", null, 2), (45, "weights", "2015-11-17 21:15", null, 2); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (466, "2015-11-22 19:10", null, 2), (507, "2015-11-21 04:48", null, 2), (535, "2015-11-20 05:30", null, 2), (395, "2015-11-19 03:24", null, 2), (397, "2015-11-18 16:59", null, 2), (521, "2015-11-17 08:31", null, 2); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (143, "2015-11-22 14:30", null, 2), (143, "2015-11-21 17:04", null, 2), (147, "2015-11-20 13:47", null, 2), (141, "2015-11-19 20:39", null, 2), (148, "2015-11-18 11:40", null, 2), (148, "2015-11-17 00:54", null, 2);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (114, "2015-11-22 05:20", null, 3), (113, "2015-11-21 16:36", null, 3), (111, "2015-11-20 11:33", null, 3), (112, "2015-11-19 17:03", null, 3), (107, "2015-11-18 03:56", null, 3), (108, "2015-11-17 10:15", null, 3); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (121, 91, "2015-11-22 04:23", null, 3), (108, 83, "2015-11-21 09:14", null, 3), (112, 89, "2015-11-20 03:02", null, 3), (109, 93, "2015-11-19 01:31", null, 3), (107, 87, "2015-11-18 06:53", null, 3), (122, 86, "2015-11-17 06:51", null, 3); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1588, "2015-11-22 02:15", null, 3), (1492, "2015-11-21 19:32", null, 3), (1568, "2015-11-20 23:14", null, 3), (1226, "2015-11-19 22:06", null, 3), (1635, "2015-11-18 01:17", null, 3), (1515, "2015-11-17 03:34", null, 3); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (60, "weights", "2015-11-22 18:41", null, 3), (41, "weights", "2015-11-21 05:54", null, 3), (23, "running", "2015-11-20 06:11", null, 3), (33, "weights", "2015-11-19 06:00", null, 3), (28, "running", "2015-11-18 02:18", null, 3), (36, "running", "2015-11-17 01:35", null, 3); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (368, "2015-11-22 16:00", null, 3), (505, "2015-11-21 14:01", null, 3), (411, "2015-11-20 10:11", null, 3), (503, "2015-11-19 17:24", null, 3), (444, "2015-11-18 17:43", null, 3), (402, "2015-11-17 11:04", null, 3); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (140, "2015-11-22 21:52", null, 3), (144, "2015-11-21 03:47", null, 3), (146, "2015-11-20 06:44", null, 3), (148, "2015-11-19 13:05", null, 3), (142, "2015-11-18 20:32", null, 3), (141, "2015-11-17 11:25", null, 3);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (100, "2015-11-22 05:49", null, 4), (98, "2015-11-21 18:28", null, 4), (96, "2015-11-20 23:43", null, 4), (108, "2015-11-19 14:25", null, 4), (98, "2015-11-18 23:15", null, 4), (104, "2015-11-17 17:08", null, 4); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (112, 87, "2015-11-22 11:24", null, 4), (120, 87, "2015-11-21 23:26", null, 4), (122, 95, "2015-11-20 09:30", null, 4), (112, 94, "2015-11-19 07:17", null, 4), (120, 91, "2015-11-18 17:02", null, 4), (121, 87, "2015-11-17 08:27", null, 4); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1616, "2015-11-22 11:14", null, 4), (1554, "2015-11-21 19:51", null, 4), (1674, "2015-11-20 15:57", null, 4), (1699, "2015-11-19 14:23", null, 4), (1191, "2015-11-18 21:49", null, 4), (1508, "2015-11-17 18:59", null, 4); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (45, "weights", "2015-11-22 14:16", null, 4), (47, "running", "2015-11-21 00:13", null, 4), (19, "running", "2015-11-20 20:47", null, 4), (32, "running", "2015-11-19 11:26", null, 4), (45, "running", "2015-11-18 17:09", null, 4), (40, "running", "2015-11-17 22:24", null, 4); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (386, "2015-11-22 06:55", null, 4), (388, "2015-11-21 00:47", null, 4), (372, "2015-11-20 20:16", null, 4), (507, "2015-11-19 04:31", null, 4), (451, "2015-11-18 01:40", null, 4), (463, "2015-11-17 19:47", null, 4); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (142, "2015-11-22 23:45", null, 4), (146, "2015-11-21 11:15", null, 4), (144, "2015-11-20 22:01", null, 4), (143, "2015-11-19 12:19", null, 4), (142, "2015-11-18 16:02", null, 4), (140, "2015-11-17 01:18", null, 4);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (107, "2015-11-22 18:45", null, 5), (108, "2015-11-21 06:47", null, 5), (98, "2015-11-20 15:40", null, 5), (111, "2015-11-19 16:47", null, 5), (113, "2015-11-18 23:09", null, 5), (96, "2015-11-17 12:05", null, 5); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (123, 95, "2015-11-22 15:20", null, 5), (119, 86, "2015-11-21 21:27", null, 5), (125, 93, "2015-11-20 07:38", null, 5), (117, 85, "2015-11-19 13:37", null, 5), (122, 84, "2015-11-18 15:37", null, 5), (116, 89, "2015-11-17 05:21", null, 5); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1170, "2015-11-22 21:21", null, 5), (1122, "2015-11-21 08:02", null, 5), (1494, "2015-11-20 09:37", null, 5), (1416, "2015-11-19 05:37", null, 5), (1550, "2015-11-18 07:37", null, 5), (1384, "2015-11-17 02:53", null, 5); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (59, "running", "2015-11-22 11:13", null, 5), (54, "running", "2015-11-21 06:45", null, 5), (32, "running", "2015-11-20 06:29", null, 5), (20, "weights", "2015-11-19 21:52", null, 5), (23, "weights", "2015-11-18 22:52", null, 5), (22, "running", "2015-11-17 17:58", null, 5); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (386, "2015-11-22 18:10", null, 5), (383, "2015-11-21 17:21", null, 5), (461, "2015-11-20 19:00", null, 5), (454, "2015-11-19 17:59", null, 5), (462, "2015-11-18 23:04", null, 5), (386, "2015-11-17 12:56", null, 5); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (143, "2015-11-22 05:25", null, 5), (141, "2015-11-21 01:43", null, 5), (143, "2015-11-20 18:53", null, 5), (145, "2015-11-19 01:36", null, 5), (142, "2015-11-18 06:27", null, 5), (147, "2015-11-17 23:48", null, 5);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (99, "2015-11-22 03:24", null, 6), (107, "2015-11-21 09:46", null, 6), (103, "2015-11-20 15:38", null, 6), (104, "2015-11-19 06:24", null, 6), (95, "2015-11-18 09:53", null, 6), (112, "2015-11-17 02:20", null, 6); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (117, 87, "2015-11-22 23:28", null, 6), (109, 82, "2015-11-21 07:31", null, 6), (107, 87, "2015-11-20 03:43", null, 6), (108, 85, "2015-11-19 01:46", null, 6), (118, 84, "2015-11-18 04:53", null, 6), (113, 82, "2015-11-17 02:48", null, 6); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1601, "2015-11-22 12:52", null, 6), (1549, "2015-11-21 18:20", null, 6), (1178, "2015-11-20 09:30", null, 6), (1129, "2015-11-19 23:01", null, 6), (1660, "2015-11-18 15:56", null, 6), (1292, "2015-11-17 09:33", null, 6); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (24, "running", "2015-11-22 16:47", null, 6), (28, "running", "2015-11-21 05:48", null, 6), (30, "weights", "2015-11-20 19:33", null, 6), (56, "running", "2015-11-19 18:32", null, 6), (20, "weights", "2015-11-18 23:42", null, 6), (40, "running", "2015-11-17 18:31", null, 6); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (413, "2015-11-22 11:00", null, 6), (534, "2015-11-21 23:59", null, 6), (361, "2015-11-20 11:37", null, 6), (387, "2015-11-19 14:05", null, 6), (522, "2015-11-18 16:11", null, 6), (398, "2015-11-17 20:45", null, 6); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (145, "2015-11-22 20:06", null, 6), (141, "2015-11-21 02:25", null, 6), (146, "2015-11-20 01:36", null, 6), (148, "2015-11-19 08:11", null, 6), (144, "2015-11-18 20:00", null, 6), (147, "2015-11-17 11:10", null, 6);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (102, "2015-11-22 12:00", null, 7), (101, "2015-11-21 10:40", null, 7), (114, "2015-11-20 06:52", null, 7), (114, "2015-11-19 13:30", null, 7), (110, "2015-11-18 12:42", null, 7), (115, "2015-11-17 16:08", null, 7); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (121, 82, "2015-11-22 17:01", null, 7), (117, 81, "2015-11-21 12:53", null, 7), (115, 81, "2015-11-20 02:58", null, 7), (111, 84, "2015-11-19 02:57", null, 7), (113, 80, "2015-11-18 14:05", null, 7), (117, 85, "2015-11-17 05:04", null, 7); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1258, "2015-11-22 15:02", null, 7), (1183, "2015-11-21 05:00", null, 7), (1477, "2015-11-20 23:50", null, 7), (1588, "2015-11-19 02:52", null, 7), (1671, "2015-11-18 01:02", null, 7), (1384, "2015-11-17 21:12", null, 7); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (40, "running", "2015-11-22 12:13", null, 7), (26, "running", "2015-11-21 03:34", null, 7), (23, "running", "2015-11-20 15:15", null, 7), (59, "running", "2015-11-19 08:42", null, 7), (46, "running", "2015-11-18 03:54", null, 7), (58, "weights", "2015-11-17 06:19", null, 7); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (513, "2015-11-22 14:41", null, 7), (526, "2015-11-21 23:55", null, 7), (482, "2015-11-20 04:24", null, 7), (534, "2015-11-19 02:03", null, 7), (496, "2015-11-18 10:16", null, 7), (364, "2015-11-17 02:31", null, 7); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (140, "2015-11-22 01:03", null, 7), (148, "2015-11-21 19:37", null, 7), (146, "2015-11-20 00:23", null, 7), (146, "2015-11-19 15:20", null, 7), (140, "2015-11-18 16:57", null, 7), (141, "2015-11-17 19:39", null, 7);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (100, "2015-11-22 06:50", null, 8), (95, "2015-11-21 14:46", null, 8), (95, "2015-11-20 18:38", null, 8), (104, "2015-11-19 18:20", null, 8), (103, "2015-11-18 13:52", null, 8), (110, "2015-11-17 13:44", null, 8); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (119, 86, "2015-11-22 02:15", null, 8), (105, 85, "2015-11-21 17:49", null, 8), (111, 95, "2015-11-20 11:38", null, 8), (111, 84, "2015-11-19 22:27", null, 8), (112, 92, "2015-11-18 04:27", null, 8), (112, 85, "2015-11-17 21:23", null, 8); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1492, "2015-11-22 12:05", null, 8), (1376, "2015-11-21 01:17", null, 8), (1339, "2015-11-20 09:54", null, 8), (1141, "2015-11-19 21:23", null, 8), (1133, "2015-11-18 20:10", null, 8), (1208, "2015-11-17 20:05", null, 8); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (30, "weights", "2015-11-22 03:46", null, 8), (59, "running", "2015-11-21 06:21", null, 8), (22, "running", "2015-11-20 01:48", null, 8), (39, "running", "2015-11-19 21:36", null, 8), (22, "running", "2015-11-18 14:43", null, 8), (43, "weights", "2015-11-17 11:36", null, 8); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (367, "2015-11-22 09:26", null, 8), (404, "2015-11-21 23:24", null, 8), (504, "2015-11-20 02:34", null, 8), (397, "2015-11-19 09:08", null, 8), (429, "2015-11-18 11:33", null, 8), (510, "2015-11-17 12:27", null, 8); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (141, "2015-11-22 03:32", null, 8), (147, "2015-11-21 12:33", null, 8), (145, "2015-11-20 21:31", null, 8), (140, "2015-11-19 05:17", null, 8), (144, "2015-11-18 00:34", null, 8), (142, "2015-11-17 05:30", null, 8);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (99, "2015-11-22 12:06", null, 9), (115, "2015-11-21 04:18", null, 9), (95, "2015-11-20 09:10", null, 9), (101, "2015-11-19 05:10", null, 9), (99, "2015-11-18 18:37", null, 9), (102, "2015-11-17 22:24", null, 9); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (116, 85, "2015-11-22 09:25", null, 9), (122, 83, "2015-11-21 05:33", null, 9), (122, 84, "2015-11-20 11:00", null, 9), (112, 90, "2015-11-19 18:30", null, 9), (121, 91, "2015-11-18 04:16", null, 9), (125, 87, "2015-11-17 08:00", null, 9); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1252, "2015-11-22 09:18", null, 9), (1676, "2015-11-21 10:26", null, 9), (1516, "2015-11-20 19:54", null, 9), (1140, "2015-11-19 13:49", null, 9), (1398, "2015-11-18 06:34", null, 9), (1516, "2015-11-17 12:28", null, 9); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (49, "running", "2015-11-22 21:41", null, 9), (47, "running", "2015-11-21 20:22", null, 9), (40, "running", "2015-11-20 08:29", null, 9), (55, "running", "2015-11-19 15:30", null, 9), (50, "running", "2015-11-18 15:23", null, 9), (53, "running", "2015-11-17 16:38", null, 9); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (384, "2015-11-22 02:32", null, 9), (393, "2015-11-21 07:42", null, 9), (474, "2015-11-20 12:23", null, 9), (383, "2015-11-19 18:34", null, 9), (366, "2015-11-18 02:18", null, 9), (518, "2015-11-17 07:49", null, 9); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (140, "2015-11-22 08:48", null, 9), (140, "2015-11-21 10:28", null, 9), (145, "2015-11-20 22:14", null, 9), (142, "2015-11-19 08:17", null, 9), (147, "2015-11-18 01:04", null, 9), (142, "2015-11-17 23:59", null, 9);
insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values (103, "2015-11-22 14:06", null, 10), (103, "2015-11-21 10:30", null, 10), (95, "2015-11-20 08:44", null, 10), (109, "2015-11-19 03:15", null, 10), (103, "2015-11-18 10:11", null, 10), (96, "2015-11-17 02:22", null, 10); insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values (117, 85, "2015-11-22 19:46", null, 10), (109, 95, "2015-11-21 05:20", null, 10), (111, 86, "2015-11-20 09:17", null, 10), (121, 90, "2015-11-19 21:21", null, 10), (124, 83, "2015-11-18 19:45", null, 10), (119, 80, "2015-11-17 19:11", null, 10); insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values (1289, "2015-11-22 12:55", null, 10), (1634, "2015-11-21 16:42", null, 10), (1557, "2015-11-20 13:08", null, 10), (1134, "2015-11-19 10:27", null, 10), (1414, "2015-11-18 12:29", null, 10), (1255, "2015-11-17 04:35", null, 10); insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values (17, "running", "2015-11-22 22:13", null, 10), (48, "running", "2015-11-21 04:52", null, 10), (42, "weights", "2015-11-20 16:31", null, 10), (26, "running", "2015-11-19 21:38", null, 10), (51, "running", "2015-11-18 13:11", null, 10), (51, "running", "2015-11-17 01:39", null, 10); insert into SleepMeasurements (duration, dateAndTime, notes, userID) values (428, "2015-11-22 00:13", null, 10), (539, "2015-11-21 02:03", null, 10), (437, "2015-11-20 15:07", null, 10), (537, "2015-11-19 11:38", null, 10), (534, "2015-11-18 12:46", null, 10), (434, "2015-11-17 18:52", null, 10); insert into WeightMeasurements (weight, dateAndTime, notes, userID) values (140, "2015-11-22 22:04", null, 10), (147, "2015-11-21 19:39", null, 10), (140, "2015-11-20 10:47", null, 10), (145, "2015-11-19 05:36", null, 10), (143, "2015-11-18 03:43", null, 10), (144, "2015-11-17 07:19", null, 10);
