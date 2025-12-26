<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $group_id = $_POST['group_id'];
    $gender = $_POST['gender'];
    
    $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, group_id = ?, gender = ? WHERE id = ?");
    $stmt->execute([$first_name, $last_name, $group_id, $gender, $id]);
    
    header('Location: index.php');
    exit;
}

// Получаем данные студента
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header('Location: index.php');
    exit;
}

// Получаем список групп
$groups_stmt = $pdo->query("SELECT id, group_number FROM groups ORDER BY group_number");
$groups = $groups_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Редактировать студента</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 400px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 5px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        a { display: inline-block; margin-top: 10px; color: #2196F3; }
    </style>
</head>
<body>
    <h1>Редактировать студента</h1>
    
    <form method="POST">
        <label>Имя:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
        
        <label>Фамилия:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
        
        <label>Группа:</label>
        <select name="group_id" required>
            <option value="">Выберите группу</option>
            <?php foreach ($groups as $group): ?>
                <option value="<?= $group['id'] ?>" <?= $student['group_id'] == $group['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($group['group_number']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Пол:</label>
        <select name="gender" required>
            <option value="male" <?= $student['gender'] === 'male' ? 'selected' : '' ?>>Мужской</option>
            <option value="female" <?= $student['gender'] === 'female' ? 'selected' : '' ?>>Женский</option>
        </select>
        
        <button type="submit">Сохранить</button>
    </form>
    
    <a href="index.php">Назад к списку</a>
</body>
</html>

