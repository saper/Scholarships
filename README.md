# Sample .htaccess file

```
DirectoryIndex index.php

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /wm/schols/index.php [L]
</IfModule>
```

# Config file

Create includes/config.php, based on config.sample.php, filling in database credentials and other settings.

## Authors
* Katie Filbert, Wikimania 2012 Washington DC organizing team
* Harel Cain, Wikimania 2011 Haifa organizing team
* Wikimania 2010 Gdansk organizing team
* Wikimania 2009 Buenos Aires organizing team

##License
GNU GPL 3.0
