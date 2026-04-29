-- =============================================================================
-- Equiterra Database (MySQL) Initialization Script
-- =============================================================================
-- Build order: Tables → Triggers → Stored Procedures → Views → Seed Data
-- =============================================================================

CREATE DATABASE IF NOT EXISTS equiterra;
USE equiterra;
-- use higher bit character encoding
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- =============================================================================
-- TABLES
-- =============================================================================

-- Farrier does not have phone# column as all farriers are web users
CREATE TABLE IF NOT EXISTS Farrier (
    Fusername VARCHAR(20) NOT NULL,
    Fpassword VARCHAR(30) NOT NULL,
    Fname     VARCHAR(30),
    UNIQUE  (Fname),
    PRIMARY KEY (Fusername)
);

CREATE TABLE IF NOT EXISTS Practitioner (
    Pname     VARCHAR(30) NOT NULL,
    Phone_num VARCHAR(20),
    Email     VARCHAR(40),
    Type      VARCHAR(20),
    PRIMARY KEY (Pname)
);

CREATE TABLE IF NOT EXISTS Client (
    Cusername VARCHAR(20) NOT NULL,
    Cpassword VARCHAR(30) NOT NULL,
    Cname     VARCHAR(40) NOT NULL,
    Email     VARCHAR(40) NOT NULL,
    Phone_num VARCHAR(20),
    PRIMARY KEY (Cusername),
    UNIQUE (Cname)
);

-- Web_user: single unified login table for both Admins and Clients.
-- User_type is either 'Admin' (e.g. a Farrier) or 'Client'.
-- 'Admin' was chosen in lieu of Farrier, as some Farriers are Admins, but not all Admins are Farriers.
CREATE TABLE IF NOT EXISTS Web_user (
    Username  VARCHAR(20)  NOT NULL,
    Password  VARCHAR(30)  NOT NULL,
    Name      VARCHAR(40)  NOT NULL,
    Email     VARCHAR(40)  NOT NULL,
    Phone_num VARCHAR(20),
    User_type VARCHAR(10)  NOT NULL DEFAULT 'Client',
    PRIMARY KEY (Username),
    UNIQUE (Email)
);

CREATE TABLE IF NOT EXISTS Barn (
    Bname       VARCHAR(30) NOT NULL,
    Contact     VARCHAR(30),
    Email       VARCHAR(40),
    Phone_num   VARCHAR(20),
    Street_num  SMALLINT,
    Street_name VARCHAR(20),
    City        VARCHAR(15),
    Province    VARCHAR(2),
    Postal_code VARCHAR(7),
    PRIMARY KEY (Bname)
);

