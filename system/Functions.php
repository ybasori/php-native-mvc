<?php

if (!function_exists('pre')) {
    function pre($value = "")
    {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
}
