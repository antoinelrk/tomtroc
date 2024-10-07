# Projet d'étude: TomTroc

Dans le cadre de ma formation chez OpenClassrooms, j'ai réalisé un site MVC de zéro avec Php 8.2.

## Requirements

- Php 8.2
- MySQL5 (ou equivalent: pgsql ou mariadb)
- Composer 2.0

## Setup

1. Créer la base de donnée
2. Insérer la structure avec le fichier ``dump.sql``. Des données d'exemple sont déjà présents.
3. **Copier** puis renommer le fichier ``config.php.example`` en ``config.php`` puis remplir les informations.

### Docker

Le fichier ``docker-compose.yml`` possède tout ce qu'il faut pour lancer l'application.

## Se connecter à l'application

Il existe à ce jour 4 comptes: John, Jane, Jack et Johanna Doe, vous pouvez vous connecter à ces comptes avec le pattern suivant:

- ``<john|jane|jack|johanna>@doe.fr``
- ``P@ss1234``

Ils ont chacun 1 à 3 livres enregistré.