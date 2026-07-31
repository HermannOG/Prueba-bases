<?php
require "includes/auth.php";
require "includes/db.php";

if (usuarioAutenticado()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $clave  = $_POST['contrasena'] ?? '';

    if ($correo === '' || $clave === '') {
        $error = 'Ingresa correo y contraseña.';
    } else {
        $stmt = $conn->prepare(
            "SELECT id_usuario, nombre_completo, contrasena_hash, activo FROM usuarios WHERE correo = ?"
        );
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();

        if (!$usuario || !password_verify($clave, $usuario['contrasena_hash'])) {
            $error = 'Correo o contraseña incorrectos.';
        } elseif (!$usuario['activo']) {
            $error = 'Esta cuenta está deshabilitada.';
        } else {
            $_SESSION['id_usuario']      = $usuario['id_usuario'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            header('Location: index.php');
            exit;
        }
    }
}

$pageTitle = "Iniciar sesión";
include "includes/header.php";
include "includes/navbar.php";
?>

<main class="flex-grow-1">
    <section class="section">
        <div class="container" style="max-width: 420px;">

            <span class="section-eyebrow">Acceso</span>
            <h2 class="section-title mb-4">Iniciar sesión</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="contact-form" novalidate>
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-cta btn-lg w-100">Entrar</button>
            </form>

            <p class="mt-3 text-center" style="color: var(--text-muted);">
                ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
            </p>

        </div>
    </section>
</main>

<?php include "includes/footer.php"; ?>
