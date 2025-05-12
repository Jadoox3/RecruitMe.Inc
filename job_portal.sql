CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    position VARCHAR(100) NOT NULL,
    application_date DATE NOT NULL
);

INSERT INTO applications (first_name, last_name, position, application_date) VALUES
    ('John', 'Doe', 'Software Developer', '2024-01-15'),
    ('Jane', 'Smith', 'Project Manager', '2024-01-16'),
    ('Michael', 'Johnson', 'Data Analyst', '2024-01-17'),
    ('Emily', 'Brown', 'UI/UX Designer', '2024-01-18'),
    ('David', 'Wilson', 'Software Engineer', '2024-01-19'),
    ('Sarah', 'Davis', 'Product Manager', '2024-01-20'),
    ('James', 'Miller', 'Full Stack Developer', '2024-01-21'),
    ('Lisa', 'Anderson', 'Business Analyst', '2024-01-22'),
    ('Robert', 'Taylor', 'DevOps Engineer', '2024-01-23'),
    ('Jennifer', 'Thomas', 'Frontend Developer', '2024-01-24'),
    ('William', 'Moore', 'Backend Developer', '2024-01-25'),
    ('Elizabeth', 'Jackson', 'System Administrator', '2024-01-26'),
    ('Richard', 'White', 'Database Administrator', '2024-01-27'),
    ('Mary', 'Harris', 'QA Engineer', '2024-01-28'),
    ('Joseph', 'Martin', 'Mobile Developer', '2024-01-29');