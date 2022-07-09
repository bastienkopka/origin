#!/bin/bash

conf_path="conf/drupal/default"
app_default_path="app/sites/default"

chmod 755 $app_default_path

if [ -f "$app_default_path/settings.php" ]; then
    rm -f "$app_default_path/settings.php"
else
    cp "$conf_path/settings.php" $app_default_path
fi