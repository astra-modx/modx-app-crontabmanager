# Dev install

Version components See [.env](.env)
```shell
PACKAGE_VERSION_MAJOR=3
PACKAGE_VERSION_MINOR=6
PACKAGE_VERSION_PATCH=0
PACKAGE_RELEASE=beta
```


```shell
# Run Docker and Database Modx
make remake
make composer-package
make package-build # your zip pack to dist ./target
make package-install # your zip pack to dist ./target
```

```shell
# Run Docker and Database Modx
make package-deploy
```

### All commands

See [Makefile](Makefile)
