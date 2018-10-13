<?php

namespace Ekimik\ApiDesc\Param;

use \Ekimik\ApiDesc\Transformation\ITransformation;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Request extends Base {

    public function __construct(string $name, string $type, bool $isRequired = true) {
        parent::__construct($name, $type);
        $this->setRequired($isRequired);
        $this->param['transformations'] = [
            ITransformation::TYPE_INPUT => [],
            ITransformation::TYPE_OUTPUT => [],
        ];
    }

    public function isRequired(): bool {
        return $this->param['required'];
    }

    public function setRequired(bool $required) {
        $this->param['required'] = $required;
        return $this;
    }

    public function getTransformations(): array {
        return $this->param['transformations'];
    }

    public function addTransformation(string $type, ITransformation $tranformation) {
        $allowedTypes = [ITransformation::TYPE_INPUT, ITransformation::TYPE_OUTPUT];
        if (!in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException("Unsupported request param transformation type '{$type}', allowed are " . implode('|', $allowedTypes));
        }

        $this->param['transformations'][$type][] = $tranformation;
        return $this;
    }

}
