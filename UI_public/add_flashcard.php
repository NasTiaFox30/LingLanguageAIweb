<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
    header("Location: userlogin.html");
    exit;
}
if ($_SESSION['user']['role'] !== 'volunteer') {
    echo "<script>
            alert('Nie masz uprawnień');
            window.location.href = 'index.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Dodaj fiszkę</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h3>✍️ Dodaj nową fiszkę</h3>
    <form id="addFlashcardForm">
      <div class="mb-3">
        <label for="word" class="form-label">Słówko</label>
        <input type="text" class="form-control" id="word" required>
      </div>
      <div class="mb-3">
        <label for="translation" class="form-label">Tłumaczenie</label>
        <input type="text" class="form-control" id="translation" required>
      </div>
      <div class="mb-3">
        <label for="example" class="form-label">Przykładowe zdanie</label>
        <input type="text" class="form-control" id="example" required>
      </div>
      <button type="submit" class="btn btn-primary">💾 Zapisz fiszkę</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">← Powrót</a>
  </div>

  <script>
    document.getElementById('addFlashcardForm').addEventListener('submit', function(e) {
      e.preventDefault();
      fetch('/REST_/API/flashcards_api.php?action=add_flashcard', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          word: document.getElementById('word').value,
          translation: document.getElementById('translation').value,
          example_sentence: document.getElementById('example').value
        })
      })
      .then(res => res.json())
      .then(data => {
        alert('✅ Fiszka została dodana!');
        document.getElementById('addFlashcardForm').reset();
      })
      .catch(err => {
        alert('❌ Błąd dodawania fiszki.');
        console.error(err);
      });
    });
  </script>
</body>
</html>
