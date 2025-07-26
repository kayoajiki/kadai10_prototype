<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("funcs.php");
loginCheck();

$pdo = db_conn();
$user_id = $_SESSION["user_id"];

// ✅ ユーザー生年月日取得
$stmt = $pdo->prepare("SELECT birthdate FROM users WHERE id = :id");
$stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$birth = $stmt->fetch(PDO::FETCH_ASSOC)["birthdate"];

// ✅ パーソナルデイ計算
function getPersonalDay($birthdate, $date){
    $digits = str_split(str_replace('-', '', $birthdate.$date));
    $sum = array_sum($digits);
    while ($sum > 9 && $sum != 11 && $sum != 22 && $sum != 33) {
        $sum = array_sum(str_split($sum));
    }
    return $sum > 9 ? $sum % 9 : $sum;
}

$icons = [
  1 => "🚀✨", 2 => "🤝😊", 3 => "🎨🎤", 4 => "🧱📈",
  5 => "🍃🌀", 6 => "🏠❤️", 7 => "🧘‍♀️🔍", 8 => "💼💰", 9 => "🍂🕊️"
];

// ✅ 今月
$year = date("Y");
$month = date("m");
$firstDay = date("Y-m-01");
$lastDay = date("Y-m-t");

// ✅ 今月の気分データ
$stmt = $pdo->prepare("SELECT id, date, mood, comment FROM diary WHERE user_id = :user_id AND date BETWEEN :start AND :end");
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':start', $firstDay, PDO::PARAM_STR);
$stmt->bindValue(':end', $lastDay, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dayMap = [];
foreach ($results as $row) {
  $dayMap[$row["date"]] = [
    "id" => $row["id"],
    "mood" => $row["mood"],
    "comment" => trim($row["comment"])
  ];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title><?= $year ?>年<?= $month ?>月の気分カレンダー</title>
<style>
  body {background:#1c1c3c;color:white;font-family:sans-serif;text-align:center;padding:40px;}
  table {margin:0 auto;border-collapse:collapse;}
  th, td {width:60px;height:60px;border:1px solid #444;vertical-align:top;padding:4px;position:relative;}
  .has-comment {background-color:#2a2a55;}
  .day-number a {font-size:0.8rem;text-align:left;display:block;color:#fff;text-decoration:none;}
  .day-icons {text-align:center;margin-top:4px;font-size:1rem;line-height:1.3;min-height:30px;}
  .pd-icon {font-size:1.2rem;display:inline-block;margin-top:2px;text-decoration:none;}
  .nav-links {margin-top:20px;}
  .nav-links a {color:#f8bbd0;text-decoration:none;margin:0 10px;}
</style>
</head>
<body>
<h2>📅 <?= $year ?>年<?= $month ?>月の気分</h2>
<table>
<tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr>
<?php
$firstWeekday = date('w', strtotime($firstDay));
$daysInMonth = date('t');
$day = 1; $cell = 0;

echo "<tr>";
for (; $cell < $firstWeekday; $cell++) echo "<td></td>";

for (; $day <= $daysInMonth; $day++, $cell++) {
    $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
    $info = $dayMap[$date] ?? null;

    $mood = $info ? str_repeat("💗", $info["mood"]) : "";
    $hasComment = $info && $info["mood"] == 0 && !empty($info["comment"]);
    $url = $info && isset($info["id"]) ? "edit.php?id={$info["id"]}" : "edit.php?date=$date";
    $class = $hasComment ? ' class="has-comment"' : '';

    $personalDay = getPersonalDay($birth, $date);
    $showIcon = (rand(1,3) === 1); // ランダム表示（3日に1回）

    echo "<td{$class}>";
    echo "<div class='day-number'><a href='$url'>$day</a></div>";
    echo "<div class='day-icons'>";
    if ($mood) echo "$mood<br>";
    if ($showIcon) echo "<a href='icon_info.php?n=$personalDay' target='_blank' class='pd-icon'>".$icons[$personalDay]."</a><br>";
    if ($hasComment) echo "💬";
    echo "</div>";
    echo "</td>";

    if ($cell % 7 == 6) echo "</tr><tr>";
}
while ($cell % 7 != 0) { echo "<td></td>"; $cell++; }
echo "</tr>";
?>
</table>

<div class="nav-links">
  <a href="home.php">← 今日の気分を記録する</a> |
  <a href="life_path.php">🔮 ライフパスナンバーを見る</a> |
  <a href="logout.php">🚪 ログアウト</a>
</div>
</body>
</html>
