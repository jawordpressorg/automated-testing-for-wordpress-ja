#!/usr/bin/env bash

set -ex

WP_TESTS_DIR=${WP_TESTS_DIR-/tmp/wordpress-tests-lib}

WP_MB_PATCH=$(curl "https://api.wordpress.org/plugins/info/1.0/wp-multibyte-patch.json" | jq -r .download_link)

curl -s $WP_MB_PATCH -o plugin.zip
unzip plugin.zip -d .plugin
rm -f plugin.zip

# Language file is used for only getting timezone and other locale settings, so we do not need to update this file. (Probably...)
curl -s https://downloads.wordpress.org/translation/core/4.8/ja.zip -o ja.zip
unzip ja.zip -d .lang
cp -f .lang/* $WP_TESTS_DIR/data/languages/
rm -f ja.zip
