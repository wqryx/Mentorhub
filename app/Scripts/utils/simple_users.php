<?php
$conn = new PDO('mysql:host=127.0.0.1;dbname=mentorhub', 'root', '');
$stmt = $conn->query('SELECT id, name, email FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "ID | Nombre | Email\n";
echo "-------------------------------\n";
foreach ($users as $user) {
    echo "{$user['id']} | {$user['name']} | {$user['email']}\n";
}
