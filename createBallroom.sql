/********************************************************
* This script creates the database named ballroom 
*********************************************************/

DROP DATABASE IF EXISTS ballroom;
CREATE DATABASE ballroom;
USE ballroom;

-- =========================
-- Tables
-- =========================
CREATE TABLE Affiliations (
    affiliation_id INT AUTO_INCREMENT PRIMARY KEY,
    affiliation_name VARCHAR(100) NOT NULL,
    affiliation_type VARCHAR(100) NOT NULL,
	UNIQUE (affiliation_name, affiliation_type),
    CHECK (affiliation_type IN ('University', 'Studio', 'Independent'))
);

CREATE TABLE Dancers (
    dancer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    student_status BOOLEAN NOT NULL,
    affiliation_id INT,
    FOREIGN KEY (affiliation_id) 
		REFERENCES Affiliations(affiliation_id) 
        ON DELETE SET NULL
);     


CREATE TABLE Partnerships (
    partnership_id INT AUTO_INCREMENT PRIMARY KEY,
    leader_id INT NOT NULL,
    follower_id INT NOT NULL,
    FOREIGN KEY (leader_id) 
		REFERENCES Dancers(dancer_id) ON DELETE CASCADE,
    FOREIGN KEY (follower_id) 
		REFERENCES Dancers(dancer_id) ON DELETE CASCADE,
    UNIQUE (leader_id, follower_id),
    CHECK (leader_id <> follower_id)
);

CREATE TABLE Competitions (
    competition_id INT AUTO_INCREMENT PRIMARY KEY,
    competition_name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    competition_date DATE NOT NULL,
    early_deadline DATE NOT NULL,
    regular_deadline DATE NOT NULL,
    late_deadline DATE NOT NULL,
    early_fee INT NOT NULL DEFAULT 50,
    regular_fee INT NOT NULL DEFAULT 60,
    late_fee INT NOT NULL DEFAULT 70
);

CREATE TABLE Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    competition_id INT NOT NULL,
    
    dance_name VARCHAR(50) NOT NULL,
    level VARCHAR(50) NOT NULL,
    style VARCHAR(50) NOT NULL,

    FOREIGN KEY (competition_id) 
		REFERENCES Competitions(competition_id) ON DELETE CASCADE,

    CHECK (style IN ('Smooth', 'Standard', 'Rhythm', 'Latin')),

    CHECK (level IN (
        'Newcomer','Bronze','Silver','Gold',
        'Novice','Pre-Champ','Champ', 'Syllabus'
    )),

    CHECK (dance_name IN (
        'Waltz','Tango','Foxtrot','Viennese Waltz',
        'Quickstep','Cha Cha','Rumba','Swing',
        'Bolero','Mambo','Samba','Paso Doble','Jive'
    )),

    UNIQUE (competition_id, dance_name, level, style)
);

CREATE TABLE Registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    partnership_id INT NOT NULL,
    registration_type VARCHAR(20) NOT NULL,
    fee_pay INT,
    FOREIGN KEY (event_id) 
		REFERENCES Events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (partnership_id) 
		REFERENCES Partnerships(partnership_id) ON DELETE CASCADE,
    UNIQUE (event_id, partnership_id)
);

CREATE TABLE Results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL UNIQUE,
    placement INT,

    FOREIGN KEY (registration_id)
        REFERENCES Registrations(registration_id)
        ON DELETE CASCADE,

    CHECK (placement IS NULL OR placement >= 0)
);

USE ballroom;


-- =========================
-- FUNCTION (Fee Logic)
-- =========================
DROP FUNCTION IF EXISTS get_base_fee;
DELIMITER //
CREATE FUNCTION get_base_fee(p_event_id INT, p_type VARCHAR(20))
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE fee INT;
    SELECT 
        CASE p_type
            WHEN 'Early' THEN c.early_fee
            WHEN 'Regular' THEN c.regular_fee
            ELSE c.late_fee
        END
    INTO fee
    FROM Events e
    JOIN Competitions c ON e.competition_id = c.competition_id
    WHERE e.event_id = p_event_id;

    RETURN COALESCE(fee, 0);
