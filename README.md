# Ballroom Database System

## Overview
The Ballroom Database System is a full-stack web application designed to manage ballroom dance competitions. It tracks dancers, affiliations, partnerships, competitions, event registrations, and final results.

The system is built using:
- MySQL (relational database)
- PHP (backend CRUD operations)
- HTML/CSS (frontend UI)
- Laragon (local development environment)

---

## Features

- Full CRUD functionality for all entities:
  - Dancers
  - Affiliations
  - Partnerships
  - Competitions
  - Events
  - Registrations
  - Results

- Automated fee calculation using SQL functions and triggers
- Safe registration validation using stored procedures
- Result tracking tied to valid registrations
- Reporting views for simplified data display
- Enforced data integrity using constraints and relationships

---

## How to Run the Project (Local Setup - Laragon)

This project was developed using **Laragon** as a local development environment.

### Setup Steps:

1. Install and open Laragon
2. Start **Apache** and **MySQL**
3. create dbBallroom folder
4. Place the project folder inside: laragon/www/dbBallroom
5. Import the database:
- Open phpMyAdmin OR MySQL console
- Run the provided `ballroom.sql` file
6. Open the project in your browser: http://localhost/index.php


---

## Database Design

The system consists of **7 main tables**:

- Affiliations
- Dancers
- Partnerships
- Competitions
- Events
- Registrations
- Results

### Key Relationships:
- Dancers belong to Affiliations
- Partnerships consist of two Dancers
- Registrations link Partnerships and Events
- Results are linked to Registrations


## Integrity Enforcement

The system uses multiple layers of integrity control:

- Primary Keys: Ensure unique records
- Foreign Keys: Maintain relationships between tables
- UNIQUE Constraints: Prevent duplicate registrations
- CHECK Constraints: Enforce valid values (levels, styles, types)
- Triggers: Automate fee calculation
- Stored Procedures: Prevent invalid registrations



## ER Diagram

The ER diagram is included in this repository:/assets/er-diagram.png
