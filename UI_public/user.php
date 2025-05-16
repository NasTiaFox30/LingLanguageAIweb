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
    <title>LingLearnAI – Mój profil</title>
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
      <div class="d-flex justify-content-between">
        <h2>👤 Mój profil</h2>
        <a href="logout.php" class="btn btn-outline-danger ms-2">🚪 Wyloguj się</a>
      </div>
      <div class="card p-4 shadow mt-3">
        <h4 id="username">Użytkownik: ...</h4>
        <p>Punkty: <span id="points">0</span></p>
        <p>Odznaki:</p>
        <div id="badges" class="d-flex flex-wrap gap-2"></div>
      </div>

      <h3 class="mt-4">📚 Stworz fiszki i podziel się z resztą!</h3>
      <a href="add_flashcard.php" class="btn btn-primary">Dodaj +</a>

      <div class="mt-4">
        <a href="index.php" class="btn btn-primary">← Powrót do menu głównego</a>
      </div>
    </div>

    <script>
      // Username
      const userId = <?= json_encode($userId) ?>;

      fetch(`/REST_/API/gamification_api.php?action=get_user_gamification&user_id=${userId}`)
        .then((res) => res.json())
        .then((user) => {
          document.getElementById("username").textContent = user.username;
          document.getElementById("points").textContent = user.points;

          const badgeList = user.badges || [];
          const badgeContainer = document.getElementById("badges");

          badgeContainer.innerHTML = badgeList.length
            ? badgeList.map((badge) => `<span class="badge bg-info">${badge}</span>`).join("")
            : "<span class='text-muted'>Brak odznak</span>";
        })
        .catch((error) => {
          console.error("Błąd podczas pobierania danych użytkownika:", error);
          alert("Wystąpił problem z pobieraniem danych użytkownika.");
        });
    </script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"></script>
  </body>
</html>