END//
DELIMITER ;

#discount check 
DROP FUNCTION IF EXISTS apply_student_discount;
DELIMITER //

CREATE FUNCTION apply_student_discount(p_partnership_id INT, p_fee INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE has_student BOOLEAN DEFAULT FALSE;

    SELECT 
        CASE 
            WHEN d1.student_status = 1 OR d2.student_status = 1 THEN TRUE
            ELSE FALSE
        END
    INTO has_student
    FROM Partnerships p
    JOIN Dancers d1 ON p.leader_id = d1.dancer_id
    JOIN Dancers d2 ON p.follower_id = d2.dancer_id
    WHERE p.partnership_id = p_partnership_id;
	#$20 disocunt
    IF has_student THEN
        RETURN p_fee - 20;
    ELSE
        RETURN p_fee;
    END IF;

END//

DELIMITER ;


-- =========================
-- TRIGGER (Auto Fee)
-- =========================
DROP TRIGGER IF EXISTS before_registration_insert;
DELIMITER //
CREATE TRIGGER before_registration_insert
BEFORE INSERT ON Registrations
FOR EACH ROW
BEGIN
    DECLARE base_fee INT;
    SET base_fee = get_base_fee(NEW.event_id, NEW.registration_type);
    SET NEW.fee_pay = apply_student_discount(
        NEW.partnership_id,
        base_fee
    );
END//
DELIMITER ;

# update trigger
DROP TRIGGER IF EXISTS before_registration_update;
DELIMITER //
CREATE TRIGGER before_registration_update
BEFORE UPDATE ON Registrations
FOR EACH ROW
BEGIN
    DECLARE base_fee INT;
    SET base_fee = get_base_fee(NEW.event_id, NEW.registration_type);
    SET NEW.fee_pay = apply_student_discount(
        NEW.partnership_id,
        base_fee
    );
END//
DELIMITER ;


-- =========================
-- PROCEDURE (Registration)
-- =========================
DROP PROCEDURE IF EXISTS RegisterPartnershipSafe;
DELIMITER //
CREATE PROCEDURE RegisterPartnershipSafe(
    IN p_event INT,
    IN p_partnership INT,
    IN p_type VARCHAR(20)
)
BEGIN
    DECLARE leader INT;
    DECLARE follower INT;
    DECLARE duplicate_pair INT DEFAULT 0;
    DECLARE dancer_conflict INT DEFAULT 0;

    #get dancers
    SELECT leader_id, follower_id
    INTO leader, follower
    FROM Partnerships
    WHERE partnership_id = p_partnership;

    #exact partnership already in event
    SELECT COUNT(*)
    INTO duplicate_pair
    FROM Registrations
    WHERE event_id = p_event
      AND partnership_id = p_partnership;
    IF duplicate_pair > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'This partnership is already registered for this event';
    END IF;

    #ANY dancer already in event (leader or follower anywhere)
    SELECT COUNT(*)
    INTO dancer_conflict
    FROM Registrations r
    JOIN Partnerships p ON r.partnership_id = p.partnership_id
    WHERE r.event_id = p_event
      AND (
            p.leader_id IN (leader, follower)
         OR p.follower_id IN (leader, follower)
      );
    IF dancer_conflict > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'One of the dancers is already registered in this event';
    END IF;

    #insert
    INSERT INTO Registrations(event_id, partnership_id, registration_type)
    VALUES (p_event, p_partnership, p_type);
END//
DELIMITER ;


-- =========================
-- VIEW (REPORTS)
-- =========================

CREATE OR REPLACE VIEW dancer_summary AS
SELECT 
    d.dancer_id,
    d.first_name,
    d.last_name,
    d.student_status,
    COUNT(r.registration_id) AS total_entries
FROM Dancers d
LEFT JOIN Partnerships p 
    ON d.dancer_id = p.leader_id OR d.dancer_id = p.follower_id
LEFT JOIN Registrations r 
    ON p.partnership_id = r.partnership_id
GROUP BY d.dancer_id;


CREATE OR REPLACE VIEW competition_event_summary AS
SELECT 
    c.competition_id,
    c.competition_name,
    e.event_id,
    e.dance_name,
    e.style,
    e.level,
    COUNT(r.registration_id) AS total_partnerships_registered
FROM Competitions c
JOIN Events e 
    ON c.competition_id = e.competition_id
LEFT JOIN Registrations r 
    ON e.event_id = r.event_id
GROUP BY 
    c.competition_id,
    c.competition_name,
    e.event_id,
    e.dance_name,
    e.style,
    e.level;


CREATE OR REPLACE VIEW registration_view AS
SELECT  
    r.registration_id,
    e.event_id,
    e.dance_name,
    e.style,
    e.level,
    c.competition_name,
    d1.first_name AS leader_first,
    d1.last_name AS leader_last,
    d2.first_name AS follower_first,
    d2.last_name AS follower_last,
    r.registration_type,
    COALESCE(r.fee_pay, 0) AS fee_pay
FROM Registrations r
JOIN Events e ON r.event_id = e.event_id
JOIN Competitions c ON e.competition_id = c.competition_id
JOIN Partnerships p ON r.partnership_id = p.partnership_id
JOIN Dancers d1 ON p.leader_id = d1.dancer_id
JOIN Dancers d2 ON p.follower_id = d2.dancer_id;

CREATE OR REPLACE VIEW results_view AS
SELECT  
    r.result_id,
    r.placement,

    reg.registration_id,
    e.event_id,
    e.dance_name,
    e.style,
    e.level,
    c.competition_name,

    p.partnership_id,

    d1.first_name AS leader_first,
    d1.last_name AS leader_last,
    d2.first_name AS follower_first,
    d2.last_name AS follower_last,

    CASE 
        WHEN r.placement IS NULL OR r.placement = 0 THEN 'Non-final'
        ELSE CAST(r.placement AS CHAR)
    END AS placement_display

FROM Results r
JOIN Registrations reg ON r.registration_id = reg.registration_id
JOIN Events e ON reg.event_id = e.event_id
JOIN Competitions c ON e.competition_id = c.competition_id
JOIN Partnerships p ON reg.partnership_id = p.partnership_id
JOIN Dancers d1 ON p.leader_id = d1.dancer_id
JOIN Dancers d2 ON p.follower_id = d2.dancer_id;

-- =========================
-- TEST Samples
-- =========================
INSERT INTO Affiliations (affiliation_name, affiliation_type) VALUES
('NYU', 'University'),
('Columbia', 'University'),
('5BB', 'Studio'),
('Sunset Park', 'Independent');

INSERT INTO Dancers (first_name, last_name, student_status, affiliation_id) VALUES
('Melanie', 'Andrade', 1, 1),
('Marco', 'Tortolani', 0, 3),
('Fanni', 'Miet', 1, 2),
('Hyun', 'Lee', 0, NULL),
('Brian', 'Lim', 0, 4),
('Sofia', 'Watts', 0, 4);

INSERT INTO Competitions (competition_name, location, competition_date, 
early_deadline, regular_deadline, late_deadline, early_fee,
    regular_fee, late_fee) VALUES
('BADC', 'Columbia University', '2026-04-10', '2026-03-01', '2026-03-20', '2026-04-01',
50,60,70),
('NYDF', 'NYC', '2026-05-10', '2026-04-01', '2026-04-20', '2026-05-01',
55,65,75);

INSERT INTO Events (competition_id, dance_name, level, style) VALUES
(1, 'Waltz', 'Bronze', 'Standard'),
(1, 'Cha Cha', 'Silver', 'Latin');

INSERT INTO Partnerships (leader_id, follower_id) VALUES
(2, 1), (2,4), (3, 4), (5,6);

INSERT INTO Registrations (event_id, partnership_id, registration_type)
VALUES (1, 1, 'Early');

INSERT INTO Results (registration_id, placement)
VALUES (1, 1);
