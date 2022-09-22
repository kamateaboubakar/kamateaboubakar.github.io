# Structure d'un projet

## Recommandations

Nous recommandons d'installer au même niveau de la racine du serveur web.

```
/src
	/<librairies projet>
/vendor
/<racine www>
	/<vos scripts php>
	/assets
	/files
/composer.json
```

Cependant, certains hébergeurs donnent l'accès au contenu du serveur web. Alors, initiez à la racine le fichier **composer.json** ou le package **php-pv**.

```
/<racine www>
	/src
		/<librairies projet>
	/vendor
	/assets
	/files
	/composer.json
	/<vos scripts php>
```

## Voir aussi

- [L'Application](application.md)
- [La zone web](zoneweb/zoneweb.md)
