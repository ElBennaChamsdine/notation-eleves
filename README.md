# API de notation d'élèves

Cette API a été créée avec Symfony 6.2 et permet de gérer les informations des élèves d'une classe ainsi que leurs notes.

## Installation

1. Cloner le dépôt: git clone https://github.com/ElBennaChamsdine/notation-eleves.git
2. Installer les dépendances : `composer install`
3. Configurer la base de données dans le fichier `.env`
4. Créer la base de données : `php bin/console doctrine:database:create`
5. Exécuter les migrations : `php bin/console doctrine:migrations:migrate`

## Utilisation

L'API utilise le format JSON pour les données.

### Ajouter un élève

Pour ajouter un élève, envoyer une requête POST à l'URL `/students` avec les paramètres suivants :
- `firstName` : le prénom de l'élève (obligatoire)
- `lastName` : le nom de l'élève (obligatoire)
- `dateOfBirth` : la date de naissance de l'élève au format YYYY-MM-DD (obligatoire)
Exemple de requête :

``` 
POST /students
Host: localhost:8000
Content-Type: application/json

{
    "firstName": "Chamseddine",
    "lastName": "El Benna",
    "dateOfBirth": "1995-01-15"
}

```

### Modifier les informations d'un élève

Pour modifier les informations d'un élève, il suffit d'envoyer une requête PUT à l'URL `/students/{id}` avec les paramètres suivants :
- `firstName` : le nouveau  prénom de l'élève (optionnel)
- `lastName` : le nouveau  nom de l'élève (optionnel)
- `dateOfBirth` : la nouvelle  date de naissance de l'élève au format YYYY-MM-DD (optionnel)
Exemple de requête :

``` 
PUT /students/1 
Host: localhost:8000
Content-Type: application/json

{
    "firstName": "Sam",
    "lastName": "El Benna",
    "birthdate": "1995-01-15"
}

```

### Supprimer un élève

Pour supprimer un élève, il suffit d'envoyer une requête DELETE à l'URL `/students/{id}` où `{id}` est l'identifiant de l'élève à supprimer.

Exemple de requête :

``` 
DELETE /students/1
Host: localhost:8000

```

### Ajouter une note à un élève

Pour ajouter une note à un élève, il suffit d'envoyer une requête POST à l'URL `/students/{id}/grades` avec les paramètres suivants :
- `value` : la valeur de la note (entre 0 et 20, obligatoire)
- `subject` : la matière de la note (optionnel)

Exemple de requête :

``` 
POST /students/1/grades
Host: localhost:8000
Content-Type: application/json

{
    "value": 15,
    "subject": "Mathématiques"
}

```


### Récupérer la moyenne de toutes les notes d'un élève

Pour récupérer la moyenne de toutes les notes d'un élève, il suffit d'envoyer une requête GET à l'URL `/students/{id}/grades/average` où `{id}` est l'identifiant de l'élève.

Exemple de requête :

``` 
GET /students/1/grades/average
Host: localhost:8000

```

### Récupérer la moyenne générale de la classe

Pour récupérer la moyenne générale de la classe (moyenne de toutes les notes données), il suffit d'envoyer une requête GET à l'URL `/grades/average`.

Exemple de requête :

``` 
GET /grades/average
Host: localhost:8000

```

### Auteurs

-Chamseddine El Benna elbenna.chamsdine@gmail.com
