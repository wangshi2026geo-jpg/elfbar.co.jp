<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
  <div class="wrap header-row">
    <a class="brand" href="https://www.elfbar.co.jp/" aria-label="ELFBAR Japan ホーム">
      <img src="https://www.elfbar.co.jp/assets/logo/elfbar-official-logo.png" width="360" height="120" alt="ELFBAR">
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-label="メニューを開閉">☰</button>
    <nav class="nav" aria-label="メインナビゲーション">
      <a href="https://www.elfbar.co.jp/">ホーム</a>
      <a href="https://www.elfbar.co.jp/products.html">製品</a>
      <a class="is-current" href="<?php echo esc_url(home_url('/')); ?>">ニュース</a>
      <a href="https://www.elfbar.co.jp/faq.html">FAQ</a>
      <a href="https://www.elfbar.co.jp/about.html">ブランド</a>
      <a class="nav-cta" href="https://www.elfbar.co.jp/contact.html">卸売申請</a>
    </nav>
  </div>
</header>
<div class="notice-bar">製品表示・仕様はSKUごとに異なります。取扱時は納品資料・成分資料をご確認ください。本サイトは20歳未満の方を対象としていません。</div>

