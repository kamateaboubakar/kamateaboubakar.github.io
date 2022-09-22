# Méthodes principales Bases de données

L'application, La zone et les scripts possèdent des méthodes principales, pour une base de donnée par défaut.

Méthode | Description
------------- | -------------
CreeBdPrinc() | Crée une instance de la base de données principale
CreeFournPrinc() | Crée un fournisseur de données référençant la base de données principale

```php
class MonApp extends \Pv\Application\Application
{
	public function CreeBdPrinc()
	{
		return new MaBD1() ;
	}
}

class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
	public function DetermineEnvironnement()
	{
		$bd = $this->CreeBdPrinc() ; // Retournera new MaBD1()
	}
}
```

# Méthodes principales Composants

La zone et les scripts possèdent des méthodes pour des composants du design par défaut.

Méthode | Description
------------- | -------------
CreeTablPrinc($nomComp='tablPrinc') | Crée un tableau de données par défaut de la zone
CreeFormPrinc($nomComp='formPrinc') | Crée un fourmulaire de données par défaut de la zone
CreeGrillePrinc($nomComp='grillePrinc') | Crée une grille de données par défaut de la zone 
CreeRepetPrinc($nomComp='repetPrinc') | Crée un répéteur de données par défaut de la zone

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
	public function DetermineEnvironnement()
	{
		$this->Form1 = $this->InsereFormPrinc() ; // Retournera le formulaire de données
	}
}
```

## Voir aussi

- [Bases de données](commondb.md)
- [Zone web](zoneweb/zoneweb.md)
- [Scripts web](zoneweb/scripts.md)
- [Tableaux de données](zoneweb/tableaudonneees.md)
- [Formulaires de données](zoneweb/formulairedonnees.md)