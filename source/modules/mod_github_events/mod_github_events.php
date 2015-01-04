<?php
/**
 * @author Yireo
 * @copyright Copyright 2015 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the helper
require_once (dirname(__FILE__).'/helper.php');

// Fetch the list of items
$items = modGithubEventsHelper::getData($params);

// Display the output
require(JModuleHelper::getLayoutPath('mod_github_events'));
