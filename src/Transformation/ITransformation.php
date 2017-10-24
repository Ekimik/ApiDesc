<?php

namespace Ekimik\ApiDesc\Transformation;

use \Ekimik\ApiDesc\IDescription;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
interface ITransformation extends IDescription {

    const TYPE_INPUT = 'input';
    const TYPE_OUTPUT = 'output';

}
