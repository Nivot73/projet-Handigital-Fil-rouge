<?php
require_once('bdd/connect.php');

$message="";

$sql = 'SELECT * FROM `utilisateurs` WHERE `id`=:id';
$query = $db->prepare($sql);
$query->bindValue(':id', $_SESSION['id']);
$query->execute();
$infoUtilisateur = $query->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['nom']) && !empty($_POST['nom'])
&& isset($_POST['mail']) && !empty($_POST['mail'])
&& isset($_POST['mdp']) && !empty($_POST['mdp']))
{
    $id = strip_tags($_SESSION['id']);
    $nom = strip_tags($_POST['nom']);
    $mail = strip_tags($_POST['mail']);
    $mdp = strip_tags($_POST['mdp']);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        if ($_FILES['image']['size'] <= 10000000) {
    
            $infosfichier = pathinfo($_FILES['image']['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('jpg','jpeg','gif','png','PNG');
    
            if (in_array($extension_upload, $extensions_autorisees)) {
    
            $filename = basename($_FILES['image']['name']);
            $image = $filename;
    
            move_uploaded_file($_FILES['image']['tmp_name'],'images/avatar/' . $image);
            }
        }
    }
    else {
        $image = $infoUtilisateur['image'];
    }

    $sql = "UPDATE `utilisateurs` SET `nom`=:nom, `e-mail`=:mail, `mdp`=:mdp, `image`=:image WHERE `id`=:id;";

    $query = $db->prepare($sql);

    $query->bindValue(':id', $id);
    $query->bindValue(':nom', $nom);
    $query->bindValue(':mail', $mail);
    $query->bindValue(':mdp', $mdp);
    $query->bindValue(':image', $image);

    $query->execute();

    $message="modification effectué";
} 

require_once('bdd/close.php');
?>
<div class="pageParametre">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="nom">
            <label for="nom">Nom : </label>
            <input type="text" name="nom" id="nom" value="<?= $infoUtilisateur['nom'] ?>"><br>
        </div>
        <div class="mail">
            <label for="mail">E-mail : </label>
            <input type="email" name="mail" id="mail" value="<?= $infoUtilisateur['e-mail'] ?>"><br>
        </div>
        <div class="mdp">
            <label for="mdp">MDP : </label>
            <input type="password" name="mdp" id="mdp" value="<?= $infoUtilisateur['mdp'] ?>"><br>
        </div>
        <div class="image">
            <label for="image">Changement d'image : </label>
            <input type="file" name="image" id="image"><br>
        </div>
        <div class="information">
            <p>Inscrit depuis le <?= $infoUtilisateur['information'] ?></p>
        </div>
        <input type="submit" value="Modifier">
    </form>
    <a class="button" href="#supprimer"><button id="buttonSupprimer"><img src="images/icone/delete.svg"><p>Supprimer le compte</p></button></a>

    <?php
    if(!empty($message)) { ?>
        <p class="message">
            <?= $message ?>
        </p>
        <?php } ?>
    <div id="supprimer">
        <a class="close" href="#"><img src="images/icone/close.svg"></a>
        <p>Etes vous sur de vouloir supprimer ce compte ?</p>
        <div>
            <a href="?page=delete"><button id="deleteCompte"><img src="images/icone/delete.svg"><p>Supprimer</p></button></a>
            <a href="#"><button id="annulerDelete">Annuler</button></a>
        </div>
        
    </div>
</div>