# Dev install

cd Extras/crontabmanager/core/components/crontabmanager/vendor && composer install --no-dev


```shell
cp .env.example .env
# Run Docker and Database Modx
make remake
make composer-package
make package-build-deploy # your zip pack to dist ./target
```

### All commands

See [Makefile](Makefile)
