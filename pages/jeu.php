<?php
require_once('bdd/connect.php');

if(isset($_GET['id']) && !empty($_GET['id']))
{
    $id = $_GET['id'];

    $sql = 'SELECT * FROM `jeux` WHERE `id`=:id';
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id);
    $query->execute();
    $jeu = $query->fetch(PDO::FETCH_ASSOC);

    if (empty($jeu)){
        header("location:index.php");
    }

    $sql = 'SELECT `id` FROM `genrejeu` WHERE `id_jeux`=:id';
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id);
    $query->execute();
    $genreJeu = $query->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM `salons` WHERE `id_jeux`=:id';
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id);
    $query->execute();
    $salons = $query->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM `jeujouer` WHERE `id`=:id';
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id);
    $query->execute();
    $joueurs = $query->fetchAll(PDO::FETCH_ASSOC);

}
else{
    header("location:index.php");
}
?>
<div class="infoJeu">
    <img src="images/imageJeu/<?= $jeu['image'] ?>" alt="">
    <div>
        <p>TITRE : <?= $jeu['nom'] ?></p>
        <div class="boutonAjoutAmis">
            <button><img src="images/icone/plus.svg" alt=""></button>
            <button><img src="images/icone/heart-plus.svg" alt=""></button>
        </div>
    </div>
    <div class="tagJeu">
    <?php
        foreach($genreJeu as $genre)
        {
            $idGenre = $genre['id'];

            $sql = 'SELECT * FROM `genres` WHERE `id`=:id';
            $query = $db->prepare($sql);
            $query->bindValue(':id', $idGenre);
            $query->execute();
            $nomGenre = $query->fetch(PDO::FETCH_ASSOC);

            echo '<p class="tagJeuUn">'.$nomGenre["nom"].'</p>';
        }
    ?>
    </div>
    <p>Description : <?= $jeu['description'] ?></p>
</div>
<div class="listDansJeu">
    <div>
        <p>Joueurs</p>
        <table>
            <thead>
                <tr>
                    <td>Image</td>
                    <td>Nom</td>
                    <td>Etat</td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($joueurs as $joueur){

                    $id = $joueur['id'];

                    $sql = 'SELECT * FROM `utilisateurs` WHERE `id`=:id';
                    $query = $db->prepare($sql);
                    $query->bindValue(':id', $id);
                    $query->execute();
                    $info = $query->fetch(PDO::FETCH_ASSOC);
                ?>
                    <tr>    
                        <td><img  class="imageListDansJeu" src="images/imageSalon/<?= $info['image'] ?>" alt=""></td>
                        <td><a href="?page=utilisateur&id=<?= $info['id'] ?>"><?= $info['nom'] ?></a></td>
                        <td>
                        <?php 
                            if($info['connexion'] == 1)
                            { echo "Connecté";}
                            else
                            { echo"Deconnecter";}
                            ?>
                        </td>  
                    </tr>
                <?php } ?>


            </tbody>
        </table>
    </div>
    <div>
        <p>Salons</p>
        <table>
            <thead>
                <tr>
                    <td>Image</td>
                    <td>Nom</td>
                    <td>Description</td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach($salons as $salon){
            ?>
                <tr>
                    <td><img  class="imageListDansJeu" src="images/imageSalon/<?= $salon['image'] ?>" alt=""></td>
                    <td><a href="?page=salon&id=<?= $salon['id'] ?>"><?= $salon['nom'] ?></a></td>
                    <td><?= $salon['description'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once('bdd/close.php'); ?>