<?php
// Configuración de email
$to = 'contacto@insanco.cl';
$subject_prefix = '[INSANCO Web] ';

// Verificar que el formulario fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Función para limpiar datos de entrada
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Obtener y limpiar datos del formulario
$name = clean_input($_POST['name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$phone = clean_input($_POST['phone'] ?? '');
$service = clean_input($_POST['service'] ?? '');
$message = clean_input($_POST['message'] ?? '');

// Validaciones básicas
$errors = [];

if (empty($name)) {
    $errors[] = 'El nombre es requerido';
}

if (empty($email)) {
    $errors[] = 'El email es requerido';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'El email no es válido';
}

if (empty($message)) {
    $errors[] = 'El mensaje es requerido';
}

// Si hay errores, redirigir con mensaje de error
if (!empty($errors)) {
    $error_message = urlencode(implode(', ', $errors));
    header("Location: index.html?error=$error_message");
    exit;
}

// Mapear servicios
$services_map = [
    'proyectos-sanitarios' => 'Proyectos Sanitarios',
    'proyectos-construccion' => 'Proyectos de Construcción',
    'regularizacion' => 'Regularización de Proyectos',
    'asesorias' => 'Asesorías Integrales',
    'otro' => 'Otro'
];

$service_name = $services_map[$service] ?? 'No especificado';

// Preparar el email
$subject = $subject_prefix . 'Nueva consulta de ' . $name;

$email_body = "Nueva consulta recibida desde el sitio web\n\n";
$email_body .= "Nombre: $name\n";
$email_body .= "Email: $email\n";
$email_body .= "Teléfono: " . ($phone ?: 'No proporcionado') . "\n";
$email_body .= "Servicio de interés: $service_name\n\n";
$email_body .= "Mensaje:\n$message\n\n";
$email_body .= "---\n";
$email_body .= "Enviado desde: " . $_SERVER['HTTP_HOST'] . "\n";
$email_body .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
$email_body .= "IP: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']) . "\n";

// Headers del email
$headers = [];
$headers[] = "From: noreply@" . $_SERVER['HTTP_HOST'];
$headers[] = "Reply-To: $email";
$headers[] = "X-Mailer: PHP/" . phpversion();
$headers[] = "Content-Type: text/plain; charset=UTF-8";

// Intentar enviar el email
$mail_sent = mail($to, $subject, $email_body, implode("\r\n", $headers));

if ($mail_sent) {
    // Email enviado exitosamente
    $success_message = urlencode('¡Gracias por contactarnos! Hemos recibido tu mensaje y te responderemos pronto.');
    header("Location: index.html?success=$success_message");
} else {
    // Error al enviar email
    $error_message = urlencode('Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente o contáctanos directamente.');
    header("Location: index.html?error=$error_message");
}

exit;
?>