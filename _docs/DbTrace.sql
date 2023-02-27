-- mysql commands
show databases;
-- use NomDb;
-- show tables;
-- desc NomTable;

create database if not exists DbTrace; -- 'if exists' pas sql standard propre a chaque moteur sql (pas au BTS)
use DbTrace;

drop table if exists user; -- 'if exists' pas sql standard propre a chaque moteur sql
-- create table user(
--	id int primary key auto_increment,
--	nameuser varchar(50) not null unique,
--	datecreation datetime
-- );
-- create table if not exists page(
-- 	id int auto_increment primary key,
-- 	page varchar(150) not null unique,
-- 	datecreation datetime
-- );

CREATE TABLE User(
        Id           Int PRIMARY KEY Auto_increment NOT NULL,
        DateCreation Datetime NOT NULL ,
        Name         Varchar (150) NOT NULL, 
	CONSTRAINT User_UC_Name UNIQUE (Name)
);

CREATE TABLE Trace(
	Id  Int  Auto_increment PRIMARY KEY, -- (PK=UNIQUE ET NOT NULL)
	Ip varchar(45) NOT NULL,
	Host varchar(45),
	DateCnx datetime not null,
	UserId Int,		-- attention enlever le 'not null', car fichier peut avoir des traces sans login
 	CONSTRAINT FK_TraceUser FOREIGN KEY (UserID) REFERENCES User(Id)
);
-- ci-dessus, en une seule fois, on crée les tables de références avant la table qui contient les FKs

-- ou ci-dessous, on crée tjs la table de référence avant les FKs, mais on peut modifier la table fille
CREATE TABLE Page (
        Id   Int  Auto_increment PRIMARY KEY,
        Page Varchar (150) NOT NULL, 
	CONSTRAINT Page_UC_Page UNIQUE (Page)
);

alter table Trace
	add PageId int not null,
	add CONSTRAINT FK_TracePage FOREIGN KEY (PageId) REFERENCES Page(Id);

-- ou alors plus simple en une fois (ci-dessous)
-- CREATE TABLE Trace(
-- 	Id  Int  Auto_increment PRIMARY KEY, -- (PK=UNIQUE ET NOT NULL)
-- 	Ip varchar(45) NOT NULL,
-- 	Host varchar(45),
-- 	DateCnx datetime not null,
-- 	UserId Int,
-- 	PageId int not null,
-- 	CONSTRAINT FK_TraceUser FOREIGN KEY (UserID) REFERENCES User(Id),
-- 	CONSTRAINT FK_TracePage FOREIGN KEY (PageId) REFERENCES Page(Id);
-- );


truncate table page;
INSERT INTO page (Id, Page) VALUES (1, 'Page Principale')
INSERT INTO page (Id, Page) (2, 'Page Secondaire');


INSERT INTO `user` (`Id`, `DateCreation`, `Name`) 
	VALUES ('1', '2023-02-12 16:38:21', 'User 1'), 
		('2', '2023-02-12 16:38:21', 'User 2');
INSERT INTO `user` (`Id`, `DateCreation`, `Name`) 
	VALUES ('3', '2023-02-12 16:38:21', 'User 3'), 
		('4', '2023-02-12 16:38:21', 'User 4');
-- ici pour la démo, j'ai fixé les id, mais en reel on ne les met jamais
-- INSERT INTO `user` (`DateCreation`, `Name`) 
--	VALUES ('2023-02-12 16:38:21', 'User 1'), 
---		('2023-02-12 16:38:21', 'User 2');


INSERT INTO trace (Ip, Host, DateCnx, UserId, PageId) 
	VALUES ('1.2.3.4.5', 'hostip.net', '2023-01-01 00:30:00.000000', 1, 1), 
			('1.2.3.4.5', 'hostip.net', '2023-01-12 16:41:57.000000', 1, 2);
INSERT INTO trace (Ip, Host, DateCnx, UserId, PageId) 
	VALUES ('5.6.7.8.9', 'hostip.com', '2023-01-12 16:41:57.000000', '2', '2');
INSERT INTO trace (Ip, Host, DateCnx, UserId, PageId) 
	VALUES ('5.5.5.5.5', 'hostip.net', '2023-02-12 16:41:57', 1, 2), 
			('5.5.5.5.5', 'hostip.net', '2023-02-10 14:41:59', 1, 2),
			('5.5.5.5.5', 'hostip.net', '2023-02-11 15:41:59', 1, 2),
			('5.5.5.5.5', 'hostip.net', '2023-02-12 16:41:59', 1, 2),
			('5.5.5.5.5', 'hostip.net', '2023-02-12 16:40:55', 1, 1),
			('5.5.5.5.5', 'hostip.net', '2023-02-13 16:41:59', 1, 2);
INSERT INTO trace (Ip, Host, DateCnx, UserId, PageId) 
	VALUES ('8.8.8.8.8', 'orange.fr', '2023-02-12 16:41:57', NULL, 1);
INSERT INTO trace (Ip, Host, DateCnx, UserId, PageId) 
	VALUES ('4.4.4.4.4', 'free.fr', '2023-02-12 16:41:57', 3, 2),
				('4.4.4.4.4', 'free.fr', '2023-02-12 16:45:57', 3, 2),
				('5.5.5.5.5', 'bt.fr', '2023-02-12 16:45:57', 4, 1),
				('5.5.5.5.5', 'bt.fr', '2023-02-12 16:45:57', 4, 2),
				('5.5.5.5.5', 'bt.fr', '2023-02-12 17:45:57', 4, 1);

CREATE TABLE excludeip (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	ip VARCHAR(45) NOT NULL
);
insert into excludeip (id, ip)
	values (1, '5.5.5.5.5');

ALTER TABLE page 
	ADD exclude tinyint(1) NOT NULL DEFAULT 0;

update page set exclude=1 where page = 'Page Principale';

SELECT t.*, u.Name, p.Page FROM trace as t
	inner join page as p on p.Id = t.PageId 
  left join user as u on u.Id = t.UserId;
