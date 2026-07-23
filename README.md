# Vite & Gourmand

Application restauration, permettant la gestion des clients, des employés des commandes et menus.

## Prequis

- Docker
- Git

## Instalation

### Cloner le projet

git clone <https://github.com/Pendouce/Vite-Gourmand.git>

cd Vite-Gourmand

### Configurer l'environement

cp .env.example .env

Renseigner les variables dans .env

## Lancer les conteneurs Docker

docker-compose up -d --build

### Services démarés

MYSQL
|Service |Description |Port local|
|-------------|-----------------------------|----------|
|Nginx |Serveur web |8080 |
|PHP-FPM |Traitement PHP |- |
|MySQL |Base de données relationnelle|3306 |
|MongoDB |Base de données NoSQL |27017 |
|phpMyAdmin |Interface MySQL |8081 |
|Mongo Express|Interface MongoDB |8082 |

## Acceder a l'application

- Application : http://localhost:8080

- phpMyAdmin : http://localhost:8081

- Mongo Express : http://localhost:8082

## Architechture

Controller → Service → Repository → Entity

- Le controller gere les requettes et les redirections

- Le service contient la logique metier

- Le repository accede aux données

- L'entity represente des objets metier
