PHPlanningMaker
===============
1) Deployment
-------------
a)First, get all the files from the repository
	git clone git@github.com:lbillon/PHPM.git

b)Update local database configuration
	cd PHPM  
Copy example config file
	cp app/config/parameters.ini.dist app/config/parameters.ini  
Edit the `parameters.ini` file to suit your local DB configuration

c)Retreive all symfony bundles files
	./bin/vendors install  

d)Generate database tables 
	./app/console doctrine:schema:create






