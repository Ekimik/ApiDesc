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



//    public function addParam(string $name, string $type, bool $isRequired, array $subParams = []) {
//	$param = new Param($name, $type, $isRequired);
//
//	if (!empty($subParams)) {
//	    $param->setSubParams($subParams);
//	}
//
//	if (!isset($this->action['params'][$name])) {
//	    $this->action['params'][$name] = $param;
//	    return $param;
//	}
//
//	return NULL;
//    }

//    /**
//     * @return Param[]
//     */
//    public function getParams(): array {
//	return $this->actionInfo['params'];
//    }
//
//    public function setResponse(string $type, string $desc) {
//	$this->actionInfo['response'] = ['type' => $type, 'description' => $desc];
//    }
//
//    public function getParam(string $paramPath) {
//	$paramPathParts = explode(Param::PATH_SEPARATOR, $paramPath);
//	$paramPathPartsClone = $paramPathParts;
//	$paramPathPartsCount = count($paramPathParts);
//	$params = $this->getParams();
//	$lastFoundParamInPath = NULL;
//	$iterableParams = $params;
//
//	while ($pathPart = array_shift($paramPathPartsClone)) {
//	    if (empty($iterableParams[$pathPart])) {
//		break;
//	    } else if ($paramPathPartsCount > 1 && !$iterableParams[$pathPart]->hasSubParams() && $iterableParams[$pathPart]->getName() !== $pathPart) {
//		break;
//	    }
//
//	    $lastFoundParamInPath = $iterableParams[$pathPart];
//	    $iterableParams = $iterableParams[$pathPart]->getSubParams();
//	}
//
//	if (!empty($lastFoundParamInPath && $lastFoundParamInPath->getName() === $paramPathParts[$paramPathPartsCount - 1])) {
//	    return $lastFoundParamInPath;
//	}
//
//	return NULL;
//    }

}
