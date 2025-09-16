## Структура данных
### dataset/

- `movies.csv` — фильмы
  - Поля:
    - `movieId`: уникальный id фильма.
    - `title`: название фильма.
    - `genres`: жанры через `|`; значения из `genres.txt`.

- `ratings.csv` — оценки 
  - Поля:
    - `userId`: id пользователя.
    - `movieId`: id фильма.
    - `rating`: числовая оценка.
    - `timestamp`: время оценки.

- `tags.csv` — теги к фильмам
  - Поля: `userId`, `movieId`, `tag`, `timestamp`.

- `genres.txt` — справочник жанров
  - По одному жанру на строку; помогает валидировать/нормализовать `movies.csv.genres`.

- `occupation.txt` — справочник профессий
  - По одной профессии на строку; используется в `users.txt`.

- `users.txt` — пользователи, разделитель `|`
  - Поля:
    - `userId`, `name`, `email`, `gender`, `registered_at`, `occupation`.

### Файлы задания

- `ratings_count.txt` — содержит две строки по данным `ratings.csv`:
  1) минимальный `userId` и количество строк с этим `userId`
  2) максимальный `userId` и количество строк с этим `userId`
- `sqlite.txt` — версия установленной SQLite и список поддерживаемых режимов вывода утилиты `sqlite3`.



