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
        //$url=get_instance()->config->base_url($uri, $protocol)."?v=".(isset(get_config()['version'])?get_config()['version']:"");

        $url=get_instance()->config->base_url($uri, $protocol);
        if($url){
            $suffix_arr=explode('.',$url);
            if(is_array($suffix_arr))
            {
                $end_str=strtolower(end($suffix_arr));
                if($end_str=='js'||$end_str=='ico'||$end_str=='png'||$end_str=='jpeg'||$end_str=='jpg'||$end_str=='css'||$end_str=='gif'||$end_str=='ttf'){
                    $url.="?v=".(isset(get_config()['version'])?get_config()['version']:"");
                }
            }
        }
        return $url;
    }
}