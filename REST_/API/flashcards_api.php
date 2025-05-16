<?php 
session_start();
require_once '../DB_connect.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && $_GET['action'] === 'get_flashcards') {
    // Pobierz losowe fiszki
    $result = $MySQLconection->query("SELECT * FROM Flashcards ORDER BY RAND() LIMIT 5");

    $flashcards = [];
    while ($row = $result->fetch_assoc()) {
        $flashcards[] = $row;
    }

    echo json_encode($flashcards);
    exit;

} elseif ($method === 'POST' && $_GET['action'] === 'submit_answer') {
    // Odczytaj dane z JSON-a
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = $data['user_id'];
    $flashcardId = $data['flashcard_id'];
    $result = $data['result'];

    // Zapisanie wyników sesji nauki użytkownika
    $stmt = $MySQLconection->prepare("INSERT INTO FlashcardSessions (user_id, flashcard_id, result, review_date) VALUES (?, ?, ?, CURDATE())");
    $stmt->bind_param("iis", $userId, $flashcardId, $result);
    $stmt->execute();

    // Aktualizacja punktów (10 pkt za poprawną odpowiedź)
    if ($result === 'znam') {
        $stmt2 = $MySQLconection->prepare("UPDATE Users SET points = points + 10 WHERE id = ?");
        $stmt2->bind_param("i", $userId);
        $stmt2->execute();
    }

    echo json_encode(["success" => true]);
    exit;

} elseif ($method === 'POST' && $_GET['action'] === 'add_flashcard') {
    $data = json_decode(file_get_contents('php://input'), true);
    $word = $data['word'] ?? '';
    $translation = $data['translation'] ?? '';
    $example = $data['example_sentence'] ?? '';

    if ($word && $translation && $example) {
        $stmt = $MySQLconection->prepare("INSERT INTO Flashcards (word, translation, example_sentence, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $word, $translation, $example, $_SESSION['user']['id']);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd zapisu']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Brak danych']);
    }
    exit;
} 
else {
    http_response_code(400);
    echo json_encode(["error" => "Nieprawidłowe żądanie"]);
    exit;
}
?>
