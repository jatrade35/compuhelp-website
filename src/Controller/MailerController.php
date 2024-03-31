<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use PHPMailer;
use phpmailerException;
Use Exception;

require '/var/www/html/compuhelp/src/Librairies/phpmailer/class.phpmailer.php';

class MailerController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function sendEmail()
    {

        $name = "";
        if(isset($_POST['name']))
        {
            $name = $_POST['name'];
        }
        elseif(isset($_POST['first-name']))
        {
            $name = $_POST['first-name'] . " " . $_POST['last-name'];
        }

        $from= $_POST['email'];
        $message = "";

        if (isset($_POST['form-type'])) {
            switch ($_POST['form-type']){
                case 'contact':
                    $subject = "A message from your site visitor ($name)";
                    $message = $_POST['message'];
                    break;
                case 'subscribe':
                    $subject = "Subscribe request ($name)";
                    break;
                case 'order':
                    $subject = "Order request ($name)";
                    break;
                default:
                    $subject = "A message from your site visitor ($name)";
                    $message = $_POST['message'];
                    break;
            }
        }
        else
        {
            if(isset($_POST['subject']))
            {
                $subject = $_POST['subject'];
            }
            else
            {
                DIE('MF004');
            }
        }

        $mail = new PHPMailer(true);

        try{
            $mail->SetFrom("hostmaster@compuhelp-enterprises.ca", "Compuhelp Webmaster", 0);
            $mail->addAddress("rene.lanteigne@compuhelp-enterprises.ca");
            $mail->CharSet = 'utf-8';
            $mail->Subject = $subject;

            $template = $this->render('email/contact.html.twig',
                                      [ 'name' => $name,
                                        'fromEmail' => $from,
                                        'subject' => $subject,
                                        'message' => $message])->getContent();

            $mail->MsgHTML($template);

            $mail->send();            

            DIE('MF000');

        } catch (phpmailerException $e) {
            die('MF254: '.$e);
        } catch (Exception $e) {
            die($e.'MF255');
        }
    }
}
