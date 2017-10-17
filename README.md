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

## Checklist

* [x] The length of the excerpt should be 110 in Japanese.
* [x] The length of the excerpt in RSS should be 110 in Japanese.
* [x] Double-width space should be used as search delimiter.
* [x] Email should be encoded with ISO-2022-JP
* [ ] Characters in pingback and trackback should not be broken.
* [ ] The filename which is in Japanese should be encoded md5.
* [x] The length of comment in dashboard should be 40.
* [x] The length of draft in dashboard should be 40.
* [ ] Incremental search of link in tinymce should fire from 2 chars.
* [x] We don't need italic in dashboard.

* https://wpdocs.osdn.jp/WordPress_%E6%97%A5%E6%9C%AC%E8%AA%9E%E7%89%88%E3%83%81%E3%83%BC%E3%83%A0%E8%B3%87%E6%96%99/%E3%83%86%E3%82%B9%E3%83%88%E9%A0%85%E7%9B%AE

## Related

* https://meta.trac.wordpress.org/ticket/3163
