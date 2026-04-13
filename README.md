---------------------------------------------
ARTECALLI - Instrucciones de Instalación
PHP + Bootstrap + MySQL (XAMPP)
---------------------------------------------

REQUISITOS:
- XAMPP instalado (incluye Apache, MySQL, PHP)
- Navegador web

--------------------------------------------
PASO 1: COPIAR EL PROYECTO
--------------------------------------------
1. Descarga la carpeta "artecalli" del proyecto
2. Copia toda la carpeta a: C:\xampp\htdocs\
   Resultado: C:\xampp\htdocs\artecalli\

---------------------------------------------
PASO 2: CREAR LA BASE DE DATOS
---------------------------------------------
1. Abre XAMPP Control Panel
2. Inicia Apache y MySQL (click en "Start")
3. Abre phpMyAdmin: http://localhost/phpmyadmin
4. Click en "Importar" en el menú superior
5. Selecciona el archivo: artecalli/database/artecalli_db.sql
6. Click en "Continuar" para importar

NOTA: Esto creará la base de datos "artecalli_db" con:
- Tablas: usuarios, productos, categorías, colores, tipos_material, registro_accesos
- Colores y tipos de material predefinidos
- Categorías de ejemplo
- NO se crea usuario por defecto (lo crearás en el paso 3)

---------------------------------------------
PASO 3: CREAR TU CUENTA DE ADMINISTRADOR
---------------------------------------------
1. Abre: http://localhost/artecalli/public/admin/
2. Como es la primera vez, se mostrará el formulario de REGISTRO
3. Completa los datos para crear tu cuenta de administrador
4. Las próximas veces solo te pedirá las credenciales para iniciar sesión

--------------------------------------------
PASO 5: CONFIGURAR CONEXIÓN (si es necesario)
--------------------------------------------
Si tu MySQL tiene password diferente, edita:
Archivo: artecalli/config/database.php

Cambia estas líneas según tu configuración:
define('DB_HOST', 'localhost');
define('DB_NAME', 'artecalli_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // <-- Pon tu password aquí

---------------------------------------------
CARACTERÍSTICAS
- Validación de productos/categorías duplicados
- Colores y tipos de material con opciones predefinidas
- Registro de accesos 
- Diseño responsive con Bootstrap 5
- Panel admin 
- Funciones CRUD para productos y administradores

---------------------------------------------
SOPORTE
Si tienes problemas:
1. Verifica que Apache y MySQL estén corriendo
2. Verifica la conexión en config/database.php
3. Revisa que la BD se haya importado correctamente
