<footer>
    <div class="footer-container">
        <p class="slogan">Desenvolvendo e comercializando soluções para gestão em Automação Comercial, gerando resultados com excelência.</p>
        <div class="social-icons">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <p>&copy; <?php echo date('Y'); ?> Automatizze Processamento de Dados Ltda. | Todos os direitos reservados.</p>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenu = document.getElementById('mobile-menu');
    const navMenu = document.getElementById('nav-menu');
    
    if (mobileMenu && navMenu) {
        mobileMenu.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
    }
    
    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            if (navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
            
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html>