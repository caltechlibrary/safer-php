#
# this is a simple makefile to generate a release to host on Github
#
PROJECT = safer-php

VERSION = $(shell grep -m 1 '"SAFER_VERSION"' safer.php | cut -d\" -f 4)

BRANCH = $(shell git branch | grep '* ' | cut -d\  -f 2)

save:
	git commit -am "Quick Save"
	git push origin $(BRANCH)

refresh:
	git fetch origin
	git pull origin $(BRANCH)

clean:
	if [ -d dist ]; then /bin/rm -R dist; fi
	if [ -f $(PROJECT)-$(VERSION)-release.zip ]; then /bin/rm -R $(PROJECT)-$(VERSION)-release.zip; fi

release:
	mkdir -p dist
	cp -v *.md dist/
	cp -v *.php dist/
	cp -vR examples dist/
	zip -r $(PROJECT)-$(VERSION)-release.zip dist/
