<?php
namespace App\Service\Db;

use PDO;

class Db 
{
    public $domaine='mysql:host=localhost;dbname=gps';
    public$username='root';
    public $password='';

    function __construct()
    {
        $this->new_connection();
    }

    function new_connection()
    {
        try {
            
            return new PDO($this->domaine,$this->username,$this->password);
        } catch (\Exception) {
            die("Une erreur est survenue, verifiez vos parametres de connection, ou contactez votre webmestre !");
        }
    }

    function new_fetch_command($command)
    {
        try {
            $con=$this->new_connection();
            $exec=$con->query($command);
            $array=$exec->fetchAll();
            return $array;
        } catch (\Exception) {
            die("Une erreur est survenue, verifiez vos parametres de connection, ou contactez votre webmestre !");
        }
    }
}
