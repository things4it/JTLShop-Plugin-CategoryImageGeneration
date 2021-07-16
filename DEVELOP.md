# Development notes

## Misc

* Trigger crons via http://localhost/includes/cron_inc.php
* I18N after modifing ```*.po``` files - the ```*.mo````files have to be generated
    * for example in [locale/de-DE](locale/de-DE) execute ```msgfmt base.po -o base.mo```

## Image-Generation-Strategies

Just implement one of the provided interfaces in ``src\service\placementStrategy``
like ``OneProductImagePlacementStrategyInterface``. The implementation have to be

* registered in ``Boostrap.php`` -> factory-method
* included into the related source file for the config -> for example
  in ``adminmenu\select-source_one-image-placement-strategy.php``