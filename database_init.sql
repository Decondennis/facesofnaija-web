-- ========================================
-- FacesOfNaija Database Initialization
-- ========================================
-- This script creates the database and user for FacesOfNaija
-- Run this before importing the schema

-- Create Database
CREATE DATABASE IF NOT EXISTS facesofnaija 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Create User (Change password for security)
CREATE USER IF NOT EXISTS 'facesofnaija_user'@'localhost' 
IDENTIFIED BY 'facesofnaija_pass123';

-- Grant Privileges
GRANT ALL PRIVILEGES ON facesofnaija.* 
TO 'facesofnaija_user'@'localhost';

-- Flush Privileges
FLUSH PRIVILEGES;

-- Use Database
USE facesofnaija;

-- Show success message
SELECT 'Database and user created successfully!' AS Status;
SELECT 'Now import the full database schema and data' AS NextStep;

-- Display database information
SELECT 
    'facesofnaija' AS DatabaseName,
    'facesofnaija_user' AS Username,
    'localhost' AS Host,
    'utf8mb4_unicode_ci' AS Collation;
