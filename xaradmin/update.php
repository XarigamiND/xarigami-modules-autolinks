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
*/
/**
 * This is a standard function that is called with the results of the
 * form supplied by xarModFunc('autolinks','admin','modify') to update a current item
 * @param 'lid' the id of the link to be updated
 * @param 'keyword' the keyword of the link to be updated
 * @param 'title' the title of the link to be updated
 * @param 'url' the url of the link to be updated
 * @param 'comment' the comment of the link to be updated
 */
function autolinks_admin_update($args)
{
    extract($args);

    // Get parameters from whatever input we need
    // TODO: catch errors and represent them to the user (same as create).
    if (!xarVarFetch('lid',     'isset', $lid,      NULL, XARVAR_DONT_SET)) {return;}
    if (!xarVarFetch('obid',    'isset', $obid,     NULL, XARVAR_DONT_SET)) {return;}

    if (!xarVarFetch('name', 'pre:ftoken:lower:passthru:str:1', $name)) {return;}
    if (!xarVarFetch('keyword', 'str:1', $keyword)) {return;}
    if (!xarVarFetch('title',   'str', $title,    NULL, XARVAR_DONT_SET)) {return;}
    if (!xarVarFetch('url',     'str:1', $url)) {return;}
    if (!xarVarFetch('enabled', 'int:0:1', $enabled, 0, XARVAR_NOT_REQUIRED)) {return;}
    if (!xarVarFetch('match_re', 'int:0:1', $match_re, 0, XARVAR_NOT_REQUIRED)) {return;}
    if (!xarVarFetch('comment', 'str', $comment,  '',   XARVAR_NOT_REQUIRED)) {return;}
    if (!xarVarFetch('sample', 'str', $sample,  '',   XARVAR_NOT_REQUIRED)) {return;}

    if (!xarVarFetch('startnumitem', 'id', $startnumitem, NULL, XARVAR_DONT_SET)) {return;}

    if (!empty($obid)) {
        $lid = $obid;
    }

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) {return;}

    $result = xarModAPIFunc(
        'autolinks', 'admin', 'update',
        array(
            'lid' => $lid,
            'keyword' => $keyword,
            'title' => $title,
            'url' => $url,
            'comment' => $comment,
            'enabled' => $enabled,
            'match_re' => $match_re,
            'sample' => $sample,
            'name' => $name
        )
    );

    if (!$result) {return;}
    $msg = xarML('Autolink - #(1) - successfully updated.',$name);
    xarTplSetMessage($msg,'status');
    if (!empty($startnumitem)) {
        xarResponseRedirect(xarModURL('autolinks', 'admin', 'view', array('startnumitem' => $startnumitem)));
    } else {
        xarResponseRedirect(xarModURL('autolinks', 'admin', 'view'));
    }

    // Return
    return true;
}

?>