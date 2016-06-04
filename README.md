# Two Factor Totp
Works with [OTP Authenticator](https://github.com/0xbb/otp-authenticator) which can be downloaded from [F-Droid](https://f-droid.org/repository/browse/?fdfilter=totp&fdid=net.bierbaumer.otp_authenticator).

## Enabling TOTP 2FA for your account
![](https://cloud.githubusercontent.com/assets/1374172/15801137/05c40164-2a8c-11e6-843e-d48b511dffc7.png)
![](https://cloud.githubusercontent.com/assets/1374172/15801138/05c54ea2-2a8c-11e6-9df4-bf8ef13391c9.png)
![](https://cloud.githubusercontent.com/assets/1374172/15801141/05ccbafc-2a8c-11e6-86a5-0f9bbfdd9a36.png)
![](https://cloud.githubusercontent.com/assets/1374172/15801139/05c75af8-2a8c-11e6-9adf-7820bece4965.png)
![](https://cloud.githubusercontent.com/assets/1374172/15801140/05c8c21c-2a8c-11e6-80de-c85faa851826.png)



## Building the app

The app can be built by using the provided Makefile by running:

    make

This requires the following things to be present:
* make
* which
* tar: for building the archive
* curl: used if phpunit and composer are not installed to fetch them from the web
* npm: for building and testing everything JS, only required if a package.json is placed inside the **js/** folder

The make command will install or update Composer dependencies if a composer.json is present and also **npm run build** if a package.json is present in the **js/** folder. The npm **build** script should use local paths for build systems and package managers, so people that simply want to build the app won't need to install npm libraries globally, e.g.:

**package.json**:
```json
"scripts": {
    "test": "node node_modules/gulp-cli/bin/gulp.js karma",
    "prebuild": "npm install && node_modules/bower/bin/bower install && node_modules/bower/bin/bower update",
    "build": "node node_modules/gulp-cli/bin/gulp.js"
}
```


## Publish to App Store

First get an account for the [App Store](http://apps.owncloud.com/) then run:

    make appstore

The archive is located in build/artifacts/appstore and can then be uploaded to the App Store.

## Running tests
You can use the provided Makefile to run all tests by using:

    make test

This will run the PHP unit and integration tests and if a package.json is present in the **js/** folder will execute **npm run test**

Of course you can also install [PHPUnit](http://phpunit.de/getting-started.html) and use the configurations directly:

    phpunit -c phpunit.xml

or:

    phpunit -c phpunit.integration.xml

for integration tests
