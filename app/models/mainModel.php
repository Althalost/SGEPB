<?php
    namespace app\models;

    use \PDO;

    if(file_exists(__DIR__."/../../config/server.php")){
        require_once __DIR__."/../../config/server.php";
    }

    class mainModel{
        private $dbServer=DB_SERVER;
        private $dbName=DB_NAME;
        private $dbUser=DB_USER;
        private $dbPass=DB_PASS;

        protected function connection(){
            $connection = new PDO("mysql:host=".$this->dbServer.";dbname=".$this->dbName,
            $this->dbUser,$this->dbPass);
            $connection->exec("SET CHARACTER SET utf8");
            return $connection;
        }

        protected function runQuery($query){
            $sql=$this->connection()->prepare($query);
            $sql->execute();
            return $sql;
        }

        protected function cleanUpString($text){
            $words=["<script>","</script>","<script src","<script type=",
            "SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO",
            "DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES",
            "SHOW DATABASES","<?php","?>","--","^","<",">","==","=",";","::"];

            $text=trim($text);
            $text=stripslashes($text);

            foreach($words as $word){
                $text=str_ireplace($word,"",$text);
            }

            $text=trim($text);
            $text=stripslashes($text);

            return $text;
        }

        protected function verifyData($filter, $text){
            if(preg_match(("/^".$filter."$/"),$text)){
                return false;
            }else{
                return true;
            }
        }

        protected function saveData($table,$data){
            $query="INSERT INTO $table (";

            $C=0;
            foreach($data as $adata){
                if($C>=1){ $query.=","; }
                $query.=$adata['campo_nombre'];
                $C++;
            }

            
            $query.= ") VALUES(";

            $C=0;
            foreach($data as $adata){
                if($C>=1){ $query.=","; }
                $query.=$adata['campo_marcador'];
                $C++;
            }

            $query.=")";
            $sql=$this->connection()->prepare($query);

            foreach($data as $adata){
                $sql->bindParam($adata['campo_marcador'],$adata['campo_valor']);
            }

            $sql->execute();

            return $sql;
        }

        public function selectData($type,$table,$field,$id){
            $type=$this->cleanUpString($type);
            $table=$this->cleanUpString($table);
            $field=$this->cleanUpString($field);
            $id=$this->cleanUpString($id);

            if($type=="Unique"){
                $sql=$this->connection()->prepare("SELECT * FROM $table WHERE $field=:ID");
                $sql->bindParam(":ID",$id);
            }elseif($type=="Normal"){
                $sql=$this->connection()->prepare("SELECT $field FROM $table");
            }

            $sql->execute();

            return $sql;
        }

        protected function updateData($table,$data,$condition){
            $query="UPDATE $table SET ";

            $C=0;
            foreach($data as $adata){
                if($C>=1){ $query.=","; }
                $query.=$adata['campo_nombre']."=".$adata['campo_marcador'];
                $C++;
            }

            $query.= " WHERE ".$condition['condition_campo']."=".$condition['condition_marcador'];
            $sql=$this->connection()->prepare($query);

            foreach($data as $adata){
                $sql->bindParam($adata['campo_marcador'],$adata['$campo_valor']);
            }

            $sql->bindParam($condition['condition_marcador'],$adata['$condition_valor']);

            $sql->execute();

            return $sql;
        }

        protected function deleteData($table,$field,$id){
            $sql=$this->connection()->prepare("DELETE FROM $table WHERE $field=:id");
            $sql->bindParam(":id",$id);
            $sql->execute();

            return $sql;
        }


        protected function tablePager($page,$Numberpages,$url,$buttons){
            $table='<nav class="pagination is-centered is-rounded" 
            role ="navigation" aria-label="pagination">';

            if($page<=1){
                $table.='
                    <a class="pagination-previous is-disabled" disabled >Anterior</a>
                    <ul class="pagination-list">
                    '; 
                
            }else{ 
                $table.='
                    <a class="pagination-previous" href="'.$url.($page-1).'/">Anterior</a>
                    <ul class="pagination-list">
                        <li><a class="pagination-link" href="'.$url.'1/">1</a></li>
                        <li><span class="pagination-ellipsis">&hellip;</span></li>
                    '; 

            }

            $ic=0;
            for($i=$page;$i<=$Numberpages;$i++){
                if($ic>=$buttons){
                    break;
                }

                if($page==$i){
                    $table.='<li><a class="pagination-link is-current" href="'.$url.$page.'/">'.$page.'</a></li>';
                }else{
                    $table.='<li><a class="pagination-link" href="'.$url.$i.'/">'.$i.'</a></li>';
                }
                $ic++;
            }

            if($page==$Numberpages){
                $table.='
                    </ul>
                    <a class="pagination-next is-disabled" disabled >Siguiente</a>
                    '; 
                
            }else{ 
                $table.='
                        <li><span class="pagination-ellipsis">&hellip;</span></li>
                        <li><a class="pagination-link" href="'.$url.$Numberpages.'/">'.$Numberpages.'</a></li>
                    </ul>
                    <a class="pagination-next" href="'.$url.($page+1).'/">Siguiente</a>
                    '; 

            }

            $table.='</nav>';

            return $table;
        }
    }
