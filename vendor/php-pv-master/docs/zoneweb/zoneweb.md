# Zone Web - Présentation

## Définition

La zone web simple représente un ensemble de pages web. L'utilisateur pourra y accéder s'il possède les droits adéquats.

## Déclaration

Créez une classe héritant de **\Pv\ZoneWeb\ZoneWeb**.

```php
class MaZone1 extends \Pv\ZoneWeb\ZoneWeb
{
}
```

La zone est un élément d'application, à inscrire avec la méthode **InsereIHM()**.

```php
class MonApplication1 extends \Pv\Application\Application
{
	protected function ChargeIHMs()
	{
		$this->InsereIHM("maZone", new MaZone1()) ;
	}
}
```

## Voir aussi

- [Entêtes de document](entetedoc.md)
- [Scripts web](scripts.md)
- [Documents web](documents.md)
- [Actions](actions.md)
- [Filtres de données](filtresdonnees.md)
- [Tâches planifiées](taches.md)
- [Composants de rendu](composants_rendu.md)
- [Tableaux de données](tableauxdonnees.md)
- [Formulaires de données](formulairedonnees.md)
- [Scripts membership](scriptsmembership.md)
- [Le composant ChartJS](chartjs.md)
- [Index](../index.md)
