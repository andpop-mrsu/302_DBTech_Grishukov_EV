<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM exams WHERE student_id = ?");
    $stmt->execute([$id]);
    
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT s.*, g.group_number FROM students s JOIN groups g ON s.group_id = g.id WHERE s.id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Удалить студента</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .warning { background-color: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin: 20px 0; }
        button { padding: 10px 20px; margin-right: 10px; border: none; cursor: pointer; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-cancel { background-color: #9e9e9e; color: white; }
        a { color: #2196F3; }
    </style>
</head>
<body>
    <h1>Удалить студента</h1>
    
    <div class="warning">
        <p>Вы уверены, что хотите удалить студента:</p>
        <p><strong><?= htmlspecialchars($student['last_name']) ?> <?= htmlspecialchars($student['first_name']) ?></strong></p>
        <p>Группа: <?= htmlspecialchars($student['group_number']) ?></p>
        <p>Все экзамены этого студента также будут удалены!</p>
    </div>
    
    <form method="POST">
        <button type="submit" class="btn-delete">Удалить</button>
        <a href="index.php" class="btn-cancel">Отмена</a>
    </form>
</body>
</html>

