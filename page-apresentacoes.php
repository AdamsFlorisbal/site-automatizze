<?php
/**
 * Template Name: Página de Apresentações
 */
get_header();
?>

<main id="site-content">
    <section id="apresentacoes" class="page-content">
        <div class="container">
            <div class="section-heading">
                <h1><?php the_title(); ?></h1>
            </div>
            
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="page-description">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>
            
            <div class="apresentacoes-container">
                <?php
                $args = array(
                    'post_type' => 'apresentacao',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                );
                
                $apresentacoes = new WP_Query($args);
                
                if ($apresentacoes->have_posts()) :
                ?>
                    <div class="apresentacoes-grid">
                        <?php while ($apresentacoes->have_posts()) : $apresentacoes->the_post(); 
                            $pdf_id = get_post_meta(get_the_ID(), '_pdf_attachment_id', true);
                            $pdf_url = $pdf_id ? wp_get_attachment_url($pdf_id) : '';
                            $pdf_size = $pdf_id ? size_format(filesize(get_attached_file($pdf_id)), 2) : '';
                            
                            // Obter a data de upload
                            $pdf_post = get_post($pdf_id);
                            $upload_date = $pdf_post ? date_i18n(get_option('date_format'), strtotime($pdf_post->post_date)) : '';
                        ?>
                            <div class="apresentacao-item">
                                <div class="apresentacao-content">
                                    <h3><?php the_title(); ?></h3>
                                    <div class="apresentacao-details">
                                        <?php if ($pdf_url) : ?>
                                            <div class="apresentacao-meta">
                                                <p><i class="far fa-calendar-alt"></i> Publicado em: <?php echo $upload_date; ?></p>
                                                <p><i class="far fa-file-pdf"></i> Tamanho: <?php echo $pdf_size; ?></p>
                                            </div>
                                            <div class="apresentacao-excerpt">
                                                <?php the_excerpt(); ?>
                                            </div>
                                            <a href="<?php echo esc_url($pdf_url); ?>" class="cta-button" target="_blank">
                                                <i class="fas fa-download"></i> Download PDF
                                            </a>
                                        <?php else : ?>
                                            <p>Nenhum arquivo disponível para download.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php 
                else : 
                ?>
                    <div class="no-apresentacoes">
                        <p>Nenhuma apresentação disponível no momento.</p>
                    </div>
                <?php 
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>