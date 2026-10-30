<?php get_header(); ?>
<main>
  <section class="masthead">
    <div class="wrap">
      <p class="eyebrow">News / Retailer Resources</p>
      <h1>ニュース</h1>
      <p>ELFBAR Japanの製品情報、取扱店向けの安全・運用資料、卸売に関するお知らせを掲載します。</p>
    </div>
  </section>
  <section class="news-main">
    <div class="wrap">
      <?php if (have_posts()) : ?>
        <div class="post-grid">
          <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('post-card'); ?>>
              <a class="post-card-link" href="<?php the_permalink(); ?>">
                <div class="post-card-media">
                  <?php if (has_post_thumbnail()) { the_post_thumbnail('large', ['loading' => 'lazy']); } ?>
                </div>
                <div class="post-card-body">
                  <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
                  <h2><?php the_title(); ?></h2>
                  <?php the_excerpt(); ?>
                  <span class="read-more">記事を読む →</span>
                </div>
              </a>
            </article>
          <?php endwhile; ?>
        </div>
        <nav class="pagination" aria-label="記事一覧ページ">
          <?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '前へ', 'next_text' => '次へ']); ?>
        </nav>
      <?php else : ?>
        <div class="empty-state"><p>現在、公開中のお知らせはありません。</p></div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>

