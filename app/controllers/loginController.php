<?php
    namespace app\controllers;
    use app\models\mainModel;

    class loginController extends mainModel{

        # controller to login #
        public function startSessionController(){
            
            # Save data from login form #
            $user=$this->cleanUpString($_POST['login_usuario']);
            $password=$this->cleanUpString($_POST['login_clave']);

            # checking required fields #
            if($user==""||$password==""){
                echo "
                    <script>
                    Swal.fire({
                            icon: 'error',
                            title: 'Ocurrio un error',
                            text: 'No ha ingresado todos los datos requeridos',
                            confirmButtonText: 'Aceptar'
                    });
                    </script>
                ";
            }else{

                # verifying data integrity #
                if($this->verifyData("[a-zA-Z0-9]{4,20}",$user)){
                    echo "
                        <script>
                        Swal.fire({
                                icon: 'error',
                                title: 'Ocurrio un error',
                                text: 'El usuario que ha ingresado no coincide con el formato solicitado',
                                confirmButtonText: 'Aceptar'
                        });
                        </script>
                    ";
                }else{
                    if($this->verifyData("[a-zA-Z0-9$@.-]{7,100}",$password)){
                        echo "
                        <script>
                        Swal.fire({
                                icon: 'error',
                                title: 'Ocurrio un error',
                                text: 'La clave que ha ingresado no coincide con el formato solicitado',
                                confirmButtonText: 'Aceptar'
                        });
                        </script>
                        ";
                    }else {
                        # verify user #
                        $check_user=$this->runQuery("SELECT * FROM usuario 
                        WHERE usuario_usuario='$user'");

                        if($check_user->rowCount()==1){
                            $check_user=$check_user->fetch();

                           if($check_user['usuario_usuario']==$user && $password == $check_user['usuario_clave']){
                                $_SESSION['id']=$check_user['usuario_id'];
                                $_SESSION['name']=$check_user['usuario_nombre'];
                                $_SESSION['lastname']=$check_user['usuario_apellidos'];
                                
                

                                if(headers_sent()){
                                    echo "
                                        <script>
                                            window.location.href='".APP_URL."dashboard/'
                                        </script>
                                    ";

                                }else{
                                    header("Location:".APP_URL."dashboard/");
                                }
                           }else{
                                echo "
                                    <script>
                                    Swal.fire({
                                            icon: 'error',
                                            title: 'Ocurrio un error',
                                            text: 'Usuario o clave incorrectos',
                                            confirmButtonText: 'Aceptar'
                                    });
                                    </script>
                                "; 
                           }
                            
                        }else{
                            echo "
                            <script>
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Ocurrio un error',
                                    text: 'Usuario o clave incorrectos',
                                    confirmButtonText: 'Aceptar'
                            });
                            </script>
                            ";  
                        }
                    }
                }
            }
        }

        # controller to logout #
        public function closeSessionController(){

            session_destroy();
            if(headers_sent()){
                echo "
                    <script>
                        window.location.href='".APP_URL."login/'
                    </script>
                ";
            }else{
                header("Location:".APP_URL."login/");
            }
        }
    }