<?php
session_start();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Site</title>
</head>
<body>
 <?php
 include('config.php');
 //test si le formulaire et remplie
 if (isset($_REQUEST["send"])) {
// sécurisation des variable
   $nom = htmlspecialchars($_REQUEST["nom"]);
   $prenom = htmlspecialchars($_REQUEST["prenom"]);
   $mail = htmlspecialchars($_REQUEST["mail"]);
   $mail_confirmation = htmlspecialchars($_REQUEST["mailconf"]);
   $password = sha1($_REQUEST["password"]);
   $password_conf = sha1($_REQUEST["passwordconf"]);
//test global du site, champs vide, nb de caractère, mail et mots de passe de confirmation
   if(!empty($_REQUEST["nom"]) AND !empty($_REQUEST["prenom"]) AND !empty($_REQUEST["mail"]) AND !empty($_REQUEST["password"]) AND !empty($_REQUEST["mailconf"]) AND !empty($_REQUEST["passwordconf"]) AND !empty($_REQUEST["date_n"])){

     $nomlength = strlen($nom);
     $prenomlength = strlen($prenom);
        if ($nomlength <= 255 ) {
            if ($prenomlength <= 255) {
               if (preg_match('#^([0-9]{2})(/-)([0-9]{2})\2([0-9]{4})$#', $_REQUEST["date_n"], $m) == 1 && checkdate($m[3], $m[1], $m[4]))  {
                if(date('d/m/Y') > $_REQUEST["date_n"]) {
                  $erreur = "date non valide";
                }else {
                  if ($mail == $mail_confirmation) {
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                      if ($password == $passwordconf) {
                        // requete sur membre pour conter le nombre de fois ou l'email entré dans le form correspond a un email de la bdd
                        $reqmail = $bdd->query("SELECT * FROM membre WHERE email = '".$mail."'");
                        $mailexist = $reqmail->rowCount();
                        if ($mailexist == 0) {
                          // si le mail apparait 0 fois dans la bdd alors on insert les information utilisateur dans la bdd
                          $insertmbr = $bdd->prepare('INSERT INTO membre(nom, prenom, date_naissance, email, mot_de_passe) VALUES(?, ?, ?, ?)');
                          $insertmbr->execute(array($nom, $prenom, $_REQUEST["date_n"], $mail, $password));
                          $erreur = "Votre compte a bien étais crée";
                        }else {
                          $erreur = "Adresse mail déja utilisé";
                        }
                      }else {
                        $erreur = "les mots de passe ne correspondent pas";
                      }

                    }else {
                      $erreur = "Votre email n'est pas valide";
                    }
                  }else {
                    $erreur = "Vos email ne correspondent pas";
                  }
                }

              }else {
                $erreur = "le format de la date n'est pas respecter";
              }

            }else {
              $erreur = "Votre prénom ne doit pas contenir plus de 255 caractère !";
            }
        }else {
          $erreur = "Votre nom ne doit pas contenir plus de 255 caractère !";
        }

   }else{
     $erreur = "Tous les champs doivent être remplie";
   }
 }
 ?>
    <div align="center">
    <h3>Inscription</h3><br />
    <!-- creation formulaire-->
  <form class="form_item" action="inscription.php" method="post">
    <div class="erreur">
     <?php
     // Variable d'arreur
     if (isset($erreur)) {
      echo '<font color="#305A72">'.htmlspecialchars($erreur).'</font>';
     }
     ?>
   </div>
  <ul>
    <li>
        <label for="nom">Nom: </label>
        <input type="text" name="nom" id="nom" class="field-style field-split align-left" placeholder="Entrez votre nom" value="<?php if (isset($nom)) {echo $nom;}?>">
    </li>
    <li>
     <label for="prenom">Prénom: </label>
       <input type="text" name="prenom" id ="prenom"  class="field-style field-split align-right"  placeholder="Entrez votre prénom" value="<?php if (isset($prenom)) {echo $prenom;}?>">
   </li>
   <li>
    <label for="date_naissance">Date de naissance: </label>
      <input type="date" name="date_n" id ="date_n"  class="field-style field-split align-right"  value="<?php if (isset($_REQUEST["date_n"])) {echo $_REQUEST["date_n"];}?>">
  </li>
   <li>
     <label for="prenom">Email: </label>
     <input type="email" name="mail" id ="mail"  class="field-style field-split align-left"  placeholder="Entrez votre email" value="<?php if (isset($mail)) {echo $mail;}?>">
   </li>
   <li>
     <label for="prenom">Confirmation mail: </label>
     <input type="email" name="mailconf" id ="mailconf"  class="field-style field-split align-right"  placeholder="Confirmer votre mail" value="<?php if (isset($mail_confirmation)) {echo $mail_confirmation;}?>">
   </li>
   <li>
     <label for="password" >Mot de passe: </label>
     <input type="password" name="password" id="password"  class="field-style field-split align-left"  placeholder="Mots de passe">
  </li>
  <li>
     <label for="password" >Confirmation mot de passe: </label>
     <input type="password" name="passwordconf" id="passwordconf"  class="field-style field-split align-right"  placeholder="Mots de passe">
   </li>
    <li>
     <input type="submit" name="send" id="send" value="Je m'inscris">
   </li>
 </ul>

  </form>
</div>

</body>
</html>
