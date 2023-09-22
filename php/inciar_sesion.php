<?php

# Almacenando datos 
$usuario = limpiar_cadena($_POST['login_usuario']);
$clave = limpiar_cadena($_POST['login_clave']);


# Verificando campos obligatorios
if ($usuario == "" || $clave == "") {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Error!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
    exit();
}

#Verificando integridad de los datos 
if (verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡¡Error!!</strong><br>
                El USUARIO no coincide con el formato solicitado
            </div>
        ';
    exit();
}

#Verificando integridad de los datos 
if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Error!</strong><br>
                La contraseña no coincide con el formato solicitado
            </div>
        ';
    exit();
}

$checar_usuario = conexion();
$checar_usuario = $checar_usuario->query("SELECT * FROM usuario WHERE usuario_usuario='$usuario'");

if ($checar_usuario->rowCount() == 1) {
    $checar_usuario = $checar_usuario->fetch();
    if ($checar_usuario['usuario_usuario'] == $usuario && password_verify($clave, $checar_usuario['usuario_clave'])) {
        $_SESSION['id'] = $checar_usuario['usuario_id'];
        $_SESSION['nombre'] = $checar_usuario['usuario_nombre'];
        $_SESSION['apellido'] = $checar_usuario['usuario_apellido'];
        $_SESSION['usuario'] = $checar_usuario['usuario_usuario'];

        if (headers_sent()) {
            echo "<script> window.location.href='index.php?vista=home' </script>";
        } else {
            header("Location: index.php?vista=home");
        }
    } else {
        echo '
            <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Usuario o clave incorrectos
            </div>
        ';
    }
} else {
    echo '
        <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Usuario o clave incorrectos
        </div>
    ';
}

$checar_usuario = null;
