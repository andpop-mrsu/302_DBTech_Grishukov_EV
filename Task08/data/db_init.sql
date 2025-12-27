BEGIN TRANSACTION;

DROP TABLE IF EXISTS exams;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS groups;

CREATE TABLE groups (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  group_number TEXT NOT NULL UNIQUE
);

CREATE TABLE students (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  first_name TEXT NOT NULL,
  last_name TEXT NOT NULL,
  group_id INTEGER NOT NULL,
  gender TEXT CHECK(gender IN ('male', 'female')) NOT NULL,
  FOREIGN KEY (group_id) REFERENCES groups(id)
);

CREATE TABLE subjects (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  group_number TEXT NOT NULL,
  course INTEGER NOT NULL CHECK(course >= 1 AND course <= 5),
  FOREIGN KEY (group_number) REFERENCES groups(group_number)
);

CREATE TABLE exams (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  student_id INTEGER NOT NULL,
  subject_id INTEGER NOT NULL,
  exam_date DATE NOT NULL,
  grade INTEGER CHECK(grade >= 2 AND grade <= 5),
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

INSERT INTO groups (group_number) VALUES 
  ('101'),
  ('102'),
  ('201'),
  ('202'),
  ('301'),
  ('302');

INSERT INTO subjects (name, group_number, course) VALUES
  ('Математика', '101', 1),
  ('Информатика', '101', 1),
  ('Физика', '101', 1),
  ('Математика', '101', 2),
  ('Программирование', '101', 2),
  ('Математика', '102', 1),
  ('Информатика', '102', 1),
  ('Математика', '201', 2),
  ('Базы данных', '201', 2),
  ('Математика', '302', 3),
  ('Веб-технологии', '302', 3);

INSERT INTO students (first_name, last_name, group_id, gender) VALUES
  ('Иван', 'Иванов', 1, 'male'),
  ('Мария', 'Петрова', 1, 'female'),
  ('Алексей', 'Сидоров', 2, 'male'),
  ('Анна', 'Козлова', 2, 'female'),
  ('Дмитрий', 'Смирнов', 3, 'male');

INSERT INTO exams (student_id, subject_id, exam_date, grade) VALUES
  (1, 1, '2024-01-15', 5),
  (1, 2, '2024-01-20', 4),
  (2, 1, '2024-01-15', 5),
  (2, 2, '2024-01-20', 5),
  (3, 6, '2024-01-16', 4);

COMMIT;

