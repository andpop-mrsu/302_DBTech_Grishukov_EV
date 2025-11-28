INSERT OR IGNORE INTO users (name, email, gender, register_date, occupation_id)
VALUES
('Гришуков Егор', 'egor@mail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
('Данькин Иван', 'ivan@mail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
('Ермаков Егор', 'egor_ermak@mail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
('Кармазов Никита', 'nikita@mail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
('Китаев Евгений', 'evgeniy@gmail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1));



INSERT OR IGNORE INTO movies (title, year)
VALUES
('Побег из Шоушенка', 1994),
('Криминальное чтиво', 1994),
('Матрица', 1999);


INSERT OR IGNORE INTO genres (name) VALUES ('Drama');
INSERT OR IGNORE INTO genres (name) VALUES ('Crime');
INSERT OR IGNORE INTO genres (name) VALUES ('Sci-Fi');

INSERT OR IGNORE INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id FROM movies m JOIN genres g ON g.name = 'Drama'
WHERE m.title = 'Побег из Шоушенка';

INSERT OR IGNORE INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id FROM movies m JOIN genres g ON g.name = 'Crime'
WHERE m.title = 'Криминальное чтиво';

INSERT OR IGNORE INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id FROM movies m JOIN genres g ON g.name = 'Sci-Fi'
WHERE m.title = 'Матрица';


INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.9, strftime('%s','now')
FROM users u JOIN movies m ON m.title = 'Побег из Шоушенка'
WHERE u.email = 'egor@mail.com'
AND NOT EXISTS (
    SELECT 1 FROM ratings r WHERE r.user_id = u.id AND r.movie_id = m.id
);

INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 5.0, strftime('%s','now')
FROM users u JOIN movies m ON m.title = 'Криминальное чтиво'
WHERE u.email = 'egor@mail.com'
AND NOT EXISTS (
    SELECT 1 FROM ratings r WHERE r.user_id = u.id AND r.movie_id = m.id
);

INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.8, strftime('%s','now')
FROM users u JOIN movies m ON m.title = 'Матрица'
WHERE u.email = 'egor@mail.com'
AND NOT EXISTS (
    SELECT 1 FROM ratings r WHERE r.user_id = u.id AND r.movie_id = m.id
);