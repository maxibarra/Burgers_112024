<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Entidades\Sucursal;
use App\Entidades\Cliente;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ControladorWebRecuperarClave extends Controller
{
    public function index(Request $request)
    {
            $sucursal = new Sucursal();
            $aSucursales = $sucursal->obtenerTodos();
            return view('web.recuperar-clave', compact("aSucursales"));
    }

    public function recuperar(Request $request){
        $titulo = "Recuperar Clave";
        $correo = $request->input('txtCorreo');
        $clave = rand(1000, 9999);
        
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();

        $cliente = new Cliente();
        $cliente->obtenerPorCorreo($correo);
        

        if($cliente ->correo != ""){
            $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
            
            $cliente->clave = $claveEncriptada;
            $cliente->guardar();

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
                $mail->addAddress($correo);              
                

              

                //Content
                $mail->isHTML(true);                                  
                $mail->Subject = 'Recuperar la Clave';
                $mail->Body    = "Los datos de acceso son:
                Usuario: $cliente->correo
                Clave Temporal: $clave
                <strong>Cambia esta clave al iniciar sesión</strong>";

                //$mail->send(); 
                $cliente->clave = password_hash($clave,PASSWORD_DEFAULT);
                $cliente->guardar();

                $mensaje = "La nueva Clave es $clave y te la hemos enviado al correo";

                return view('web.recuperar-clave', compact("titulo","mensaje", "aSucursales"));
            } catch (Exception $e) {
                $mensaje = "Hubo un error al enviar el correo. Por favor, intente nuevamente" . $mail->ErrorInfo;    
                return view('web.recuperar-clave', compact("titulo", 'mensaje', "aSucursales"));
            }
        }else{
            $mensaje = "El email ingresado no existe";
            return view('web.recuperar-clave', compact("titulo","mensaje", "aSucursales"));
        }
    }
}