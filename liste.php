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

class Apprenant {
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion;
    }

    public function listerApprenant() {
        $requete = $this->connexion->prepare("SELECT * FROM Apprenant");
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterApprenant($prenom, $nom) {
        $requete = $this->connexion->prepare("INSERT INTO Apprenant (prenom, nom) VALUES (:prenom, :nom)");
        $requete->bindParam(':prenom', $prenom);
        $requete->bindParam(':nom', $nom);
        return $requete->execute();
    }

    public function modifierApprenant($id, $prenom, $nom) {
        $requete = $this->connexion->prepare("UPDATE Apprenant SET prenom = :prenom, nom = :nom WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':prenom', $prenom);
        $requete->bindParam(':nom', $nom);
        return $requete->execute();
    }

    public function supprimerApprenant($id) {
        $requete = $this->connexion->prepare("DELETE FROM Apprenant WHERE id = :id");
        $requete->bindParam(':id', $id);
        return $requete->execute();
    }
}

$apprenantManager = new Apprenant($connexion);
if(isset($_POST['ajouter'])) {
    $apprenantManager->ajouterApprenant($_POST['prenom'], $_POST['nom']);
}

if(isset($_POST['modifier'])) {
    $apprenantManager->modifierApprenant($_POST['id'], $_POST['prenom'], $_POST['nom']);
}

if(isset($_GET['action']) && $_GET['action'] == 'supprimer') {
    $apprenantManager->supprimerApprenant($_GET['id']);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Apprenant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Liste des Apprenant</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prenom</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($apprenantManager->listerApprenant() as $apprenant): ?>
                <tr>
                    <td><?= $apprenant['id'] ?></td>
                    <td><?= $apprenant['prenom'] ?></td>
                    <td><?= $apprenant['nom'] ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $apprenant['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                        <a href="liste.php?action=supprimer&id=<?= $apprenant['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet apprenant ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajouterModal">Ajouter</button>
    </div>

    <div class="modal fade" id="ajouterModal" tabindex="-1" role="dialog" aria-labelledby="ajouterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajouterModalLabel">Ajouter un apprenant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="prenom">Prenom de l'apprenant</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom de l'Apprenant</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>