<?php

namespace Ekimik\ApiDesc\Resource;

use Ekimik\ApiDesc\Param\Request as RequestParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class ActionHelper {

    const OPTION_API_VERSION = 'apiVersion';

    private $options = [];
    /** @var RequestParam[] */
    private $defaultRequestParams = [];

    public function __construct(array $options = [], array $defaultRequestParams = []) {
        $this->defaultRequestParams = $defaultRequestParams;
        $this->setOptions($options);
    }

    public static function createDefaultRequestParam(string $name, string $dataType, $desc = null): RequestParam {
        $p = new RequestParam($name, $dataType);
        if (!empty($desc)) {
            $p->setAdditionalInfoKey('info', $desc);
        }

        return $p;
    }

    public function createHelpAction(string $resource): Action {
        $action = $this->createAction($this->createActionPath($resource, 'help'), IAction::METHOD_GET);
        $response = new Response('array');
        $response->setAboutInfo(sprintf("Description of '%s' resource", $resource));
        $action->setResponse($response);

        return $action;
    }

    public function createAction(string $path, string $method, array $additionalData = []): Action {
        $action = new Action($path, $method);
        foreach ($additionalData as $key => $value) {
            $action->setAdditionalInfoKey($key, $value);
        }

        foreach ($this->defaultRequestParams as $param) {
            $action->addParam($param);
        }

        return $action;
    }

    public function createActionPath(): string {
        $args = func_get_args();
        if ($this->hasOption(self::OPTION_API_VERSION)) {
            array_unshift($args, $this->options[self::OPTION_API_VERSION]);
        }
        $argsCount = count($args);

        $urlParts = array_fill(0, $argsCount, '%s');
        $urlFormat = implode('/', $urlParts);
        return vsprintf($urlFormat, $args);
    }

    protected function setOptions(array $options) {
        $this->options = $options;
        return $this;
    }

    protected function hasOption(string $option): bool {
        return key_exists($option, $this->options);
    }

}