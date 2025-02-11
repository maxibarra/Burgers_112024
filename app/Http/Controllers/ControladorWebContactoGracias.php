<?php

namespace App\Http\Controllers;

use App\Entidades\Sucursal;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;


class ControladorWebContactoGracias extends Controller
{
    public function index()
    {
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();
            return view('web.contacto-gracias', compact("aSucursales"));
    }

    public function Contacto(Request $request)
    {
            $sucursal = new Sucursal();
            $aSucursales = $sucursal->obtenerTodos();
            
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       =  env('MAIL_HOST');                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = env('MAIL_USERNAME');                     //SMTP username
                $mail->Password   = env('MAIL_PASSWORD');                               //SMTP password
                $mail->SMTPSecure = env('MAIL_ENCRYPTION');            //Enable implicit TLS encryption
                $mail->Port       = env('MAIL_PORT');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                $mail->addAddress($mail);
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Mensaje enviado desde la web';
                $mail->Body    =  'hola ';

                //$mail->send(); 
                return view('web.contacto-gracias', compact("titulo"));
            } catch (Exception $e) {
                $mensaje = "Hubo un error al enviar el correo. Por favor, intente nuevamente" . $mail->ErrorInfo;
            }

            return view('web.recuperar-clave', compact("titulo", 'mensaje', "aSucursales"));
        }
    
}