<?php

namespace Ekimik\ApiDesc;

use Ekimik\ApiDesc\Resource\Action;
use Ekimik\ApiDesc\Resource\Description;
use Nette\Utils\Strings;

abstract class ApiDescriptor {

    protected $methodsPattern = 'get%sResourceDescription';
    protected $forbiddenActionFields = [];

    public function getDescription(): Api {
        $reflection = new \ReflectionClass($this);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PROTECTED);
        $resources = [];

        foreach ($methods as $method) {
            $match = Strings::match($method, '#' . sprintf($this->methodsPattern, '([a-z]{1,})') . '#ui');
            if ($match) {
                $resources[] = Strings::lower($match[1]);
            }
        }

        $api = $this->createApi();
        foreach ($resources as $resource) {
            $r = $this->getResourceDescription($resource);
            if (empty($r)) {
                continue;
            }

            $api->addResourceDescription($r);
        }

        return $api;
    }

    public function getHumanDescription(): array {
        $description = $this->getDescription()->getRawData();

        if (!empty($this->forbiddenActionFields)) {
            foreach ($description['resources'] as &$resource) {
                foreach ($resource['actions'] as &$action) {
                    foreach ($this->forbiddenActionFields as $field) {
                        unset($action[$field]);
                    }
                }
            }
        }

        return $description;
    }

    /**
     * @param string $resource
     * @return Description|null
     */
    public function getResourceDescription(string $resource) {
        $method = sprintf($this->methodsPattern, $resource);
        if (!method_exists($this, $method)) {
            return null;
        }

        $resourceDesc = call_user_func([$this, $method]);
        return $resourceDesc;
    }

    /**
     * @param string $method
     * @param string $path
     * @return Action|null
     */
    public function getAction(string $method, string $path) {
        foreach ($this->getDescription()->getResources() as $resource) {
            foreach ($resource->getActions() as $action) {
                if ($action->getName() === $path && $action->getMethod() === $method) {
                    return $action;
                }
            }
        }

        return null;
    }

    protected abstract function createApi(): Api;

}