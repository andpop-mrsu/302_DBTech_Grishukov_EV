<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $group_id = $_POST['group_id'];
    $gender = $_POST['gender'];
    
    $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, group_id, gender) VALUES (?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $group_id, $gender]);
    
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
    <title>Добавить студента</title>
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
    <h1>Добавить студента</h1>
    
    <form method="POST">
        <label>Имя:</label>
        <input type="text" name="first_name" required>
        
        <label>Фамилия:</label>
        <input type="text" name="last_name" required>
        
        <label>Группа:</label>
        <select name="group_id" required>
            <option value="">Выберите группу</option>
            <?php foreach ($groups as $group): ?>
                <option value="<?= $group['id'] ?>"><?= htmlspecialchars($group['group_number']) ?></option>
            <?php endforeach; ?>
        </select>
        
        <label>Пол:</label>
        <select name="gender" required>
            <option value="">Выберите пол</option>
            <option value="male">Мужской</option>
            <option value="female">Женский</option>
        </select>
        
        <button type="submit">Сохранить</button>
    </form>
    
    <a href="index.php">Назад к списку</a>
</body>
</html>

