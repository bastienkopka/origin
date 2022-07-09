<?php

namespace DrupalProject\composer;

use Composer\Script\Event;
use Composer\Semver\Comparator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The script handler class.
 */
class ScriptHandler {

  /**
   * Default directory.
   *
   * @var string
   */
  protected static string $defaultDir = '/app';

  /**
   * Get the Drupal root.
   *
   * @param string $projectRoot
   *   The project root.
   *
   * @return string
   *   The Drupal root.
   */
  protected static function getDrupalRoot($projectRoot) {
    return $projectRoot . self::$defaultDir;
  }

  /**
   * Create the required files.
   *
   * @param \Composer\Script\Event $event
   *   THe composer event.
   */
  public static function createRequiredFiles(Event $event): void {
    $fs = new Filesystem();
    /* @phpstan-ignore-next-line */
    $drupalRoot = static::getDrupalRoot(getcwd());

    $publicDirectoryPath = $drupalRoot . '/sites/default/files';
    if (!$fs->exists($publicDirectoryPath)) {
      $oldmask = umask(0);
      $fs->mkdir($drupalRoot . '/sites/default/files', 0777);
      umask($oldmask);
      $event->getIO()->write("Create a sites/default/files directory with chmod 0777");
    }

    $settingsFilePath = $drupalRoot . '/sites/default/settings.php';
    $customSettingsFilePath = $drupalRoot . '/sites/default/default.settings.php';
    if (!$fs->exists($settingsFilePath) && $fs->exists($customSettingsFilePath)) {
      $fs->copy($customSettingsFilePath, $settingsFilePath);
      $fs->chmod($settingsFilePath, 0666);
      $event->getIO()->write("Create a sites/default/settings.php file with chmod 0666");
    }
  }

  /**
   * Checks if the installed version of Composer is compatible.
   *
   * Composer 1.0.0 and higher consider a `composer install` without having a
   * lock file present as equal to `composer update`. We do not ship with a lock
   * file to avoid merge conflicts downstream, meaning that if a project is
   * installed with an older version of Composer the scaffolding of Drupal will
   * not be triggered. We check this here instead of in drupal-scaffold to be
   * able to give immediate feedback to the end user, rather than failing the
   * installation after going through the lengthy process of compiling and
   * downloading the Composer dependencies.
   *
   * @see https://github.com/composer/composer/pull/5035
   */
  public static function checkComposerVersion(Event $event): void {
    $composer = $event->getComposer();
    $io = $event->getIO();

    $version = $composer::VERSION;

    // The dev-channel of composer uses the git revision as version number,
    // try to the branch alias instead.
    if (preg_match('/^[0-9a-f]{40}$/i', $version)) {
      $version = $composer::BRANCH_ALIAS_VERSION;
    }

    // If Composer is installed through git we have no easy way to determine if
    // it is new enough, just display a warning.
    if ($version === '@package_version@' || $version === '@package_branch_alias_version@') {
      $io->writeError('<warning>You are running a development version of Composer. If you experience problems, please update Composer to the latest stable version.</warning>');
    }
    elseif (Comparator::lessThan($version, '1.0.0')) {
      $io->writeError('<error>Drupal-project requires Composer version 1.0.0 or higher. Please update your Composer before continuing</error>.');
      exit(1);
    }
  }

}
