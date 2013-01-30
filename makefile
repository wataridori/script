PHPPATH := $(shell which php)
SCRIPTPATH := $(shell dirname $(PHPPATH))
LIB := $(shell dirname $(SCRIPTPATH))/lib/php	

shellScript:  	
	@cp TUSLanguage.php $(LIB) 
	@cp -rf TUSLib $(LIB)
	@echo "#!$(PHPPATH) " > $(SCRIPTPATH)/tus
	@echo "<?php" >> $(SCRIPTPATH)/tus
	@echo "	include (\"TUSLanguage.php\");" >> $(SCRIPTPATH)/tus
	@echo "?>"  >> $(SCRIPTPATH)/tus
	chmod 777 $(SCRIPTPATH)/tus
	@echo "Install tus command successfully !"