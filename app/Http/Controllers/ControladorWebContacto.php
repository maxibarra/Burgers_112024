<?php

namespace App\Http\Controllers;

use App\Entidades\Sucursal;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';

class ControladorWebContacto extends Controller
{
    public function index()
    {
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();
        return view('web.contacto',compact ('aSucursales'));
    }

    public function enviar(Request $request)
    {
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();

        $titulo = "Contacto";
        $nombre = $request->input('txtNombre');
        $correo = $request->input('txtCorreo');
        $asunto = $request->input('txtAsunto');
        $mensaje = $request->input('txtMensaje');

        if($nombre != "" && $correo != "" && $asunto != "" && $mensaje != ""){
    
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       =  env('MAIL_HOST');                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = env('MAIL_USERNAME');                     //SMTP username
                $mail->Password   = env('MAIL_PASSWORD');                               //SMTP password
                $mail->SMTPSecure = env('MAIL_ENCRYPTION');            //Enable implicit TLS encryption
                $mail->Port       = env('MAIL_PORT');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                $mail->addAddress($correo);
                $mail->addReplyTo('no-reply@fmed.uba.ar');
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Mensaje enviado desde la web';
                $mail->Body='los datos del formulario son: 
                 Nombre: ' . $nombre . ' <br>
                 Correo: ' . $correo . '  <br>
                 Asunto: ' . $asunto . '  <br>
                 Mensaje: ' . $mensaje  . '<br>    
                    ';
                // $mail->send(); 
                // print_r($mail->Body);exit;
                return view('web.contacto-gracias', compact("aSucursales"));

            } catch (Exception $e) {
                $msg["ESTADO"] = MSG_ERROR;
                $msg["MSG"] = "Hubo un error al enviar el correo. Por favor, intente nuevamente";
                return view('web.contacto', compact("aSucursales","msg"));
            }   
        }else{            
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "Complete todos los datos";
            return view('web.contacto', compact("aSucursales", "msg"));
        }
    }
}