PHPlanningMaker
===============
Version 1.0b2


1) Required Configuration
-------------------------
Apache 2 w/ mod_rewrite
PHP5.3 w/ php5-intl, php5-apc --> cf PHP Config section below
node in your path with jshiht, less, recess and uglify modules
composer.phar in your path

2) Deployment
-------------

a) First, get all the files from the repositor
'git clone git@github.com:lbillon/PHPM.git'

b) Update local configuration
'cd PHPM'

Copy example config file
'cp app/config/parameters.ini.dist app/config/parameters.ini'

Edit the `parameters.ini` file to suit your local configuration

c) Make sure the file permissions are correct

d) Execute the deployment script
'/bin/deploy'
The script must return no errors for the deployment to be successful.

e) Generate database tables and import data
'./app/console doctrine:schema:update --force'
'app/console doctrine:fixtures:load --purge-with-truncate'

*** Mac-specific instructions ***
Install MacPort:
'http://www.macports.org/install.php'

How to install intl on MAMP
http://blog.geertvd.be/2011/05/18/installing-the-intl-extension-on-mamp/

Then follow this:
'http://www.blogafab.com/installer-et-utiliser-nodejs-et-le-module-less-sur-mac-os-x/'

*** PHP Config ***

You'll need PHP-Intl module

Magic quotes should be disabled (or there'll be strange behaviors in some forms, Symfony2 already handles this).
Add this directive to your php.ini :
'magic_quotes_gpc = Off'



