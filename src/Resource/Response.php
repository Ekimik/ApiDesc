<?php

namespace Ekimik\ApiDesc\Resource;

use \Ekimik\ApiDesc\Param\Response as ResponseAttr;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Response implements IResponse {

    protected $response = [
	'dataType' => NULL,
	'about' => NULL,
	'attrs' => [],
    ];

    public function __construct(string $dataType) {
	$this->response['dataType'] = $dataType;
    }

    public function getDescription(): array {
	return $this->response;
    }

    public function getRawData(): array {
	$data = $this->getDescription();
	foreach ($data['attrs'] as &$attr) {
	    $attr = $attr->getRawData();
	}

	return $data;
    }

    public function setRawData(array $rawData) {
	foreach ($rawData['attrs'] as &$attr) {
	    $ra = new ResponseAttr('', '');
	    $ra->setRawData($attr);
	    $attr = $ra;
	}

	$this->response = $rawData;
    }

    public function setAboutInfo(string $about) {
	$this->response['about'] = $about;
    }

    public function addAttr(ResponseAttr $attr) {
	$this->response['attrs'][] = $attr;
	return $this;
    }

    /**
     * @return ResponseAttr[]
     */
    public function getAttrs(): array {
	return $this->response['attrs'];
    }

}
