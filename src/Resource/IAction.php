<?php

namespace Ekimik\ApiDesc\Resource;

use \Ekimik\ApiDesc\IDescription;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
interface IAction extends IDescription {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const OPTION_PUBLIC = 'isActionPublic';
    const OPTION_HANDLER_IDENT = 'handlerIdent';
    const OPTION_INFO = 'info';

}
