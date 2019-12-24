<?php

namespace Ekimik\ApiDesc\Resource;

use Ekimik\ApiDesc\Param\Request as RequestParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Action implements IAction {

    protected $action = [
        'name' => null,
        'about' => null, // human friendly description of action
        'additionalInfo' => [],
        'method' => null, // see constants IAction::METHOD_*
        'response' => null,
        'params' => [],
        'authorization' => [],
        'handler' => null,
        'headers' => [],
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
            /** @var RequestParam $param */
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

    public function getMethod(): string {
        return $this->action['method'];
    }

    public function getName(): string {
        return $this->action['name'];
    }

    public function getAdditionalInfo(): array {
        return $this->action['additionalInfo'];
    }

    public function setAdditionalInfo(array $additionalInfo) {
        $this->action['additionalInfo'] = $additionalInfo;
        return $this;
    }

    public function setAdditionalInfoKey(string $key, $value) {
        $this->action['additionalInfo'][$key] = $value;
        return $this;
    }

    public function setAboutInfo(string $about) {
        $this->action['about'] = $about;
    }

    public function getResponse(): IResponse {
        return $this->action['response'];
    }

    public function setResponse(IResponse $response) {
        $this->action['response'] = $response;
        return $this;
    }

    public function isPublic(): bool {
        return empty($this->action['authorization']);
    }

    public function getAuthorization(): array {
        return $this->action['authorization'];
    }

    public function setAuthorization(string $resource, string $privilege) {
        $this->action['authorization'] = ['resource' => $resource, 'privilege' => $privilege];
        return $this;
    }

    /**
     * @return array|null
     * @see Action::setHandler for more informations
     */
    public function getHandler() {
        return $this->action['handler'];
    }

    /**
     * @param array $handler     same structure as definition of callable using array. It's up to user how handler
     * information will be used in app
     */
    public function setHandler(array $handler) {
        $this->action['handler'] = $handler;
        return $this;
    }

    public function getHeaders(): array {
        return $this->action['headers'];
    }

    public function setHeaders(array $headers) {
        $this->action['headers'] = $headers;
        return $this;
    }

    public function addHeader(string $name, $value, bool $required = true) {
        $key = strtolower($name);
        $headers = $this->getHeaders();
        $headers[$key] = ['name' => $name, 'value' => $value, 'required' => $required];
        $this->setHeaders($headers);

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

        $param = null;
        $parent = null;
        foreach ($nameParts as $part) {
            $param = $this->getParamByName($part, $parent);
            if (empty($param)) {
                return null;
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
    private function getParamByName(string $name, RequestParam $parent = null) {
        if ($parent === null) {
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

        return null;
    }

}
