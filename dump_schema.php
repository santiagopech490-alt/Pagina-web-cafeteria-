<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=CafeteriaParisien', 'root', '');
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $t) {
        echo "=== TABLE: $t ===\n";
        $r = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
        echo $r['Create Table'] . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
