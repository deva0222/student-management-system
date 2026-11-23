CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

CREATE TABLE students (
    roll_no INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    academic_year VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no INT NOT NULL,
    subject_name VARCHAR(50) NOT NULL,
    FOREIGN KEY (roll_no) REFERENCES students(roll_no)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO students VALUES (1, 'Rahul', '2025-26');
INSERT INTO students VALUES (2, 'Priya', '2025-26');
INSERT INTO subjects (roll_no, subject_name) VALUES (1, 'Maths'), (1, 'Physics'), (2, 'Chemistry');
