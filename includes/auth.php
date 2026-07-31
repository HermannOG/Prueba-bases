<?php
/**
 * Helper de autenticación y manejo de sesiones.
 * Incluir este archivo al inicio de cualquier página que necesite
 * saber si hay un usuario logueado, o que requiera login obligatorio.
 */
 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
function usuarioAutenticado(): bool
{
    return isset($_SESSION['id_usuario']);
}
 
/**
 * Llamar al inicio de páginas que requieren login obligatorio.
 * Si no hay sesión activa, redirige a login.php.
 */
function requerirLogin(): void
{
    if (!usuarioAutenticado()) {
        header('Location: login.php');
        exit;
    }
}
 
