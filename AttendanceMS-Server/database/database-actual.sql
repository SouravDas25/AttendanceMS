-- DROPs---
DROP TABLE IF EXISTS attendence;
DROP TABLE IF EXISTS active_day;
DROP TABLE IF EXISTS lt_student_attendance;
DROP TABLE IF EXISTS lt_teacher_report;
DROP TABLE IF EXISTS reset_password;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS subject;
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS student_batch;
DROP TABLE IF EXISTS batch;
DROP TABLE IF EXISTS dept;
DROP TABLE IF EXISTS misc;


-- DEPT TABLE ---


CREATE TABLE IF NOT EXISTS dept (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(15) NOT NULL UNIQUE,
    full_name varchar(255) NOT NULL,
    course_years INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (id)
);

DELIMITER $$
CREATE TRIGGER dept_before_insert BEFORE INSERT ON dept
FOR EACH ROW
BEGIN
    IF NEW.course_years <= 0 THEN
        SIGNAL sqlstate '45000' 
        SET MESSAGE_TEXT = 'check constraint on dept.course_years failed. dept.course_years should be greater than 0';
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER dept_before_update BEFORE UPDATE ON dept
FOR EACH ROW
BEGIN
    IF NEW.course_years <= 0 THEN
        SIGNAL sqlstate '45000' 
        SET MESSAGE_TEXT = 'check constraint on dept.course_years failed. dept.course_years should be greater than 0';
    END IF;
END $$
DELIMITER ;

-- BATCH TABLE ---


CREATE TABLE IF NOT EXISTS batch (
  batch_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  sem_no int(11) UNSIGNED NOT NULL,
  dept_id int(11) UNSIGNED NOT NULL REFERENCES dept(id) ,

  PRIMARY KEY (batch_id),
  CONSTRAINT FK_batch_dept_id FOREIGN KEY (dept_id) REFERENCES dept(id)
  ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT UC_batch_dept_id_sem_no UNIQUE (sem_no,dept_id)
);

-- STUDENT_BATCH TABLE ---


