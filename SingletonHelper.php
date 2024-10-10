<?php
/**
 * SingletonHelper.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace TestToolkit;

use \Harmonia\Patterns\Singleton;

/**
 * Manages Singleton instances in tests, allowing you to back up, restore, and
 * modify them as needed.
 *
 * @codeCoverageIgnore
 */
class SingletonHelper
{
	/**
	 * Holds a backup of the Singleton instances.
	 *
	 * @var array<Singleton>
	 */
	private static $singletonsBackup = null;

	/**
	 * Backs up the current Singleton instances.
	 *
	 * This method saves the current state of all Singleton instances. It's
	 * useful for preserving the Singleton state before performing operations
	 * that might modify them.
	 *
	 * @return array<Singleton>
	 *   An array containing the backed-up Singleton instances.
	 * @throws \RuntimeException
	 *   If Singletons are already backed up.
	 */
	public static function BackupSingletons()
	{
		if (self::$singletonsBackup !== null) {
			throw new \RuntimeException('Singletons already backed up.');
		}
		self::$singletonsBackup = AccessHelper::GetNonPublicStaticProperty(
			Singleton::class,
			'instances'
		);
		return self::$singletonsBackup;
	}

	/**
	 * Restores Singleton instances from the backup.
	 *
	 * This method resets the Singleton instances to their state when the backup
	 * was made using BackupSingletons. This is typically used to revert
	 * Singletons to a known state after testing or other operations that may
	 * have altered them.
	 *
	 * @throws \RuntimeException
	 *   If no Singleton backup is found.
	 */
	public static function RestoreSingletons()
	{
		if (self::$singletonsBackup === null) {
			throw new \RuntimeException('No Singleton backup found.');
		}
		AccessHelper::SetNonPublicStaticProperty(
			Singleton::class,
			'instances',
			self::$singletonsBackup
		);
		self::$singletonsBackup = null;
	}

	/**
	 * Updates the Singleton instances.
	 *
	 * This method allows for updating the current Singleton instances with a
	 * new set of instances. It is primarily used in scenarios where Singleton
	 * instances need to be modified or replaced.
	 *
	 * As a precaution, this method throws an exception if the Singletons
	 * haven't been backed up using BackupSingletons, to prevent accidental
	 * loss or modification of the original instances.
	 *
	 * @param array<Singleton> $singletons
	 *   An array containing the new Singleton instances to be set.
	 * @return void
	 * @throws \RuntimeException
	 *   If Singletons are updated without having a backup.
	 */
	public static function UpdateSingletons($singletons)
	{
		if (self::$singletonsBackup === null) {
			throw new \RuntimeException('No Singleton backup found.');
		}
		AccessHelper::SetNonPublicStaticProperty(
			Singleton::class,
			'instances',
			$singletons
		);
	}
}
