<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private readonly PHPMailer $mail;
    public function __construct(
        private string $userEmail,
        private string $pwdEmail,
        private string $smtpEmail,
        private int $portSmtpEmail
    ) {
        $this->mail = new PHPMailer(true); 
    }
    public function getUserEmail() : string {
        return $this->userEmail;
    }
    
    //Méthode pour envoyer les emails
    public function sendEmail(string $destinataire, string $objet, string $body) {
        
        try {
            //Configuration
            $this->configEmail();

            //Expéditeur
            $this->mail->setFrom($this->userEmail, 'Mailer');
            //Destinataire
            $this->mail->addAddress($destinataire);     //Add a recipient

            //Contenu du mail
            //Format HTML
            $this->mail->isHTML(true);
            //Objet du mail                                  //Set email format to HTML
            $this->mail->Subject = $objet;
            //Contenu du mail
            $this->mail->Body    = $body;
            //Envoi le mail
            $this->mail->send();
            
            return 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    //Méthode pour configurer l'envoi de mail
    public function configEmail() {
        //Server settings
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = $this->smtpEmail;                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = $this->userEmail;                     //SMTP username
        $this->mail->Password   = $this->pwdEmail;                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->mail->Port       = $this->portSmtpEmail;
        $this->mail->CharSet = PHPMailer::CHARSET_UTF8;     
    }
}
