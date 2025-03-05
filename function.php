<?php
function automatizze_enqueue_styles() {
    wp_enqueue_style('automatizze-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'automatizze_enqueue_styles');


