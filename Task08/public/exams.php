<?php
require_once 'config.php';

$student_id = $_GET['student_id'] ?? null;

if (!$student_id) {
    header('Location: index.php');
    exit;
}

// Получаем данные студента
$stmt = $pdo->prepare("SELECT s.*, g.group_number FROM students s JOIN groups g ON s.group_id = g.id WHERE s.id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header('Location: index.php');
    exit;
}

// Получаем экзамены студента
$stmt = $pdo->prepare("SELECT e.id, e.exam_date, e.grade, sub.name as subject_name, sub.course 
                       FROM exams e 
                       JOIN subjects sub ON e.subject_id = sub.id 
                       WHERE e.student_id = ? 
                       ORDER BY e.exam_date DESC");
$stmt->execute([$student_id]);
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Результаты экзаменов</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .actions { white-space: nowrap; }
        .actions a { margin-right: 5px; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-edit { background-color: #2196F3; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-add { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin-top: 20px; }
        a.back-link { color: #2196F3; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a href="index.php" class="back-link">← Назад к списку студентов</a>
    
    <h1>Результаты экзаменов</h1>
    <h2><?= htmlspecialchars($student['last_name']) ?> <?= htmlspecialchars($student['first_name']) ?> (Группа: <?= htmlspecialchars($student['group_number']) ?>)</h2>
    
    <table>
        <thead>
            <tr>
                <th>Дата экзамена</th>
                <th>Дисциплина</th>
                <th>Курс</th>
                <th>Оценка</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($exams)): ?>
                <tr>
                    <td colspan="5">Нет результатов экзаменов</td>
                </tr>
            <?php else: ?>
                <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td><?= htmlspecialchars($exam['exam_date']) ?></td>
                        <td><?= htmlspecialchars($exam['subject_name']) ?></td>
                        <td><?= htmlspecialchars($exam['course']) ?></td>
                        <td><?= htmlspecialchars($exam['grade']) ?></td>
                        <td class="actions">
                            <a href="edit_exam.php?id=<?= $exam['id'] ?>&student_id=<?= $student_id ?>" class="btn-edit">Редактировать</a>
                            <a href="delete_exam.php?id=<?= $exam['id'] ?>&student_id=<?= $student_id ?>" class="btn-delete">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="add_exam.php?student_id=<?= $student_id ?>" class="btn-add">Добавить экзамен</a>
</body>
</html>

