<?php

if ( ! function_exists('dump') )
{
    function dump( $var )
    {
        echo "<pre>";
        var_dump( $var );
        echo "</pre>";
    }
}

if ( ! function_exists('dd') )
{
    function dd()
    {
        echo "<pre>";
        var_dump( $var );
        echo "</pre>";
        die;
    }
}

if ( ! function_exists('dump_log') )
{
    function dump_log( $var )
    {
        ob_start();
        var_dump($var);
        $contents = ob_get_clean();
        error_log( $contents );
    }
}