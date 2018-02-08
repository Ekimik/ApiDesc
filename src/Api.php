<?php

namespace Ekimik\ApiDesc;

use \Ekimik\ApiDesc\Resource\Description as ResourceDescription;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Api implements IDescription {

    protected $api = [
	'name' => NULL,
	'about' => NULL, // human friendly description
	'version' => NULL,
	'resources' => [],
    ];

    public function __construct(string $name, string $version) {
	$this->api['name'] = $name;
	$this->api['version'] = $version;
    }

    public function getDescription(): array {
	return $this->api;
    }

    public function getRawData(): array {
	$data = $this->getDescription();
	foreach ($data['resources'] as &$resource) {
	    $resource = $resource->getRawData();
	}

	return $data;
    }

    public function setRawData(array $rawData) {
	foreach ($rawData['resources'] as &$resource) {
	    $r = new ResourceDescription('');
	    $r->setRawData($resource);
	    $resource = $r;
	}

	$this->api = $rawData;
    }

    public function setAboutInfo(string $about) {
	$this->api['about'] = $about;
    }

    public function addResourceDescription(ResourceDescription $resource) {
	$this->api['resources'][] = $resource;
	return $this;
    }

}
