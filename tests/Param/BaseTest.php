<?php

namespace Ekimik\ApiDesc\Tests\Param;

use Ekimik\ApiDesc\Param\Base;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class BaseTest extends \PHPUnit_Framework_TestCase {

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

    private function getParamMock(string $paramName, string $paramType): \PHPUnit_Framework_MockObject_MockObject {
	return $this->getMockForAbstractClass(Base::class, [$paramName, $paramType], ucfirst($paramName) . 'Mock');
    }

}
