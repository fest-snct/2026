<?php
require_once __DIR__ . '/../config/site.php';

// OGP settings
$ogp_title = 'ご挨拶 | ' . $site_config['festival_label'];
$ogp_description = '仙台高専校長と高専祭実行委員長からのご挨拶。';
$ogp_image = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $site_config['base_path'] . 'images/principal.webp';

session_start();
$nonce = base64_encode(random_bytes(16));
$_SESSION['nonce'] = $nonce;
header("Content-Security-Policy: ".
"    default-src 'self';".
"    script-src 'self' 'nonce-" . $nonce . "';".
"    style-src 'self' 'nonce-" . $nonce . "';".
"    frame-src 'self';".
"    frame-ancestors 'none';"
);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ogp_title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="../css/greeting.css" nonce="<?= htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8') ?>">
    <?php include './includes/header-favicon.php'; ?>
    <script src="../js/hamburger.js" nonce="<?= htmlspecialchars($nonce, ENT_QUOTES, "UTF-8"); ?>" defer></script>
</head>
<body>
    <?php include './includes/header.php' ?>
    <div class="wrapper">
        <main>
            <?php include './includes/breadcrumb.php'; render_breadcrumb(); ?>
            <p class="title">校長からの挨拶</p>
            <div class="greeting_contents">
                <div class="greeting">
                    <p class="greeting_post">仙台高専校長</p>
                    <p class="greeting_name">橋爪 秀利</p>
                </div>
                <div class="greeting_content">
                    <img src="../images/principal.webp">
                    <div class="greeting_speech">
                        <p>　今年の⾼専祭のテーマは「Amp.」です。このテーマには、楽しい・嬉しいという感情を引き出し、参加者や学生さん皆さんのポジティブな気持ちをより大きくしたいという思いが込められています。また、AMPは、Accelerated Mobile Page という別の意味の略語でもあり、ウェブサイトのページを瞬時に表示するための手法を表しており、広瀬キャンパスのテーマにぴったりのようにも思います。<p>
                        <p>　さて、今年の高専祭も、個々の個性や多様な考え方を大事にしながら、互いを尊重し合いクラスや学年の枠を越え、様々なイベントを実施し、幸せを感じ笑顔になれることを目指しています。学生が生み出す仙台高専の新しいパフォーマンスと何事にも挑戦するという活発な学生の姿を通して、本高専の創造力と新たな魅力を体験して頂くと同時に、本校の高専祭を是非、楽しんでください。そして高専ならではの魅力を感じていただければ幸いです。また、この機会を通して本校への理解を深めていただければ大変うれしく思います。</p>
                    </div>
                </div>
            </div>
            <div class="border"></div>
            <p class="title">実行委員長からの挨拶</p>
            <div class="greeting_contents">
                <div class="greeting">
                    <p class="greeting_post">高専祭実行委員長</p>
                    <p class="greeting_name">高橋 劉和</p>
                </div>
                <div class="greeting_content">
                    <img src="../images/chairperson.webp">
                    <div class="greeting_speech">
                        <p>　高専祭実行委員長の高橋です。今年度も無事に高専祭を開催できますことを、大変嬉しく思っております。</p>
                        <p>　昨年度のテーマ『彩風』では、活気を取り戻し、新しい挑戦の「風」を吹かせることを目指しました。そして今年、私たちが目指すのは、昨年芽生えた皆さんの情熱を、より大きく、ダイレクトに響かせることです。</p>
                        <p>　今年のテーマは『Amp.（アンプ）』です。学生一人ひとりの「楽しい！」「見てほしい！」という感情の音量を、キャンパス全体、そして足を運んでくださった皆さんを巻き込みながら、大きく増幅（アンプリファイ）させたいという想いを込めました。</p>
                        <p>　毎年恒例の学生出店や文化部発表、実行委員会企画に加え、今年は熱量をさらに高める仕掛けを用意しております。特に、委員のアイデアと熱意から生まれた挑戦的な特別企画や校内装飾など、試行錯誤を重ねて準備した見どころにあふれております。</p>
                        <p>　ぜひ会場で、ご自身の感情が大きく増幅する瞬間を見つけ、心ゆくまでお楽しみください。</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include './includes/footer.php' ?>
</body>
</html>
