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
	'about' => NULL, // human friendly description of action
	'additionalInfo' => [],
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

	$data['response'] = $data['response']->getRawData();

	return $data;
    }

    public function setRawData(array $rawData) {
	foreach ($rawData['params'] as &$param) {
	    $rp = new RequestParam('', '');
	    $rp->setRawData($param);
	    $param = $rp;
	}

	$response = new Response('');
	$response->setRawData($rawData['response']);
	$rawData['response'] = $response;

	$this->action = $rawData;
    }

    public function setAdditionalInfo(array $aditionalInfo) {
	$this->action['additionalInfo'] = $aditionalInfo;
	return $this;
    }

    public function setAditionalInfoKey(string $key, $value) {
	$this->action['additionalInfo'][$key] = $value;
	return $this;
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

    /**
     * @return RequestParam[]
     */
    public function getParams(): array {
	return $this->action['params'];
    }

    /**
     * @param string $path
     * @return RequestParam|null
     */
    public function getParam(string $path) {
	$nameParts = explode(RequestParam::NAME_PATH_SEPARATOR, $path);

	$param = NULL;
	$parent = NULL;
	foreach ($nameParts as $part) {
	    $param = $this->getParamByName($part, $parent);
	    if (empty($param)) {
		return NULL;
	    }

	    $parent = $param;
	}

	return $param;
    }

    /**
     * @param string $name
     * @param RequestParam $parent
     * @return RequestParam|null
     */
    private function getParamByName(string $name, RequestParam $parent = NULL) {
	if ($parent === NULL) {
	    $params = $this->getParams();
	} else {
	    $params = $parent->getParams();
	}

	foreach ($params as $param) {
	    $pName = $param->getDescription()['name'];
	    if ($pName === $name) {
		return $param;
	    }
	}

	return NULL;
    }

}
