#!/usr/bin/env bash

set -ex

WP_MB_PATCH=$(curl "https://api.wordpress.org/plugins/info/1.0/wp-multibyte-patch.json" | jq -r .download_link)

curl -s $WP_MB_PATCH -o plugin.zip
unzip plugin.zip -d .plugin
rm -f plugin.zip
