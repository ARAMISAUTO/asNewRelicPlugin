<?php

/**
 * asNewRelicPlugin configuration.
 *
 * @package     asNewRelicPlugin
 * @subpackage  config
 * @author      Bysoft
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class asNewRelicPluginConfiguration extends sfPluginConfiguration
{
	const VERSION = '1.0.0-DEV';

	/**
	 * @see sfPluginConfiguration
	 */
	public function initialize()
	{
		if (extension_loaded('newrelic')) {
			// Make sure New Relic instrumentation is executed prior to command run
			$this->dispatcher->connect('command.pre_command', array('asNewRelicEvents', 'commandPreEventHook'));
		}
	}
}
