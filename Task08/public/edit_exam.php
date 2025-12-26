<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;
$student_id = $_GET['student_id'] ?? null;

if (!$id || !$student_id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $exam_date = $_POST['exam_date'];
    $grade = $_POST['grade'];
    
    $stmt = $pdo->prepare("UPDATE exams SET subject_id = ?, exam_date = ?, grade = ? WHERE id = ?");
    $stmt->execute([$subject_id, $exam_date, $grade, $id]);
    
    header("Location: exams.php?student_id=$student_id");
    exit;
}

// Получаем данные экзамена
$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$id]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    header('Location: index.php');
    exit;
}

// Получаем данные студента
$stmt = $pdo->prepare("SELECT s.*, g.group_number FROM students s JOIN groups g ON s.group_id = g.id WHERE s.id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Получаем дисциплины
$stmt = $pdo->prepare("SELECT id, name, course FROM subjects WHERE group_number = ? ORDER BY course, name");
$stmt->execute([$student['group_number']]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Редактировать экзамен</title>
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
    <h1>Редактировать экзамен</h1>
    
    <form method="POST">
        <label>Дисциплина:</label>
        <select name="subject_id" required>
            <option value="">Выберите дисциплину</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?= $subject['id'] ?>" <?= $exam['subject_id'] == $subject['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($subject['name']) ?> (Курс <?= $subject['course'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Дата экзамена:</label>
        <input type="date" name="exam_date" value="<?= htmlspecialchars($exam['exam_date']) ?>" required>
        
        <label>Оценка:</label>
        <select name="grade" required>
            <option value="5" <?= $exam['grade'] == 5 ? 'selected' : '' ?>>5 (Отлично)</option>
            <option value="4" <?= $exam['grade'] == 4 ? 'selected' : '' ?>>4 (Хорошо)</option>
            <option value="3" <?= $exam['grade'] == 3 ? 'selected' : '' ?>>3 (Удовлетворительно)</option>
            <option value="2" <?= $exam['grade'] == 2 ? 'selected' : '' ?>>2 (Неудовлетворительно)</option>
        </select>
        
        <button type="submit">Сохранить</button>
    </form>
    
    <a href="exams.php?student_id=<?= $student_id ?>">Назад к результатам</a>
</body>
</html>

