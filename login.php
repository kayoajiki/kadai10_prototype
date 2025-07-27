<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("funcs.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST["email"];
  $pw = $_POST["password"];

  $pdo = db_conn();
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindValue(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch();

  if ($user && password_verify($pw, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["name"];
    header("Location: home.php");
    exit();
  } else {
    $error = "メールアドレスまたはパスワードが正しくありません。";
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Moon Diary - ログイン</title>
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

  .login-box {
    background: rgba(255, 255, 255, 0.05);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(255,255,255,0.1);
    text-align: center;
    width: 100%;
    max-width: 320px;
  }

  .login-box h2 {
    margin-bottom: 20px;
    font-weight: normal;
    color: #f8bbd0;
    font-size: 1.4rem;
  }

  .error {
    color: #ff8080;
    font-size: 0.9rem;
    margin-bottom: 10px;
  }

  input[type="text"],
  input[type="email"],
  input[type="password"],
  input[type="submit"] {
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    margin-bottom: 15px;
    border: none;
    border-radius: 6px;
    background: rgba(255,255,255,0.1);
    color: white;
    font-size: 1rem;
  }

  input[type="submit"] {
    background: #b39ddb;
    color: #2c3e50;
    cursor: pointer;
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

  .quote {
    font-size: 0.8rem;
    margin-top: 15px;
    opacity: 0.7;
  }

  @media (max-width: 480px) {
    .login-box { padding: 20px; max-width: 90%; }
    .login-box h2 { font-size: 1.2rem; }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="submit"] { font-size: 0.95rem; padding: 10px; }
  }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>🌙 Moon Diary</h2>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="post">
      <input type="email" name="email" placeholder="メールアドレス" required>
      <input type="password" name="password" placeholder="パスワード" required>
      <input type="submit" value="ログイン">
    </form>
    <div class="quote">
      今日のタロットメッセージ：<br>
<?php
// タロットメッセージ配列（大アルカナ22）
$quotes = [
  "新しい旅は一歩から始まる。【0：愚者】",
  "自分を信じて進めば道は開ける。【1：魔術師】",
  "心の声を静かに聴く時間を持とう。【2：女教皇】",
  "愛と優しさを周りに注いでみよう。【3：女帝】",
  "責任を引き受ける勇気が未来を創る。【4：皇帝】",
  "信じるものを大切に、心の軸を守って。【5：法王】",
  "運命の出会いを恐れず、心を開いて。【6：恋人】",
  "迷わず進む決意が力になる。【7：戦車】",
  "力は優しさと共にある、焦らず調和を大切に。【8：力】",
  "焦らず、心の声を聴いて。【9：隠者】",
  "人生は巡る、流れに身を任せて。【10：運命の輪】",
  "正義は心の中に、冷静な判断を。【11：正義】",
  "止まることも勇気、視点を変えてみよう。【12：吊るされた男】",
  "終わりは始まり、変化を受け入れて。【13：死神】",
  "バランスを保つことで道は整う。【14：節制】",
  "恐れを超えれば、本当の自分が見えてくる。【15：悪魔】",
  "壊れることで、新しい光が差す。【16：塔】",
  "希望はいつも空にある、星を見上げて。【17：星】",
  "暗闇も必要、休むことで力が戻る。【18：月】",
  "朝は必ず訪れる、太陽を信じて。【19：太陽】",
  "過去を受け入れ、未来へ羽ばたこう。【20：審判】",
  "世界はあなたの味方、今を楽しもう。【21：世界】"
];

// ランダムメッセージ取得
$message = $quotes[array_rand($quotes)];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<style>
  .tarot-message {
    background: rgba(255,255,255,0.08);
    padding: 15px;
    border-radius: 10px;
    max-width: 300px;
    margin: 15px auto;
    font-family: 'Helvetica Neue', sans-serif;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 10px rgba(255,255,255,0.1);
  }
  .tarot-text {
    font-size: 1rem;
    line-height: 1.4;
    margin-bottom: 8px;
  }
  .tarot-card {
    font-size: 0.9rem;
    color: #f8bbd0;
    font-weight: bold;
  }
</style>
</head>
<body>
  <div class="tarot-message">
    <?php
      // ✅ 【カード名】だけ色を変えて表示
      if (preg_match('/(.*)【(.*)】/', $message, $matches)) {
        echo "<div class='tarot-text'>{$matches[1]}</div>";
        echo "<div class='tarot-card'>【{$matches[2]}】</div>";
      } else {
        echo "<div class='tarot-text'>$message</div>";
      }
    ?>
  </div>
  </div>
    <!-- 新規登録リンクを最下部に移動 -->
    <a href="register.php">新規登録はこちら</a>
  </div>
</body>
</html>
