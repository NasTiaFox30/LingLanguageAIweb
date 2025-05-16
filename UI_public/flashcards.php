<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: userlogin.html");
  exit;
}

$userId = $_SESSION['user']['id'];
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <title>LingLearnAI</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous" />
  </head>

  <body>
    <div class="container mt-5">
      <h2 class="mb-4">📚 Nauka fiszek</h2>
      <div id="flashcard-container" class="card p-4 shadow-sm">
        <h4 id="word">Ładowanie słówka...</h4>
        <p id="example" class="text-muted"></p>
        <div class="mt-3">
          <button class="btn btn-success" onclick="submitAnswer('znam')">✅ Znam</button>
          <button class="btn btn-danger" onclick="submitAnswer('nie_znam')">❌ Nie znam</button>
        </div>
      </div>
    </div>
    <div class="container mt-5">
      <a href="index.php" class="btn btn-dark">Skończyć sesję</a>
    </div>

    <script>
      let flashcards = [];
      let current = 0;

      function loadFlashcards() {
        fetch("/REST_/API/flashcards_api.php?action=get_flashcards")
          .then((res) => res.json())
          .then((data) => {
            flashcards = data;
            showFlashcard();
          })
          .catch(() => {
            document.getElementById("word").textContent = "Błąd ładowania fiszek.";
          });
      }

      function showFlashcard() {
        const fc = flashcards[current];
        document.getElementById("word").textContent = fc.word;
        document.getElementById("example").textContent = fc.example_sentence;
      }

      function submitAnswer(result) {
        const fc = flashcards[current];

        // Zapisanie wyniku do bazy danych
        fetch("/REST_/API/flashcards_api.php?action=submit_answer", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            user_id: <?= $userId ?>, 
            flashcard_id: fc.id,
            result: result,
          }),
        }).then(() => {
          current++;
          if (current < flashcards.length) {
            if (result === "nie_znam") {
              document.getElementById("word").textContent = fc.translation;
              document.getElementById("word").style.color = "darkorange";

              document.querySelectorAll(".btn").forEach((btn) => {
                btn.disabled = true;
                btn.style.cursor = "not-allowed";
                btn.style.opacity = 0.5;
              });

              setTimeout(() => {
                document.querySelectorAll(".btn").forEach((btn) => {
                  btn.disabled = false;
                  btn.style.cursor = "pointer";
                  btn.style.opacity = 1;
                });
                document.getElementById("word").style.color = "black";
                showFlashcard();
              }, 2000);
            } else {
              showFlashcard();
            }
          } else {
            alert("Koniec sesji!");
            window.location.href = "index.php";
          }
        });
      }

      document.addEventListener("DOMContentLoaded", loadFlashcards);
    </script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"></script>
  </body>
</html>
