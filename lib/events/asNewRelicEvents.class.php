<?php
class asNewRelicEvents
{
	/**
	 * This event is fired prior to any command execution.
	 *
	 * @param sfEvent $event
	 * @event command.pre_command
	 * @see http://newrelic.com/docs/php/the-php-api
	 */
	public static function commandPreEventHook(sfEvent $event)
	{
		/* @var $task sfBaseTask */
		$task = $event->getSubject();

		// Make sure metrics are collected as background job
		newrelic_background_job(true);

		// Define New Relic application name
		if (isset($event['options']['application'])) {
			$sfAppName = $event['options']['application'];
			$task->checkAppExists($sfAppName);
			ProjectConfiguration::getApplicationConfiguration($sfAppName, $event['options']['env'], true);
		}
		ini_set('newrelic.appname', sfConfig::get('app_newrelic_appname', sfConfig::get('app_newrelic_appname')));

		// Name transaction
		newrelic_name_transaction(sprintf('symfony/task/%s', $task->getFullName()));

		// Collect task parameters
		foreach ($event['arguments'] as $argName => $argValue) {
			if (!empty($argValue)) {
				newrelic_add_custom_parameter($argName, $argValue);
			}
		}
		foreach ($event['options'] as $optName => $optValue) {
			if (!empty($optValue)) {
				newrelic_add_custom_parameter($optName, $optValue);
			}
		}
	}
}