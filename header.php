<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?> - <?php wp_title(); ?></title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap"
      rel="stylesheet"
    >
    <?php wp_head(); ?>
    <script>
        function handleAnchorClick(event, anchor) {
           // Obtém o caminho da homepage a partir do WordPress
           var homePath = '<?php echo parse_url(home_url(), PHP_URL_PATH); ?>';
           if (!homePath) {
             homePath = '/';
           }
        
           // Se não estiver na página inicial ou se houver query string na URL, redireciona para a home com a âncora
           if (window.location.pathname !== homePath || window.location.search) {
             event.preventDefault(); // Impede o comportamento padrão
             window.location.href = homePath + '#' + anchor;
           }
         }
    </script>

  </head>
  <body <?php body_class(); ?>>
    <header>
      <div class="header-container">
        <a href="<?php echo home_url(); ?>">
          <img
            src="<?php echo get_template_directory_uri(); ?>/assets/logo-branco.png"
            class="logo"
            alt="Logo Automatizze"
          >
        </a>
        <div class="menu-toggle" id="mobile-menu">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <nav id="nav-menu">
          <ul>
            <li>
              <a href="#sobre" onclick="handleAnchorClick(event, 'sobre')">Sobre</a>
            </li>
            <li>
              <a href="#servicos" onclick="handleAnchorClick(event, 'servicos')">Serviços</a>
            </li>
            <li>
              <a href="#contato" onclick="handleAnchorClick(event, 'contato')">Contato</a>
            </li>
            <li>
              <a href="#localizacao" onclick="handleAnchorClick(event, 'localizacao')">Localização</a>
            </li>
            <li>
              <a href="#suporte" onclick="handleAnchorClick(event, 'suporte')">Suporte</a>
            </li>
            <li>
              <a href="<?php echo esc_url(get_permalink(get_page_by_path('apresentacoes'))); ?>">Apresentação</a>
            </li>
          </ul>
        </nav>
      </div>
    </header>
  </body>
</html>
