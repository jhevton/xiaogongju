<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/28
 * Time: 11:52
 */
if ( ! function_exists('base_url'))
{
    /**
     * Base URL
     *
     * Create a local URL based on your basepath.
     * Segments can be passed in as a string or an array, same as site_url
     * or a URL to a file can be passed in, e.g. to an image file.
     *
     * @param	string	$uri
     * @param	string	$protocol
     * @return	string
     */
    function base_url($uri = '', $protocol = NULL)
    {
        $uri=strip_tags($uri);
        return get_instance()->config->base_url($uri, $protocol)."?v=".(isset(get_config()['version'])?get_config()['version']:"");
    }
}