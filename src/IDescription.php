<?php

namespace Ekimik\ApiDesc;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
interface IDescription {

    public function getDescription(): array;
    public function getRawData(): array;
    public function setRawData(array $rawData);

}
