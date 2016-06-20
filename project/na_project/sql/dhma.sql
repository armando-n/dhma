drop database if exists na_projectdb;
create database na_projectdb;
use na_projectdb;

drop table if exists Users;
create table Users(
    userID          integer primary key auto_increment,
    userName        varchar(50) unique not null,
    password        varchar(255) not null,
    isAdministrator boolean default false,
    dateCreated     timestamp default CURRENT_TIMESTAMP
);

drop table if exists UserProfiles;
create table UserProfiles(
    profileID             integer primary key auto_increment,
    firstName             varchar(50),
    lastName              varchar(50),
    email                 varchar(50),
    phone                 varchar(20),
    gender                enum('male', 'female', 'other'),
    dob                   date,
    country               varchar(50),
    picture               varchar(50) default 'profile_default.png',
    facebook              varchar(50),
    theme                 varchar(20),
    accentColor           char(7), -- example: '#0088BB'
    isProfilePublic       boolean default false,
    isPicturePublic       boolean default false,
    sendReminders         boolean default false,
    stayLoggedIn          boolean default false,
    userID                integer not null,
    foreign key (userID) references Users (userID) on delete cascade
);

drop table if exists MeasurementsOptions;
create table MeasurementsOptions(
    optionsID                     integer primary key auto_increment,
    optionsName                   varchar(20) not null,
    isActive                      boolean default false,
    activeMeasurement             enum('bloodPressure', 'calories', 'exercise', 'glucose', 'sleep', 'weight') default 'glucose',
    bloodPressureUnits            enum('mm Hg') default 'mm Hg',
    calorieUnits                  enum('calories') default 'calories',
    exerciseUnits                 enum('minutes') default 'minutes',
    glucoseUnits                  enum('mg/dL', 'mM') default 'mg/dL',
    sleepUnits                    enum('minutes') default 'minutes',
    weightUnits                   enum('lbs', 'kg') default 'lbs',
    timeFormat                    enum('12 hour', '24 hour') default '12 hour',
    durationFormat                enum('minutes', 'hours', 'hours:minutes') default 'hours:minutes',
    showTooltips                  boolean default false,
    showSecondaryCols             boolean default true, -- e.g. exercise type column
    showDateCol                   boolean default true,
    showTimeCol                   boolean default true,
    showNotesCol                  boolean default true,
    numRows                       integer default 25,
    showTable                     boolean default true,
    tableSize                     integer default 35,
    chartPlacement                enum('left', 'right', 'top', 'bottom') default 'bottom',
    showFirstChart                boolean default true,
    showSecondChart               boolean default true,
    firstChartType                enum('individual', 'daily', 'weekly', 'monthly', 'yearly') default 'individual',
    secondChartType               enum('individual', 'daily', 'weekly', 'monthly', 'yearly') default 'monthly',
    chartLastYear                 boolean default false,
    chartGroupDays                boolean default false,
    individualBloodPressureChartStart date not null,
    individualBloodPressureChartEnd   date default null,
    dailyBloodPressureChartStart      date not null, -- null indicates today
    dailyBloodPressureChartEnd        date default null,
    weeklyBloodPressurechartStart     date not null,
    weeklyBloodPressurechartEnd       date default null,
    monthlyBloodPressureChartStart    date not null,
    monthlyBloodPressureChartEnd      date default null,
    yearlyBloodPressureChartStart     date not null,
    yearlyBloodPressureChartEnd       date default null,
    individualCaloriesChartStart      date not null,
    individualCaloriesChartEnd        date default null,
    dailyCaloriesChartStart           date not null,
    dailyCaloriesChartEnd             date default null,
    weeklyCaloriesChartStart          date not null,
    weeklyCaloriesChartEnd            date default null,
    monthlyCaloriesChartStart         date not null,
    monthlyCaloriesChartEnd           date default null,
    yearlyCaloriesChartStart          date not null,
    yearlyCaloriesChartEnd            date default null,
    individualExerciseChartStart      date not null,
    individualExerciseChartEnd        date default null,
    dailyExerciseChartStart           date not null,
    dailyExerciseChartEnd             date default null,
    weeklyExerciseChartStart          date not null,
    weeklyExerciseChartEnd            date default null,
    monthlyExerciseChartStart         date not null,
    monthlyExerciseChartEnd           date default null,
    yearlyExerciseChartStart          date not null,
    yearlyExerciseChartEnd            date default null,
    individualGlucoseChartStart       date not null,
    individualGlucoseChartEnd         date default null,
    dailyGlucoseChartStart            date not null,
    dailyGlucoseChartEnd              date default null,
    weeklyGlucoseChartStart           date not null,
    weeklyGlucoseChartEnd             date default null,
    monthlyGlucoseChartStart          date not null,
    monthlyGlucoseChartEnd            date default null,
    yearlyGlucoseChartStart           date not null,
    yearlyGlucoseChartEnd             date default null,
    individualSleepChartStart         date not null,
    individualSleepChartEnd           date default null,
    dailySleepChartStart              date not null,
    dailySleepChartEnd                date default null,
    weeklySleepChartStart             date not null,
    weeklySleepChartEnd               date default null,
    monthlySleepChartStart            date not null,
    monthlySleepChartEnd              date default null,
    yearlySleepChartStart             date not null,
    yearlySleepChartEnd               date default null,
    individualWeightChartStart        date not null,
    individualWeightChartEnd          date default null,
    dailyWeightChartStart             date not null,
    dailyWeightChartEnd               date default null,
    weeklyWeightChartStart            date not null,
    weeklyWeightChartEnd              date default null,
    monthlyWeightChartStart           date not null,
    monthlyWeightChartEnd             date default null,
    yearlyWeightChartStart            date not null,
    yearlyWeightChartEnd              date default null,
    userID                            integer not null, 
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_measOpts unique (optionsName, userID)
);

