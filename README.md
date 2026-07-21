# Sujet 5 - Gestion des tâches (ToDo) — Démarche pour réaliser le projet (Symfony)

## 1) Mise en place du projet
1. Créer le projet Symfony.
2. Configurer la connexion à la base de données dans le fichier `.env`.
3. Lancer le projet pour vérifier que tout fonctionne (`symfony serve`).

## 2) Fonctionnalités à couvrir
Le projet doit permettre :
- Ajouter une tâche
- Modifier une tâche
- Supprimer une tâche
- Définir une priorité
- Changer le status
- Affecter une tâche à un utilisateur

## 3) Conception du modèle de données
### 3.1 Entités
- **User** (utilisateur)
- **Task** (tâche)

### 3.2 Relations
- Une **Task** doit être **affectée à un User** (relation `ManyToOne`).
- Une option possible :
  - soit l’affectation est obligatoire (assignee non nullable)
  - soit elle est optionnelle (assignee nullable).  
  Choisir l’une des deux et l’appliquer dans l’entité.

### 3.3 Champs de Task (exemples)
- `title` (titre)
- `priority` (priorité)
- `status` (statut)
- `assignee` (utilisateur affecté)
- (optionnel) `description`, `createdAt`, `updatedAt`

### 3.4 Valeurs autorisées (important)
Définir des listes de valeurs autorisées, par exemple :
- `priority` : `LOW`, `MEDIUM`, `HIGH`
- `status` : `TODO`, `IN_PROGRESS`, `DONE`

> Objectif : empêcher les valeurs invalides (via validation + choix dans le formulaire).

### 3.5 Génération Doctrine
- Générer les entités avec Maker Bundle : `make:entity`.

## 4) Création / mise à jour de la base de données
1. Générer une migration à partir des entités : `doctrine:migrations:diff`.
2. Appliquer la migration : `doctrine:migrations:migrate`.
3. Vérifier que les tables `task` et `user` sont créées.

## 5) Formulaires (saisie & validation)
1. Créer un formulaire (ex : `TaskType`) pour créer et modifier une tâche.
2. Champs du formulaire :
   - `title` (type texte)
   - `priority` (liste déroulante)
   - `status` (liste déroulante)
   - `assignee` (sélection d’un `User` via `EntityType`)
3. Validation :
   - `title` obligatoire (ex : `NotBlank`)
   - `priority` et `status` uniquement parmi les valeurs autorisées
4. Le formulaire doit permettre de modifier réellement `priority`, `status` et `assignee`.

## 6) Logique CRUD (Controller + Routes)
Créer un contrôleur de tâches avec les routes et actions suivantes :

- **Index / Liste** (optionnel mais recommandé)
  - Affiche toutes les tâches (avec au moins `title`, `priority`, `status`, `assignee`).
- **New**
  - Créer une tâche avec `TaskType` + persistance Doctrine.
- **Edit**
  - Modifier une tâche existante (update Doctrine) via `TaskType`.
- **Delete**
  - Supprimer une tâche (confirmation recommandée).

Pour chaque action :
- afficher la vue Twig correspondante
- traiter le formulaire (`handleRequest`)
- `persist()` + `flush()` pour créer/modifier
- `remove()` + `flush()` pour supprimer

## 7) Vues (Twig)
Créer ces pages Twig :
- **Index** : liste des tâches (title, priority, status, assignee)
- **New** : formulaire d’ajout
- **Edit** : formulaire de modification
- **Delete** : page de confirmation / action de suppression

## 8) Vérification des exigences (tests manuels)
Faire un test manuel pour confirmer que chaque fonctionnalité marche.

1. **Ajouter une tâche**
   - elle apparaît dans la liste
   - priorité/status/assignee sont enregistrés correctement

2. **Modifier une tâche**
   - changer `title`, `priority`, `status`, `assignee`
   - vérifier que les changements sont visibles après édition

3. **Supprimer une tâche**
   - supprimer une tâche
   - vérifier qu’elle disparaît de la liste

4. **Cas invalides**
   - essayer d’envoyer un `title` vide → rejet attendu
   - essayer de forcer une valeur invalide de `priority`/`status` → rejet attendu
   - (si affectation obligatoire) vérifier que l’assignee ne peut pas être laissée vide
