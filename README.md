Description :
-
Un site galerie conçu pour afficher des photos, gérer les utilisateurs, et offrir une interface intuitive. 
Ce projet inclut un backend et un frontend, configurés pour fonctionner dans un environnement Docker.


Dépendances :
-
Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :
Docker : Installation de Docker
Docker Compose : Installation de Docker Compose


Structure du Projet :
-

Voici les principaux fichiers et leur rôle :
-
Dockerfile : Définit l'image Docker pour exécuter le projet.

docker-compose.yml : Configuration multi-conteneurs pour le site (serveur web, base de données, etc.).

Dossier www :
-
Contient le code source du site.
Fichiers PHP comme index.php, login.php pour la logique du backend.
Dossier style pour les fichiers CSS.
Dossier media-img pour les images.
Dossier uploads pour les fichiers uploadés.
Dossier utilisateur pour la gestion des utilisateurs.

Pour construire vos serveurs dockers, veuillez suivre les étapes suivantes :
-
Ouvrez le terminal, entrer la commande : " cd c:\chemind_vers_cerépertoire\docker_web "

tapez les deux commandes suivantes : 
  Commande 1 -> Créer l'image pour le serveur Apache/PHP : "docker-compose build
  Commande 2 -> Démarrer les conteneurs : "docker-compose up -d"
  Commande 3 -> vérification : "docker ps"

Concernant la base de données, vous y accédez grâce au lien :1200 ou directement par http://localhost:1200/  dans l'URL.

Connexion à la base de données :
-
Hôte : db (nom du conteneur MariaDB)
Utilisateur : user
Mot de passe : user
Base de données : test (créée automatiquement avec les variables d'environnement)

requetes pour création tables à entrer directement dans la base de donnée test :
-
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (login) REFERENCES utilisateurs(login)
);

CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL,
    photo_url VARCHAR(255) NOT NULL,
    description TEXT,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    public BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (login) REFERENCES utilisateurs(login)
);

L'accès à vos projets se fait en écrivant "localhost" dans la barre de recherche du navigateur.
-
