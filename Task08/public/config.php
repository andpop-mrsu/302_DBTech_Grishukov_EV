<?php
$db_path = __DIR__ . '/../data/database.db';
$init_sql_path = __DIR__ . '/../data/db_init.sql';

// Если база данных не существует, создаём её
if (!file_exists($db_path)) {
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Читаем и выполняем SQL скрипт
    $sql = file_get_contents($init_sql_path);
    $pdo->exec($sql);
} else {
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
?>

