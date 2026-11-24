# Employment Management System MVP

A PHP/MySQL MVP for employee records, recruitment, job applications, attendance, dashboards, and reports.

## Requirements

- PHP 8.1+
- MySQL 8 or MariaDB
- Apache/WAMP/XAMPP
- Bootstrap 5 via CDN

## Installation

1. Copy this project into your XAMPP `htdocs` directory.
2. Import `database/schema.sql` in phpMyAdmin.
3. Copy `config/config.example.php` to `config/config.php` and update the database credentials.
4. Open `http://localhost/employment-management-system/`.

## Demo accounts

Accounts are created when `schema.sql` is imported:

- Administrator: `admin@ems.local` / `Admin@123`
- Employer: `employer@ems.local` / `Employer@123`
- Employee: `employee@ems.local` / `Employee@123`

Change these passwords after the first login.
