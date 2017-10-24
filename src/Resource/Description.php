<?php

namespace Ekimik\ApiDesc\Resource;

use \Ekimik\ApiDesc\IDescription;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Description implements IDescription {

    protected $resource = [
	'name' => NULL,
	'about' => NULL,
	'actions' => []
    ];

    public function __construct(string $resourceName) {
	$this->resource['name'] = $resourceName;
    }

    public function getDescription(): array {
	return $this->resource;
    }

    public function addAction(IAction $action) {
	$this->resource['actions'][] = $action;
    }

    public function setAboutInfo(string $about) {
	$this->resource['about'] = $about;
    }

}
