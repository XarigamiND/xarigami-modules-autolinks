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
/**
 * Main menu for utility functions
 */
function autolinks_util_main()
{
    // Security Check
    if(!xarSecurityCheck('AdminAutolinks')) {return;}

    $data = array();
    // Get the samples.
    $atypes = xarModAPIFunc('autolinks','user','getall');
    $sample_data = xarModAPIfunc('autolinks', 'admin', 'samples', array('action' => 'get'));
    $sample_data =isset($sample_data['autolink-types']) ? $sample_data['autolink-types'] : array();
    $counter = 0;
    $linkcount = 0;
    foreach ($sample_data as $sample=>$info)
    {   $links = isset($info['links']) ? count($info['links']):1;
        $linkcount = $linkcount + $links;
        $sample_data[$sample]['exists'] = 0;
        foreach($atypes as $key => $typeinfo)
        {
            if (isset($typeinfo['template_name'])  && ($typeinfo['template_name']== $info['template_name']) ) {
                $counter++;
                $sample_data[$sample]['exists'] = 1;
            } elseif (isset($typeinfo['template_name']) && ($info['template_name'] == 'sample1')){
                //hmm have to hard code this one
                if (!isset($sample_data[$sample]['exists']) || ($sample_data[$sample]['exists'] == 0)) {
                    $counter++;
                }
                $sample_data[$sample]['exists'] = 1;
            }
        }
    }
    $data['sample_data'] = $sample_data;
    $data['allcreated'] =0;
    $data['counter'] = $counter;

    if ($linkcount == $counter) $data['allcreated'] = 1;
    return $data;
}

?>