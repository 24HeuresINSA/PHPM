PHPlanningMaker
===============

1) Required Configuration
-------------------------
Apache 2 w/mod_rewrite
PHP5.3 w/ php5-intl --> cf PHP Config section below

2) Deployment
-------------

a) First, get all the files from the repositor
'git clone git@github.com:lbillon/PHPM.git'

b) Update local database configuration
'cd PHPM'

Copy example config file
'cp app/config/parameters.ini.dist app/config/parameters.ini'

Edit the `parameters.ini` file to suit your local DB configuration

c) Retreive all symfony bundles files
'./bin/vendors install'

d) Generate database tables 
'./app/console doctrine:schema:update --force'

e) Link assets
'app/console assets:install web --symlink'

f) Dump assetc
'app/console assetic:dump'



DEPRECATED) Add NodeJS and LESS on your webserver - tutorial:
'http://www.dobervich.com/2011/05/10/less-css-assetic-configuration-in-a-symfony2-project/'

*** Mac-specific instructions ***
Install MacPort:
'http://www.macports.org/install.php'

How to install intl on MAMP
http://blog.geertvd.be/2011/05/18/installing-the-intl-extension-on-mamp/

Then follow this:
'http://www.blogafab.com/installer-et-utiliser-nodejs-et-le-module-less-sur-mac-os-x/'


*** PHP Config ***

You'll need Php-Intl module

Magic quotes should be disabled (or there'll be strange behaviors in some forms, Symfony2 already handles this).
Add this directive to your php.ini :
'magic_quotes_gpc = Off'



