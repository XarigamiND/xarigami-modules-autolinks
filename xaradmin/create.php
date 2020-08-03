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
 * form supplied by xarModFunc('autolinks','admin','new') to create a new item
 * @param 'keyword' the keyword of the link to be created
 * @param 'title' the title of the link to be created
 * @param 'url' the url of the link to be created
 * @param 'comment' the comment of the link to be created
 */
function autolinks_admin_create()
{
    $errorcount = 0;
    $data = array();

    // Security check
    if(!xarSecurityCheck('AddAutolinks')) {return;}

    // Confirm authorisation code.
    if (!xarSecConfirmAuthKey()) {return;}

    // Get parameters from whatever input we need
    if (!xarVarFetch('tid', 'id', $tid)) {
        $errorcount += 1;
        $data['tid_error'] = xarML('Could not retrieve tid');
    } else {
        // Get the autolink type details.
        $type = xarModAPIfunc('autolinks', 'user', 'gettype', array('tid' => $tid));

        if ($type) {
            // A valid autolink type is selected.
            // Pass the details to the template.
            $data['tid'] = $tid;
            $data['type'] = $type;
        } else {
            $errorcount += 1;
            $data['tid_error'] = xarML('Autolink Type does not exist: #(1)', $tid);
        }
    }

    // TODO: handle these in one go using the new array validation.
    if (!xarVarFetch('keyword', 'str:1', $keyword)) {
        $errorcount += 1;
        $data['keyword_error'] = xarML('Keyword not found');
        if (trim($keyword) == '' ) {
            $keyword = NULL;
        }
    }

    if (!xarVarFetch('title', 'str', $title)) {
        $errorcount += 1;
        $data['title_error'] = xarML('Title not found');
    }

    if (!xarVarFetch('url', 'str:1', $url)) {
        $errorcount += 1;
        $data['url_error'] = xarML('URL not found');
    }

    if (!xarVarFetch('comment', 'isset', $comment, NULL, XARVAR_DONT_SET)) {
        $errorcount += 1;
        $data['comment_error'] = xarML('No Comment found');
    }

    // Default the name to the same as the (modified) keyword.
    $prekeyword = $keyword;
    xarVarValidate('pre:ftoken:lower', $prekeyword, true);
    if (!xarVarFetch('name', 'pre:ftoken:lower:passthru:str:1', $name, $prekeyword)) {
        $errorcount += 1;
        $data['name_error'] = xarML('Name not found');
    }

    if ($errorcount == 0) {
        // The API function is called
        $lid = xarModAPIFunc(
            'autolinks', 'admin', 'create',
            array(
                'keyword' => $keyword,
                'title' => $title,
                'url' => $url,
                'comment' => $comment,
                'name' => $name,
                'enabled' => false,
                'tid' => $tid
            )
        );

        // Fetch it back, to get the item type.
        $link = xarModAPIFunc('autolinks', 'user', 'get', array('lid'=>$lid));
    }

    // Error in creating the item.
        $errorcount += 1;
        $data['global_error'] = xarML('Error creating an autolink.');


    if ($errorcount > 0) {
        $data['tid'] = $tid;
        $data['keyword'] = $keyword;
        $data['name'] = $name;
        $data['title'] = $title;
        $data['url'] = $url;
        $data['comment'] = $comment;
        $msg = xarML('New autolink - #(1) - successfully created',$name);
        xarTplSetMessage( $data['global_error'],'error');
        // Represent the form, with error messages passed in.
        return xarModFunc('autolinks', 'admin', 'new', $data);
    }

    $msg = xarML('New autolink - #(1) - successfully created',$name);
    xarTplSetMessage($msg,'status');

    xarResponseRedirect(xarModURL('autolinks', 'admin', 'modify', array('lid' => $lid)));
    // Return
    return true;
}

?>
