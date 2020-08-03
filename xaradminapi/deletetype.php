<?php

/**
 * delete an autolink
 * @param $args['tid'] ID of the link type
 * @param $args['cascade'] cascades delete to child links if set
 * @returns bool
 * @return true on success, false on failure
 */
function autolinks_adminapi_deletetype($args)
{
    // Get arguments from argument array
    extract($args);

    // Security check
    if(!xarSecurityCheck('DeleteAutolinks')) {return;}

    // Argument check
    if (!isset($tid) || !is_numeric($tid)) {
        $msg = xarML('Invalid parameter count');
        throw new BadParameterException(null,$msg);
    }

    // The user API function is called
    $linktype = xarModAPIFunc(
        'autolinks', 'user', 'gettype',
        array('tid' => $tid)
    );

    if ($linktype == false) {
        $msg = xarML('No such link type present: #(1)', $tid);
        throw new BadParameterException(null,$msg);
    }

    // Now check if there are any links for this link type
    $linkcount = xarModAPIFunc(
        'autolinks', 'user', 'countitems',
        array('tid' => $tid)
    );

    if ($linkcount > 0) {
        // There are links - raise an error.

        if (!empty($cascade)) {
            // Delete each link first.
            $links = xarModAPIFunc(
                'autolinks', 'user', 'getall',
                array('tid' => $tid)
            );
            foreach($links as $lid => $link) {
                $result = xarModAPIFunc(
                    'autolinks', 'admin', 'delete',
                    array('lid' => $lid)
                );
                if (!$result) {return;}
            }
        } else {
            $msg = xarML('There are links present for this link type', 'autolinks');
            throw new BadParameterException(null,$msg);
        }
    }

    // Get datbase setup
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();

    $autolinkstypestable = $xartable['autolinks_types'];

    // Delete the item
    $query = 'DELETE FROM ' . $autolinkstypestable
          . ' WHERE xar_tid = ?';
    $result = $dbconn->Execute($query, array($tid));
    if (!$result) return;

    // Let any hooks know that we have deleted a link type (as an item)
    xarModCallHooks(
        'item', 'delete', $tid,
        array(
            'itemtype' => xarModGetVar('autolinks', 'typeitemtype'),
            'module' => 'autolinks'
        )
    );

    // Let any hooks know that we have deleted a link type (as an item type)
    // TODO: we don't have that kind of hook yet.
    //xarModCallHooks(
    //    'item', 'delete', $linktype['itemtype'],
    //    array('itemtype' => , 'module' => 'autolinks')
    //);

    // Let the calling process know that we have finished successfully
    return true;
}

?>