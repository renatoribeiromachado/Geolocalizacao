<?php
    // CONFIGRAÇÕES DO BANCO ####################
    define('HOST', 'localhost');
    define('USER', '');
    define('PASS', '');
    define('DBSA', '');
    
    function conectar(){
        try{
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $pdo = new PDO("mysql:host=".HOST.";dbname=".DBSA."",USER,PASS, $options);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>" . $e->getLine() . "<br/>" . $e->getFile() . "<br/>";
            die();
        }
        //retorna a conexão
        return $pdo;
    }