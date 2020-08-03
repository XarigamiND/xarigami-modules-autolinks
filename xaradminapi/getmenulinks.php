<?php

/**
 * utility function pass individual menu items to the main menu
 *
 * @subpackage Xarigami Autolinks module
 * @copyright (C) 2007,2008,2009,2010 2skies.com
 * @link http://xarigami.com/projects/xarigami_core
 * @author Xarigami Team 
 */
function autolinks_adminapi_getmenulinks()
{
    // Security Check
    if (xarSecurityCheck('EditAutolinks', 0)) {
        $menulinks[] = array(
            'url' => xarModURL('autolinks', 'admin', 'view'),
            'title' => xarML('View and Edit Autolinks'),
            'label' => xarML('View Links'),
            'active' => array('view','modify','move','delete'),
            'activelabels' => array('',xarML('Modify'),xarML('Change Type'),xarML('Delete'))
        );
    }

    // Security Check
    if (xarSecurityCheck('AddAutolinks', 0)) {
        $menulinks[] = array(
            'url' => xarModURL('autolinks', 'admin', 'new'),
            'title' => xarML('Add a new Autolink into the system'),
            'label' => xarML('Add Link'),
            'active' => array('new')
        );
    }

    // Security Check
    if (xarSecurityCheck('EditAutolinks', 0)) {
        $menulinks[] = array(
            'url' => xarModURL('autolinks', 'admin', 'viewtype'),
            'title' => xarML('View and Edit Autolink Types'),
            'label' => xarML('View Types'),
            'active' => array('viewtype','modifytype','deletetype'),
            'activelabels' => array('',xarML('Modify Type'),xarML('Delete Type'))
        );
    }

    // TODO: AddAutolinksTypes ?
    // Security Check
    if (xarSecurityCheck('AddAutolinks', 0)) {
        $menulinks[] = array('url' => xarModURL('autolinks', 'admin', 'newtype'),
            'title' => xarML('Add a new Autolink Type into the system'),
            'label' => xarML('Add Type'),
            'active' => array('newtype')
        );
    }
 // Security Check
    if (xarSecurityCheck('AdminAutolinks', 0)) {
        $menulinks[] = array(
            'url'   => xarModURL('autolinks', 'util', 'main'),
            'title' => xarML('Autolink utilities'),
            'label' => xarML('Utilities'),
            'active' => array('main','export','samples'),
            'activelabels' => array('',xarML('Export autolinks'),xarML('Create samples'))
        );
    }
    // Security Check
    if (xarSecurityCheck('AdminAutolinks', 0)) {
        $menulinks[] = array('url' => xarModURL('autolinks', 'admin', 'modifyconfig'),
            'title' => xarML('Modify the configuration for the Autolinks'),
            'label' => xarML('Modify Config'),
            'active' => array('modifyconfig')
        );
    }

   

    if (empty($menulinks)){
        $menulinks = '';
    }

    return $menulinks;
}
?>