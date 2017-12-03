<?php

namespace Ekimik\ApiDesc\Transformation;

use Ekimik\ApiDesc\Param\Transformation as TransformationParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Transformation implements ITransformation {

    protected $transformation = [
	'name' => NULL,
	'params' => [],
    ];

    public function __construct(string $name) {
	$this->transformation['name'] = $name;
    }

    public function getDescription(): array {
	return $this->transformation;
    }

    public function getRawData(): array {
	$data = $this->getDescription();
	foreach ($data['params'] as &$param) {
	    $param = $param->getRawData();
	}

	return $data;
    }

    public function setRawData(array $rawData) {
	foreach ($rawData['params'] as &$param) {
	    $tp = new TransformationParam('', '');
	    $tp->setRawData($param);
	    $param = $tp;
	}

	$this->transformation = $rawData;
    }

    public function addParam(TransformationParam $param) {
	$this->transformation['params'][] = $param;
	return $this;
    }

    /**
     * @return TransformationParam[]
     */
    public function getParams(): array {
	return $this->transformation['params'];
    }

}
