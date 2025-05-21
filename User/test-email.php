<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $recipient = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if ($recipient) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ecoride.ecf.studi@gmail.com';
            $mail->Password = 'jsdglhptfbkgmwzg';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ecoride.ecf.studi@gmail.com', 'EcoRide');
            $mail->addAddress($recipient);
            $mail->Subject = 'Test PHPMailer';
            $mail->Body = 'Ceci est un test.';

            $mail->send();
            echo 'Message envoyé.';
        } catch (Exception $e) {
            echo "Erreur : {$mail->ErrorInfo}";
        }
    } else {
        echo "Adresse email invalide.";
    }
}
?>

<form method="post">
    <label for="email">Email destinataire :</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Envoyer</button>
</form>
