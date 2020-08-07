<?php

/**
 * $Id$
 * Expand a variable into executable declaritive PHP code with var_export
 * @param $var value or array of any type or types
 * @returns string
 * @return string of $var expanded into a PHP definition
 */
function autolinks_userapi_varexport($var)
{
    return var_export($var, true);
}

?>
