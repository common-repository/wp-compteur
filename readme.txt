=== wp-compteur ===
Contributors: resoneo, diije
Tags: spam, author, moderation, contributor, enforce rules, multi-author blog, multiauthor blog, multi-author, multiauthor
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.0.1

Compte le nombre d'articles refusés pour chaque contributeur et passe les contributeurs trop souvent refusés en abonnés.

== Description ==

Compte le nombre d'articles refusés (supprimés) pour chaque contributeur.
Quand l'utilisateur atteint le maximum de refus autorisés, il devient abonné et ne peut plus proposer d'articles.

Par défaut, le plugin autorise 5 refus avant de retirer à l'utilisateur le droit de proposer des articles.

== Installation ==

1. Uploader le répertoire `wp-compteur` dans `/wp-content/plugins/`
1. Activer le plugin dans le menu 'Extensions' de WordPress

== Frequently Asked Questions ==

= Comment modifier le nombre maximum d'articles refusés ? =

Editer le fichier wp-compteur.php et modifier la valeur de `$max_refus` (ligne 13).

= Pourquoi passer les utilisateurs en Abonnés et ne pas les supprimer ? =

Pour garder une trace de leur email et les empêcher de se réinscrire avec la même adresse, en remettant alors leur compteur à 0.

== Changelog ==

= 1.0 =
* Version stable
* Mise à jour de la FAQ

= 0.2 =
* Première version diffusée sur WordPress.org
* Ajout du readme.txt

== Upgrade Notice ==

= 1.0 =
Version stable

= 0.2 =
Préparation du plugin pour soumission sur WordPress.org

