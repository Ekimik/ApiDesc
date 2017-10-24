<?php

namespace Ekimik\ApiDesc\Tests\Resource;

use Ekimik\ApiDesc\Resource\Action;
use \Ekimik\ApiDesc\Param\Request as RequestParam;
use \Ekimik\ApiDesc\Resource\Response;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class ActionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Action::getDescription
     * @covers Action::setResponse
     */
    public function testAction() {
	$action = new Action('Foo', 'GET');

	$actionDef = [
	    'name' => 'Foo',
	    'method' => 'GET',
	    'about' => NULL,
	    'params' => [],
	    'response' => NULL
	];
	$this->assertEquals($actionDef, $action->getDescription());

	$response = new Response('integer');
	$response->setAboutInfo('number of something');
	$action->setResponse($response);
	$actionDef = [
	    'name' => 'Foo',
	    'method' => 'GET',
	    'about' => NULL,
	    'params' => [],
	    'response' => $response
	];
	$this->assertEquals($actionDef, $action->getDescription());
    }

    /**
     * @covers Action::addParam
     */
    public function testAddParam() {
	$action = new Action('Foobar', 'GET');
	$action->setAboutInfo('Foobar desc');

	$param1 = new RequestParam('param_1', 'string');
	$action->addParam($param1);
	$param2 = new RequestParam('param_2', 'string', FALSE);
	$action->addParam($param2);

	$actionDef = [
	    'name' => 'Foobar',
	    'method' => 'GET',
	    'about' => 'Foobar desc',
	    'params' => [
		'param_1' => $param1,
		'param_2' => $param2,
	    ],
	    'response' => NULL
	];

	$this->assertEquals($actionDef, $action->getDescription());
    }

}
