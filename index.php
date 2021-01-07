<?php
// $serv=$_SERVER;
// echo'<pre>';
// var_dump($serv);
// echo'</pre>';

//lien à la base de données
try {
    $pdo =new PDO("mysql:host=localhost;dbname=exo_06012021",'root','');

}catch(PDOException $e){
    die('Erreur: '.$e->getMessage());
}


 
//condition du post et files
if(!empty($_POST) && !empty($_FILES)){


  // constante : Taille max en octets du fichie
    define('MAX_SIZE', 500000);    


    // *********************************** FILE IMAGE ***********************************************************
 

        //recu le nom du fichier
        $file_name = $_FILES['fichier']['name'];
        //recup le type du fichier
        $file_extension = strrchr($file_name,".");

        
        //image temporaire
        $file_tmp_name = $_FILES['fichier']['tmp_name'];
        
        //chemin du dossier
        $file_dest= 'img/'.$file_name;


        //tableau des extension autorisé lors de l'upload
        $extension_autorisee = array('.jpeg','.jpg','.png','.gif','.webp');

     // **************************************************************************************************** 

    $firstName=strip_tags(trim($_POST['firstName']));
    $lastName=strip_tags(trim($_POST['lastName']));
    $mailAdresse=strip_tags(trim($_POST['mailAdresse']));
    $mdp=sha1(trim($_POST['mdp']));

// /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/

        if(preg_match("/^[a-zA-Z-']*$/",$firstName)){
            if(preg_match("/^[a-zA-Z-']*$/",$lastName)){
                if (preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/",$mailAdresse)) {

                    // *********************** import img *********************

                                // est-ce que fait parti du tableau ci dessus
                    if (in_array($file_extension, $extension_autorisee)){
                        // pour un autre if pour la taille ou dimension
                            if((filesize($_FILES['fichier']['tmp_name']) <= MAX_SIZE)){
                
                                //pour déplacer du temp vers la destination
                                if(move_uploaded_file($file_tmp_name, $file_dest)){
                
                                    echo 'Fichier envoyé avec succés';
                
                                }else{
                                    echo"Une erreur est survenue lors de l'envoi du fichier";
                                }
                            }else{
                                echo 'Erreur dans le poids !';
                            }
                        }else{
                            echo 'seuls les images avec les extensions jpeg, jpg, png, gif, webp sont autorisées';
                        }
                        // ******************************* FIN IMPORT IMG **********************************************


                    //après verification du nom prenom mail on execute la requete
                    $request=$pdo->prepare("INSERT INTO user(firstName,lastName,mailAdresse,mdp,fichier) VALUES (?,?,?,?,?)");
                    $request->execute(array($firstName, $lastName, $mailAdresse, $mdp,$file_dest)); 
                    
                    echo ' et User enregistré';

                }else{
                    echo"l'adresse mail n'est pas valide";
                }
            }else{
                echo 'le nom de doit contenir que des lettre majuscule ou minuscule';
            }  
        }else{
            echo 'le prenom ne doit contenir que des lettres majuscule ou minuscule';
        }
    
}


?>

<form action="index.php" method="POST" enctype="multipart/form-data"><br>
    <label>Prénom</label><br>
    <input type="text" name="firstName"><br>
    <label>Nom</label><br>
    <input type="text" name="lastName"><br>
    <label>E-mail</label><br>
    <input type="text" name="mailAdresse"><br>
    <label>Mot de passe</label><br>
    <input type="password" name="mdp"><br>
    <!-- pour l'image -->
    <input name="fichier" type="file" />
    <!-- <input type="file" name="avatar"> <br>-->
    <input type="submit" value="enregistrer" name="subm">
</form>

