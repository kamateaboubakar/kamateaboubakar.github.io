# Authentification API Restful

L'API Restful se base sur le système d'authentification **\Pv\Membership\Sql**, décrit [ici](../membership.md).

## Prérequis base de données

En plus des tables du membership, vous devez créer la table des sessions, disponibles sur la branche Github [sql](https://github.com/PvSolutions/php-pv/tree/sql/SessionMembership) de PHP-PV.

Fichier | Description
------------- | -------------
membership-session-mysql.sql | Tables pour MySQL

## Déclaration

Dans votre code source, dérivez la classe **\Pv\Membership\Sql**.

```php
class Membership1 extends \Pv\Membership\Sql
{
	public $RootMemberId = "1" ;
	protected function InitConfig(& $parent)
	{
		parent::InitConfig($parent) ;
		$this->Database = new BDPrinc1() ;
	}
}
```

Puis, renseignez la propriété **$NomClasseMembership** et **$InclureScriptsMembership** de l'API Restful.

```php
class ApiRestful1 extends \Pv\ApiRestful\ApiRestful
{
	public $NomClasseMembership = 'Membership1' ;
	public $InclureScriptsMembership = true ;
	protected function ChargeRoutes()
	{
		$this->InsereRouteParDefaut(new RouteAccueilRestful1()) ;
		$this->InsereRoute("listePdts", "produits/list", new RouteListePdts()) ;
		$this->InsereRoute("editPdt", "produits", new RouteEditPdt()) ;
	}
}
```

Vous pouvez vérifier, en vous connectant avec la route \/acces\/connexion.

Exemple de requête CURL :
```
curl --location --request POST 'http://localhost/mon_api_rest/acces/connexion' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'login=root' \
--data-urlencode 'password=ADMIN' \
--data-urlencode 'device=mon device 1.0'
```

## Propriétés API

Propriétés | Description
------------- | -------------
$NomTableSession | Nom de la table des sessions, "membership_session" par defaut.
$DelaiExpirSession | Délai(en sec) pour terminer les sessions inactives. Valeur par défaut : 90.
$TotalJoursExpirDevice | Délai(en jours) pour terminer les sessions enregistrées avec l'option "se souvenir". Valeur par défaut : 90.
$AutoriserInscription | Inscrire automatiquement une route pour s'inscrire. Valeur par défaut : false.
$AutoriserModifPrefs | Inscrire automatiquement une route pour changer les informations personnelles. Valeur par défaut : false.
$NomRoutesAcces | Dossier pour les routes d'accès (connexion, inscription, recouvre mot de passe...). la valeur par défaut est "acces". 
$NomRoutesMonEspace | Dossier pour les routes d'espace membre (deconnexion, modif infos persos, change mot de passe...). la valeur par défaut est "mon_espace".

```php
class ApiRestful1 extends \Pv\ApiRestful\ApiRestful
{
	public $AccepterTousChemins = true ;
	public $CheminRacineApi = "/mon_api_rest/membership.php" ; // Chemin relatif du serveur web
	public $NomClasseMembership = 'Membership1' ;
	public $NomRoutesAcces = 'authentification' ; // La route login sera /authentification/connexion
	public $NomRoutesMonEspace = 'espace' ; // La route logout sera /espace/deconnexion
}
```

## Classes Routes membership

Propriétés | Valeur par défaut | Description
------------- | ------------- | -------------
$NomClasseRouteConnexion | \Pv\ApiRestful\RouteMembership\Connexion | Nom de la classe connexion
$NomClasseRouteDeconnexion | \Pv\ApiRestful\RouteMembership\Deconnexion | Nom de la classe déconnexion

```php
class RouteConnexion1 extends \Pv\ApiRestful\RouteMembership\Connexion
{
}
class ApiRestful1 extends \Pv\ApiRestful\ApiRestful
{
	public $NomClasseRouteConnexion = 'RouteConnexion1' ;
}
```

## Autres liens

- [Le système d'authentification](../membership.md)
- [La route Individuelle](individuel.md)