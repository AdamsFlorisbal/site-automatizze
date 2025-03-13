<?php
function automatizze_enqueue_styles() {
    wp_enqueue_style('automatizze-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'automatizze_enqueue_styles');


/**
 * Registra o Custom Post Type para Apresentações
 */
function automatizze_register_apresentacao_post_type() {
    $labels = array(
        'name'               => 'Apresentações',
        'singular_name'      => 'Apresentação',
        'menu_name'          => 'Apresentações',
        'name_admin_bar'     => 'Apresentação',
        'add_new'            => 'Adicionar Nova',
        'add_new_item'       => 'Adicionar Nova Apresentação',
        'new_item'           => 'Nova Apresentação',
        'edit_item'          => 'Editar Apresentação',
        'view_item'          => 'Ver Apresentação',
        'all_items'          => 'Todas as Apresentações',
        'search_items'       => 'Buscar Apresentações',
        'parent_item_colon'  => 'Apresentações Pai:',
        'not_found'          => 'Nenhuma apresentação encontrada.',
        'not_found_in_trash' => 'Nenhuma apresentação encontrada na lixeira.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'apresentacao'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('apresentacao', $args);
}
add_action('init', 'automatizze_register_apresentacao_post_type');

/**
 * Adiciona metabox para upload de arquivos PDF
 */
function automatizze_add_pdf_metabox() {
    add_meta_box(
        'pdf_metabox',
        'Arquivo PDF',
        'automatizze_pdf_metabox_callback',
        'apresentacao',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'automatizze_add_pdf_metabox');

/**
 * Callback para a metabox de PDF
 */
function automatizze_pdf_metabox_callback($post) {
    wp_nonce_field(basename(__FILE__), 'pdf_metabox_nonce');
    
    $pdf_id = get_post_meta($post->ID, '_pdf_attachment_id', true);
    $pdf_url = $pdf_id ? wp_get_attachment_url($pdf_id) : '';
    ?>
    <p>
        <label for="pdf_attachment">Selecione um arquivo PDF para esta apresentação:</label>
        <input type="hidden" name="pdf_attachment_id" id="pdf_attachment_id" value="<?php echo esc_attr($pdf_id); ?>" />
    </p>
    
    <div id="pdf-preview">
        <?php if ($pdf_url) : ?>
            <p><strong>Arquivo atual:</strong> <a href="<?php echo esc_url($pdf_url); ?>" target="_blank"><?php echo basename($pdf_url); ?></a></p>
        <?php endif; ?>
    </div>
    
    <p>
        <input type="button" id="upload_pdf_button" class="button" value="Selecionar arquivo PDF" />
        <?php if ($pdf_id) : ?>
            <input type="button" id="remove_pdf_button" class="button" value="Remover arquivo PDF" />
        <?php endif; ?>
    </p>

    <script>
    jQuery(document).ready(function($) {
        // Upload PDF
        $('#upload_pdf_button').click(function(e) {
            e.preventDefault();
            
            var custom_uploader = wp.media({
                title: 'Selecionar arquivo PDF',
                button: {
                    text: 'Usar este arquivo'
                },
                multiple: false,
                library: {
                    type: 'application/pdf'
                }
            });

            custom_uploader.on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#pdf_attachment_id').val(attachment.id);
                $('#pdf-preview').html('<p><strong>Arquivo selecionado:</strong> <a href="' + attachment.url + '" target="_blank">' + attachment.filename + '</a></p>');
                $('#remove_pdf_button').show();
            });

            custom_uploader.open();
        });

        // Remover PDF
        $('#remove_pdf_button').click(function(e) {
            e.preventDefault();
            $('#pdf_attachment_id').val('');
            $('#pdf-preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * Salva o ID do anexo PDF
 */
function automatizze_save_pdf_metabox($post_id) {
    // Verifica o nonce
    if (!isset($_POST['pdf_metabox_nonce']) || !wp_verify_nonce($_POST['pdf_metabox_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Verifica autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Verifica permissões
    if ('apresentacao' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Salva o ID do PDF
    if (isset($_POST['pdf_attachment_id'])) {
        update_post_meta($post_id, '_pdf_attachment_id', sanitize_text_field($_POST['pdf_attachment_id']));
    }
}
add_action('save_post', 'automatizze_save_pdf_metabox');

/**
 * Adiciona coluna para o PDF na listagem admin
 */
function automatizze_add_pdf_column($columns) {
    $columns['pdf_file'] = 'Arquivo PDF';
    return $columns;
}
add_filter('manage_apresentacao_posts_columns', 'automatizze_add_pdf_column');

/**
 * Exibe o conteúdo da coluna PDF
 */
function automatizze_show_pdf_column($column, $post_id) {
    if ('pdf_file' === $column) {
        $pdf_id = get_post_meta($post_id, '_pdf_attachment_id', true);
        if ($pdf_id) {
            $pdf_url = wp_get_attachment_url($pdf_id);
            echo '<a href="' . esc_url($pdf_url) . '" target="_blank">Ver PDF</a>';
        } else {
            echo 'Sem arquivo';
        }
    }
}
add_action('manage_apresentacao_posts_custom_column', 'automatizze_show_pdf_column', 10, 2);