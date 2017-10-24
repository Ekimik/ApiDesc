<?php

namespace Ekimik\ApiDesc\Param;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
abstract class Base implements IParam {

    const PATH_SEPARATOR = '.';

    protected $param = [
	'name' => NULL,
	'dataType' => NULL,
	'params' => [],
	'additionalInfo' => [],
    ];

    /** @var Base */
    private $parent = NULL;

    public function __construct(string $name, string $type) {
	$this->param['name'] = $name;
	$this->param['dataType'] = $type;
    }

    public function getDescription(): array {
	return $this->param;
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
	    $paramClass = static::class;
	    $p = new $paramClass('', '');
	    $p->setRawData($param);
	    $param = $p;
	}

	$this->param = $rawData;
    }

    /**
     * @param Base[] $params
     */
    public function setSubParams(array $params) {
	foreach ($params as $param) {
	    if (!$param instanceof Base) {
		throw new \InvalidArgumentException('Every item have to be instance of ' . self::class);
	    }

	    $param->setParent($this);
	    $paramName = $param->getDescription()['name'];
	    $this->param['params'][$paramName] = $param;
	}
    }

    public function hasSubParams(): bool {
	return !empty($this->param['params']);
    }

    public function setAdditionalInfo(array $aditionalInfo) {
	$this->param['additionalInfo'] = $aditionalInfo;
	return $this;
    }

    public function setAditionalInfoKey(string $key, $value) {
	$this->param['additionalInfo'][$key] = $value;
	return $this;
    }

    public function getParent(): Base {
	return $this->parent;
    }

    public function setParent(Base $parent) {
	$this->parent = $parent;
	return $this;
    }

}