CREATE TABLE IF NOT EXISTS Horse (
    Hname      VARCHAR(30) NOT NULL,
    Gender     VARCHAR(1),
    Discipline VARCHAR(20),
    Height     SMALLINT,
    Birthdate  DATE,
    Breed      VARCHAR(20),
    Conf_notes TEXT,
    Bname      VARCHAR(30),
    Cusername  VARCHAR(20),
    Status     TINYINT(1) DEFAULT 1,
    PRIMARY KEY (Hname),
    FOREIGN KEY (Bname)     REFERENCES Barn(Bname),
    FOREIGN KEY (Cusername) REFERENCES Client(Cusername) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Invoice (
    Number   INT          NOT NULL AUTO_INCREMENT,
    Status   TINYINT(1)   NOT NULL DEFAULT 0,
    Fusername VARCHAR(20),
    Cusername VARCHAR(20),
    PRIMARY KEY (Number),
    FOREIGN KEY (Cusername) REFERENCES Client(Cusername) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Invoice_Item (
    Item_id      INT      NOT NULL AUTO_INCREMENT,
    Inumber      INT,
    Hname        VARCHAR(30),
    Idescription TINYTEXT,
    Price        SMALLINT,
    Date         DATE     NOT NULL DEFAULT (CURRENT_DATE),
    PRIMARY KEY (Item_id),
    FOREIGN KEY (Inumber) REFERENCES Invoice(Number) ON UPDATE CASCADE,
    FOREIGN KEY (Hname)   REFERENCES Horse(Hname)   ON UPDATE CASCADE
);

-- Status default value set to 1 (active). A new protocol is assumed to be active.
CREATE TABLE IF NOT EXISTS Shoeing_Protocol (
    Hname       VARCHAR(30) NOT NULL,
    Date        DATE,
    Left_Front  TINYTEXT,
    Right_Front TINYTEXT,
    Left_Rear   TINYTEXT,
    Right_Rear  TINYTEXT,
    Status      TINYINT(1)  NOT NULL DEFAULT 1,
    Notes       TEXT,
    PRIMARY KEY (Hname, Date),
    FOREIGN KEY (Hname) REFERENCES Horse(Hname) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Analysis (
    Analysis_path   VARCHAR(255) NOT NULL,
    Date            DATE         NOT NULL DEFAULT (CURRENT_DATE),
    Type            VARCHAR(40),
    Details         TEXT,
    Hname           VARCHAR(30),
    PRIMARY KEY (Analysis_path),
    FOREIGN KEY (Hname) REFERENCES Horse(Hname) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Medical_Record (
    Hname    VARCHAR(30),
    Date     DATE         NOT NULL,
    Status   TINYINT(1)   DEFAULT 1,
    Filepath VARCHAR(255),     
    Ailment  TEXT,
    Pname    VARCHAR(30),
    PRIMARY KEY (Hname, Date),
    FOREIGN KEY (Hname)  REFERENCES Horse(Hname)        ON UPDATE CASCADE,
    FOREIGN KEY (Pname)  REFERENCES Practitioner(Pname) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Image (
    Hname      VARCHAR(30),
    Date       DATE         NOT NULL DEFAULT (CURRENT_DATE),
    Context    VARCHAR(30),
    Image_path VARCHAR(255) NOT NULL,
    PRIMARY KEY (Image_path),
    FOREIGN KEY (Hname) REFERENCES Horse(Hname) ON UPDATE CASCADE
);

-- =============================================================================
-- TRIGGERS
-- =============================================================================

DELIMITER //

-- Automatically generate a username for new clients:
-- first letter of first name + entire surname, all lowercase.
CREATE TRIGGER before_insert_Client_username
BEFORE INSERT ON Client
FOR EACH ROW
BEGIN
    SET NEW.Cusername = CONCAT(
        LOWER(SUBSTRING(NEW.Cname, 1, 1)),
        LOWER(SUBSTRING_INDEX(NEW.Cname, ' ', -1))
    );
END //

-- Automatically generate a 14-character random password for new clients.
CREATE TRIGGER before_insert_Client_password
BEFORE INSERT ON Client
FOR EACH ROW
BEGIN
    SET NEW.Cpassword = CONCAT(
        SUBSTRING(MD5(RAND()), 1, 7),
        SUBSTRING(MD5(RAND()), 1, 7)
    );
END //

DELIMITER ;

-- =============================================================================
-- STORED PROCEDURES
-- =============================================================================

DELIMITER //

-- -----------------------------------------------------------------------------
-- AUTH / USER MANAGEMENT
-- -----------------------------------------------------------------------------

-- Unified login: checks Web_user table by username OR email + password.
-- Returns the matching row (Username, Name, Email, Phone_num, User_type).
CREATE PROCEDURE GetUserLogin(
    IN in_username_or_email VARCHAR(40),
    IN in_password          VARCHAR(30)
)
BEGIN
    SELECT Username, Name, Email, Phone_num, User_type
    FROM Web_user
    WHERE (Username = in_username_or_email OR Email = in_username_or_email)
      AND Password = in_password
    LIMIT 1;
END //

-- Register a new web user (Client or Admin).
CREATE PROCEDURE AddWebUser(
    IN in_username  VARCHAR(20),
    IN in_password  VARCHAR(30),
    IN in_name      VARCHAR(40),
    IN in_email     VARCHAR(40),
    IN in_phone_num VARCHAR(20)
)
BEGIN
    INSERT INTO Web_user (Username, Password, Name, Email, Phone_num, User_type)
    VALUES (in_username, in_password, in_name, in_email, in_phone_num, 'Client');
END //

-- Check whether a username or email already exists (used during signup).
CREATE PROCEDURE CheckUsernameEmail(
    IN in_username VARCHAR(20),
    IN in_email    VARCHAR(40)
)
BEGIN
    SELECT Username FROM Web_user
    WHERE Username = in_username OR Email = in_email
    LIMIT 1;
END //

-- Return all farriers (used to populate the farrier dropdown on invoices).
CREATE PROCEDURE GetFarriers()
BEGIN
    SELECT Fusername, Fname FROM Farrier;
END //

-- -----------------------------------------------------------------------------
-- HORSE PROCEDURES
-- -----------------------------------------------------------------------------

-- Insert a new horse record.
CREATE PROCEDURE AddHorse(
    IN in_hname      VARCHAR(30),
    IN in_gender     VARCHAR(1),
    IN in_discipline VARCHAR(20),
    IN in_height     SMALLINT,
    IN in_birthdate  DATE,
    IN in_breed      VARCHAR(20),
    IN in_conf_notes TEXT,
    IN in_bname      VARCHAR(30),
    IN in_cusername  VARCHAR(20),
    IN in_status     TINYINT(1)
)
BEGIN
    INSERT INTO Horse (Hname, Gender, Discipline, Height, Birthdate, Breed, Conf_notes, Bname, Cusername, Status)
    VALUES (in_hname, in_gender, in_discipline, in_height, in_birthdate, in_breed, in_conf_notes, in_bname, in_cusername, in_status);
END //

-- Return all horses with their owner's name (Admin view).
CREATE PROCEDURE GetHorses()
BEGIN
    SELECT H.Hname, C.Cname
    FROM Horse H
    JOIN Client C ON H.Cusername = C.Cusername;
END //

-- Return all horses belonging to a specific client (Client view).
CREATE PROCEDURE GetClientHorses(IN in_cusername VARCHAR(20))
BEGIN
    SELECT H.Hname, C.Cname
    FROM Horse H
    JOIN Client C ON H.Cusername = C.Cusername
    WHERE H.Cusername = in_cusername;
END //

-- Return full details for a single horse, including the owner's name.
CREATE PROCEDURE GetHorseDetails(IN in_hname VARCHAR(30))
BEGIN
    SELECT H.Hname, H.Gender, H.Discipline, H.Height, H.Birthdate,
           H.Breed, H.Conf_notes, H.Bname, H.Cusername, H.Status,
           C.Cname
    FROM Horse H
    JOIN Client C ON H.Cusername = C.Cusername
    WHERE H.Hname = in_hname;
END //

-- Update conformation notes for a specific horse.
CREATE PROCEDURE UpdateConformationNotes(
    IN in_conf_notes TEXT,
    IN in_hname      VARCHAR(30)
)
BEGIN
    UPDATE Horse
    SET Conf_notes = in_conf_notes
    WHERE Hname = in_hname;
END //

-- Return all image paths associated with a specific horse.
CREATE PROCEDURE GetHorseImages(IN in_hname VARCHAR(30))
BEGIN
    SELECT Image_path
    FROM Image
    WHERE Hname = in_hname;
END //

-- -----------------------------------------------------------------------------
-- SHOEING PROTOCOL PROCEDURES
-- -----------------------------------------------------------------------------

CREATE PROCEDURE AddImage(
    IN in_hname      VARCHAR(30),
    IN in_date       DATE,
    IN in_context    VARCHAR(30),
    IN in_image_path VARCHAR(255)
)
BEGIN
    INSERT INTO Image (Hname, Date, Context, Image_path)
    VALUES (in_hname, in_date, in_context, in_image_path);
END //

-- Insert a new shoeing protocol and mark all previous ones for that horse as past (Status=0).
CREATE PROCEDURE AddShoeingProtocol(
    IN in_hname       VARCHAR(30),
    IN in_date        DATE,
    IN in_left_front  TINYTEXT,
    IN in_right_front TINYTEXT,
    IN in_left_rear   TINYTEXT,
    IN in_right_rear  TINYTEXT,
    IN in_status      TINYINT(1),
    IN in_notes       TEXT
)
BEGIN
    -- Archive all previous protocols for this horse
    UPDATE Shoeing_Protocol
    SET Status = 0
    WHERE Hname = in_hname AND Date < in_date;

    -- Insert the new protocol
    INSERT INTO Shoeing_Protocol (Hname, Date, Left_Front, Right_Front, Left_Rear, Right_Rear, Status, Notes)
    VALUES (in_hname, in_date, in_left_front, in_right_front, in_left_rear, in_right_rear, in_status, in_notes);
END //

-- Return all shoeing protocol dates for a specific horse (for the list view).
CREATE PROCEDURE GetShoeingProtocolDates(IN in_hname VARCHAR(30))
BEGIN
    SELECT Hname, Date
    FROM Shoeing_Protocol
    WHERE Hname = in_hname
    ORDER BY Date DESC;
END //

-- Return the full details of a specific shoeing protocol (horse + date).
CREATE PROCEDURE GetShoeingProtocol(
    IN in_hname VARCHAR(30),
    IN in_date  DATE
)
BEGIN
    SELECT Hname, Date, Left_Front, Right_Front, Left_Rear, Right_Rear, Status, Notes
    FROM Shoeing_Protocol
    WHERE Hname = in_hname AND Date = in_date;
END //

-- Update the status (Current/Past) of a specific shoeing protocol.
CREATE PROCEDURE UpdateProtocolStatus(
    IN in_status VARCHAR(1),
    IN in_hname  VARCHAR(30),
    IN in_date   DATE
)
BEGIN
    UPDATE Shoeing_Protocol
    SET Status = in_status
    WHERE Hname = in_hname AND Date = in_date;
END //

-- -----------------------------------------------------------------------------
-- ANALYSIS PROCEDURES
-- -----------------------------------------------------------------------------

-- Insert a new analysis record for a horse.
CREATE PROCEDURE AddAnalysis(
    IN in_analysis_path VARCHAR(255),
    IN in_date     DATE,
    IN in_type     VARCHAR(40),
    IN in_hname    VARCHAR(30),
    IN in_details  TEXT
)
BEGIN
    INSERT INTO Analysis (Analysis_path, Date, Type, Hname, Details)
    VALUES (in_analysis_path, in_date, in_type, in_hname, in_details);
END //

-- Return all analysis dates and types for a specific horse (for the list view).
CREATE PROCEDURE GetHorseAnalysis(IN in_hname VARCHAR(30))
BEGIN
    SELECT Date AS Analysis_Date, Type
    FROM Analysis
    WHERE Hname = in_hname
    ORDER BY Date DESC;
END //

-- Return details of a specific analysis record (horse + date + type).
CREATE PROCEDURE GetHorseAnalysisDetails(
    IN in_hname VARCHAR(30),
    IN in_date  DATE,
    IN in_type  VARCHAR(40)
)
BEGIN
    SELECT Analysis_path, Date, Type, Details
    FROM Analysis
    WHERE Hname = in_hname AND Date = in_date AND Type = in_type;
END //

-- Update the details text of a specific analysis record.
CREATE PROCEDURE UpdateAnalysisDetails(
    IN in_details VARCHAR(1000),
    IN in_date    DATE,
    IN in_type    VARCHAR(40),
    IN in_hname   VARCHAR(30)
)
BEGIN
    UPDATE Analysis
    SET Details = in_details
    WHERE Hname = in_hname AND Date = in_date AND Type = in_type;
END //

-- -----------------------------------------------------------------------------
-- MEDICAL RECORD PROCEDURES
-- -----------------------------------------------------------------------------

-- Return all medical records for a specific horse (for the list view on horse page).
CREATE PROCEDURE GetMedicalRecords(IN in_hname VARCHAR(30))
BEGIN
    SELECT M.Hname, M.Date, M.Status, M.Filepath, M.Ailment, M.Pname
    FROM Medical_Record M
    WHERE M.Hname = in_hname
    ORDER BY M.Date DESC;
END //

-- Return full details of a specific medical record including practitioner details.
CREATE PROCEDURE GetMedicalRecordDetails(
    IN in_hname VARCHAR(30),
    IN in_date  DATE
)
BEGIN
    SELECT M.Hname, M.Date, M.Status, M.Filepath, M.Ailment, M.Pname,
           P.Phone_num, P.Email, P.Type
    FROM Medical_Record M
    LEFT JOIN Practitioner P ON M.Pname = P.Pname
    WHERE M.Hname = in_hname AND M.Date = in_date;
END //

-- Add a new medical record.
CREATE PROCEDURE AddMedicalRecord(
    IN in_hname    VARCHAR(30),
    IN in_date     DATE,
    IN in_status   TINYINT(1),
    IN in_filepath VARCHAR(255),   
    IN in_ailment  TEXT,
    IN in_pname    VARCHAR(30)
)
BEGIN
    INSERT INTO Medical_Record (Hname, Date, Status, Filepath, Ailment, Pname)
    VALUES (in_hname, in_date, in_status, in_filepath, in_ailment, in_pname);
END //

-- Add a practitioner if they don't already exist (Option B — bare row, admin fills details later).
CREATE PROCEDURE AddPractitionerIfNotExists(IN in_pname VARCHAR(30))
BEGIN
    INSERT IGNORE INTO Practitioner (Pname)
    VALUES (in_pname);
END //

-- Return all practitioners for the dropdown (ordered alphabetically).
CREATE PROCEDURE GetPractitioners()
BEGIN
    SELECT Pname, Phone_num, Email, Type
    FROM Practitioner
    ORDER BY Pname ASC;
END //

-- Delete a specific medical record (Admin only).
CREATE PROCEDURE DeleteMedicalRecord(
    IN in_hname VARCHAR(30),
    IN in_date  DATE
)
BEGIN
    DELETE FROM Medical_Record
    WHERE Hname = in_hname AND Date = in_date;
END //

-- -----------------------------------------------------------------------------
-- BARN PROCEDURES
-- -----------------------------------------------------------------------------

-- Insert a new barn record.
CREATE PROCEDURE AddBarn(
    IN in_bname       VARCHAR(30),
    IN in_contact     VARCHAR(30),
    IN in_email       VARCHAR(40),
    IN in_phone_num   VARCHAR(20),
    IN in_street_num  SMALLINT,
    IN in_street_name VARCHAR(20),
    IN in_city        VARCHAR(15),
    IN in_province    VARCHAR(2),
    IN in_postal_code VARCHAR(7)
)
BEGIN
    INSERT INTO Barn (Bname, Contact, Email, Phone_num, Street_num, Street_name, City, Province, Postal_code)
    VALUES (in_bname, in_contact, in_email, in_phone_num, in_street_num, in_street_name, in_city, in_province, in_postal_code);
END //

-- Return all horses stabled at a specific barn, along with their owner's name.
CREATE PROCEDURE GetBarnHorses(IN in_bname VARCHAR(30))
BEGIN
    SELECT H.Hname, C.Cname
    FROM Horse H
    JOIN Client C ON H.Cusername = C.Cusername
    WHERE H.Bname = in_bname;
END //

-- Return all distinct clients who have at least one horse at a specific barn.
CREATE PROCEDURE GetBarnNumClients(IN in_bname VARCHAR(30))
BEGIN
    SELECT DISTINCT C.Cusername, C.Cname
    FROM Client C
    JOIN Horse H ON C.Cusername = H.Cusername
    WHERE H.Bname = in_bname;
END //

-- -----------------------------------------------------------------------------
-- INVOICE PROCEDURES
-- -----------------------------------------------------------------------------

-- Insert a new invoice record.
CREATE PROCEDURE AddInvoice(
    IN in_status    TINYINT(1),
    IN in_fusername VARCHAR(20),
    IN in_cusername VARCHAR(20)
)
BEGIN
    INSERT INTO Invoice (Status, Fusername, Cusername)
    VALUES (in_status, in_fusername, in_cusername);
END //

-- Aggregate all invoice items by Invoice number and sum their prices
CREATE PROCEDURE GetInvoiceTotal(IN in_inumber INT)
BEGIN
    SELECT COALESCE(SUM(Price), 0) AS Total
    FROM Invoice_Item
    WHERE Inumber = in_inumber;
END //

-- Insert a single invoicable item for an existing invoice.
CREATE PROCEDURE AddInvoiceItem(
    IN in_inumber      INT,
    IN in_hname        VARCHAR(30),
    IN in_description  TINYTEXT,
    IN in_price        SMALLINT,
    IN in_date         DATE
)
BEGIN
    INSERT INTO Invoice_Item (Inumber, Hname, Idescription, Price, Date)
    VALUES (in_inumber, in_hname, in_description, in_price, in_date);
END //


-- Toggle an invoice's paid/unpaid status.
CREATE PROCEDURE ChangeStatus(
    IN in_new_status     TINYINT(1),
    IN in_invoice_number INT
)
BEGIN
    UPDATE Invoice
    SET Status = in_new_status
    WHERE Number = in_invoice_number;
END //

DELIMITER ;

-- =============================================================================
-- VIEWS  (Admin reporting queries for use by a Farrier)
-- =============================================================================

-- Number of clients with at least one horse at each barn.
CREATE OR REPLACE VIEW Num_clients_by_barn AS
SELECT B.Bname AS Barn,
       COUNT(DISTINCT C.Cusername) AS Num_of_clients
FROM Barn B
JOIN Horse  H ON B.Bname     = H.Bname
JOIN Client C ON C.Cusername = H.Cusername
GROUP BY B.Bname;

-- Number of horses at each barn.
CREATE OR REPLACE VIEW Num_horses_by_barn AS
SELECT B.Bname AS Barn,
       COUNT(DISTINCT H.Hname) AS Num_of_horses
FROM Barn B
JOIN Horse H ON B.Bname = H.Bname
GROUP BY B.Bname;

-- =============================================================================
-- SEED DATA FOR INITIAL SETUP
-- =============================================================================

-- Default admin account (change password after first login)

INSERT IGNORE INTO Web_user (Username, Password, Name, Email, Phone_num, User_type)
VALUES ('admin', 'open sesame!', 'Aidan Sloman', 'admin@helpdesk.ca', '(403) 500-l337', 'Admin');
