<?php
// ✅ アイコン番号を取得
$num = isset($_GET['n']) ? intval($_GET['n']) : 0;

// ✅ アイコン解説データ
$iconInfo = [
    1 => ["🚀✨", "1️⃣ 新たな挑戦にぴったりの日。自分から一歩踏み出してみて！"],
    2 => ["🤝😊", "2️⃣ 人との関わりがカギ。思いやりと協力を意識して過ごそう。"],
    3 => ["🎨🎤", "3️⃣ 創造力・発信力が光る日。心が動く瞬間大切に発信してみて！"],
    4 => ["🧱📈", "4️⃣ コツコツ積み上げる力が試される日。地道な努力を継続が吉"],
    5 => ["🍃🌀", "5️⃣ 変化の流れが強い日。柔軟に動いて風に乗ろう。"],
    6 => ["🏠❤️", "6️⃣ 家庭や仲間との時間を大切に。優しさが運を呼びます。"],
    7 => ["🧘‍♀️🔍", "7️⃣ 内省に向いた日。静かな時間があなたに気づきをもたらします。"],
    8 => ["💼💰", "8️⃣ 成果を意識すると良い日。お金や仕事運に動きがありそう。"],
    9 => ["🍂🕊️", "9️⃣ 手放しと癒しのタイミング。過去を振り返ることで前に進めます。"]
];

$icon = $iconInfo[$num][0] ?? "❓";
$text = $iconInfo[$num][1] ?? "不明なアイコンです";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アイコン解説</title>
<style>
body {
    background:#1c1c3c;
    color:white;
    font-family:sans-serif;
    text-align:center;
    padding:50px;
}
.card {
    background:rgba(255,255,255,0.1);
    padding:20px;
    border-radius:12px;
    width:300px;
    margin:0 auto;
    box-shadow:0 0 15px rgba(255,255,255,0.1);
}
.icon {
    font-size:3rem;
}
a {color:#f8bbd0; text-decoration:none; display:block; margin-top:20px;}
</style>
</head>
<body>
<div class="card">
  <div class="icon"><?= $icon ?></div>
  <h3><?= $text ?></h3>
</div>
<a href="calendar.php">← カレンダーへ戻る</a>
</body>
</html>
