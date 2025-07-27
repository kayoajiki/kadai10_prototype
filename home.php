<?php
// セッション開始（重複防止）
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Moon Diary - 今日の気分</title>
  <style>
    body {
      background: linear-gradient(#2e003e, #1a1a40);
      font-family: 'Helvetica Neue', sans-serif;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 10px;
    }

    .container {
      text-align: center;
      background: rgba(255,255,255,0.05);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(255,255,255,0.1);
      width: 100%;
      max-width: 350px;
      margin-top: 40px;
      margin-bottom: 10px; /* ✅ 余白を最小限に */
    }

    h2 {
      font-size: 1.4rem;
      margin-bottom: 20px;
    }

    .hearts {
      display: flex;
      justify-content: center;
      gap: 15px;
      font-size: 2.5rem;
      margin-bottom: 20px;
      cursor: pointer;
    }

    textarea {
      width: 100%;
      height: 100px;
      border: none;
      border-radius: 10px;
      padding: 10px;
      font-size: 1rem;
      resize: none;
      background-color: rgba(255,255,255,0.1);
      color: #fff;
    }
    textarea::placeholder {
      color: #aaa;
    }

    /* ✅ 登録ボタンをコメント欄と同じ幅 */
    .submit-button {
      margin-top: 15px;
      width: 100%; /* ✅ コメント欄と同じ幅 */
      background-color: #d1c4e9;
      color: #2c3e50;
      border: none;
      padding: 12px 0;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .submit-button:hover {
      background-color: #e8e0f8;
    }
    /* ✅ 登録ボタンとリンクの間の余白を調整 */
    form {
      margin-bottom: 0; /* フォームの下の余白を削除 */
    }

    /* ✅ コメント欄と登録ボタンの幅を揃える */
    textarea, .submit-button {
      box-sizing: border-box; /* 幅を正確に揃える */
    }

    .nav-links a {
      color: #f8bbd0;
      text-decoration: none;
      margin: 0 10px;
    }
    .nav-links a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .container { padding: 20px; }
      .hearts { font-size: 2rem; gap: 10px; }
      h2 { font-size: 1.2rem; }
      textarea { font-size: 0.9rem; height: 90px; }
      .submit-button { font-size: 0.95rem; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🌙 今日の気分を記録しよう</h2>
    <form action="save_mood.php" method="post">
      <div class="hearts">
        <span class="heart" data-value="1">🤍</span>
        <span class="heart" data-value="2">🤍</span>
        <span class="heart" data-value="3">🤍</span>
      </div>
      <input type="hidden" name="mood" id="mood" value="0">
      <textarea name="comment" placeholder="今日のひとことをどうぞ…" required></textarea>
      <input type="submit" value="登録" class="submit-button">
    </form>
  </div>

  <!-- ✅ calendar.php と同じデザインのリンク -->
  <div class="nav-links">
    <a href="calendar.php">カレンダーを見る</a> |
    <a href="life_path.php">ライフパスナンバーを見る</a><br><br>
    <a href="logout.php" style="font-size: 0.8rem;">🚪ログアウト</a>
  </div>

  <script>
    const hearts = document.querySelectorAll('.heart');
    const moodInput = document.getElementById('mood');

    hearts.forEach((heart, index) => {
      heart.addEventListener('click', () => {
        const current = parseInt(moodInput.value);
        const clicked = index + 1;
        moodInput.value = (current === clicked) ? clicked - 1 : clicked;
        hearts.forEach((h, i) => {
          h.textContent = i < moodInput.value ? '💗' : '🤍';
        });
      });
    });
  </script>
</body>
</html>
