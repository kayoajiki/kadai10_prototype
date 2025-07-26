<?php
$showResult = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    function calcLifePath($birthdate) {
        return array_sum(str_split(preg_replace('/[^0-9]/', '', $birthdate))) % 9 ?: 9;
    }

    $your_birthday = $_POST['your_birthday'];
    $partner_birthday = $_POST['partner_birthday'];

    $your_num = calcLifePath($your_birthday);
    $partner_num = calcLifePath($partner_birthday);

    $diff = abs($your_num - $partner_num);
    $score = 100 - ($diff * 10); // 相性スコア計算

    $message = [
        0 => "運命の相手レベル！価値観がピッタリ合います。",
        1 => "似ているようで違う。刺激し合える関係です。",
        2 => "正反対の要素があり、学びの多いご縁です。",
        3 => "意見のすれ違いも。歩み寄りがカギ。",
        4 => "価値観にズレが。互いを理解する努力を。",
        5 => "慎重に関係を育てていきましょう。",
        6 => "テンポや考え方が合いづらいかも。無理せず距離感を保って。",
        7 => "相手に依存せず、自分軸で関係を見直すと◎。",
        8 => "相性は低め。ただしご縁があるならそれは学びです。",
        9 => "全く違うタイプ。無理をしないで自然体で付き合いましょう。"
    ];

    $result = [
        "your_num" => $your_num,
        "partner_num" => $partner_num,
        "score" => $score,
        "advice" => $message[$diff]
    ];
    $showResult = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>相性チェック</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(to bottom, #f9f2f2, #ffe8e8);
      text-align: center;
      padding: 40px 20px;
      color: #333;
    }
    h1 {
      font-size: 28px;
      margin-bottom: 20px;
      color: #d14f3f;
    }
    form {
      background: #ffffffcc;
      padding: 20px;
      border-radius: 12px;
      display: inline-block;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    input[type="date"], input[type="submit"] {
      padding: 10px;
      margin: 10px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background-color: #f38181;
      color: white;
      border: none;
      cursor: pointer;
    }
    .result {
      margin-top: 30px;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      display: inline-block;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease-out;
    }
    .result h2 {
      font-size: 24px;
      color: #ff6b6b;
    }
    .result p {
      font-size: 18px;
      margin-top: 10px;
      line-height: 1.6;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px);}
      to   { opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <h1>💘 あなたと気になる人の相性チェック</h1>
  <form method="POST">
    <div>
      <label>あなたの生年月日：</label><br>
      <input type="date" name="your_birthday" required>
    </div>
    <div>
      <label>相手の生年月日：</label><br>
      <input type="date" name="partner_birthday" required>
    </div>
    <input type="submit" value="相性を見る">
  </form>

  <?php if ($showResult): ?>
    <div class="result">
      <h2>🎯 相性スコア：<?= $result['score'] ?> 点</h2>
      <p>あなたの数秘ナンバー：<?= $result['your_num'] ?><br>
         相手の数秘ナンバー：<?= $result['partner_num'] ?></p>
      <p><?= $result['advice'] ?></p>
    </div>
  <?php endif; ?>
</body>
</html>