drop table if exists BloodPressureMeasurements;
create table BloodPressureMeasurements(
    bpID                integer primary key auto_increment,
    systolicPressure    integer not null,
    diastolicPressure   integer not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    units               enum('mm Hg') default 'mm Hg',
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
    units               enum('calories') default 'calories',
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists ExerciseMeasurements;
create table ExerciseMeasurements(
    exerciseID          integer primary key auto_increment,
    duration            double not null,
    type                varchar(100) not null, -- e.g. 'running', 'swimming', 'weight-lifting'
    dateAndTime         datetime not null,
    notes               varchar(255),
    units               enum('minutes') default 'minutes',
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists GlucoseMeasurements;
create table GlucoseMeasurements(
    glucoseID           integer primary key auto_increment,
    glucose             double not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    units               enum('mg/dL', 'mM') default 'mg/dL',
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

drop table if exists SleepMeasurements;
create table SleepMeasurements(
    sleepID             integer primary key auto_increment,
    duration            double not null,
    dateAndTime         datetime not null,
    notes               varchar(255),
    units               enum('minutes') default 'minutes',
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
    units               enum('lbs', 'kg') default 'lbs',
    userID              integer not null,
    foreign key (userID) references Users (userID) on delete cascade,
    constraint uniq_meas unique (dateAndTime, userID)
);

delimiter //

-- sets up default 'Session' measurements options for user with the specified user ID (uID)
create procedure defaultMeasurementsOptions(in uID int)
    BEGIN
        insert into MeasurementsOptions (optionsName, isActive,
            individualBloodPressureChartStart, dailyBloodPressureChartStart, weeklyBloodPressureChartStart,
            monthlyBloodPressureChartStart, yearlyBloodPressureChartStart,
            individualCaloriesChartStart, dailyCaloriesChartStart, weeklyCaloriesChartStart,
            monthlyCaloriesChartStart, yearlyCaloriesChartStart,
            individualExerciseChartStart, dailyExerciseChartStart, weeklyExerciseChartStart,
            monthlyExerciseChartStart, yearlyExerciseChartStart,
            individualGlucoseChartStart, dailyGlucoseChartStart, weeklyGlucoseChartStart,
            monthlyGlucoseChartStart, yearlyGlucoseChartStart,
            individualSleepChartStart, dailySleepChartStart, weeklySleepChartStart,
            monthlySleepChartStart, yearlySleepChartStart,
            individualWeightChartStart, dailyWeightChartStart, weeklyWeightChartStart,
            monthlyWeightChartStart, yearlyWeightChartStart,
            userID)
            values
                ('Session', true,
                date_sub(now(), interval 1 month), -- individual blood pressure chart
                date_sub(now(), interval 1 month), -- daily blood pressure chart
                date_sub(now(), interval 1 year),  -- weekly blood pressure chart
                date_sub(now(), interval 1 year),  -- monthly blood pressure chart
                date_sub(now(), interval 5 year),  -- yearly blood pressure chart
                date_sub(now(), interval 1 month), -- individual calories chart
                date_sub(now(), interval 1 month), -- daily calories chart
                date_sub(now(), interval 1 year),  -- weekly calories chart
                date_sub(now(), interval 1 year),  -- monthly calories chart
                date_sub(now(), interval 5 year),  -- yearly calories chart
                date_sub(now(), interval 1 month), -- individual exercise chart
                date_sub(now(), interval 1 month), -- daily exercise chart
                date_sub(now(), interval 1 year),  -- weekly exercise chart
                date_sub(now(), interval 1 year),  -- monthly exercise chart
                date_sub(now(), interval 5 year),  -- yearly exercise chart
                date_sub(now(), interval 1 month), -- individual glucose chart
                date_sub(now(), interval 1 month), -- daily glucose chart
                date_sub(now(), interval 1 year),  -- weekly glucose chart
                date_sub(now(), interval 1 year),  -- monthly glucose chart
                date_sub(now(), interval 5 year),  -- yearly glucose chart
                date_sub(now(), interval 1 month), -- individual sleep chart
                date_sub(now(), interval 1 month), -- daily sleep chart
                date_sub(now(), interval 1 year),  -- weekly sleep chart
                date_sub(now(), interval 1 year),  -- monthly sleep chart
                date_sub(now(), interval 5 year),  -- yearly sleep chart
                date_sub(now(), interval 1 month), -- individual weight chart
                date_sub(now(), interval 1 month), -- daily weight chart
                date_sub(now(), interval 1 year),  -- weekly weight chart
                date_sub(now(), interval 1 year),  -- monthly weight chart
                date_sub(now(), interval 5 year),  -- yearly weight chart
                uID);
    END//

delimiter ;

-- User data (passwords are hashes for 'pass123', except admin password is 'admin')
insert into Users (userName, password) values
    ('member', '$2y$10$Xvd13JJMs0aNuXI3DeCDQOmSOPmdBuYzxuc8pTrTiDz80GwL2VrWO'),
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

-- measurements options data
call defaultMeasurementsOptions(1);  -- member
call defaultMeasurementsOptions(2);  -- robbins
call defaultMeasurementsOptions(3);  -- john-s
call defaultMeasurementsOptions(4);  -- bob
call defaultMeasurementsOptions(5);  -- sarahk
call defaultMeasurementsOptions(6);  -- whatup
call defaultMeasurementsOptions(7);  -- delete-me-1
call defaultMeasurementsOptions(8);  -- delete-me-2
call defaultMeasurementsOptions(9);  -- delete-me-3
call defaultMeasurementsOptions(10); -- admin

-- UserProfile data
insert into UserProfiles (firstName, lastName, email, phone, gender, dob, country, picture, facebook, theme, accentColor, isProfilePublic,  isPicturePublic, sendReminders, stayLoggedIn, userID)
    values
        ("Member", "Guy", "member@email.com", "210-555-2170", "male", "1992-08-07", "United States of America", "member.jpg", null, "light", "#0088BB", true, true, false, false, 1),
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
