<footer class="usuario-footer" style="margin-top: 50px;">
        <div class="social">
            <a href="https://www.instagram.com/delim3ter/"><img src="/public/assets/images/instagram.png" alt="Instagram"></a>
            <a href="#"><img src="/public/assets/images/whatsapp.png" alt="WhatsApp"></a>
            <a href="#"><img src="/public/assets/images/linkedin.png" alt="LinkedIn"></a>
        </div>
        <div class="links">
            <a href="#">Política de Privacidade</a> |
            <a href="#">Contato</a> |
            <a href="#">Termos de uso</a>
        </div>
            <p>&copy; <?php echo date('Y'); ?> - Deliméter LTDA - Todos os direitos reservados.</P>
    </footer>
    <?php
    try {
        if (isset($_SESSION['usuario'])) {
            echo '<script src="/public/assets/scripts/' . $_SESSION['usuario']['tipo'] . '.js"></script>';
        } else {
            echo '<script src="/public/assets/scripts/delimeter.js"></script>';
        }
    } catch (Exception $e) {
        echo "Erro ao incluir o JavaScript do usuário: " . $e->getMessage();
        exit(1);
    }
    ?>
    <script src="/public/assets/scripts/acessibilidade.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
</body>
</html>