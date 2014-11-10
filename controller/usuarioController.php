<?php

/**
 * archivoController
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class usuarioController Extends baseController {

    function index() {

        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;
        $this->registry->template->usu_id = "";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "add";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->FORM_SW = "display:none;";
        $this->registry->template->PATH_J = "jquery";
        $this->registry->template->show('headerG');
        $this->registry->template->show('usuario/tab_usuariog.tpl');
        $this->registry->template->show('footer');
    }

    function load() {
        $this->usuario = new tab_usuario();
        $this->usuario->setRequest2Object($_REQUEST);
        $page = $_REQUEST['page'];
        $rp = $_REQUEST['rp'];
        $sortname = $_REQUEST['sortname'];
        $sortorder = $_REQUEST['sortorder'];
        if (!$sortname)
            $sortname = 'usu_id';
        if (!$sortorder)
            $sortorder = 'desc';
        $sort = "ORDER BY $sortname $sortorder";
        if (!$page)
            $page = 1;
        if (!$rp)
            $rp = 15; //10
        $start = (($page - 1) * $rp);
        $limit = "LIMIT $rp OFFSET $start";
        $query = $_REQUEST['query'];
        $qtype = $_REQUEST['qtype'];
        $where = "";
        if ($query) {
            if ($qtype == 'usu_id')
                $where = " and usu_id = '$query' ";
            elseif ($qtype == 'usu_leer_doc') {
                if (strtolower($query) == 's' || strtolower($query) == 'si')
                    $where = " and usu_leer_doc = '1' ";
                if (strtolower($query) == 'n' || strtolower($query) == 'no')
                    $where = " and usu_leer_doc = '2' ";
            }elseif ($qtype == 'unidad')
                $where = " and uni_id IN (SELECT uni_id FROM tab_unidad WHERE uni_codigo LIKE '%$query%') ";
            elseif ($qtype == 'rol_cod')
                $where = " and rol_id IN (SELECT rol_id FROM tab_rol WHERE rol_cod LIKE '%$query%') ";
            else
                $where = " AND $qtype LIKE '$query%' ";
        }
        $root = "";
        if ($_SESSION ["ROL_COD"] == 'ROOT') {
            $root = "AND u.usu_estado = 0";
        }

        $sql = "SELECT
                tab_usuario.usu_id,
                tab_usuario.usu_apellidos,
                tab_usuario.usu_nombres,
                tab_usuario.usu_login,
                tab_usuario.usu_fono,
                tab_usuario.usu_email,
                tab_rol.rol_descripcion,
                tab_unidad.uni_descripcion
                FROM tab_usuario
                INNER JOIN tab_rol ON tab_rol.rol_id=tab_usuario.rol_id
                INNER JOIN tab_unidad ON tab_unidad.uni_id=tab_usuario.uni_id
                WHERE tab_usuario.usu_estado = 1 
                $root 
                $where 
                $sort 
                $limit";

        $result = $this->usuario->dbselectBySQL($sql);
        $total = $this->usuario->countBySQL("SELECT count (tab_usuario.usu_id)
                                    FROM tab_usuario
                                    INNER JOIN tab_rol ON tab_rol.rol_id=tab_usuario.rol_id
                                    INNER JOIN tab_unidad ON tab_unidad.uni_id=tab_usuario.uni_id
                                    WHERE tab_usuario.usu_estado = 1 
                                    $root 
                                    $where");

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: text/x-json");
        $json = "";
        $json .= "{\n";
        $json .= "page: $page,\n";
        $json .= "total: $total,\n";
        $json .= "rows: [";
        $rc = false;
        $i = 0;
        foreach ($result as $un) {
            if ($rc)
                $json .= ",";
            $json .= "\n{";
            $json .= "id:'" . $un->usu_id . "',";
            $json .= "cell:['" . $un->usu_id . "'";
            $json .= ",'" . addslashes($un->usu_nombres) . "'";
            $json .= ",'" . addslashes($un->usu_apellidos) . "'";
            $json .= ",'" . addslashes($un->rol_descripcion) . "'";
            $json .= ",'" . addslashes($un->uni_descripcion) . "'";
            $json .= ",'" . addslashes($un->usu_fono) . "'";
            $json .= ",'" . addslashes($un->usu_email) . "'";
            $json .= ",'" . addslashes($un->usu_login) . "'";
            $json .= "]}";
            $rc = true;
            $i++;
        }
        $json .= "]\n";
        $json .= "}";
        echo $json;
    }

    function add() {
        $rol = new rol();
        $this->registry->template->roles = $rol->obtenerSelect();
        $unidad = new unidad();
        $this->registry->template->uni_id = $unidad->obtenerSelect();
        $this->registry->template->usu_id = "";
        $this->registry->template->mod_login = "";
        $this->registry->template->usu_nombres = "";
        $this->registry->template->usu_apellidos = "";
        $this->registry->template->usu_fono = "";
        $this->registry->template->usu_email = "";
        $this->registry->template->usu_fech_ing = "";
        $this->registry->template->usu_fech_fin = "";
        $this->registry->template->usu_login = "";
        $this->registry->template->usu_leer_doc = "";
        $this->registry->template->usu_verproy = "NO";

        $this->registry->template->lista_seccion = "";

        $usuario = new usuario();
        $this->registry->template->lista_encuestas = $usuario->allencuesta();

        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;

        $this->registry->template->titulo = "NUEVO USUARIO DEL SISTEMA";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "save";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->FORM_SW = "";
        $this->registry->template->PATH_J = "jquery-1.4.1";
        $this->registry->template->show('headerF');
        $this->registry->template->show('usuario/tab_usuario.tpl');
        $this->registry->template->show('footer');
    }

    function save() {
        $this->usuario = new tab_usuario();
        $this->usuario->setRequest2Object($_REQUEST);
        $this->usuario->setUsu_id($_REQUEST['usu_id']);
        $this->usuario->setUni_id($_REQUEST['uni_id']);
        $this->usuario->setUsu_nombres(strtoupper(trim($_REQUEST['usu_nombres'])));
        $this->usuario->setUsu_apellidos(strtoupper(trim($_REQUEST['usu_apellidos'])));
        $this->usuario->setUsu_iniciales('AA');
        $this->usuario->setUsu_fono($_REQUEST['usu_fono']);
        $this->usuario->setUsu_email($_REQUEST['usu_email']);
        $this->usuario->setUsu_fech_ing(date("Y-m-d"));
        $pass = '';
        if ($_REQUEST['usu_login'] == "") {
            $usuario = new usuario();
            $this->usuario->setUsu_login($usuario->generarLogin($_REQUEST['usu_nombres'], $_REQUEST['usu_apellidos']));
        }
        else
            $this->usuario->setUsu_login(trim($_REQUEST['usu_login']));
        if ($_REQUEST['usu_pass'] != '')
            $pass = md5(trim($_REQUEST['usu_pass']));
        else
            $pass = '';
        $this->usuario->setUsu_pass($pass);
        
        $this->usuario->setUsu_pass_fecha(date("Y-m-d"));
//        $this->usuario->setUsu_pass_dias($pass_dias);
        $this->usuario->setUsu_estado(1);
        $this->usuario->setRol_id($_REQUEST['usu_rol_id']);
        $usu_id = $this->usuario->insert();
        $serie = "";
        if (isset($_REQUEST['lista_encuesta'])) {
            $encuesta = $_REQUEST['lista_encuesta'];
            foreach ($encuesta as $serie) {
                $use = new tab_usu_encuesta();
                $use->setUsu_id($usu_id);
                $use->setEnc_id($serie);
                $use->setUen_estado(1);
                $use->insert();
            }
        }

        
//        // Mailer
//        $nombre = $_REQUEST['usu_nombres'] . " " . $_REQUEST['usu_apellidos'];
//        $email = $_REQUEST['usu_email'];
//        $encuesta = new encuesta ();
//        $nombreEncuesta = "";
//        if ($serie)
//            $nombreEncuesta = $encuesta->obtenerEncuestaNombre($serie);
////        // Test
////        $email = "ariel.blanco@planificacion.gob.bo";
//        
//        try {
//            // Include phpmail.php
//            require_once ('includes/class.phpmailer.php'); 
//            // Phpmailer instance
//            $mail = new PHPMailer();
//            $mail->SetLanguage("es", "includes/");
//            // SMTP definition
//            $mail->IsSMTP();
//            //Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
//            // 0 = off (producción)
//            // 1 = client messages
//            // 2 = client and server messages
//            $mail->SMTPDebug  = 0;
//            
//            // Gmail SMTP
//            $mail->Host       = 'smtp.gmail.com';
//            // Port
//            $mail->Port       = 587;
//            // Encription
//            $mail->SMTPSecure = 'tls';
//            // Gmail authentication
//            $mail->SMTPAuth   = true;
//            // Gmail account
//            $mail->Username   = "dggebolivia@gmail.com";
//            // Password account
//            $mail->Password   = "Pl4n1f1c4c10n";
//            // Destinatary: (email, name optional)
//            $mail->AddAddress($email, $nombre);  
//            
//            // CC
////            $mail->AddCC("ariel.blanco@planificacion.gob.bo");
//            $mail->AddCC("arsenio.castellon@planificacion.gob.bo");
//            // BCC
////            $mail->AddBCC("arsenio.castellon@planificacion.gob.bo");
//            $mail->AddBCC("arseniocastellon@gmail.com");
//            // Reply to
//            $mail->AddReplyTo('arsenio.castellon@planificacion.gob.bo','Arsenio Castellon');        
//            // Remitente (email, name optional)
//            $mail->SetFrom('arsenio.castellon@planificacion.gob.bo', 'Arsenio Castellon');
//            // Subject email
//            
//            if ($_REQUEST['usu_rol_id'] == 1){
//                $mail->Subject = 'Creacion de cuenta de correo para administrar el Sistema de Accidentes - DGGE : ';
//                // Format HTML to send with load file
////            $mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
//                $mail->MsgHTML("<b>Estimado " . $nombre .":</b>"
//                        . "<br>Se creo una Cuenta de Administrador para usted para el acceso al Sistema de Accidentes de la DGEE - Ministerio de Planificacion"
//                        . "<br>La direccion para administrar el sistema es la siguiente:"
//                        . "<br><a href='http://192.168.7.143/encuestas/'>http://192.168.7.143/encuestas/" . "</a>"                    
//                        . "<br><br>Puede usar las siguientes credenciales para el acceso:" 
//                        . "<br>Usuario: " . $_REQUEST['usu_login']
//                        . "<br>Password: " . $_REQUEST['usu_pass']
//                        . "<br><br>Gracias !");
//                
//            }else{
//                $mail->Subject = 'Creacion de cuenta de correo para llenar la: ' . $nombreEncuesta . " de la DGGE";
//                // Format HTML to send with load file
////            $mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
//                $mail->MsgHTML("<b>Estimado " . $nombre .":</b>"
//                        . "<br>Se creo una Cuenta de Usuario para usted para el acceso al Sistema de Accidentes de la DGEE - Ministerio de Planificacion"
//                        . "<br>La direccion para el llenado de la encuesta es la siguiente:"
//                        . "<br><a href='http://192.168.7.143/encuestas/'>http://192.168.7.143/encuestas/" . "</a>"                    
//                        . "<br><br>Puede usar las siguientes credenciales para el acceso:" 
//                        . "<br>Usuario: " . $_REQUEST['usu_login']
//                        . "<br>Password: " . $_REQUEST['usu_pass']
//                        . "<br><br>Gracias por su colaboracion !");
//                
//            }
//
//            // Alternate for block
//            $mail->AltBody = 'This is a plain-text message body';
//            // Send mail
//            if(!$mail->Send()) {
//              echo "Error: " . $mail->ErrorInfo;
//              Header("Location: " . PATH_DOMAIN . "/usuario/index/");
//            } else {
//              echo "Enviado!";
//              Header("Location: " . PATH_DOMAIN . "/usuario/index/");
//            }        
//        } catch (phpmailerException $e) {
//            // PhpMailer Error
//            $result .= "<b class='red'>Error: PhpMailer.</b><br />".$e->errorMessage()."</b>";
//            echo $result;            
//        } catch (Exception $e) {
//            // Other Error
//            $result .= "<b class='red'>Error: </b><br />".$e->getMessage()."</b>";
//            echo $result;
//        }        
        
        
        
        Header("Location: " . PATH_DOMAIN . "/usuario/");
    }

    function edit() {
        Header("Location: " . PATH_DOMAIN . "/usuario/view/" . $_REQUEST["usu_id"] . "/");
    }

    function view() {
        if (!VAR3) {
            die("Error del sistema 404");
        }
        $this->usuario = new tab_usuario();

        $this->usuario->setRequest2Object($_REQUEST);
        $row = $this->usuario->dbselectByField("usu_id", VAR3);
        if (!$row) {
            die("Error del sistema 404");
        }
        $row = $row[0];
        $this->registry->template->usu_id = $row->usu_id;
        $this->registry->template->mod_login = " disabled=\"disabled\"";
        $unidad = new unidad();
        $this->registry->template->uni_id = $unidad->obtenerSelect($row->uni_id);
        $rol = new rol();
        $this->registry->template->roles = $rol->obtenerSelect($row->rol_id);

        $this->registry->template->usu_nombres = $row->usu_nombres;
        $this->registry->template->usu_apellidos = $row->usu_apellidos;
        $this->registry->template->usu_fono = $row->usu_fono;
        $this->registry->template->usu_email = $row->usu_email;
        $this->registry->template->usu_nro_item = $row->usu_nro_item;
        $this->registry->template->usu_fech_ing = $row->usu_fech_ing;
        $this->registry->template->usu_fech_fin = $row->usu_fech_fin;
        $this->registry->template->usu_login = $row->usu_login;
        $this->registry->template->usu_leer_doc = $row->usu_leer_doc;
        $this->registry->template->usu_verproy = $row->usu_verproy;

        $usuario = new usuario();
        $this->registry->template->lista_encuestas = $usuario->allencuestaSeleccionado($row->usu_id);
        $this->registry->template->lista_seccion = "";



        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;

        $this->registry->template->titulo = "EDITAR USUARIO DEL SISTEMA";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "update";
        $this->registry->template->PATH_J = "jquery";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->FORM_SW = "";
        $this->registry->template->show('headerF');
        $this->registry->template->show('usuario/tab_usuario.tpl');
        $this->registry->template->show('footer');
    }

    function update() {
        $this->usuario = new tab_usuario();
        $this->usuario->setRequest2Object($_REQUEST);
        $rol = new rol();
        $this->registry->template->roles = $rol->obtenerSelect();
        $rows = $this->usuario->dbselectByField("usu_id", $_REQUEST['usu_id']);
        $this->usuario = $rows[0];
        $id = $this->usuario->usu_id;
        $this->usuario->setUsu_id($_REQUEST['usu_id']);
        $this->usuario->setUni_id($_REQUEST['uni_id']);
        $this->usuario->setUsu_nombres(strtoupper(trim($_REQUEST['usu_nombres'])));
        $this->usuario->setUsu_apellidos(strtoupper(trim($_REQUEST['usu_apellidos'])));
        $this->usuario->setUsu_iniciales(trim('AA'));
        $this->usuario->setUsu_fono(trim($_REQUEST['usu_fono']));
        $this->usuario->setUsu_email(trim($_REQUEST['usu_email']));
        if ($_REQUEST['usu_pass'] != '')
            $this->usuario->setUsu_pass(md5(trim($_REQUEST['usu_pass'])));
        
        
        
        $this->usuario->setRol_id($_REQUEST['usu_rol_id']);
        $this->usuario->setUsu_estado(1);
        $this->usuario->update();

        // tab_usu_encuesta
        $use = new usu_encuesta();
        $use->deleteXUsuario($id);
        if (isset($_REQUEST['lista_encuesta'])) {
            $encuesta = $_REQUEST['lista_encuesta'];
            foreach ($encuesta as $serie) {
                //insert
                $tuse = new tab_usu_encuesta();
                $tuse->setUsu_id($id);
                $tuse->setEnc_id($serie);
                $tuse->setUen_estado('1');
                $tuse->insert();
            }
        }

        Header("Location: " . PATH_DOMAIN . "/usuario/");
    }

    function delete() {
        $this->usuario = new tab_usuario();
        /* $this->usurolmenu = new Tab_usurolmenu();
          $this->usuario->setRequest2Object($_REQUEST);
          $sql = "UPDATE tab_usurolmenu SET urm_estado='2' Where usu_id='".$_REQUEST['usu_id']."' ";
          $this->usurolmenu->dbselectBySQL($sql); */
        $this->usuario->setUsu_id($_REQUEST['usu_id']);
        $this->usuario->setUsu_estado(2);
        $this->usuario->update();
        $usu_encuesta = new usu_encuesta();
        $usu_encuesta->deleteXUsuario($_REQUEST['usu_id']);
        $usu_uni = new usu_uni();
        $usu_uni->deleteXUsuario($_REQUEST['usu_id']);
    }

    function clonar() {
        Header("Location: " . PATH_DOMAIN . "/usuario/clonar_view/" . $_REQUEST["usu_id"] . "/");
    }

    function clonar_view() {
        if (!VAR3) {
            die("Error del sistema 404");
        }
        $this->usuario = new tab_usuario();
        $usuario = new usuario();
        $this->usuario->setRequest2Object($_REQUEST);
        $row = $this->usuario->dbselectByField("usu_id", VAR3);
        if (!$row) {
            die("Error del sistema 404");
        }
        $row = $row[0];
        $this->registry->template->titulo = "NUEVO USUARIO DEL SISTEMA";
        $this->registry->template->usu_id = "";
        $this->registry->template->mod_login = "";
        $unidad = new unidad();
        $this->registry->template->uni_id = $unidad->listUnidad($row->uni_id);
        $rol = new rol();
        $this->registry->template->roles = $rol->obtenerSelect($row->rol_id);
        $this->registry->template->leer_doc = '<option value="1">LEER</option><option value="2">NO LEER</option>';
        $this->registry->template->crear_doc = 'NO';

        $this->registry->template->usu_nombres = "";
        $this->registry->template->usu_apellidos = "";
        //$this->registry->template->usu_iniciales = $row->usu_iniciales;
        $this->registry->template->usu_fono = "";
        $this->registry->template->usu_email = "";
        $this->registry->template->usu_nro_item = "";
        $this->registry->template->usu_fech_ing = "";
        $this->registry->template->usu_fech_fin = "";
        $this->registry->template->usu_login = "";
        $this->registry->template->usu_leer_doc = "";
        $this->registry->template->usu_verproy = "";


        $encuesta = new encuesta();
        $this->registry->template->lista_encuesta = $usuario->allencuestaSeleccionado($row->usu_id); 


        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;

        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "save";
        $this->registry->template->PATH_J = "jquery";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->FORM_SW = "";
        $this->registry->template->show('headerF');
        $this->registry->template->show('usuario/tab_usuario.tpl');
        $this->registry->template->show('footer');
    }

    function verifLogin() {
        $usuario = new usuario();
        $usuario->setRequest2Object($_REQUEST);
        $usu_id = $_REQUEST['usu_id'];
        $login = strtolower(trim($_REQUEST['login']));
        if ($usuario->existeLogin($login, $usu_id)) {
            echo 'Login ya existe, escriba otro.';
        }
        echo '';
    }

    function verificaPass() {
        $usuario = new Tab_usuario();
        $row = $usuario->dbSelectBySQL("SELECT * FROM tab_usuario WHERE
		usu_id='" . $_SESSION['USU_ID'] . "' AND
		usu_pass ='" . md5($_REQUEST['pass_usu']) . "' ");
        if (count($row))
            echo 'OK';
        else
            echo 'La contrase&ntilde;a no coincide. Digite el password correcto';
    }

    function obtenerCon() {
        $contenedor = new contenedor();
        $res = $contenedor->selectCon(0, $_REQUEST['Usu_id']);
        echo $res;
    }

    function loadAjaxCkeck() {
        $unidad = new unidad();
        $usu_id = $_REQUEST['Usu_id'];
        $uni_id = $_REQUEST['Uni_id'];
        $lista = $unidad->obtenerCheck($uni_id, $usu_id);
        echo $lista;
    }

    function verifyFieldsLogin() {
        $usuario = new usuario();
        $usu_id = $_POST['usu_id'];
        $usu_login = strtolower(trim($_POST['usu_login']));
        if ($usuario->existeLogin($usu_login, $usu_id)) {
            echo 'Login ya existe, escriba otro.';
        } else {
            echo '';
        }
    }

    function listUsuarioJson() {
        $this->usu = new usuario();
        echo $this->usu->listUsuarioJson();
    }

}

?>
