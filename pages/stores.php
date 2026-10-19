<?php
require_once __DIR__ . '/../config/site.php';

// OGP settings
$ogp_title = '出店一覧 | ' . $site_config['festival_label'];
$ogp_description = $site_config['festival_label'] . 'の出店一覧です。各クラスや部活動、教職員による出店情報を確認できます。';

session_start();
$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: ".
"    default-src 'self';".
"    script-src 'self' 'nonce-" . $nonce . "';".
"    style-src 'self' 'nonce-" . $nonce . "';".
"    frame-src 'self';".
"    frame-ancestors 'none';"
);

include './includes/stores_array.php';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ogp_title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="../css/stores.css" nonce="<?= htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8') ?>">
    <?php include './includes/header-favicon.php'; ?>
    <script src="../js/stores.js" nonce="<?= htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <script src="../js/hamburger.js" nonce="<?= htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</head>

<body>
    <?php include_once './includes/header.php'; ?>
    <main>
        <?php include './includes/breadcrumb.php'; render_breadcrumb(); ?>
        <p class="title">出店一覧</p>
        <div id="s_container">
            <?php foreach ($stores as $store) : ?>
                <figure id="<?= htmlspecialchars($store['id'], ENT_QUOTES, 'UTF-8') ?>" class="s_items" data-name='<?= htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8') ?>' data-location='<?= htmlspecialchars($store['location'], ENT_QUOTES, 'UTF-8') ?>' data-description='<?= htmlspecialchars($store['description'], ENT_QUOTES, 'UTF-8') ?>' data-news-link='<?= htmlspecialchars($store['news_link'] ?? '', ENT_QUOTES, 'UTF-8') ?>'>
                    <img src="<?= htmlspecialchars($store['image'], ENT_QUOTES, 'UTF-8') ?>" class="s_pic" alt="<?= htmlspecialchars($store['alt'], ENT_QUOTES, 'UTF-8') ?>" />
                </figure>
            <?php endforeach; ?>
        </div>
        
        <div class="border"></div>
        <p class="b_title">出店マップ</p>
        
        <div class="b_container">
            <p class="b_info">クリックまたはタップで画像を拡大できます。</p>
        </div>
        <div class="map_container">
            <a href="../images/map.webp" target="_self">
                <img src="../images/map66.webp" class="image_map" alt="全体の配置概図"/>
            </a>
            <a href="../images/detailmap-square.webp" target="_self">
                <img src="../images/detailmap-square66.webp" class="image_map" alt="広場の配置概図"/>
            </a>
        </div>
        
        <div class="border"></div>
        <p class="b_title">教職員等の出展</p>
        <div class="b_container">
            <p class="b_name">ホームカミングルーム</p>
            <div class="b_info">出店場所：2-201</div>
            <div class="b_info">同窓会に関するパネルなどを展示します。</div>
            <p class="b_name">入試相談コーナー</p>
            <div class="b_info">出店場所：2-202</div>
            <div class="b_info">入試に関する質問を受け付けます。</div>
            <p class="b_name">寮の紹介</p>
            <div class="b_info">出店場所：2-202</div>
            <div class="b_info">寮に関する説明を行います。</div>
            <p class="b_name">とうほくプロコン応援 こども創造フェス</p>
            <div class="b_info">出店場所：6-101</div>
            <div class="b_info">LEDやじろべえをはんだづけで作るワークショップを開催します。プログラミング部などの学生による作品展示もあります！なお、本ワークショップは、COOPトリプルカードみやぎスマイル基金の助成を受けて実施します。</div>
            <p class="b_name">体験コーナー</p>
            <div class="b_info">出店場所：6-201</div>
            <div class="b_info">簡単な電子工作 －たたいてピッカリ光るLED－の体験を行います。</div>
            <p class="b_name">コース紹介コーナー</p>
            <div class="b_info">出店場所：6-209</div>
            <div class="b_info">広瀬キャンパスのコース紹介パネルを展示します。</div>
            <p class="b_name">後援会ブース</p>
            <div class="b_info">出店場所：ピロティ</div>
            <div class="b_info">保護者や教職員から提供品を募ってバザーで販売します。</div>
        </div>
    </main>
    <!--モダール構成部分-->
    <div id="modal" class="nodisp">
        <div id="modal_bg"></div>
        <div id="modal_inner">
            <div id="modal_root">
                <div id="modal_head">
                    <button class="close-button">
                        <div class="close-button__line"></div>
                        <div class="close-button__line"></div>
                    </button>
                    <div id="modal_title">
                        企画名
                    </div>
                </div>
                <img src="<?= htmlspecialchars($site_config['base_path'] . 'images/stores/store_modal_placeholder.webp', ENT_QUOTES, 'UTF-8') ?>" id="modal_img" alt="企画名" /><br>
                <div id="modal_place">
                    場所
                </div>
                <div id="modal_txt">
                    出店詳細
                </div>
            </div>
            <a id="prev" class="modal_pn">❮</a>
            <a id="next" class="modal_pn">❯</a>
        </div>
    </div>
    <?php include_once './includes/footer.php'; ?>
    <?php
    if (isset($_GET['store'])) {
        $store_id = $_GET['store'];
        $selected_store_index = -1;
        foreach ($stores as $index => $store) {
            if ($store['id'] === $store_id) {
                $selected_store_index = $index;
                break;
            }
        }

        if ($selected_store_index !== -1) {
            echo "<script nonce='" . htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8') . "'>";
            echo "window.addEventListener('load', function() {";
            echo "    const storeElement = document.getElementById('" . htmlspecialchars($store_id, ENT_QUOTES, 'UTF-8') . "');";
            echo "    if (storeElement) { showModal.call({ img_id: " . $selected_store_index . " }, { currentTarget: storeElement }); }";
            echo "});";
            echo "</script>";
        }
    }
    ?>
</body>


</html>
