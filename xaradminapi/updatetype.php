<?php

/**
 * update an autolink type
 * @param $args['tid'] link type ID
 * @param $args['type_name'] the name of the link type (optional)
 * @param $args['template_name'] name of the link type template (optional)
 * @param $args['type_desc'] description of the link type (optional)
 * @param $args['itemtype'] the itemtype used for links of this type (optional)
 */
function autolinks_adminapi_updatetype($args)
{
    // Get arguments from argument array
    extract($args);

    // Array of column set statements.
    $set = array();
    $bind = array();

    // TODO: use xarVarFetch to validate the parameters.

    // String parameters.
    foreach(array('type_name', 'template_name', 'type_desc') as $parameter)
    {
        if (isset($$parameter))
        {
            $set[] = 'xar_' . $parameter . ' = ?';
            $bind[] = $$parameter;
        }
    }

    // Numeric (boolean) parameters.
    foreach(array('dynamic_replace' => 0) as $parameter => $default)
    {
        if (isset($$parameter))
        {
            if ($$parameter == '0' || $$parameter == '1')
            {
                $set[] = 'xar_' . $parameter . ' = ?';
                $bind[] = $$parameter;
            } else {
                $set[] = 'xar_' . $parameter . ' = ?';
                $bind[] = $default;
            }
        }
    }

    if (isset($itemtype) && is_numeric($itemtype)) {
        $set[] = 'xar_link_itemtype = ' . $itemtype;
    }

    // Argument check
    if (empty($tid)) {
        throw new EmptyParameterException('tid');
    }
    if (empty($set)) {
        throw new EmptyParameterException('set');
    }

    // Create the 'set' statement.
    $set = implode(', ', $set);

    // The user API function is called
    $type = xarModAPIFunc(
        'autolinks', 'user', 'gettype',
        array('tid' => $tid)
    );

    if (!$type) {
        $msg = xarML('No such link type present: #(1)', $tid);
        throw new BadParameterExceptio('tid',$msg);
    }

    if (!xarSecurityCheck('EditAutolinks')) {return;}

    // Get datbase setup
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();

    $autolinkstypestable = $xartable['autolinks_types'];

    // Check if that type name exists
    if (isset($type_name)) {
        $query = 'SELECT xar_tid FROM ' . $autolinkstypestable
            . ' WHERE xar_type_name = ?'
            . ' AND xar_tid <> ?';

        $result = $dbconn->Execute($query, array($type_name, $tid));
        if (!$result) {return;}

        if ($result->RecordCount() > 0) {
            $msg = xarML('The autolink type name (#(1)) already exists', $type_name);
            throw new DuplicateException(array('autolink',$type_name));
        }
    }

    // Update the link
    $query = 'UPDATE ' . $autolinkstypestable
        . ' SET ' . $set
        . ' WHERE xar_tid = ?';

    $bind[] = $tid;
    $result = $dbconn->Execute($query, $bind);
    if (!$result) {return;}

    // Now recompile the cache for autolinks of this type.
    // Only do this if the template name has changed or
    // dynamic_replace has been switched.
    if (
        isset($template_name) && $template_name !== $type['template_name']
        || isset($dynamic_replace) && $dynamic_replace !== $type['dynamic_replace']
    ) {
        $result = xarModAPIfunc('autolinks', 'admin', 'updatecache', array('tid' => $tid));
        if (!$result) {return;}
    }

    // Update/config hooks - set DD property values etc.

    // Config hooks for the type as an itemtype.
    xarModCallHooks(
        'module', 'updateconfig', 'autolinks',
        array('module' => 'autolinks', 'itemtype' => $type['itemtype'])
    );

    // Hooks for the autolink type as an item.
    xarModCallHooks(
        'item', 'update', $tid,
        array(
            'module' => 'autolinks',
            'itemtype' => xarModGetVar('autolinks', 'typeitemtype')
        )
    );

    // Let the calling process know that we have finished successfully
    return true;
}

?>