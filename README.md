PHPlanningMaker
===============

1) Deployment
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

e) Links assets
'app/console assets:install web --symlink'

f) Add NodeJS and LESS on your webserver - tutorial:
'http://www.dobervich.com/2011/05/10/less-css-assetic-configuration-in-a-symfony2-project/'

*** Mac-specific instructions ***
Install MacPort:
'http://www.macports.org/install.php'

Then follow this:
'http://www.blogafab.com/installer-et-utiliser-nodejs-et-le-module-less-sur-mac-os-x/'





