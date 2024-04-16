<?php
    namespace app\controllers;
    use app\models\mainModel;

    class teacherController extends mainModel{
        
        # controller to register #
        public function registerTeacherController(){

            # Save data from newteacher form #
            $name=$this->cleanUpString($_POST['maestro_nombres']);
            $lastName=$this->cleanUpString($_POST['maestro_apellidos']);
            $tlfn=$this->cleanUpString($_POST['maestro_telefono']);
            $ci=$this->cleanUpString($_POST['maestro_ci']);
            $email=$this->cleanUpString($_POST['maestro_email']);

            # checking required fields #
            if($name==""|| $lastName==""||$tlfn==""||$ci==""||$email==""){
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

            if($this->verifyData("[a-zA-Z0-9]{4,20}",$tlfn)){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "EL telefono no coincide con el formato solicitado",
                    "icon" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            if($this->verifyData("[a-zA-Z0-9]{4,20}",$ci)){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "EL Cedula de identidad no coincide con el formato solicitado",
                    "icon" => "error"
                ];
                return json_encode($alert);
                exit();
            }

        

            # verify email #
            if($email!=""){
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
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


            # verify ci #
            $check_user=$this->runQuery("SELECT maestro_ci FROM maestro 
            WHERE maestro_ci='$ci'");

            if($check_user->rowCount()>0){
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "El maestro que intenta registrar ya se encuentra registrado",
                    "icon" => "error"
                    ];

                    return json_encode($alert);
                    exit();
            }

            // # images directory #
            // $img_dir="../views/photos/";

            // # Check if an image has been selected #
            // if($_FILES['usuario_foto']['name']!="" && 
            // $_FILES['usuario_foto']['size']>0){
                 
            //     # creating image directory #
            //     if(!file_exists($img_dir)){
            //         if(!mkdir($img_dir,0777)){
            //             $alert=[
            //                 "type" => "single",
            //                 "title" => "Ocurrio un error inesperado",
            //                 "text" => "Error al crear el directorio de imagenes",
            //                 "icon" => "error"
            //                 ];
        
            //                 return json_encode($alert);
            //                 exit();
            //         }
            //     }

            //     # check image format #
            //     if(mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/jpeg" && "image/png"){
            //         $alert=[
            //             "type" => "single",
            //             "title" => "Ocurrio un error inesperado",
            //             "text" => "La imagen que ha selecionado no tiene un formato permitido",
            //             "icon" => "error"
            //             ];
    
            //             return json_encode($alert);
            //             exit();
            //     }

            //     # Crecking the image size #
            //     if(($_FILES['usuario_foto']['size']/1024)>5120){
            //         $alert=[
            //             "type" => "single",
            //             "title" => "Limite de peso excedido",
            //             "text" => "La imagen no puede exceder los 5mb",
            //             "icon" => "error"
            //             ];
    
            //             return json_encode($alert);
            //             exit();
            //     }

            //     # photo name #
            //     $photo=str_ireplace(" ","_",$name);
            //     $photo=$photo."_".rand(0,100);

            //     # image extension #
            //     switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
            //         case "image/jpeg":
            //             $photo=$photo.".jpg";
            //         break;
            //         case "image/jpeg":
            //             $photo=$photo.".png";
            //         break;
            //     }

            //     chmod($img_dir,0777);

            //     # moving the image to directory #
            //     if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'],$img_dir.$photo)){
            //         $alert=[
            //             "type" => "single",
            //             "title" => "Ocurrio un error inesperado",
            //             "text" => "No se pudo subir la imagen al sistema",
            //             "icon" => "error"
            //             ];
    
            //             return json_encode($alert);
            //             exit();
            //     }
            // }else{
            //     $photo="";
            // }

            $user_data_reg=[
                [
                    "campo_nombre"=>"maestro_nombres",
                    "campo_marcador"=>":name",
                    "campo_valor"=>$name
                ],
                [
                    "campo_nombre"=>"maestro_apellidos",
                    "campo_marcador"=>":lastname",
                    "campo_valor"=>$lastName
                ],
                [
                    "campo_nombre"=>"maestro_telefono",
                    "campo_marcador"=>":tlfn",
                    "campo_valor"=>$tlfn
                ],
                [
                    "campo_nombre"=>"maestro_ci",
                    "campo_marcador"=>":ci",
                    "campo_valor"=>$ci
                ],
                [
                    "campo_nombre"=>"maestro_correo",
                    "campo_marcador"=>":email",
                    "campo_valor"=>$email
                ]
            ];

            $register_user=$this->saveData("maestro",$user_data_reg);

            if($register_user->rowCount()==1){
                $alert=[
                    "type" => "cleanup",
                    "title" => "Mestro registrado",
                    "text" => "El Maestro ".$name." ".$lastName." se ha registrado con exitó",
                    "icon" => "success"
                    ];     
            }else{
                
                // if(is_file($img_dir.$photo)){
                //     chmod($img_dir.$photo,0777);
                //     unlink($img_dir.$photo);
                // }
                $alert=[
                    "type" => "single",
                    "title" => "Ocurrio un error inesperado",
                    "text" => "No se pudo registrar el maestro, por favor intente nuevamente",
                    "icon" => "error"
                    ];
            }
            return json_encode($alert);
        }

        # controller to list users #
        public function listerTeacherController($page,$registers,$url,$search){
            $page=$this->cleanUpString($page);
            $registers=$this->cleanUpString($registers);
            $url=$this->cleanUpString($url);
            $url=APP_URL.$url."/";

            $search=$this->cleanUpString($search);
            $table="";

            $page=(isset($page) && $page>0) ? (int) $page : 1;
            $start=($page>0) ? (($page*$registers)-$registers) : 0 ;

            if(isset($search) && $search!=""){

                $query_data="SELECT * FROM maestro WHERE ((maestro_id!='".$_SESSION['id']."') 
                AND (maestro_nombres LIKE '%$search%' OR maestro_apellidos LIKE '%$search%'
                OR maestro_correo LIKE '%$search%')) ORDER BY maestro_nombres ASC LIMIT $start,$registers";

                $query_total="SELECT COUNT(maestro_id) FROM maestro WHERE maestro_nombres LIKE '%$search%' OR maestro_apellidos LIKE '%$search%'
                OR maestro_correo LIKE '%$search%'";

            }else{

                $query_data="SELECT * FROM maestro WHERE maestro_id!='".$_SESSION['id']."' 
                 ORDER BY maestro_nombres ASC LIMIT $start,$registers";

                $query_total="SELECT COUNT(maestro_id) FROM maestro WHERE maestro_id!='".$_SESSION['id']."'";
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
                                <th class="has-text-centered">Cedula</th>
                                <th class="has-text-centered">Telefono</th>
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
                        <td>'.$rows['maestro_nombres'].' '.$rows['maestro_apellidos'].'</td>
                        <td>'.$rows['maestro_ci'].'</td>
                        <td>'.$rows['maestro_telefono'].'</td>
                        <td>'.$rows['maestro_correo'].'</td>
                        <td>
                            <a href="'.APP_URL.'teacherPhoto/'.$rows['maestro_id'].'/" class="button is-info is-rounded is-small">Foto</a>
                        </td>
                        <td>
                            <a href="'.APP_URL.'teacherUpdate/'.$rows['maestro_id'].'/" class="button is-success is-rounded is-small">Actualizar</a>
                        </td>
                        <td>
                            <form class="FormularioAjax" action="'.APP_URL.'app/ajax/teacherAjax.php" method="POST" autocomplete="off">

                                <input type="hidden" name="modulo_usuario" value="eliminar">
                                <input type="hidden" name="usuario_id" value="'.$rows['maestro_id'].'">

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
                <p class="has-text-right">Mostrando Maestros
                <strong>'.$page_start.'</strong> al <strong>'.$page_end.'</strong> de un 
                <strong>total de '.$total.'</strong></p>
                ';

                $table.=$this->tablePager($page,$numberOfPages,$url,10);
            }

            return $table;
        }
    }