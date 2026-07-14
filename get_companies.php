<?php
require_once 'db_connect.php';

try {
    $stmt = $pdo->query("SELECT com_id, com_name FROM companies ORDER BY com_name ASC");
    $companies = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<option value="">خطأ في تحميل الشركات</option>';
    exit;
}

if (count($companies) > 0) {
    foreach ($companies as $company) {
        $id = htmlspecialchars($company['com_id']);
        $name = htmlspecialchars($company['com_name']);
        // Value = com_name, data-id = com_id
        echo '<option value="' . $name . '" data-id="' . $id . '">' . $name . '</option>';
    }
} else {
    echo '<option value="">لا توجد شركات</option>';
}
?>