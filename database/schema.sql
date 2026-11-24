CREATE DATABASE IF NOT EXISTS employment_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE employment_management;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','employer','employee') NOT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employees (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  name VARCHAR(100) NOT NULL,
  gender ENUM('male','female','other') NULL,
  phone VARCHAR(30) NULL,
  department VARCHAR(100) NULL,
  position VARCHAR(100) NULL,
  address VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE employers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  company_name VARCHAR(150) NOT NULL,
  phone VARCHAR(30) NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE jobs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employer_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  requirements TEXT NULL,
  deadline DATE NOT NULL,
  status ENUM('open','closed') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (employer_id) REFERENCES employers(id) ON DELETE CASCADE
);

CREATE TABLE applications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id INT UNSIGNED NOT NULL,
  applicant_id INT UNSIGNED NOT NULL,
  status ENUM('pending','reviewed','accepted','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_application(job_id, applicant_id),
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (applicant_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE attendance (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,
  attendance_date DATE NOT NOT NULL,
  status ENUM('present','absent','late','leave') NOT NULL,
  notes VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY employee_day(employee_id, attendance_date),
  FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

INSERT INTO users (name,email,password,role) VALUES
('System Administrator','admin@ems.local','$2y$10$gI9E0C5V5W5X8R6K1dZ3VOP3dU5m8rjQv3j8Z6oH0Zl2s1u0p5Y2C','admin'),
('Demo Employer','employer@ems.local','$2y$10$gI9E0C5V5W5X8R6K1dZ3VOP3dU5m8rjQv3j8Z6oH0Zl2s1u0p5Y2C','employer'),
('Demo Employee','employee@ems.local','$2y$10$gI9E0C5V5W5X8R6K1dZ3VOP3dU5m8rjQv3j8Z6oH0Zl2s1u0p5Y2C','employee');

INSERT INTO employers (user_id,company_name,phone)
SELECT id,'Demo Company','+000000000' FROM users WHERE email='employer@ems.local';

INSERT INTO employees (user_id,name,gender,phone,department,position)
SELECT id,'Demo Employee','other','+000000000','Operations','Assistant' FROM users WHERE email='employee@ems.local';
