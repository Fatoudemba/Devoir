<?php

$serveur = "localhost:3307";
$utilisateur = "root";
$motdepasse = "";
$bdd = "poo";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$bdd", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $requete = $connexion->prepare("SELECT * FROM Apprenant WHERE id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $filiere = $requete->fetch(PDO::FETCH_ASSOC);
} else {
  
    header("Location: liste.php");
    exit();
}

if(isset($_POST['modifier'])) {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
   
    $requete = $connexion->prepare("UPDATE Apprenant SET prenom = :prenom, nom = :nom WHERE id = :id");
    $requete->bindParam(':id', $id);
    $requete->bindParam(':prenom', $prenom);
    $requete->bindParam(':nom', $nom);
    $requete->execute();

    header("Location: liste.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Apprenant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un Apprenant</h1>
        <form method="POST">
            <div class="form-group">
                <label for="prenom">Prenom de l'apprenant'</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom de l'Apprenant</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <button type="submit" class="btn btn-primary" name="modifier">Valider</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>