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
 if (isset($_POST["send"])) {
// sécurisation des variable

   $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
   $password_conf = password_hash($_POST["passwordconf"], PASSWORD_DEFAULT);
//test global du site, champs vide, nb de caractère, mail et mots de passe de confirmation
   if(!empty($_POST["pseudo"])  AND !empty($_POST["mail"]) AND !empty($_POST["password"]) AND !empty($_POST["mailconf"]) AND !empty($_POST["passwordconf"])){

// TODO: remplacer request par post, changer le test du mdp , essayer fetch au lieu de rowCount

     $pseudolength = strlen($_POST["pseudo"]);
        if ($pseudolength <= 255 ) {
                  if ($_POST["mail"] == $_POST["mailconf"]) {
                    if (filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
                      if ($_POST["password"] == $_POST["passwordconf"]) {
                        $reqpseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = ?");
                        $reqpseudo->execute(array($_POST["pseudo"]));
                        $pseudoexist = $reqpseudo->fetch();
                        if ($pseudoexist == 0) {
                          $reqmail = $bdd->prepare("SELECT * FROM membre WHERE email = ? AND ");
                          $reqmail->execute(array($_POST["mail"]));
                          $mailexist = $reqmail->fetch();
                          if ($mailexist == 0) {
                            // si le mail apparait 0 fois dans la bdd alors on insert les information utilisateur dans la bdd
                            //requête pour insertion de membre
                            $insertmbr = $bdd->prepare('INSERT INTO membre(pseudo, email, mot_de_passe) VALUES(?, ?, ?)');
                            $insertmbr->execute(array($_POST["pseudo"], $_POST["mail"], $password));
                            $erreur = "Votre compte a bien étais crée";
                          }else {
                            $erreur = "Adresse mail déja utilisé";
                          }
                        }else {
                          $erreur = "pseudo deja existant";
                        }
                      }else {
                        $erreur = "les mots de passe ne correspondent pas";
                      }

                  }else {
                    $erreur = "votre email n'est pas valide";
                  }
                }else {
                  $erreur = "vos email ne correspondent pas";
                }
              }else{
                $erreur="votre pseudo ne doit pas dépasser 255 caractère!";
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
        <label for="pseudo">Pseudo: </label>
        <input type="text" name="pseudo" id="pseudo" class="field-style field-split align-left" placeholder="Entrez votre Pseudo" value="<?php if (isset($_POST["pseudo"])) {echo $_POST["pseudo"];}?>">
    </li>
   <li>
     <label for="prenom">Email: </label>
     <input type="email" name="mail" id ="mail"  class="field-style field-split align-left"  placeholder="Entrez votre email" value="<?php if (isset($_POST["mail"])) {echo $_POST["mail"];}?>">
   </li>
   <li>
     <label for="prenom">Confirmation mail: </label>
     <input type="email" name="mailconf" id ="mailconf"  class="field-style field-split align-right"  placeholder="Confirmer votre mail" value="<?php if (isset($_POST["mailconf"])) {echo $_POST["mailconf"];}?>">
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
