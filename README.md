# Automated testing for the WordPress Japanese package with the WP Multibyte Patch

[![Build Status](https://travis-ci.org/jawordpressorg/automated-testing-for-wordpress-ja.svg?branch=master)](https://travis-ci.org/jawordpressorg/automated-testing-for-wordpress-ja)

## Run automated testing on the local machine

```
$ git clone git@github.com:jawordpressorg/automated-testing-for-wordpress-ja.git
$ cd automated-testing-for-wordpress-ja
$ bash bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version]
$ bash bin/prepare.sh
```

Then run phpunit.

```
$ phpunit
```
