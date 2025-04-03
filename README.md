Description :

Un site galerie conçu pour afficher des photos, gérer les utilisateurs, et offrir une interface intuitive. 
Ce projet inclut un backend et un frontend, configurés pour fonctionner dans un environnement Docker.


Dépendances :

Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :
Docker : Installation de Docker
Docker Compose : Installation de Docker Compose


Structure du Projet :

Voici les principaux fichiers et leur rôle :

Dockerfile : Définit l'image Docker pour exécuter le projet.

docker-compose.yml : Configuration multi-conteneurs pour le site (serveur web, base de données, etc.).

Dossier www :
Contient le code source du site.
Fichiers PHP comme index.php, login.php pour la logique du backend.
Dossier style pour les fichiers CSS.
Dossier media-img pour les images.
Dossier uploads pour les fichiers uploadés.
Dossier utilisateur pour la gestion des utilisateurs.

Concernant la base de données, après avoir créé les serveurs vous y accédez grâce au lien :1200 ou directement par http://localhost:1200/  dans l'URL.

Concernant le site, il suffit de taper localhost dans l'URL.
