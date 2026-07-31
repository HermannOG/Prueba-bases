<?php require_once __DIR__ . '/auth.php'; ?>
<header class="site-nav">
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <a class="navbar-brand" href="index.php">
                <span class="brand-mark" aria-hidden="true">
                    <span class="brand-cell c-low"></span>
                    <span class="brand-cell c-mid"></span>
                    <span class="brand-cell c-high"></span>
                    <span class="brand-cell c-mid"></span>
                </span>
                <span class="brand-text">RiskGuard<span class="brand-text-accent">.</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#metodologia">Metodología</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#normas">Normas ISO</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#equipo">Equipo</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#contacto">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="evaluacion-riesgos.php">Evaluación de riesgos</a></li>
                </ul>

                <?php if (usuarioAutenticado()): ?>
                    <div class="d-flex align-items-center gap-3">
                        <span style="color: var(--text-muted); font-size: 0.9rem;">
                            <i class="fa-solid fa-user me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>
                        </span>
                        <a href="logout.php" class="btn btn-ghost">Cerrar sesión</a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-ghost me-2">Iniciar sesión</a>
                    <a href="registro.php" class="btn btn-cta">Crear cuenta</a>
                <?php endif; ?>
            </div>

        </div>
    </nav>
</header>
