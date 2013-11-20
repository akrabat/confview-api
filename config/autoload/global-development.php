<?php

// make life easier
ini_set('html_errors', 0);

function LDBG($var, $title = '')
{
    return \Zend\Debug\Debug::dump($var, $title);
}


return array(
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
    )
);
