<?php
require_once 'config.php';

$selected_group = isset($_GET['group_filter']) ? $_GET['group_filter'] : '';

// Получаем список групп для фильтра
$groups_stmt = $pdo->query("SELECT DISTINCT group_number FROM groups ORDER BY group_number");
$groups = $groups_stmt->fetchAll(PDO::FETCH_COLUMN);

// Формируем запрос для списка студентов
$sql = "SELECT s.id, s.first_name, s.last_name, s.gender, g.group_number 
        FROM students s 
        JOIN groups g ON s.group_id = g.id";

$params = [];
if ($selected_group) {
    $sql .= " WHERE g.group_number = :group_number";
    $params[':group_number'] = $selected_group;
}

$sql .= " ORDER BY g.group_number, s.last_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Список студентов</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .actions { white-space: nowrap; }
        .actions a { margin-right: 5px; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-edit { background-color: #2196F3; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-exams { background-color: #FF9800; color: white; }
        .btn-add { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin-top: 20px; }
        .filter { margin-bottom: 20px; }
        .filter select, .filter input { padding: 5px; }
    </style>
</head>
<body>
    <h1>Список студентов</h1>
    
    <div class="filter">
        <form method="GET" action="">
            <label>Фильтр по группе:</label>
            <select name="group_filter">
                <option value="">Все группы</option>
                <?php foreach ($groups as $group): ?>
                    <option value="<?= htmlspecialchars($group) ?>" <?= $selected_group === $group ? 'selected' : '' ?>>
                        <?= htmlspecialchars($group) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Применить">
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Группа</th>
                <th>Пол</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="6">Студенты не найдены</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['id']) ?></td>
                        <td><?= htmlspecialchars($student['last_name']) ?></td>
                        <td><?= htmlspecialchars($student['first_name']) ?></td>
                        <td><?= htmlspecialchars($student['group_number']) ?></td>
                        <td><?= $student['gender'] === 'male' ? 'М' : 'Ж' ?></td>
                        <td class="actions">
                            <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn-edit">Редактировать</a>
                            <a href="delete_student.php?id=<?= $student['id'] ?>" class="btn-delete">Удалить</a>
                            <a href="exams.php?student_id=<?= $student['id'] ?>" class="btn-exams">Результаты экзаменов</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="add_student.php" class="btn-add">Добавить студента</a>
</body>
</html>

