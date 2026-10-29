<?php
require_once __DIR__ . '/../config/site.php';
require_once __DIR__ . '/../lib/content.php';

$ogp_title = 'デジタルパンフレット | ' . $site_config['festival_label'];
$ogp_description = '高専祭2026のデジタルパンフレット。校内マップや出店・イベント情報をご覧いただけます。';
$ogp_image = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $site_config['base_path'] . 'images/poster_yoko_2026.webp';

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
    <link rel="stylesheet" href="../css/news.css?v=<?= filemtime(__DIR__ . '/../css/news.css') ?>" nonce="<?= htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8') ?>">
    <?php include __DIR__ . '/includes/header-favicon.php'; ?>
    <script src="../js/hamburger.js" nonce="<?= htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8') ?>" defer></script>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <div class="wrapper">
        <main>
            <?php include __DIR__ . '/includes/breadcrumb.php'; render_breadcrumb(); ?>
            <p class="title">デジタルパンフレット</p>
            <div class="news_content">
                <p>校内マップや出店・イベント情報を掲載しています。画像をタップすると拡大できます。</p>
                <p><a class="link" href="../attachment/kosensai2026_pamphlet.pdf">PDFを開く</a> ／ <a class="link" href="../attachment/kosensai2026_pamphlet.pdf" download>PDFをダウンロード</a></p>
                <nav class="pamphlet-index" aria-label="パンフレットの目次">
                    <a href="#spread-3">校内マップ</a>
                    <a href="#spread-4">出店・企画一覧</a>
                    <a href="#spread-8">企画スケジュール</a>
                    <a href="#spread-9">シャトルバス</a>
                </nav>
                <div class="pamphlet-pages">
                <?php
                $labels = ['表紙', '目次・ご挨拶（1〜2ページ）', '校内マップ（3〜4ページ）', '出店団体一覧（5〜6ページ）', '出店団体一覧（7〜8ページ）', '出店団体・企画一覧（9〜10ページ）', '出店団体・企画一覧（11〜12ページ）', '企画スケジュール（13〜14ページ）', 'シャトルバス（15〜16ページ）', 'ご案内・注意事項（17〜18ページ）', 'スタンプラリー（19〜20ページ）', '実行委員一覧・ハンコを捜せ・協力（21〜22ページ）', '裏表紙'];
                foreach ($labels as $index => $label):
                    $image = '../images/pamphlet/page-' . sprintf('%02d', $index + 1) . '.webp';
                ?>
                    <figure id="spread-<?= $index + 1 ?>">
                        <figcaption><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></figcaption>
                        <a href="<?= $image ?>"><img src="<?= $image ?>" alt="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>。タップして拡大" width="1685" height="1191" loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"></a>
                    </figure>
                <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
