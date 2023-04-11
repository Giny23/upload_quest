<?php

if($_SERVER['REQUEST_METHOD'] === "POST") {
    $errors = [];
    $lastname = $firstname = $age = "";
    function checkdata($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlentities($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'public/uploads/';
    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // le nom de fichier est un ID unique
    $uploadName = uniqid('',true).".".$extension;
    //chemin de destination
    $uploadFile = $uploadDir . $uploadName;
    // Les extensions autorisées
    $authorizedExtensions = ['jpg','jpeg','png','gif','webp'];
    // Le poids max géré par PHP par défaut est de 1M
    $maxFileSize = 1000000;

    // Je sécurise et effectue mes tests
    /****** Si l'extension est autorisée *************/
    if( (!in_array($extension, $authorizedExtensions))){
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png ou Gif ou Webp !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if( file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize)
    {
        $errors[] = "Votre fichier doit faire moins de 1M !";
    }

    if (isset($_POST)) {
        if (empty($_POST['lastname'])) {
            $errors[] = 'le nom est obligatoire';
        } else {
            $lastname = checkdata($_POST['lastname']);
        }
        if (empty($_POST['firstname'])) {
            $errors[] = 'le prénom est obligatoire';
        } else {
            $firstname = checkdata($_POST['firstname']);
        }
        if (empty($_POST['age'])) {
            $errors[] = "l'âge est obligatoire";
        } else {
            $age = checkdata($_POST['age']);
        }
    } else {
        $errors[] = 'Merci de remplir le formulaire';
    }

    if (!empty($errors)){ ?>
<ul>
    <?php foreach ($errors as $error){ ?>
    <li>
        <?= $error ?>
    </li>
    <?php } ?>
        </ul>
<?php
    } else {
    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
    }
}
if(isset($_POST['delete'])){
    unlink($uploadFile);
    echo 'test';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form method="post" enctype="multipart/form-data">
    <div>
        <label for="imageUpload">Upload ton plus beau profil</label>
        <input type="file" name="avatar" id="imageUpload" />
    </div>
    <div>
        <label for="lastname">Nom</label>
        <input type="text" name="lastname" id="lastname">
    </div>
    <div>
        <label for="firstname">Prénom</label>
        <input type="text" name="firstname" id="firstname">
    </div>
    <div>
        <label for="age">Age</label>
        <input type="number" name="age" id="age">
    </div>
    <button name="send">Send</button>
</form>

<h1>Hello you !</h1>
<p>Bonjour <?=$firstname." ".$lastname?>. Es-tu prêt(e) à <?=$age?> ans à rencontrer le big love ?</p>
<img src="<?=$uploadFile?>">
<br>
<form action="" method="post">
    <button name="delete">Supprimer</button>
</form>
</body>
</html>