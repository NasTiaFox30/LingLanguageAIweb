<?php
require_once '../DB_connect.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && $_GET['action'] === 'get_leaderboard') {
    // Pobierz ranking użytkowników (top 10)
    $result = $MySQLconection->query("SELECT id, username, points FROM Users ORDER BY points DESC LIMIT 10");

    $leaderboard = [];
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }

    echo json_encode($leaderboard);

} elseif ($method === 'GET' && $_GET['action'] === 'get_user_gamification' && isset($_GET['user_id'])) {
    // Pobierz punkty i odznaki danego użytkownika z jednej tabeli
    $userId = intval($_GET['user_id']);

    $stmt = $MySQLconection->prepare("SELECT username, points, badges FROM Users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        // Zamień string odznak na tablicę
        $badges = array_map('trim', explode(',', $data['badges']));

        echo json_encode([
            "username" => $data['username'],
            "points" => intval($data['points']),
            "badges" => $badges
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Użytkownik nie znaleziony"]);
    }

} else {
    http_response_code(400);
    echo json_encode(["error" => "Nieprawidłowe żądanie"]);
}
?>
