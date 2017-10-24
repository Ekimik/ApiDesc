<?php

namespace Ekimik\ApiDesc\Tests\Param;

use \Ekimik\ApiDesc\Param\Request;
use \Ekimik\ApiDesc\Transformation\ITransformation;
use \Ekimik\ApiDesc\Transformation\Transformation;
use \Ekimik\ApiDesc\Param\Transformation as TransformationParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class RequestTest extends \PHPUnit_Framework_TestCase {

    /** @var Request */
    protected $object;

    protected function setUp() {
	parent::setUp();

	$this->object = new Request('foo', 'string');
    }

    /**
     * @cover Request::addTransformation
     */
    public function testAddTransformation() {
	$t1 = new Transformation('bar');
	$t1->addParam(new TransformationParam('param1', 'string'));
	$t1->addParam(new TransformationParam('param2', 'int'));
	$this->object->addTransformation(ITransformation::TYPE_INPUT, $t1);

	$t2 = new Transformation('baz');
	$t2->addParam(new TransformationParam('param1', 'bool'));
	$this->object->addTransformation(ITransformation::TYPE_INPUT, $t2);

	$t3 = new Transformation('barbar');
	$t3->addParam(new TransformationParam('param1', 'array'));
	$this->object->addTransformation(ITransformation::TYPE_OUTPUT, $t3);

	$assert = [
	    'name' => 'foo',
	    'dataType' => 'string',
	    'additionalInfo' => [],
	    'params' => [],
	    'required' => TRUE,
	    'transformations' => [
		ITransformation::TYPE_INPUT => [$t1, $t2],
		ITransformation::TYPE_OUTPUT => [$t3],
	    ]
	];
	$this->assertEquals($assert, $this->object->getDescription());
    }

}
