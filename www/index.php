<?php
// Démarrer la session PHP
session_start();

// Vérifier si le paramètre "page" existe dans l'URL et n'est pas vide
if (isset($_GET['page']) && !empty($_GET['page'])) {
    // Convertir la valeur en entier de manière sécurisée
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    // Si aucun paramètre de page, on considère qu'on est sur la page 1
    $currentPage = 1;
}

// Informations de connexion à la base de données
$host = 'db_lamp';        // Nom de l'hôte (nom du service dans Docker par exemple)
$user = 'user';           // Nom d'utilisateur MySQL
$password = 'user';       // Mot de passe MySQL
$dbname = 'test';         // Nom de la base de données

try {
    // Création d'une instance PDO pour se connecter à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    // Activer le mode d'affichage des erreurs (lancer une exception en cas d'erreur SQL)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Afficher un message et arrêter l'exécution en cas d'erreur de connexion
    die("Erreur de connexion : " . $e->getMessage());
}

// Définir le nombre de photos affichées par page
$limit = 3;

// Récupérer la page actuelle depuis l'URL, ou 1 par défaut
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

// Calculer l'offset pour la requête SQL (combien de lignes sauter)
$offset = ($page-1) * $limit;


try {
    // Requête SQL pour compter toutes les photos publiques
    $stmt = $pdo->query("SELECT COUNT(*) FROM photos WHERE public = 1 LIMIT $limit");
    // Récupérer le résultat (nombre total de photos publiques)
    $total_photos = (int) $stmt->fetchColumn();
} catch (PDOException $e) {
    // Afficher une erreur si la requête échoue
    die("Erreur lors du comptage des photos : " . $e->getMessage());
}

// Calculer le nombre total de pages nécessaires pour la pagination
$total_pages = ceil($total_photos / $limit);

try {
    // Préparer la requête SQL pour récupérer les photos publiques avec pagination
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE public = 1 ORDER BY date_added DESC LIMIT :limit OFFSET :offset");
    
    // Lier la valeur du paramètre LIMIT
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    
    // Lier la valeur du paramètre OFFSET
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
    // Exécuter la requête
    $stmt->execute();
    
    // Récupérer les résultats dans un tableau associatif
    $public_photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Afficher une erreur en cas d'échec de la requête
    die("Erreur lors de la récupération des photos publiques : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Encodage des caractères -->
    <meta charset="UTF-8">
    <!-- Affichage responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de l’onglet -->
    <title>Voyages & Découvertes</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="style/index.css">

</head>
<body>
    <header>
        <!-- Titre du site -->
        <h1>Voyages & Découvertes</h1>
        <nav>
            <!-- Liens de navigation vers connexion et inscription -->
            <a href="login.php" class="btn">Connexion</a>
            <a href="register.php" class="manage-btn">Inscription</a>
        </nav>
    </header>

    <main>
        <!-- Section contenant la galerie de photos publiques -->
        <section class="gallery">
            <h2>Photos publiques</h2>
            <div class="media-container">
                <!-- Affichage d’un message si aucune photo n’est trouvée -->
                <?php if (empty($public_photos)): ?>
                    <p>Aucune photo publique pour le moment.</p>
                <?php else: ?>
                    <!-- Boucle sur les photos pour les afficher une par une -->
                    <?php foreach ($public_photos as $photo): ?>
                        <div class="media-item">
                            <!-- Affichage de la photo -->
                            <img src="<?= htmlspecialchars($photo['photo_url']) ?>" alt="Photo publique" class="media-img">
                            <!-- Affichage de la description -->
                            <p class="media-description"><?= htmlspecialchars($photo['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Navigation de pagination -->
<nav class="pagination">
    <ul class="pagination-list">
        <!-- Lien vers la première page -->
        <li class="<?= ($page <= 1) ? 'disabled' : '' ?>">
            <a href="?page=1">Première page</a>
        </li>

        <!-- Lien vers la page précédente -->
        <li class="<?= ($page <= 1) ? 'disabled' : '' ?>">
            <a href="?page=<?= max(1, $page - 1) ?>">Précédente</a>
        </li>

        <!-- Liens vers chaque page -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="<?= ($page === $i) ? 'active' : '' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Lien vers la page suivante -->
        <li class="<?= ($page >= $total_pages) ? 'disabled' : '' ?>">
            <a href="?page=<?= min($total_pages, $page + 1) ?>">Suivante</a>
        </li>

        <!-- Lien vers la dernière page -->
        <li class="<?= ($page >= $total_pages) ? 'disabled' : '' ?>">
            <a href="?page=<?= $total_pages ?>">Dernière page</a>
        </li>
    </ul>
</nav>

        </section>
    </main>
</body>
</html>
