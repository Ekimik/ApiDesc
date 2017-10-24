<?php

namespace Ekimik\ApiDesc\Resource;

use Ekimik\ApiDesc\Param\Request as RequestParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Action implements IAction {

    protected $action = [
	'name' => NULL,
	'about' => NULL,
	'method' => NULL,
	'response' => NULL,
	'params' => [],
    ];

    public function __construct(string $actionName, string $method) {
	$this->action['name'] = $actionName;
	$this->action['method'] = $method;
    }

    public function getDescription(): array {
	return $this->action;
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
	    $rp = new RequestParam('', '');
	    $rp->setRawData($param);
	    $param = $rp;
	}

	$this->action = $rawData;
    }

    public function setAboutInfo(string $about) {
	$this->action['about'] = $about;
    }

    public function setResponse(IResponse $response) {
	$this->action['response'] = $response;
	return $this;
    }

    public function addParam(RequestParam $param) {
	$paramDef = $param->getDescription();
	$this->action['params'][$paramDef['name']] = $param;
	return $this;
    }

}
