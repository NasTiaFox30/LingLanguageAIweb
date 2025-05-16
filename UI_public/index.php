<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>LingLearnAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous" />
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="text-center">
    <h1 class="mb-4">👋 Witamy w LingLanguageAI</h1>

    <?php if ($isLoggedIn): ?>
      <h2>Witaj, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>!</h2>
      <p>Wybierz jedną z opcji, aby rozpocząć swoją własną naukę:</p>
    

      <div class="row justify-content-center">
        <div class="col-md-4">
          <a href="leaderboard.html" class="btn btn-success w-100">🏆 Ranking</a>
        </div>

        <div class="col-md-4">
          <a href="flashcards.php" class="btn btn-primary w-100">🧠 Nauka fiszek</a>
        </div>

        <div class="col-md-4">
          <a href="user.php" class="btn btn-info w-100">📓 Mój profil</a>
        </div>
      </div>
    <?php else: ?>
        <p>Zaloguj się lub zarejestruj, aby rozpocząć naukę!</p>
        <div class="row justify-content-center">
            <div class="col-md-3">
            <a href="userlogin.html" class="btn btn-primary w-100">🔐 Zaloguj się</a>
            </div>
            <div class="col-md-4">
            <a href="userregister.html" class="btn btn-secondary w-100">📝 Zarejestruj się</a>
            </div>
            
            <div class="col-md-4">
            <a href="leaderboard.html" class="btn btn-success w-100">🏆 Ranking</a>
            </div>
        </div>
    <?php endif; ?>
  </div>
</div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"></script>
</body>
</html>
