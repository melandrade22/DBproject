/********************************************************
* This script creates the database named ballroom 
*********************************************************/

DROP DATABASE IF EXISTS ballroom;
CREATE DATABASE ballroom;
USE ballroom;

CREATE TABLE IF NOT EXISTS Dancers (
    dancer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    student_status BOOLEAN NOT NULL
);

INSERT INTO Dancers (first_name, last_name, student_status)
VALUES 
('Melanie', 'Andrade', 1), ('John', 'Doe', 0), ('Jane', 'Smith', 1); 

SELECT * FROM Dancers;       


-- CREATE TABLE IF NOT EXISTS Affiliations (
--     affiliation_id INT AUTO_INCREMENT PRIMARY KEY,
--     affiliation_name VARCHAR(100) NOT NULL,
--     affiliation_type VARCHAR(50) NOT NULL
-- );
-- 
-- CREATE TABLE IF NOT EXISTS Partnerships (
--     partnership_id INT AUTO_INCREMENT PRIMARY KEY,
--     leader_id INT NOT NULL,
--     follower_id INT NOT NULL,
--     affiliation_id INT NOT NULL,
--     FOREIGN KEY (leader_id) REFERENCES Dancers(dancer_id),
--     FOREIGN KEY (follower_id) REFERENCES Dancers(dancer_id),
--     FOREIGN KEY (affiliation_id) REFERENCES Affiliations(affiliation_id)
-- );
-- 
-- CREATE TABLE IF NOT EXISTS Partnerships (
--     partnership_id INT AUTO_INCREMENT PRIMARY KEY,
--     leader_id INT NOT NULL,
--     follower_id INT NOT NULL,
--     affiliation_id INT NOT NULL,
--     FOREIGN KEY (leader_id) REFERENCES Dancers(dancer_id),
--     FOREIGN KEY (follower_id) REFERENCES Dancers(dancer_id),
--     FOREIGN KEY (affiliation_id) REFERENCES Affiliations(affiliation_id)
-- );
-- 
-- CREATE TABLE IF NOT EXISTS Competitions (
--     competition_id INT AUTO_INCREMENT PRIMARY KEY,
--     competition_name VARCHAR(100) NOT NULL,
--     location VARCHAR(100) NOT NULL,
--     competition_date DATE NOT NULL,
--     early_deadline DATE NOT NULL,
--     regular_deadline DATE NOT NULL,
--     late_deadline DATE NOT NULL
-- );
-- 
-- CREATE TABLE IF NOT EXISTS Events (
--     event_id INT AUTO_INCREMENT PRIMARY KEY,
--     competition_id INT NOT NULL,
--     dance_name VARCHAR(50) NOT NULL,
--     level VARCHAR(50) NOT NULL,
--     style VARCHAR(50) NOT NULL,
--     FOREIGN KEY (competition_id) REFERENCES Competitions(competition_id)
-- );
-- 
-- CREATE TABLE IF NOT EXISTS Registrations (
--     registration_id INT AUTO_INCREMENT PRIMARY KEY,
--     event_id INT NOT NULL,
--     partnership_id INT NOT NULL,
--     registration_type VARCHAR(20) NOT NULL,
--     fee_paid DECIMAL(6,2),
--     FOREIGN KEY (event_id) REFERENCES Events(event_id),
--     FOREIGN KEY (partnership_id) REFERENCES Partnerships(partnership_id)
-- );
-- 
-- CREATE TABLE IF NOT EXISTS Results (
--     result_id INT AUTO_INCREMENT PRIMARY KEY,
--     event_id INT NOT NULL,
--     partnership_id INT NOT NULL,
--     placement INT NOT NULL,
--     FOREIGN KEY (event_id) REFERENCES Events(event_id),
--     FOREIGN KEY (partnership_id) REFERENCES Partnerships(partnership_id)
-- );