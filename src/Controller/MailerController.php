<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Attribute\Route;

class MailerController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function sendEmail(MailerInterface $mailer)
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
        $message = $_POST['message'];

        if (isset($_POST['form-type'])) {
            switch ($_POST['form-type']){
                case 'contact':
                    $subject = "A message from your site visitor ($name)";
                    break;
                case 'subscribe':
                    $subject = "Subscribe request ($name)";
                    break;
                case 'order':
                    $subject = "Order request ($name)";
                    break;
                default:
                    $subject = "A message from your site visitor ($name)";
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

        $email = (new TemplatedEmail())
        ->from('hostmaster@compuhelp-enterprises.ca')
        ->to('rene.lanteigne@compuhelp-enterprises.ca')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject($subject)
        ->text($from . ": " . $message)
        ->htmlTemplate('/email/contact.html.twig')
        ->context([
                                                           'subject'=>$subject,
                                                           'name'=>$name,
                                                           'fromEmail'=>$from,
                                                           'message'=>$message
                                                        ]);

    $mailer->send($email);

    DIE('MF000');
    }
}
