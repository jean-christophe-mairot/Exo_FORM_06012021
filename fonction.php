<?php 

function getPdo() :PDO
{
    try {
        $pdo =new PDO("mysql:host=localhost;dbname=exo_06012021",'root','');
    
    }catch(PDOException $e){
        die('Erreur: '.$e->getMessage());
    }return $pdo;
}
