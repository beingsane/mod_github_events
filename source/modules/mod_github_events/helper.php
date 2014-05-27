<?php
/**
 * @author Yireo
 * @copyright Copyright (C) 2013 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Helper class
 */
class modGithubEventsHelper
{
    /*
     * Method to get the feed-data
     */
	static public function getData(&$params)
	{
        // Fetch the remote lines
        $result = self::getRemoteData($params);
        $rows = json_decode($result, true);
        if(empty($rows)) {
            return null;
        }

        // Parse the lines
        foreach($rows as $rowId => $row) {

            switch($row['type']) {
                case 'PushEvent':
                    $row['type'] = 'MOD_GITHUB_EVENTS_FIELD_TYPE_PUSH';
                    break;
            }

            $row['repo_name'] = (isset($row['repo']['name'])) ? $row['repo']['name'] : null; 
            $row['repo_url'] = (isset($row['repo']['url'])) ? $row['repo']['url'] : null; 

            $row['actor_name'] = (isset($row['actor']['login'])) ? $row['actor']['login'] : null; 
            $row['actor_url'] = (isset($row['actor']['url'])) ? $row['actor']['url'] : null; 
            $row['actor_image'] = (isset($row['actor']['avatar_url'])) ? $row['actor']['avatar_url'] : null; 
            unset($row['actor']);

            $rows[$rowId] = $row;
        }

        return $rows;
    }

    /*
     * Method to get the remote data
     */
	static public function getRemoteData(&$params)
	{
        // Construct the remote URL from the parameters
        $user = $params->get('user');
        $clientId = $params->get('client_id');
        $clientSecret = $params->get('client_secret');
        $url = 'https://api.github.com/users/'.$user.'/events?client_id='.$clientId.'&client_secret='.$clientSecret;

        // Check for feed-caching
        if($params->get('owncache', 1) == 1) {
            $cacheDir = JPATH_SITE.'/cache/mod_github_events/';
            if(!is_dir($cacheDir)) @mkdir($cacheDir);
            $cacheFile = $cacheDir.md5($url).'.json';
            $cacheExpiry = (60 * 60 * 4);

            if(file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheExpiry) {
                $result = file_get_contents($cacheFile);
                if(!empty($result)) {
                    return $result;
                }
            }
        }

        // Fetch the remote content using CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'mod_github_events [Joomla!] (by yireo.com)');
        $result = curl_exec($ch);

        // If feed-caching is enabled, dump to the cache
        if($params->get('owncache', 1) == 1) {
            @file_put_contents($cacheFile, $result);
        }

        return $result;
    }
}
