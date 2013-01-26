<?php

/**
 * Absolute Path
 *
 * Returns an absolute path based on your basepath.
 * Segments can be passed via the
 * first parameter either as a string.
 * ie absolute_path('uploads/images/cow/)
 * would
 *
 * @access    public
 * @param    string
 * @return    string
 */
if ( ! function_exists('absolute_path'))
{
    function absolute_path($path = '')
    {
        $abs_path = str_replace('system/',$path, BASEPATH);
        
        //Add a trailing slash if it doesn't exist.
        $abs_path = preg_replace("#([^/])/*$#", "\\1/", $abs_path);
        return $abs_path;
    }
}