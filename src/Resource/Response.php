<?php

namespace Ekimik\ApiDesc\Resource;

use \Ekimik\ApiDesc\Param\Response as ResponseAttr;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class Response implements IResponse {

    protected $response = [
        'dataType' => null,
        'about' => null,
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

    public function getDataType(): string {
        return $this->response['dataType'];
    }

    public function getAboutInfo(): string {
        return $this->response['about'];
    }

    public function setAboutInfo(string $about) {
        $this->response['about'] = $about;
    }

    /**
     * @return ResponseAttr[]
     */
    public function getAttrs(): array {
        return $this->response['attrs'];
    }

    public function addAttr(ResponseAttr $attr) {
        $this->response['attrs'][] = $attr;
        return $this;
    }

}
