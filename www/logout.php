<?php
// Démarre une session pour permettre la gestion de l'utilisateur connecté
session_start();

// Paramètres de connexion à la base de données
$host = 'db_lamp';  // Nom de l'hôte de la base de données
$user = 'user';     // Nom d'utilisateur pour se connecter à la base
$password = 'user'; // Mot de passe pour la connexion
$dbname = 'test';   // Nom de la base de données

try {
    // Création de la connexion PDO à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    // Définition du mode d'erreur pour gérer les exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, affiche un message d'erreur et arrête l'exécution du script
    die("Erreur de connexion : " . $e->getMessage());
}

// Nombre d'éléments par page
$limit = 3;

// Récupérer la page actuelle, ou définir la première page par défaut
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculer l'offset basé sur la page
$offset = ($page - 1) * $limit;

try {
    // Récupérer le nombre total de photos publiques pour la pagination
    $stmt = $pdo->query("SELECT COUNT(*) FROM photos WHERE public = 1");
    $total_photos = $stmt->fetchColumn(); // Récupère le nombre total de photos publiques

    // Calculer le nombre total de pages
    $total_pages = ceil($total_photos / $limit);

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <!-- Lien vers la feuille de style CSS pour la page -->
    <link rel="stylesheet" href="style/index.css">
</head>

<body>
    <!-- Entête de la page avec le titre et la navigation -->
    <header>
        <h1>Voyages & Découvertes</h1>
        <nav>
            <!-- Lien vers la page de connexion -->
            <a href="login.php" class="btn">Connexion</a>
            <!-- Lien vers la page d'inscription -->
            <a href="register.php" class="manage-btn">Inscription</a>
            <br><br>
            <!-- Message de bienvenue -->
            <p>A bientôt !</p>
        </nav>
    </header>

    <!-- Section principale de la page, affichage de la galerie de photos -->
    <main>
        <section class="gallery">
            <!-- Conteneur pour les éléments multimédia (photos) -->
            <div class="media-container">
                <!-- Vérification si aucune photo n'est présente -->
                <?php if (empty($public_photos)): ?>
                    <!-- Message affiché si aucune photo n'est disponible -->
                    <p>Aucune photo publique pour le moment.</p>
                <?php else: ?>
                    <!-- Si des photos sont présentes, affichage de chaque photo -->
                    <?php foreach ($public_photos as $photo): ?>
                        <div class="media-item">
                            <!-- Affichage de l'image avec la source et une description -->
                            <img src="<?php echo htmlspecialchars($photo['photo_url']); ?>" alt="Photo publique" class="media-img">
                            <p class="media-description"><?php echo htmlspecialchars($photo['description']); ?></p>
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
