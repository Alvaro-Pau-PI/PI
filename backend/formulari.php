<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger y limpiar datos
    $nombre = htmlspecialchars($_POST["nombre"] ?? '');
    $email = htmlspecialchars($_POST["email"] ?? '');
    $ciclo = htmlspecialchars($_POST["ciclo"] ?? '');
    $telefono = htmlspecialchars($_POST["telefono"] ?? '');
    $terminos = isset($_POST["terminos"]);

    $errors = [];

    // Validar nombre (mínimo 3 caracteres)
    if (strlen(trim($nombre)) < 3) {
        $errors[] = "El nombre debe tener al menos 3 caracteres.";
    }

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email no es válido.";
    }

    // Validar ciclo formativo (no vacío)
    if (empty(trim($ciclo))) {
        $errors[] = "Debe seleccionar un ciclo formativo.";
    }

    // Validar telefono (9 dígitos numéricos)
    if (!preg_match("/^[0-9]{9}$/", trim($telefono))) {
        $errors[] = "El teléfono debe tener exactamente 9 dígitos numéricos.";
    }

    // Validar aceptación de términos
    if (!$terminos) {
        $errors[] = "Debe aceptar los términos y condiciones.";
    }

    // Mostrar errores o mensaje de éxito
    if (!empty($errors)) {
        echo "<h3>Se han encontrado los siguientes errores:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
        echo "<a href='javascript:history.back()'>Volver</a>";
        exit;
    } else {
        // Aquí podrías guardar datos o enviar correo
        echo "<h3>Formulario enviado correctamente. ¡Gracias!</h3>";
    }
} else {
    echo "Formulario no enviado correctamente.";
}
?>