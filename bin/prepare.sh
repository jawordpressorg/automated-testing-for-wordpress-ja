#!/usr/bin/env bash

set -ex

WP_TESTS_DIR=${WP_TESTS_DIR-/tmp/wordpress-tests-lib}
WP_MB_PATCH=$(curl "https://api.wordpress.org/plugins/info/1.0/wp-multibyte-patch.json" | jq -r .download_link)

curl -s $WP_MB_PATCH -o plugin.zip
if [ -d $WP_TESTS_DIR/data/wp-multibyte-patch ]; then
	rm -fr $WP_TESTS_DIR/data/wp-multibyte-patch
fi
unzip plugin.zip -d $WP_TESTS_DIR/data
rm -f plugin.zip

curl -s https://downloads.wordpress.org/translation/core/4.8/ja.zip -o ja.zip
unzip ja.zip -d .lang
cp -f .lang/* $WP_TESTS_DIR/data/languages/
rm -f ja.zip
rm -fr .lang
