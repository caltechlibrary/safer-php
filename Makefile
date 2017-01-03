#
# this is a simple makefile to generate a release to host on Github
#
PROJECT = safer-php

VERSION = $(shell grep -m 1 '"SAFER_VERSION"' safer.php | cut -d\" -f 4)

BRANCH = $(shell git branch | grep '* ' | cut -d\  -f 2)

DIST_DIR = $(PROJECT)-$(VERSION)

save:
	git commit -am "Quick Save"
	git push origin $(BRANCH)

refresh:
	git fetch origin
	git pull origin $(BRANCH)

clean:
	if [ -d $(DIST_DIR) ]; then /bin/rm -R $(DIST_DIR); fi
	if [ -f $(PROJECT)-$(VERSION)-release.zip ]; then /bin/rm -R $(PROJECT)-$(VERSION)-release.zip; fi

release:
	mkdir -p $(DIST_DIR)
	cp -v *.md $(DIST_DIR)
	cp -v *.php $(DIST_DIR)/
	cp -vR examples $(DIST_DIR)/
	zip -r $(PROJECT)-$(VERSION)-release.zip $(DIST_DIR)/
