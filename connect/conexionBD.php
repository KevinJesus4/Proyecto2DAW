<?php

class connection{

    static public function dbConnection(){
        $servername = "localhost";
        $database = "kevstore";
        $username = "root";
        $password = "";

        $conn = mysqli_connect($servername, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $conn;
        mysqli_close($conn);
        
    }

    //TODO: Quitar esto porque sin esto funciona.
    static public function conectado(){

        try{
            $link = new PDO(
                "mysql:host=localhost;dbname=kevstore",
                "root",
                ""
            );

            $link->exec("set names utf8");
             
        }catch(PDOException $e){
            die("Error: ".$e->getMessage());
        }

        return $link;
    }
}

?>