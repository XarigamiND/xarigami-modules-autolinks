<?php
/**
 * Xarigami Autolinks
 *
 * @package modules
 * @copyright (C) 2006-2011 2skies.com
 * @link http://xarigami.com/project/xarigami_autolinks
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Autolinks Module
 * @author Jason Judge
*
/**
 * Create sample Autolink data (from an array - not quite an import)
 */

function autolinks_util_samples()
{
    // Security Check
    if (!xarSecurityCheck('AdminAutolinks')) {return;}

    // Template data.
    $data = array();
    $linkcount= 0;
    $counter = 0;
    // Create sample data if required.
    if (!xarVarFetch('createsamples', 'str', $createsamples, NULL, XARVAR_NOT_REQUIRED)) {return;}
    if (!empty($createsamples)) {
        if (!xarSecConfirmAuthKey()) {return;}
        $result = xarModAPIfunc('autolinks', 'admin', 'samples', array('action' => 'create'));
        $data['status'] = $result;
    }

    $data['authid'] = xarSecGenAuthKey();

    // Get the samples.
    $atypes = xarModAPIFunc('autolinks','user','getall');
    $sample_data = xarModAPIfunc('autolinks', 'admin', 'samples', array('action' => 'get'));
    $sample_data =isset($sample_data['autolink-types']) ? $sample_data['autolink-types'] : array();

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
    if (!empty($data['status'])) {
       $msg = xarML('Samples successfully created.');
        xarTplSetMessage($msg,'status');
    }
    return $data;
}

?>