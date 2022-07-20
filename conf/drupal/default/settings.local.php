<?php

// phpcs:ignoreFile

declare(strict_types = 1);

// Database settings.
$databases = [];
$databases['default']['default'] = [
  'database' => $_ENV['MYSQL_DATABASE'],
  'username' => $_ENV['MYSQL_USER'],
  'password' => $_ENV['MYSQL_PASSWORD'],
  'prefix' => '',
  'host' => $_ENV['MYSQL_HOST'],
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => $_ENV['MYSQL_DRIVER'],
];

// Hash salt.
// var_dump(Drupal\Component\Utility\Crypt::randomBytesBase64(55));
$settings['hash_salt'] = $_ENV['DRUPAL_HASH_SALT'];

// Access control for update.php script.
$settings['update_free_access'] = FALSE;
// Allows users to download code into site.
$settings['allow_authorize_operations'] = FALSE;

// The default list of directories that will be ignored by Drupal's file API.
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

// Location of configuration files.
$settings['config_sync_directory'] = $app_root . '/../conf/drupal/default/sync';

// Prevent deletion of orphan files.
// @todo Remove this line when the following issues will be fixed:
// - https://www.drupal.org/node/2801777
// - https://www.drupal.org/node/2708411
// - https://www.drupal.org/node/1239558
// - https://www.drupal.org/node/2666700
// - https://www.drupal.org/node/2810355
$config['system.file']['temporary_maximum_age'] = 0;

// Errors.
$config['system.logging']['error_level'] = 'hide';

// Trusted host patterns.
$settings['trusted_host_patterns'] = [
  '^localhost$',
  '^127\.0\.0\.1$',
  '^nginx$'
];

// Redis.
$settings['redis.connection']['interface'] = $_ENV['REDIS_INTERFACE'];
$settings['redis.connection']['host'] = $_ENV['REDIS_HOST'];
$settings['redis.connection']['port'] = 6379;
$settings['redis.connection']['base'] = $_ENV['REDIS_BASE'];
$settings['redis_compress_length'] = $_ENV['REDIS_COMPRESS_LENGTH'];
$settings['redis_compress_level'] = $_ENV['REDIS_COMPRESS_LEVEL'];

// Performance - cache.
$settings['cache_prefix'] = $_ENV['SITE_CACHE_PREFIX'];
$settings['cache']['default'] = $_ENV['SITE_CACHE_DEFAULT'];

// Load services definition file.
$settings['container_yamls'][] = $app_root . '/modules/contrib/redis/exemple.services.yml';
$settings['container_yamls'][] = $app_root . '/modules/contrib/redis/redis.services.yml';
// $settings['container_yamls'][] = $app_root . '/../conf/drupal/default/services.yml';
$settings['container_yamls'][] = $app_root . '/../conf/drupal/default/development.services.yml';


// Dev.
$settings['rebuild_access'] = TRUE;
$config['devel.settings']['devel_dumper'] = 'var_dumper';
$config['system.logging']['error_level'] = 'verbose';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';
$settings['cache']['bins']['render'] = 'cache.backend.null';

