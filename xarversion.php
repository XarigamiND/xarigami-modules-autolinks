<?php
/**
 * Xarigami Autolinks
 *
 * @package modules
 * @copyright (C) 2002-2008 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Autolinks Module
 * @copyright (C) 2006-2011 2skies.com
 * @link http://xarigami.com/project/xarigami_autolinks
 * @author Jason Judge
*/

$modversion['name']         = 'Autolinks';
$modversion['id']           = '11';
$modversion['version']      = '1.7.1';
$modversion['displayname']  = 'Autolinks';
$modversion['description']  = 'Automatically link key words';
$modversion['credits']      = 'xardocs/credits.txt';
$modversion['help']         = 'xardocs/help.txt';
$modversion['changelog']    = 'xardocs/changelog.txt';
$modversion['license']      = 'xardocs/license.txt';
$modversion['coding']       = 'xardocs/coding.txt';
$modversion['official']     = 1;
$modversion['author']       = 'Jason Judge';
$modversion['homepage']      = 'http://xarigami.com';
$modversion['admin']        = 1;
$modversion['user']         = 0;
$modversion['class']        = 'Utility';
$modversion['category']     = 'Utilities';
$modversion['dependencyinfo']   = array(
                                    0 => array(
                                            'name' => 'core',
                                            'version_ge' => '1.4.0'
                                         )
                                );
if (false) { //Load and translate once
    xarML('Autolinks');
    xarML('Automatically link key words');
}
?>