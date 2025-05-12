CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    message TEXT,
    resume_path VARCHAR(255) NOT NULL,
    cover_letter_path VARCHAR(255),
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_application_date ON applications(application_date);

CREATE INDEX idx_email ON applications(email);

ALTER TABLE applications ADD COLUMN status VARCHAR(50) DEFAULT 'Pending' AFTER application_date;

ALTER TABLE applications ADD COLUMN cancellation_reason TEXT DEFAULT NULL;

ALTER TABLE applications ADD COLUMN last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

CREATE INDEX idx_status ON applications(status);