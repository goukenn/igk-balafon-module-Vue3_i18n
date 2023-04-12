# igk/js/Vue3_i18n Module
 
> @C.A.D.BONDJEDOUE


Ce module permet l'importation du fichier du cdn i18n pour vue3.

# utilisation dans le context de génération de vue.
```PHP
igk_require_module(igk\js\Vue3_i18n::class);
```

Il dispose d'une classe utilitaire qui prend le chargement de la locale du projet 

```PHP
\igk\js\Vue3_i18n\Helpers::Locale::LoadLocale(BaseController $ctrl) : ?array;
```


Un projet vue3 se chargera de l'utilisé pour injecter la locale dans le projet vue

```PHP
$i18n = Vuei18n::InitDoc($doc, $ctrl);
```

Au moment  du la creation de l'application = utilisé la méthode `uses` pour l'enregistrer

> un exemple: 
```PHP 
use igk\js\Vue3\Libraries\i18n\Vuei18n; 
use igk\js\Vue3\Libraries\VueRouter;
use IGK\Helper\ViewHelper;
igk_require_module(igk\js\Vue3::class);
igk_require_module(igk\js\Vue3_i18n::class);

$i18n   = Vuei18n::InitDoc($doc, $ctrl);
$router = VueRouter::InitDoc($doc);  
$router->addRoute("/", [
    "template" => "<div>OK render / document </div>"
]); 
$t->vue_sfc_app($ctrl, 'app-main', ViewHelper::Dir() . "/src/main.vue")
->uses([
    $i18n,
    $router
]); 
```


## Configuration du Projet 


Le projet(Controller) doit contenir l'ensemble des locales pour i18n dans le dossier
'Configs' du project 

- Si `Configs/vue3-i18n.php` est un fichier ce dernier sera utilisé pour charger la locale.
il  doit retourner un tableau de [locale=>[key=>value]].

- Si `Configs/vue3-i18n` est présent et est un dossier. l'ensemble des fichier sera alors merger pour
produire la locale. 

Note: 
afin de garder une cohérence avec la localisation présente dans Balafon, le module précharge d'abord les fichiers `Configs/Lang/lang.[locale].presx` avant ce qui seront applicatif.

