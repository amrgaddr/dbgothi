<?php
require_once 'db_connect.php';

try {
    $stmt = $pdo->query("SELECT hospital_name FROM hospitals ORDER BY hospital_name ASC");
    $hospitals = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<option value="">خطأ في تحميل المستشفيات</option>';
    exit;
}

if (count($hospitals) > 0) {
    foreach ($hospitals as $hospital) {
        $name = htmlspecialchars($hospital['hospital_name']);
        echo '<option value="' . $name . '">' . $name . '</option>';
    }
} else {
    echo '<option value="">لا توجد مستشفيات</option>';
}
?>