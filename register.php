<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("funcs.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $pw = password_hash($_POST["password"], PASSWORD_DEFAULT);

  // ✅ 年・月・日を結合して birthdate 形式に変換
  $birthdate = $_POST["year"]."-".$_POST["month"]."-".$_POST["day"];

  $pdo = db_conn();
  $stmt = $pdo->prepare("INSERT INTO users(name,email,password,birthdate) VALUES(:name,:email,:password,:birthdate)");
  $stmt->bindValue(':name', $name);
  $stmt->bindValue(':email', $email);
  $stmt->bindValue(':password', $pw);
  $stmt->bindValue(':birthdate', $birthdate);
  $stmt->execute();

  header("Location: life_path.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー登録</title>
  <style>
    body {
      margin: 0;
      font-family: 'Helvetica Neue', sans-serif;
      background: linear-gradient(#1c1c3c, #4b3c5c);
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 10px;
    }
    .register-box {
      background: rgba(255, 255, 255, 0.05);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(255,255,255,0.1);
      text-align: center;
      width: 100%;
      max-width: 340px;
    }
    .register-box h2 {
      margin-bottom: 20px;
      font-weight: normal;
      color: #f8bbd0;
      font-size: 1.4rem;
    }
    .input-field, select {
      width: 100%;
      box-sizing: border-box;
      padding: 12px;
      margin-bottom: 12px;
      border: none;
      border-radius: 6px;
      background: rgba(255,255,255,0.1);
      font-size: 1rem;
      color: white;
    }
    select {
      background: rgba(255,255,255,0.1);
      color: #fff;
      appearance: none;
    }
    select option { color: black; }
    input[type="submit"] {
      background: #b39ddb;
      color: #2c3e50;
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      transition: 0.3s;
    }
    input[type="submit"]:hover {
      background: #d1c4e9;
    }
    a {
      color: #f8bbd0;
      display: block;
      margin-top: 10px;
      text-decoration: none;
      font-size: 0.9rem;
    }
    label {
      text-align: left;
      display: block;
      margin: 10px 0 5px;
      color: rgba(255,255,255,0.8);
      font-size: 0.9rem;
    }
    /* ✅ スマホ調整 */
    @media (max-width: 480px) {
      .register-box { padding: 20px; max-width: 90%; }
      .input-field, select { font-size: 0.95rem; padding: 10px; }
      input[type="submit"] { font-size: 0.95rem; padding: 10px; }
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>🌙 ユーザー登録</h2>
    <form method="post">
      <input type="text" name="name" class="input-field" placeholder="お名前" required>
      <input type="email" name="email" class="input-field" placeholder="メールアドレス" required>
      <input type="password" name="password" class="input-field" placeholder="パスワード" required>

      <label>生年月日</label>
      <select name="year" required>
        <?php for($y = date('Y'); $y >= 1900; $y--): ?>
          <option value="<?= $y ?>"><?= $y ?>年</option>
        <?php endfor; ?>
      </select>
      <select name="month" required>
        <?php for($m = 1; $m <= 12; $m++): ?>
          <option value="<?= sprintf('%02d',$m) ?>"><?= $m ?>月</option>
        <?php endfor; ?>
      </select>
      <select name="day" required>
        <?php for($d = 1; $d <= 31; $d++): ?>
          <option value="<?= sprintf('%02d',$d) ?>"><?= $d ?>日</option>
        <?php endfor; ?>
      </select>

      <input type="submit" value="登録">
    </form>
    <a href="login.php">← ログインはこちら</a>
  </div>
</body>
</html>
