<?php
session_start();
include("funcs.php");
loginCheck();

$pdo = db_conn();
$user_id = $_SESSION['user_id'];

// URLパラメータ取得
$id = $_GET['id'] ?? null;
$date = $_GET['date'] ?? null;
$mood = 0;
$comment = "";

// id優先で既存データを取得
if ($id) {
  $stmt = $pdo->prepare("SELECT * FROM diary WHERE id = :id AND user_id = :user_id");
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $date = $row['date'];
    $mood = $row['mood'];
    $comment = $row['comment'];
    $id = $row['id'];
  } else {
    echo "対象データが見つかりませんでした。";
    exit();
  }
} elseif ($date) {
  $stmt = $pdo->prepare("SELECT * FROM diary WHERE user_id = :user_id AND date = :date");
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $mood = $row['mood'];
    $comment = $row['comment'];
    $id = $row['id'];
  }
} else {
  $date = date('Y-m-d'); // デフォルトは今日の日付
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>編集：<?= $date ?></title>
  <style>
    body {
      background: #1c1c3c;
      color: white;
      font-family: 'Helvetica Neue', sans-serif;
      text-align: center;
      padding: 20px;
      margin: 0;
    }
    .container {
      background: rgba(255,255,255,0.05);
      padding: 30px 20px;
      border-radius: 12px;
      max-width: 350px;
      margin: 40px auto;
      box-shadow: 0 0 20px rgba(255,255,255,0.1);
    }
    h2 {
      font-size: 1.4rem;
      margin-bottom: 20px;
    }
    form {
      width: 100%;
    }
    .hearts {
      font-size: 2.5rem;
      cursor: pointer;
      user-select: none;
      margin-bottom: 20px;
      display: flex;
      justify-content: center;
      gap: 15px;
    }
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: none;
      resize: vertical;
      background: rgba(255,255,255,0.1);
      color: white;
      font-size: 1rem;
    }
    textarea::placeholder {
      color: #aaa;
    }
    input[type="submit"] {
      background: #b39ddb;
      color: #2c3e50;
      border: none;
      padding: 12px 0;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
      font-size: 1rem;
      transition: 0.3s;
    }
    input[type="submit"]:hover {
      background: #d1c4e9;
    }
    a.back {
      margin-top: 15px;
      display: block;
      color: #f8bbd0;
      text-decoration: none;
      font-size: 0.95rem;
    }
    @media (max-width: 480px) {
      .container { padding: 20px; }
      .hearts { font-size: 2rem; gap: 10px; }
      h2 { font-size: 1.2rem; }
      textarea { font-size: 0.9rem; }
      input[type="submit"] { font-size: 0.95rem; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📝 <?= $date ?> の気分編集</h2>

    <form action="update_mood.php" method="post">
      <div class="hearts">
        <span class="heart" data-value="1">🤍</span>
        <span class="heart" data-value="2">🤍</span>
        <span class="heart" data-value="3">🤍</span>
      </div>
      <input type="hidden" name="mood" id="mood" value="<?= $mood ?>">
      <textarea name="comment" rows="3" placeholder="今日のひとこと"><?= htmlspecialchars($comment) ?></textarea>
      <input type="hidden" name="id" value="<?= htmlspecialchars($id ?? '') ?>">
      <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
      <input type="submit" value="更新">
    </form>

    <a href="calendar.php" class="back">← カレンダーへ戻る</a>
  </div>

  <script>
    const hearts = document.querySelectorAll('.heart');
    const moodInput = document.getElementById('mood');
    let currentMood = <?= $mood ?>;

    function updateHearts(mood) {
      hearts.forEach((heart, index) => {
        heart.textContent = index < mood ? '💗' : '🤍';
      });
      moodInput.value = mood;
    }

    updateHearts(currentMood);

    hearts.forEach((heart, index) => {
      heart.addEventListener('click', () => {
        currentMood = (currentMood === index + 1) ? index : index + 1;
        updateHearts(currentMood);
      });
    });
  </script>
</body>
</html>
