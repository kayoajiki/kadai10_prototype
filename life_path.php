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
$birthdate = $row["birthdate"];

// ✅ ライフパスナンバー計算関数
function calcLifePath($birthdate) {
    // YYYY-MM-DD → 数字だけに
    $digits = str_split(str_replace('-', '', $birthdate));
    $sum = array_sum($digits);

    // 1桁になるまで足し続け（ただし11,22,33はマスター）
    while ($sum > 9 && $sum != 11 && $sum != 22 && $sum != 33) {
        $sum = array_sum(str_split($sum));
    }
    return $sum;
}

$lifePath = calcLifePath($birthdate);

// ✅ 数秘メッセージ（例）
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
<style>
  body {
    background:#1c1c3c;
    color:white;
    text-align:center;
    font-family:sans-serif;
    padding:40px;
  }
  .card {
    background:rgba(255,255,255,0.1);
    padding:20px;
    border-radius:12px;
    width:300px;
    margin:0 auto;
    box-shadow:0 0 15px rgba(255,255,255,0.1);
  }
  .number {
    font-size:3rem;
    color:#f8bbd0;
  }
  a {
    display:block;
    margin-top:20px;
    color:#f8bbd0;
    text-decoration:none;
  }
</style>
</head>
<body>
  <div class="card">
    <h2>🌙 あなたの</h2>
    <h2>ライフパスナンバー</h2>
    <p class="number"><?= $lifePath ?></p>
    <p><?= $messages[$lifePath] ?? "✨ あなたの特別な力を活かしてください！" ?></p>
  </div>
  <a href="home.php">→ 今日の気分入力へ</a>
</body>
</html>
