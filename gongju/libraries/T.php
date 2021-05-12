<?php

    /**
     * Class Navigate_model
     */
    class T
    {
        function __construct()
        {

        }

        /**生成字符串
         * @param int $length
         *
         * @return string
         */
        static function makeString($length=6)
        {
            $str="3456789abcdfghijkmnprstuvwxyABDFGHJKMNPQRTUY";
            return substr(str_shuffle($str),0,$length);
        }

        /**得到IP
         * @return mixed
         */
        static function Ip()
        {
            return isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ? $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] : $_SERVER[ "REMOTE_ADDR" ];
        }

    }

?>