CREATE TABLE IF NOT EXISTS student_batch (
  batch_no int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  start_date date NOT NULL,
  dept_id int(11) UNSIGNED NOT NULL,

  PRIMARY KEY (batch_no),
  CONSTRAINT FK_student_batch_dept_id FOREIGN KEY (dept_id) REFERENCES dept(id)
  ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- STUDENT TABLE ---


CREATE TABLE IF NOT EXISTS student (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  roll_no int(11) UNSIGNED NOT NULL,
  batch_no int(11) UNSIGNED NOT NULL,

  PRIMARY KEY (id),
  CONSTRAINT UC_student UNIQUE (roll_no,batch_no),
  CONSTRAINT FK_student_batch_no FOREIGN KEY(batch_no) REFERENCES student_batch(batch_no) 
  ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- SUBJECT TABLE ---


CREATE TABLE IF NOT EXISTS subject (
  code varchar(20) NOT NULL UNIQUE,
  name varchar(100) NOT NULL,
  batch_id int(11) UNSIGNED NOT NULL,

  PRIMARY KEY (code),
  CONSTRAINT FK_subject_batch_id FOREIGN KEY(batch_id) REFERENCES batch(batch_id) 
  ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- USERS TABLE ---


CREATE TABLE IF NOT EXISTS users (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL UNIQUE,
  password varchar(255) NOT NULL,
  phn_no varchar(10) DEFAULT NULL UNIQUE,
  user_type varchar(15) NOT NULL,
  remember_token varchar(255) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  dept_id int(11) UNSIGNED DEFAULT NULL,

  PRIMARY KEY (id),
  CONSTRAINT FK_user_dept_id FOREIGN KEY (dept_id) REFERENCES dept(id)
  ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- ACTIVE_DAY TABLE ---

CREATE TABLE IF NOT EXISTS active_day (
  active_day_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  class_date date NOT NULL,
  class_topic varchar(127) NOT NULL,
  class_remarks varchar(255) NOT NULL,
  teacher_id int(11) UNSIGNED NOT NULL,
  subject_code varchar(15) NOT NULL,
  batch_no int(11) UNSIGNED NOT NULL,
  mark_count int(11) UNSIGNED NOT NULL,

  PRIMARY KEY (active_day_id),
  CONSTRAINT FK_active_day_teacher_id FOREIGN KEY (teacher_id) REFERENCES users(id)
  ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_active_day_subject_code FOREIGN KEY (subject_code) REFERENCES subject(code)
  ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_active_day_batch_no FOREIGN KEY (batch_no) REFERENCES student_batch(batch_no)
  ON DELETE RESTRICT ON UPDATE RESTRICT
);


-- attendence TABLE ---

CREATE TABLE IF NOT EXISTS attendence (
  student_id int(11) UNSIGNED NOT NULL,
  active_day_id int(11) UNSIGNED  NOT NULL,

  CONSTRAINT UC_student_attendance UNIQUE (student_id,active_day_id),
  CONSTRAINT FK_attendence_active_day_id FOREIGN KEY (active_day_id) REFERENCES active_day(active_day_id)
  ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_attendence_student_id FOREIGN KEY (student_id) REFERENCES student(id)
  ON DELETE RESTRICT ON UPDATE RESTRICT
) ;




          -- LONG TERM --



CREATE TABLE IF NOT EXISTS lt_student_attendance (
  student_id int(11) UNSIGNED NOT NULL ,
  sem_no int(11) NOT NULL,
  attendance int(11) DEFAULT 0,
  total int(11) DEFAULT 0,


  PRIMARY KEY(student_id,sem_no),
  
  CONSTRAINT FK_lt_student_attendance_student_id 
  FOREIGN KEY (student_id) REFERENCES student(id)
  ON DELETE RESTRICT ON UPDATE RESTRICT

);


CREATE TABLE IF NOT EXISTS reset_password (
  user_id int(11) UNSIGNED NOT NULL UNIQUE,
  token varchar(255) Not NULL UNIQUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY(token),
  
  CONSTRAINT FK_lt_reset_password_user_id 
  FOREIGN KEY (user_id) REFERENCES users(id)
  ON DELETE RESTRICT ON UPDATE RESTRICT

);

CREATE TABLE IF NOT EXISTS misc (
  config_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  sem_starting_day INT UNSIGNED NOT NULL,
  sem_starting_month INT UNSIGNED NOT NULL,
  sem_length FLOAT UNSIGNED NOT NULL,

  PRIMARY KEY(config_id)
);
INSERT INTO misc(config_id,sem_starting_day,sem_starting_month,sem_length) VALUES(1,1,7,182.5);


-- VIEWS ---




DROP VIEW IF EXISTS user_view;
DROP VIEW IF EXISTS subject_attn_student_view;
DROP VIEW IF EXISTS suject_attendence_view_1;
DROP VIEW IF EXISTS sub_dept_view_1;
DROP VIEW IF EXISTS student_view_1;
DROP VIEW IF EXISTS student_attendence;
DROP VIEW IF EXISTS dept_view;
DROP VIEW IF EXISTS dept_batch_view_3;
DROP VIEW IF EXISTS class_count;
DROP VIEW IF EXISTS batch_dept_view_2;
DROP VIEW IF EXISTS batch_dept_view_1;
DROP VIEW IF EXISTS active_day_view_1;
DROP VIEW IF EXISTS batch_dept_sem_view;



CREATE VIEW user_view AS
SELECT
  users.id AS user_id,
  users.password AS password,
  users.remember_token AS remember_token,
  users.name AS user_name,
  dept.id AS dept_id,
  dept.name AS dept_name,
  users.email AS email,
  users.phn_no AS phn_no,
  users.user_type AS user_type
FROM
  users
LEFT JOIN
  dept ON users.dept_id = dept.id;




CREATE VIEW student_attendence AS
SELECT
  student.id AS id,
  student.name AS NAME,
  student.roll_no AS roll_no,
  student.batch_no AS batch_no,
  attendence.student_id AS student_id,
  attendence.active_day_id AS active_day_id
FROM
  student
JOIN
  attendence ON student.id = attendence.student_id;





CREATE  VIEW suject_attendence_view_1 AS
SELECT SUM(active_day.mark_count)  AS attn,
  student_attendence.name AS name,
  student_attendence.roll_no AS roll_no,
  student_attendence.student_id AS student_id,
  student_attendence.batch_no AS batch_no,
  active_day.subject_code AS subject_code
FROM
  student_attendence
JOIN
  active_day Using(active_day_id)
GROUP BY
  student_attendence.id,
  active_day.subject_code;



CREATE VIEW batch_dept_view_2 AS
SELECT
  batch.batch_id AS batch_id,
  batch.sem_no AS sem_no,
  dept.name AS dept_name,
  dept.id AS dept_id
FROM
  batch
JOIN
  dept ON dept.id = batch.dept_id
ORDER BY
  dept.name,
  batch.sem_no;





CREATE VIEW sub_dept_view_1 AS
SELECT 
  subject.code AS subject_code,
  subject.name AS subject_name,
  batch_dept_view_2.dept_id AS dept_id,
  batch_dept_view_2.dept_name AS dept_name,
  batch_dept_view_2.sem_no AS sem_no,
  batch_dept_view_2.batch_id AS batch_id
FROM 
  subject
JOIN
  batch_dept_view_2 ON batch_dept_view_2.batch_id = subject.batch_id;


CREATE VIEW dept_batch_view_3 AS
SELECT
  student_batch.batch_no AS batch_no,
  student_batch.start_date AS start_date,
  student_batch.dept_id AS dept_id,
  dept.name AS dept_name,
  FLOOR((TO_DAYS(CURDATE()) - TO_DAYS(student_batch.start_date)) / (SELECT sem_length FROM misc WHERE config_id = 1) ) + 1 AS sem_no,
  dept.course_years AS course_years,
  YEAR(CURDATE()) - YEAR(student_batch.start_date) + 1 AS current_year
FROM
  student_batch
JOIN
  dept ON dept.id = student_batch.dept_id;



CREATE VIEW student_view_1 AS
SELECT
  student.name AS student_name,
  student.roll_no AS student_roll,
  student.id AS student_id,
  student.batch_no AS batch_no,
  dept_batch_view_3.dept_id AS dept_id,
  dept_batch_view_3.dept_name AS student_dept,
  (YEAR(CURDATE()) - YEAR(dept_batch_view_3.start_date)) + 1 AS year
FROM
  student
JOIN
  dept_batch_view_3 ON student.batch_no = dept_batch_view_3.batch_no
ORDER BY
  student.roll_no;



CREATE VIEW dept_view AS
SELECT
  dept.id AS id,
  dept.name AS dept_name,
  dept.full_name AS full_name,
  dept.course_years AS course_years
FROM
  dept;




CREATE VIEW class_count AS
SELECT 
  COUNT(0) AS head_count,
  attendence.active_day_id AS active_day_id
FROM
  active_day
JOIN
  attendence ON attendence.active_day_id = active_day.active_day_id
GROUP BY
  attendence.active_day_id;




CREATE VIEW batch_dept_view_1 AS
SELECT
  student_batch.batch_no AS batch_no,
  YEAR(student_batch.start_date) AS start_date,
  dept.course_years AS course_years,
  dept.name AS dept_name,
  YEAR(CURDATE()) - YEAR(student_batch.start_date) + 1 AS current_year
FROM
  student_batch
JOIN
  dept ON student_batch.dept_id = dept.id;


CREATE VIEW active_day_view_1 AS
SELECT
  active_day.active_day_id AS active_day_id,
  user_view.user_id AS teacher_id,
  user_view.user_name AS teacher_name,
  sub_dept_view_1.subject_name AS subject_name,
  sub_dept_view_1.subject_code AS subject_code,
  active_day.class_date AS class_date,
  active_day.class_topic AS class_topic,
  active_day.class_remarks AS class_remarks,
  dept_batch_view_3.dept_name AS dept_name,
  (YEAR(CURDATE()) - YEAR(dept_batch_view_3.start_date)) + 1 AS batch_current_year,
  active_day.batch_no AS batch_no,
  class_count.head_count AS head_count,
  active_day.mark_count
FROM
    active_day
JOIN
    user_view ON user_view.user_id = active_day.teacher_id
JOIN
    sub_dept_view_1 ON sub_dept_view_1.subject_code = active_day.subject_code
JOIN
    dept_batch_view_3 ON dept_batch_view_3.batch_no = active_day.batch_no
LEFT JOIN
    class_count ON class_count.active_day_id = active_day.active_day_id;



CREATE VIEW subject_attn_student_view AS
SELECT
    student.id AS student_id,
    student.name AS name,
    student.roll_no AS roll_no,
    student.batch_no AS batch_no,
    attendence.active_day_id AS active_day_id,
    active_day_view_1.class_date AS class_date,
    active_day_view_1.teacher_id AS teacher_id,
    active_day_view_1.teacher_name AS teacher_name,
    active_day_view_1.subject_name AS subject_name,
    active_day_view_1.subject_code AS subject_code,
    active_day_view_1.class_topic AS class_topic,
    active_day_view_1.class_remarks AS class_remarks,
    active_day_view_1.dept_name AS dept_name,
    active_day_view_1.batch_current_year AS batch_current_year,
    active_day_view_1.head_count AS head_count,
    active_day_view_1.mark_count AS mark_count
FROM
    student
JOIN
    attendence
ON
    student.id = attendence.student_id
JOIN
    active_day_view_1
ON
    active_day_view_1.active_day_id = attendence.active_day_id;





CREATE VIEW batch_dept_sem_view AS
SELECT
  a.batch_no AS batch_no,
  a.start_date AS start_date,
  a.dept_id AS dept_id,
  a.dept_name AS dept_name,
  b.sem_no AS sem_no,
  a.course_years AS course_years,
  a.current_year AS current_year,
  b.batch_id AS batch_id
FROM
  dept_batch_view_3 a
LEFT JOIN
  batch_dept_view_2 b ON(
    (a.dept_id = b.dept_id) AND (a.sem_no = b.sem_no)
  )
;



