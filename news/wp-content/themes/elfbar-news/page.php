<?php get_header(); ?>
<main class="article"><div class="wrap breadcrumbs"><a href="https://www.elfbar.co.jp/">ホーム</a> / <?php the_title(); ?></div><div class="article-content"><?php while (have_posts()) : the_post(); the_content(); endwhile; ?></div></main>
<?php get_footer(); ?>

