# Le Script de session

Le script de session est un script de la zone, sur lequel vous pouvez rediriger avec ses paramètres HTTP (GET et POST) d'origine.

Exemple :
Redirigez vers un script avec tableau de données, sans avoir à renseigner les filtres.

## Utilisation

Lors de la déclaration du script, renseignez la propriété **$EstScriptSession** à **true**.

```php
class MonScript1 extends \Pv\ZoneWeb\Script\Script
{
public $EstScriptSession = true ;
}
```

A partir des autres scripts, redirigez avec la fonction **UrlRedirScriptSession** de la zone parent.

```php
class MonScript2 extends \Pv\ZoneWeb\Script\Script
{
public function DetermineEnvironnement()
{
// Rediriger si le résultat est concluant
if(isset($_POST["soumetResult"] == 1))
{
Header("Location: ".$this->ZoneParent->UrlRedirScriptSession()) ;
exit ;
}
}
}
```

## Voir aussi

- [Zone web](zoneweb.md)
- [Entêtes de document](entetedoc.md)
- [Scripts web](scripts.md)
- [Documents web](documents.md)
- [Actions](actions.md)
- [Filtres de données](filtresdonnees.md)
- [Tableaux de données](tableauxdonnees.md)
- [Formulaires de données](formulairedonnees.md)
- [Scripts membership](scriptsmembership.md)
