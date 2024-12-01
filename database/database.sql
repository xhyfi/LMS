  create database lms;

  use lms;

  CREATE TABLE `user` (
    `IDNo` varchar(50) NOT NULL,
    `Name` varchar(50) DEFAULT NULL,
    `Type` varchar(50) DEFAULT NULL,
    `EmailId` varchar(50) DEFAULT NULL,
    `MobNo` bigint(11) DEFAULT NULL,
    `Password` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`IDNo`),
    UNIQUE KEY `EmailId` (`EmailId`)
  );

  CREATE TABLE `book` (
    `BookId` int(10) NOT NULL AUTO_INCREMENT,
    `Title` varchar(50) DEFAULT NULL,
    `Publisher` varchar(50) DEFAULT NULL,
    `Year` varchar(50) DEFAULT NULL,
    `Availability` int(5) DEFAULT NULL,
    PRIMARY KEY (`BookId`)
  ); 

  CREATE TABLE `message` (
    `M_Id` int(10) NOT NULL AUTO_INCREMENT,
    `IDNo` varchar(50) DEFAULT NULL,
    `Msg` varchar(255) DEFAULT NULL,
    `Date` date DEFAULT NULL,
    `Time` time DEFAULT NULL,
    PRIMARY KEY (`M_Id`),
    KEY `IDNo` (`IDNo`),
    CONSTRAINT `message_ibfk_1` FOREIGN KEY (`IDNo`) REFERENCES `user` (`IDNo`)
  ); 


  CREATE TABLE `recommendations` (
    `R_ID` int(10) NOT NULL AUTO_INCREMENT,
    `Book_Name` varchar(50) DEFAULT NULL,
    `Description` varchar(255) DEFAULT NULL,
    `IDNo` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`R_ID`),
    KEY `IDNo` (`IDNo`),
    CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`IDNo`) REFERENCES `user` (`IDNo`)
  ); 

CREATE TABLE `record` (
    `IDNo` varchar(50) NOT NULL,
    `BookId` int(10) NOT NULL,
    `Date_of_Issue` date DEFAULT NULL,
    `Due_Date` date DEFAULT NULL,
    `Date_of_Return` date DEFAULT NULL,
    `Dues` int(10) DEFAULT NULL,
    `Renewals_left` int(10) DEFAULT NULL,
    `Time` time DEFAULT NULL,
    PRIMARY KEY (`IDNo`, `BookId`),
    KEY `BookId` (`BookId`),
    CONSTRAINT `record_ibfk_1` FOREIGN KEY (`IDNo`) REFERENCES `user` (`IDNo`),
    CONSTRAINT `record_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
);


  CREATE TABLE `author` (
    `BookId` int(10) NOT NULL,
    `Author` varchar(50) NOT NULL,
    PRIMARY KEY (`BookId`,`Author`),
    CONSTRAINT `author_ibfk_1` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
  ); 

  CREATE TABLE `renew` (
    `IDNo` varchar(50) NOT NULL,
    `BookId` int(10) NOT NULL,
    PRIMARY KEY (`IDNo`,`BookId`),
    KEY `BookId` (`BookId`),
    CONSTRAINT `renew_ibfk_1` FOREIGN KEY (`IDNo`) REFERENCES `user` (`IDNo`),
    CONSTRAINT `renew_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
  ); 

  CREATE TABLE `return` (
    `IDNo` varchar(50) NOT NULL,
    `BookId` int(10) NOT NULL,
    PRIMARY KEY (`IDNo`,`BookId`),
    KEY `BookId` (`BookId`),
    CONSTRAINT `return_ibfk_1` FOREIGN KEY (`IDNo`) REFERENCES `user` (`IDNo`),
    CONSTRAINT `return_ibfk_2` FOREIGN KEY (`BookId`) REFERENCES `book` (`BookId`)
  ); 


  INSERT INTO `user` VALUES ( 23063589,'admin','Admin','admin@gmail.com',123456789,'admin');

  INSERT INTO `book` VALUES (1,'OS','PEARSON','2006',0),(2,'DBMS','TARGET67','2010',0),(3,'TOC','NITC','2018',4),(4,'TOC','NITC','2018',1),(5,'DAA','y','2014',0),(6,'DSA','X','2010',10),(7,'Discrete Structures','Pearson','2010',10),(8,'Database Processing','Prentice Hall','2013',12),(9,'Computer System Architecture','Prentice Hall','2015',4),(10,'C: How to program','Prentice Hall','2009',3),(11,'Atomic and Nuclear Systems','Pearson India ','2017',13),(12,'The PlayBook','Stinson','2010',12),(13,'General Theory of Relativity','Pearson India ','2012',5),(14,'Heat and Thermodynamics','Pearson','2013',9),(15,'Machine Design','Pearson India ','2012',5),(16,'Nuclear Physics','Pearson India ','1998',7),(17,'Operating System','Pearson India ','1990',7),(18,'Theory of Machines','Pearson','1992',12);
