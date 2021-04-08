# Development notes

* Trigger crons via http://localhost/includes/cron_inc.php
* I18N after modifing ```*.po``` files - the ```*.mo````files have to be generated
    * for example in [locale/de-DE](locale/de-DE) execute ```msgfmt base.po -o base.mo```