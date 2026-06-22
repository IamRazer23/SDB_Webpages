<footer class="footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <img src="/images/logo-sd-biosensor.png" alt="SD Biosensor Panamá" class="footer-logo-img">
            <p class="footer-tagline">Diagnóstico que salva vidas</p>
            <p class="footer-copy">
                © {{ date('Y') }} SD Biosensor, INC.<br>
                TODOS LOS DERECHOS RESERVADOS.
            </p>
            <div class="social-icons">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <div class="footer-info">
            <p><strong>CEO:</strong> Hyo Keun Lee</p>
            <p><strong>Oficial de Protección de Datos:</strong> Moon Soo</p>
            <a href="#" class="footer-link">Política de Privacidad</a>

            <p class="mt-2">
                <strong>Sede Central:</strong><br>
                C-4&amp;5Floor, 16, Deogyeong-daero 1556beon-gil,<br>
                Yeongtong-gu, Suwon-si, Gyeonggi-do, 16690,<br>
                REPÚBLICA DE COREA
            </p>

            <p>
                <strong>Oficina Panamá:</strong><br>
                RBS Tower, Piso 14, Ciudad de Panamá, República de Panamá
            </p>

            <p><strong>Tel. Panamá:</strong> +507 XXX-XXXX</p>
            <p>
                <strong>Correo:</strong>
                <a href="mailto:sales@sdbiosensor.com">sales@sdbiosensor.com</a> ·
                <a href="mailto:panama@sdbiosensor.com">panama@sdbiosensor.com</a>
            </p>

            <p class="footer-legal">
                Productos registrados ante la Dirección General de Salud, MINSA Panamá.
            </p>
        </div>

        <div class="footer-nav">
            <h4>Navegación</h4>
            <ul>
                <li><a href="{{ route('home') }}">Sobre Nosotros</a></li>
                <li><a href="{{ route('products.index') }}">Productos</a></li>
                <li><a href="{{ route('research.index') }}">Investigación y Desarrollo</a></li>
                <li><a href="{{ route('media.index') }}">Centro de Medios</a></li>
                <li><a href="{{ route('support.index') }}">Centro de Soporte</a></li>
            </ul>
        </div>
    </div>
</footer>
