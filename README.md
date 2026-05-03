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

## Setup Instructions

### 1. Install Laragon
Install Laragon and start Apache + MySQL or XAMPP

### 2. Clone repo
Place project in Web Root: 
For laragon: 
C:\laragon\www\ballroom

### 3. Create database
Open MySQL Workbench or phpMyAdmin and run:

RUN FILE:
database/createBallroom.sql

or 

File -> Open SQL Script -> Select createBallroom.sql -> Execute

### 4. Configure database
Copy:
config.example.php → config.php

Then edit:
DB_PASS = your MySQL password

### 6. Run project
Open:
http://localhost/ballroom


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
