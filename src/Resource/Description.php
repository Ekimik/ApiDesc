<?php

namespace Ekimik\ApiDesc\Resource;

use \Ekimik\ApiDesc\IDescription;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Description implements IDescription {

    protected $resource = [
        'name' => null,
        'about' => null,
        'actions' => []
    ];

    public function __construct(string $resourceName) {
        $this->resource['name'] = $resourceName;
    }

    public function getDescription(): array {
        return $this->resource;
    }

    public function getRawData(): array {
        $data = $this->getDescription();
        foreach ($data['actions'] as &$action) {
            $action = $action->getRawData();
        }

        return $data;
    }

    public function setRawData(array $rawData) {
        foreach ($rawData['actions'] as &$action) {
            $a = new Action('', '');
            $a->setRawData($action);
            $action = $a;
        }

        $this->resource = $rawData;
    }

    public function getName(): string {
        return $this->resource['name'];
    }

    /**
     * @return Action[]
     */
    public function getActions(): array {
        return $this->resource['actions'];
    }

    public function addAction(IAction $action) {
        $this->resource['actions'][] = $action;
    }

    public function getAboutInfo(): string {
        return $this->resource['about'];
    }

    public function setAboutInfo(string $about) {
        $this->resource['about'] = $about;
    }

}
