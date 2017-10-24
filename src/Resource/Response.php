<?php

namespace Ekimik\ApiDesc\Resource;

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

    public function setAboutInfo(string $about) {
	$this->response['about'] = $about;
    }

    public function addAttr(Param\Response $attr) {
	$this->response['attrs'][] = $attr;
	return $this;
    }

}
