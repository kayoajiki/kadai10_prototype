<?php
session_start();
include("funcs.php");
loginCheck();

$pdo = db_conn();
$user_id = $_SESSION["user_id"];

// ✅ ユーザーの生年月日を取得
$stmt = $pdo->prepare("SELECT birthdate FROM users WHERE id = :id");
$stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$birthdate = $row["birthdate"] ?? "";

// ✅ ライフパスナンバー計算（修正）
function calcLifePath($birthdate) {
    // ✅ 数字のみ抽出（-や/を削除）
    $digitsOnly = preg_replace('/[^0-9]/', '', $birthdate);
    $digits = str_split($digitsOnly);
    $sum = array_sum($digits);

    // ✅ 1桁またはマスターナンバー(11,22,33)まで計算
    while ($sum > 9 && $sum != 11 && $sum != 22 && $sum != 33) {
        $sum = array_sum(str_split((string)$sum));
    }
    return $sum;
}

$lifePath = $birthdate ? calcLifePath($birthdate) : "？";

// ✅ メッセージ
$messages = [
  1 => "🌟 1：リーダーシップと開拓精神を持つタイプ。新しい道を切り開きます。",
  2 => "🤝 2：協調性と優しさが魅力。人間関係が運を運びます。",
  3 => "🎨 3：創造力と発信力が豊か。明るく周囲を元気にする力があります。",
  4 => "🧱 4：堅実で努力家。基盤を固めて着実に成果を出します。",
  5 => "🍃 5：自由と変化を好む冒険家。柔軟な対応でチャンスをつかみます。",
  6 => "🏠 6：愛と調和の守護者。家庭・仲間を大切にし、癒しの力を持ちます。",
  7 => "🧘 7：探究心と精神性が高い。内面を深めることで成長します。",
  8 => "💼 8：実行力と成功運。物質的・社会的な豊かさを引き寄せます。",
  9 => "🕊️ 9：博愛と手放しの精神。人のために尽くす大きな愛を持ちます。",
  11 => "🔮 11：直感力と霊感が強いマスター。人々に影響を与える特別な力があります。",
  22 => "🏗️ 22：壮大なビジョンを現実化するマスタービルダー。",
  33 => "🌈 33：無償の愛と奉仕のマスター。人を癒し導く存在です。"
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>あなたのライフパスナンバー</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body {
    background:#1c1c3c;
    color:white;
    text-align:center;
    font-family:sans-serif;
    padding:20px;
    margin:0;
  }
  .card {
    background:rgba(255,255,255,0.08);
    padding:25px;
    border-radius:14px;
    max-width:340px;
    margin:30px auto;
    box-shadow:0 0 15px rgba(255,255,255,0.15);
  }
  h2 {
    margin:10px 0;
    font-size:1.4rem;
    color:#f8bbd0;
  }
  .number {
    font-size:3.2rem;
    color:#f8bbd0;
    margin:15px 0;
  }
  p {
    font-size:1rem;
    line-height:1.6;
  }
  a {
    display:block;
    margin-top:25px;
    color:#f8bbd0;
    text-decoration:none;
    font-size:1rem;
  }
  @media (max-width:480px) {
    .card { width:90%; padding:20px; }
    .number { font-size:2.5rem; }
    h2 { font-size:1.2rem; }
    p { font-size:0.95rem; }
  }
</style>
</head>
<body>
  <div class="card">
    <h2>🌙 あなたの</h2>
    <h2>ライフパスナンバー</h2>
    <p class="number"><?= htmlspecialchars($lifePath) ?></p>
    <p><?= $messages[$lifePath] ?? "✨ あなたの特別な力を活かしてください！" ?></p>
  </div>

  <a href="home.php">← 今日の気分入力へ</a>
</body>
</html>
