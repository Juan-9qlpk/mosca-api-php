<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;




class Correo{
    public function cargarVariablesEntorno(): void{
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                $esComentario = strpos($line, '#') === 0;
                $tieneIgual = strpos($line, '=') !== false;
                if (!$esComentario && $tieneIgual) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    $value = trim($value, '"\'');
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    }

    public function enviarCorreo($datinhos, $contrasena){
    
        try{
                $mail = new PHPMailer(true);
                $mail->isSMTP(); 
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = getenv('MAIL_PROPIO');
                $mail->Password = getenv('SMTP_PASSWORD');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->setFrom(getenv('MAIL_PROPIO'), "Administradorinhos de la mosca"); 
                $mail->addAddress($datinhos["email"], "Querido usuario: " . $datinhos["nombre"]);
            // comprobar en controlador que los datos sean correctos [nombre usuario e email enviados]
            // pillarlos de la base de datos y compararlos
            // si es correcto, enviarle a su email los datos para que recupere su contraseña
                $mail->isHTML(true);                         
                $mail->Subject = 'Asunto';
                $mail->Body    =" Contraseña temporal para recuperar el acceso {$contrasena}" ;
                $mail->AltBody = "Su contraseña temporal es la siguiente";

                $mail->send();
        } catch(Exception $e){
            echo "No se pudo enviar el mensaje. Error de correo: {$mail->ErrorInfo}";
        }
    }
}