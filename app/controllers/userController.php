<?php
    namespace app\controllers;
    use app\models\mainModel;

    class userController extends mainModel{
        
        # controller to register #
        public function registerUserController(){

            # Save data from newuser form #
            $name=$this->cleanUpString($_POST['usuario_nombre']);
            $lastName=$this->cleanUpString($_POST['usuario_apellido']);
            $user=$this->cleanUpString($_POST['usuario_usuario']);
            $email=$this->cleanUpString($_POST['usuario_email']);
            $password1=$this->cleanUpString($_POST['usuario_clave_1']);
            $password2=$this->cleanUpString($_POST['usuario_clave_2']);

            # checking required fields #
            if($name==""|| $lastName==""||$user==""||$password1==""||$password2==""){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "No has llenado todos los campos requeridos",
                    "icon" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # verifying data integrity #
            if($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$name)){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "EL nombre no coincide con el formato solicitado",
                    "icon" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            if($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$lastName)){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "EL apellido no coincide con el formato solicitado",
                    "icon" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            if($this->verifyData("[a-zA-Z0-9]{4,20}",$user)){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "EL usuario no coincide con el formato solicitado",
                    "icon" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            if($this->verifyData("[a-zA-Z0-9$@.-]{7,100}",$password1) || 
            $this->verifyData("[a-zA-Z0-9$@.-]{7,100}",$password2)){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "Las claves no coincide con el formato solicitado",
                    "icon" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # verify email #
            if($email!=""){
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $check_email=$this->runQuery("SELECT usuario_email FROM usuario 
                    WHERE usuario_email='$email'");

                    if($check_email->rowCount()>0){
                        $alert=[
                            "type" => "single",
                            "title" => "Ocurrio un error inesperado",
                            "text" => "El email que ha ingresado ya se encuentra registrado",
                            "icon" => "error"
                            ];
        
                            return json_encode($alert);
                            exit();
                    }
                }else{
                    $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "El email no coincide con el formato",
                    "icon" => "error"
                    ];

                    return json_encode($alert);
                    exit();
                }
            }

            # verify passwords #
            if($password1!=$password2){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "Las claves ingresadas no coinciden",
                    "icon" => "error"
                    ];

                    return json_encode($alert);
                    exit();
            }else{
                $password=$password1;
            }

            # verify user #
            $check_user=$this->runQuery("SELECT usuario_usuario FROM usuario 
            WHERE usuario_usuario='$user'");

            if($check_user->rowCount()>0){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "El usuario que ha ingresado ya se encuentra registrado",
                    "icon" => "error"
                    ];

                    return json_encode($alert);
                    exit();
            }

            $user_data_reg=[
                [
                    "campo_nombre"=>"usuario_nombre",
                    "campo_marcador"=>":name",
                    "campo_valor"=>$name
                ],
                [
                    "campo_nombre"=>"usuario_apellidos",
                    "campo_marcador"=>":lastname",
                    "campo_valor"=>$lastName
                ],
                [
                    "campo_nombre"=>"usuario_email",
                    "campo_marcador"=>":email",
                    "campo_valor"=>$email
                ],
                [
                    "campo_nombre"=>"usuario_usuario",
                    "campo_marcador"=>":username",
                    "campo_valor"=>$user
                ],
                [
                    "campo_nombre"=>"usuario_clave",
                    "campo_marcador"=>":password",
                    "campo_valor"=>$password
                ]
            ];

            $register_user=$this->saveData("usuario",$user_data_reg);

            if($register_user->rowCount()==1){
                $alert=[
                    "type" => "cleanup",
                    "title" => "Usuario registrado",
                    "text" => "El usuario ".$name." ".$lastName." se ha registrado con exitó",
                    "icon" => "success"
                    ];     
            }else{
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "No se pudo registrar el usuario, por favor intente nuevamente",
                    "icon" => "error"
                    ];
            }
            return json_encode($alert);
        }

        # controller to list users #
        public function listerUserController($page,$registers,$url,$search){
            $page=$this->cleanUpString($page);
            $registers=$this->cleanUpString($registers);
            $url=$this->cleanUpString($url);
            $url=APP_URL.$url."/";

            $search=$this->cleanUpString($search);
            $table="";

            $page=(isset($page) && $page>0) ? (int) $page : 1;
            $start=($page>0) ? (($page*$registers)-$registers) : 0 ;

            if(isset($search) && $search!=""){

                $query_data="SELECT * FROM usuario WHERE ((usuario_id!='".$_SESSION['id']."') 
                AND (usuario_nombre LIKE '%$search%' OR usuario_apellidos LIKE '%$search%'
                OR usuario_email LIKE '%$search%' OR usuario_usuario LIKE '%$search%')) 
                ORDER BY usuario_nombre ASC LIMIT $start,$registers";

                $query_total="SELECT COUNT(usuario_id) FROM usuario WHERE ((usuario_id!='".$_SESSION['id']."') 
                AND (usuario_nombre LIKE '%$search%' OR usuario_apellidos LIKE '%$search%'
                OR usuario_email LIKE '%$search%' OR usuario_usuario LIKE '%$search%'))";

            }else{

                $query_data="SELECT * FROM usuario WHERE usuario_id!='".$_SESSION['id']."' 
                 ORDER BY usuario_nombre ASC LIMIT $start,$registers";

                $query_total="SELECT COUNT(usuario_id) FROM usuario WHERE usuario_id!='".$_SESSION['id']."'";
            }

            $data = $this->runQuery($query_data);
            $data = $data->fetchAll();

            $total = $this->runQuery($query_total);
            $total = (int) $total->fetchColumn();

            $numberOfPages=ceil($total/$registers);

            $table.='
                <div class="table-container">
                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                            <tr>
                                <th class="has-text-centered">#</th>
                                <th class="has-text-centered">Nombre</th>
                                <th class="has-text-centered">Usuario</th>
                                <th class="has-text-centered">Email</th>
                                <th class="has-text-centered" colspan="3">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
            ';

            if($total>=1 && $page<=$numberOfPages){
                $count=$start+1;
                $page_start=$start+1;
                foreach($data as $rows){
                    $table.='
                    <tr class="has-text-centered">
                        <td>'.$count.'</td>
                        <td>'.$rows['usuario_nombre'].' '.$rows['usuario_apellidos'].'</td>
                        <td>'.$rows['usuario_usuario'].'</td>
                        <td>'.$rows['usuario_email'].'</td>
                        <td>
                            <a href="'.APP_URL.'userUpdate/'.$rows['usuario_id'].'/" class="button is-success is-rounded is-small">Actualizar</a>
                        </td>
                        <td>
                            <form class="FormularioAjax" action="'.APP_URL.'app/ajax/userAjax.php" method="POST" autocomplete="off">

                                <input type="hidden" name="modulo_usuario" value="eliminar">
                                <input type="hidden" name="usuario_id" value="'.$rows['usuario_id'].'">

                                <button type="submit" class="button is-danger is-rounded is-small">Eliminar</button>
                            </form>
                        </td>
				    </tr>
                    ';
                    $count++;
                }
                $page_end=$count-1;
            }else{
                if($total>=1){
                    $table.='
                    <tr class="has-text-centered" >
                    <td colspan="7">
                        <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
                            Haga clic acá para recargar el listado
                        </a>
                    </td>
                    </tr>
                    ';
                }else{
                    $table.='
                    <tr class="has-text-centered" >
                        <td colspan="7">
                            No hay registros en el sistema
                        </td>
	                </tr>
                    ';
                }
            }

            $table.='</tbody></table></div>';

            if($total>=1 && $page<=$numberOfPages){
                $table.='
                <p class="has-text-right">Mostrando usuarios 
                <strong>'.$page_start.'</strong> al <strong>'.$page_end.'</strong> de un 
                <strong>total de '.$total.'</strong></p>
                ';

                $table.=$this->tablePager($page,$numberOfPages,$url,10);
            }

            return $table;
        }
    }