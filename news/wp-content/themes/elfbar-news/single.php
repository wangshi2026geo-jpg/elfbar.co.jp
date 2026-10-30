<?php get_header(); ?>
<main class="article">
<?php while (have_posts()) : the_post(); ?>
  <div class="wrap breadcrumbs"><a href="https://www.elfbar.co.jp/">ホーム</a> / <a href="<?php echo esc_url(home_url('/')); ?>">ニュース</a> / <?php the_title(); ?></div>
  <div class="wrap">
    <article <?php post_class(); ?>>
      <header class="article-hero">
        <?php if (has_post_thumbnail()) { the_post_thumbnail('full', ['fetchpriority' => 'high']); } ?>
        <div class="article-hero-overlay">
          <?php $label = get_post_meta(get_the_ID(), 'elfbar_evidence_label', true); if ($label) : ?>
            <span class="article-label"><?php echo esc_html($label); ?></span>
          <?php endif; ?>
          <h1><?php the_title(); ?></h1>
          <?php if (has_excerpt()) : ?><p class="lead"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
          <div class="article-meta">
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
            <span>ELFBAR Japan 編集部</span>
          </div>
        </div>
      </header>
      <?php $caption = get_post_meta(get_the_ID(), 'elfbar_image_caption', true); if ($caption) : ?>
        <p class="article-caption"><?php echo esc_html($caption); ?></p>
      <?php endif; ?>
      <div class="article-content"><?php the_content(); ?></div>
    </article>
  </div>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>

