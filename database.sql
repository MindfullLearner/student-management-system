-- ============================================
-- Student Management System - Database Schema
-- ============================================

CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- ---------------------------------------------
-- Table: users  (handles authentication + role-based login)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,          -- stored using PHP password_hash()
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Table: students  (core data managed by the system)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    course VARCHAR(100),
    enrollment_date DATE,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Seed data: one admin account (password = "Admin@123")
-- ---------------------------------------------
INSERT INTO users (full_name, email, password, role)
VALUES (
    'System Admin',
    'admin@devixo.com',
    '$2b$10$Mdrj2/QKp8r0xxXX9NevB.pZT06y2OD0H.qSjdeIIjfieCwecHxC6', -- this is the real hash for "Admin@123"
    'admin'
);

-- Indexes to speed up search & pagination
CREATE INDEX idx_students_name ON students(full_name);
CREATE INDEX idx_students_email ON students(email);
