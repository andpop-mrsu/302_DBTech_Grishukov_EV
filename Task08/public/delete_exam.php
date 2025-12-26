<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;
$student_id = $_GET['student_id'] ?? null;

if (!$id || !$student_id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM exams WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: exams.php?student_id=$student_id");
    exit;
}

// Получаем данные экзамена
$stmt = $pdo->prepare("SELECT e.*, sub.name as subject_name FROM exams e JOIN subjects sub ON e.subject_id = sub.id WHERE e.id = ?");
$stmt->execute([$id]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Удалить экзамен</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .warning { background-color: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin: 20px 0; }
        button { padding: 10px 20px; margin-right: 10px; border: none; cursor: pointer; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-cancel { background-color: #9e9e9e; color: white; }
        a { color: #2196F3; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Удалить экзамен</h1>
    
    <div class="warning">
        <p>Вы уверены, что хотите удалить экзамен:</p>
        <p><strong><?= htmlspecialchars($exam['subject_name']) ?></strong></p>
        <p>Дата: <?= htmlspecialchars($exam['exam_date']) ?></p>
        <p>Оценка: <?= htmlspecialchars($exam['grade']) ?></p>
    </div>
    
    <form method="POST">
        <button type="submit" class="btn-delete">Удалить</button>
        <a href="exams.php?student_id=<?= $student_id ?>" class="btn-cancel">Отмена</a>
    </form>
</body>
</html>

