<?php
require_once '../DB_connect.php'; 
session_start();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST' && $action === 'register') {
    // Rejestracja użytkownika
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    // Sprawdź czy email już istnieje
    $stmt = $MySQLconection->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "error" => "Email już istnieje."]);
        exit;
    }

    $stmt->close();

    // Dodaj użytkownika
    $created_at = date('Y-m-d H:i:s');
    $badges = "beginner";
    $points = 0;
    $userRole = "user"; // Domyślnie ustawiamy rolę na "user"


    $stmt = $MySQLconection->prepare("INSERT INTO Users (username, email, password, points, badges, created_at, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $username, $email, $password, $points, $badges, $created_at, $userRole);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id;

        $_SESSION['user'] = [
            "id" => $userId,
            "username" => $username,
            "role" => $userRole
        ];

        echo json_encode([
            "success" => true,
            "user" => [
                "id" => $userId,
                "username" => $username
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Błąd podczas rejestracji."]);
    }   

    $stmt->close();

} elseif ($method === 'POST' && $action === 'login') {
    // Logowanie użytkownika
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $MySQLconection->prepare("SELECT id, username, password, role FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["success" => false, "error" => "Nieprawidłowy email lub hasło."]);
        exit;
    }

    $stmt->bind_result($id, $username, $hashedPassword, $userRole);
    $stmt->fetch();
    // Sprawdź hasło
     if (password_verify($password, $hashedPassword)) {
        $_SESSION['user'] = [
            "id" => $id,
            "username" => $username,
            "role" => $userRole
        ];
        echo json_encode([
            "success" => true,
            "user" => [
                "id" => $id,
                "username" => $username
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Nieprawidłowe hasło."]);
    }
    $stmt->close();

} else {
    http_response_code(400);
    echo json_encode(["error" => "Niepoprawne zapytanie."]);
}
