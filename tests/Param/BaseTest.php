<?php

namespace Ekimik\ApiDesc\Tests\Param;

use Ekimik\ApiDesc\Param\Base;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class BaseTest extends \PHPUnit\Framework\TestCase {

    /**
     * @covers Base::setSubParams
     */
    public function testSetSubParams() {
	$params = [];
	$params[] = $this->getParamMock('foo', 'string');
	$params[] = $this->getParamMock('bar', 'array');
	$params[] = $this->getParamMock('baz', 'integer');

	$mainParam = $this->getParamMock('foobar', 'array');
	$mainParam->setSubParams($params);

	$mainParamDesc = $mainParam->getDescription();
	$this->assertEquals('foobar', $mainParamDesc['name']);
	$this->assertEquals('array', $mainParamDesc['dataType']);

	$subParamsAssert = [
	    'foo' => $params[0],
	    'bar' => $params[1],
	    'baz' => $params[2],
	];
	$this->assertSame($subParamsAssert, $mainParamDesc['params']);
    }

    /**
     * @covers Base::getRawData
     */
    public function testGetRawData() {
	$param = $this->getParamMock('foo', 'bool');
	$rawData = [
	    'name' => 'foo',
	    'dataType' => 'bool',
	    'params' => [],
	    'additionalInfo' => [],
	];
	$this->assertEquals($rawData, $param->getRawData());

	$param = $this->getParamMock('foo', 'array');
	$param1 = $this->getParamMock('bar', 'int');
	$param2 = $this->getParamMock('baz', 'string');
	$param->setSubParams([$param1, $param2]);
	$rawData = [
	    'name' => 'foo',
	    'dataType' => 'array',
	    'params' => [
		'bar' => [
		    'name' => 'bar',
		    'dataType' => 'int',
		    'params' => [],
		    'additionalInfo' => [],
		],
		'baz' => [
		    'name' => 'baz',
		    'dataType' => 'string',
		    'params' => [],
		    'additionalInfo' => [],
		],
	    ],
	    'additionalInfo' => [],
	];
	$this->assertEquals($rawData, $param->getRawData());
    }

    /**
     * @covers Base::setRawData
     */
    public function testSetRawData() {
	$rawData = [
	    'name' => 'foo',
	    'dataType' => 'bool',
	    'params' => [],
	    'additionalInfo' => [],
	];
	$param = $this->getParamMock('', '');
	$param->setRawData($rawData);
	$pDesc = $param->getDescription();

	$this->assertEquals('foo', $pDesc['name']);
	$this->assertEquals('bool', $pDesc['dataType']);
	$this->assertEmpty($pDesc['additionalInfo']);
	$this->assertEmpty($pDesc['params']);

	$rawData = [
	    'name' => 'foo',
	    'dataType' => 'array',
	    'params' => [
		'bar' => [
		    'name' => 'bar',
		    'dataType' => 'int',
		    'params' => [],
		    'additionalInfo' => [],
		],
		'baz' => [
		    'name' => 'baz',
		    'dataType' => 'string',
		    'params' => [],
		    'additionalInfo' => [],
		],
	    ],
	    'additionalInfo' => [],
	];
	$param = $this->getParamMock('', '');
	$param->setRawData($rawData);
	$pDesc = $param->getDescription();

	$this->assertEquals('foo', $pDesc['name']);
	$this->assertEquals('array', $pDesc['dataType']);
	$this->assertEmpty($pDesc['additionalInfo']);
	$this->assertCount(2, $pDesc['params']);
	$this->assertInstanceOf(get_class($param), $pDesc['params']['bar']);
	$this->assertEquals($rawData['params']['bar'], $pDesc['params']['bar']->getDescription());
	$this->assertInstanceOf(get_class($param), $pDesc['params']['baz']);
	$this->assertEquals($rawData['params']['baz'], $pDesc['params']['baz']->getDescription());
    }

    private function getParamMock(string $paramName, string $paramType): Base {
	return $this->getMockForAbstractClass(Base::class, [$paramName, $paramType], ucfirst($paramName) . 'Mock');
    }

}
