<?php
require "includes/auth.php";
require "includes/db.php";

$errores = [];
$exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre_completo'] ?? '');
    $correo  = trim($_POST['correo'] ?? '');
    $clave   = $_POST['contrasena'] ?? '';
    $clave2  = $_POST['contrasena_confirmar'] ?? '';

    if ($nombre === '' || $correo === '' || $clave === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico no es válido.';
    }
    if (strlen($clave) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    }
    if ($clave !== $clave2) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    if (empty($errores)) {
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errores[] = 'Ya existe una cuenta registrada con ese correo.';
        }
        $stmt->close();
    }

    if (empty($errores)) {
        $hash = password_hash($clave, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO usuarios (nombre_completo, correo, contrasena_hash) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $nombre, $correo, $hash);

        if ($stmt->execute()) {
            $exito = true;
        } else {
            $errores[] = 'No se pudo crear la cuenta. Intenta de nuevo.';
        }
        $stmt->close();
    }
}

$pageTitle = "Crear cuenta";
include "includes/header.php";
include "includes/navbar.php";
?>

<main class="flex-grow-1">
    <section class="section">
        <div class="container" style="max-width: 480px;">

            <span class="section-eyebrow">Acceso</span>
            <h2 class="section-title mb-4">Crear cuenta</h2>

            <?php if ($exito): ?>
                <div class="alert alert-success">
                    Cuenta creada correctamente. Ya puedes
                    <a href="login.php">iniciar sesión</a>.
                </div>
            <?php else: ?>

                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errores as $e): ?>
                                <li><?php echo htmlspecialchars($e); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" class="contact-form" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="nombre_completo" class="form-control"
                               value="<?php echo htmlspecialchars($_POST['nombre_completo'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" class="form-control"
                               value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="contrasena" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="contrasena_confirmar" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-cta btn-lg w-100">Crear cuenta</button>
                </form>

                <p class="mt-3 text-center" style="color: var(--text-muted);">
                    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                </p>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php include "includes/footer.php"; ?>
