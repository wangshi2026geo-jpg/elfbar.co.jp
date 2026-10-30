<?php
declare(strict_types=1);

function elfbar_news_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
}
add_action('after_setup_theme', 'elfbar_news_setup');

function elfbar_news_assets(): void {
    wp_enqueue_style('elfbar-news', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
    wp_enqueue_script('elfbar-news-navigation', get_template_directory_uri() . '/navigation.js', [], wp_get_theme()->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'elfbar_news_assets');

function elfbar_news_excerpt_length(): int {
    return 72;
}
add_filter('excerpt_length', 'elfbar_news_excerpt_length');

function elfbar_news_document_title(string $title): string {
    if (is_home()) {
        return 'ニュース・取扱店向け情報 | ELFBAR Japan';
    }
    return $title;
}
add_filter('pre_get_document_title', 'elfbar_news_document_title');

function elfbar_news_body_classes(array $classes): array {
    $classes[] = 'elfbar-news-site';
    return $classes;
}
add_filter('body_class', 'elfbar_news_body_classes');

