<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

$basePath = '/artecalli/public';

// Verificar si hay admin registrado
if (!AuthController::hasAdmin()) {
    header('Location: ' . $basePath . '/admin/register.php');
    exit;
}

// Verificar si esta autenticado
if (!AuthController::isAuthenticated()) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

// Redirigir al panel de productos
header('Location: ' . $basePath . '/admin/productos.php');
exit;
