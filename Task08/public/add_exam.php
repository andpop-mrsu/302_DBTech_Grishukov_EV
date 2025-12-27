<?php
require_once 'config.php';

$student_id = $_GET['student_id'] ?? null;

if (!$student_id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT s.*, g.group_number FROM students s JOIN groups g ON s.group_id = g.id WHERE s.id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $exam_date = $_POST['exam_date'];
    $grade = $_POST['grade'];
    
    $stmt = $pdo->prepare("INSERT INTO exams (student_id, subject_id, exam_date, grade) VALUES (?, ?, ?, ?)");
    $stmt->execute([$student_id, $subject_id, $exam_date, $grade]);
    
    header("Location: exams.php?student_id=$student_id");
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, course FROM subjects WHERE group_number = ? ORDER BY course, name");
$stmt->execute([$student['group_number']]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добавить экзамен</title>
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
    <h1>Добавить экзамен</h1>
    <p>Студент: <?= htmlspecialchars($student['last_name']) ?> <?= htmlspecialchars($student['first_name']) ?></p>
    <p>Группа: <?= htmlspecialchars($student['group_number']) ?></p>
    
    <form method="POST">
        <label>Дисциплина:</label>
        <select name="subject_id" required>
            <option value="">Выберите дисциплину</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?= $subject['id'] ?>">
                    <?= htmlspecialchars($subject['name']) ?> (Курс <?= $subject['course'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Дата экзамена:</label>
        <input type="date" name="exam_date" required>
        
        <label>Оценка:</label>
        <select name="grade" required>
            <option value="">Выберите оценку</option>
            <option value="5">5 (Отлично)</option>
            <option value="4">4 (Хорошо)</option>
            <option value="3">3 (Удовлетворительно)</option>
            <option value="2">2 (Неудовлетворительно)</option>
        </select>
        
        <button type="submit">Сохранить</button>
    </form>
    
    <a href="exams.php?student_id=<?= $student_id ?>">Назад к результатам</a>
</body>
</html>

