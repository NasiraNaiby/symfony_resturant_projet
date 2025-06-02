# 🍽️ THE DISTRICT - Système de Commande de Restaurant (Symfony)

## 📌 Présentation du Projet
THE DISTRICT est un système de commande de restaurant basé sur le framework Symfony (architecture MVC). Il permet aux utilisateurs de parcourir les catégories, consulter les plats, ajouter des articles à leur panier et passer commande. Le système inclut l'authentification et un contrôle d'accès basé sur les rôles.

## 🚀 Fonctionnalités
- Modèle de données basé sur des entités (Doctrine ORM)
- Gestion des catégories et des plats
- Fonctionnalité de panier
- Authentification des utilisateurs & gestion des rôles
- Traitement des commandes & envoi d'e-mails de confirmation
- Design responsive et mobile (Bootstrap)
- Gestion des rôles (Admin, Client)

## 🛠️ Technologies Utilisées
- Symfony (PHP)
- Doctrine (ORM)
- MySQL (Base de données)
- Bootstrap (Frontend)
- Twig (Moteur de templates)

---
## 🚀 État du projet

Ce projet est en cours de développement.  
Certaines fonctionnalités peuvent être incomplètes ou nécessiter des améliorations.  
Toute contribution ou suggestion est la bienvenue pour l'améliorer !
## 🔧 Guide d’Installation

### 1️⃣ Cloner le dépôt
```bash
git clone https://github.com/NasiraNaiby/symfony_resturant_projet.git
cd symfony_resturant_projet
mysql -u admin -p sf_district < database_dump.sql
