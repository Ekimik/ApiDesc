<?php

namespace Ekimik\ApiDesc\Tests\Resource;

use \Ekimik\ApiDesc\Resource\Action;
use \Ekimik\ApiDesc\Param\Request as RequestParam;
use \Ekimik\ApiDesc\Resource\IAction;
use \Ekimik\ApiDesc\Resource\Response;
use \Ekimik\ApiDesc\Transformation\ITransformation;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class ActionTest extends \PHPUnit\Framework\TestCase {

    /**
     * @covers Action::getDescription
     * @covers Action::setResponse
     */
    public function testAction() {
        $action = new Action('Foo', IAction::METHOD_GET);

        $actionDef = [
            'name' => 'Foo',
            'method' => IAction::METHOD_GET,
            'about' => null,
            'params' => [],
            'response' => null,
            'additionalInfo' => [],
            'authorization' => [],
            'handler' => null,
            'headers' => [],
        ];
        $this->assertEquals($actionDef, $action->getDescription());
        $this->assertTrue($action->isPublic());

        $action->setAuthorization('foo resource', 'read');
        $actionDef = [
            'name' => 'Foo',
            'method' => IAction::METHOD_GET,
            'about' => null,
            'params' => [],
            'response' => null,
            'additionalInfo' => [],
            'authorization' => ['resource' => 'foo resource', 'privilege' => 'read'],
            'handler' => null,
            'headers' => [],
        ];
        $this->assertEquals($actionDef, $action->getDescription());
        $this->assertFalse($action->isPublic());

        $response = new Response('integer');
        $response->setAboutInfo('number of something');
        $action->setResponse($response);
        $actionDef = [
            'name' => 'Foo',
            'method' => IAction::METHOD_GET,
            'about' => null,
            'params' => [],
            'response' => $response,
            'additionalInfo' => [],
            'authorization' => ['resource' => 'foo resource', 'privilege' => 'read'],
            'handler' => null,
            'headers' => [],
        ];
        $this->assertEquals($actionDef, $action->getDescription());

        $action->addHeader('Foo', 'Bar');
        $actionDef = [
            'name' => 'Foo',
            'method' => IAction::METHOD_GET,
            'about' => null,
            'params' => [],
            'response' => $response,
            'additionalInfo' => [],
            'authorization' => ['resource' => 'foo resource', 'privilege' => 'read'],
            'handler' => null,
            'headers' => [
                'foo' => ['name' => 'Foo', 'value' => 'Bar', 'required' => true],
            ],
        ];
        $this->assertEquals($actionDef, $action->getDescription());
    }

    /**
     * @covers Action::addParam
     */
    public function testAddParam() {
        $action = new Action('Foobar', IAction::METHOD_GET);
        $action->setAboutInfo('Foobar desc');

        $param1 = new RequestParam('param_1', 'string');
        $action->addParam($param1);
        $param2 = new RequestParam('param_2', 'string', false);
        $action->addParam($param2);

        $actionDef = [
            'name' => 'Foobar',
            'method' => IAction::METHOD_GET,
            'about' => 'Foobar desc',
            'params' => [
                'param_1' => $param1,
                'param_2' => $param2,
            ],
            'response' => null,
            'additionalInfo' => [],
            'authorization' => [],
            'handler' => null,
            'headers' => [],
        ];

        $this->assertEquals($actionDef, $action->getDescription());
    }

    /**
     * @covers Action::getParam
     */
    public function testGetParam() {
        $action = new Action('Foobar', IAction::METHOD_GET);
        $action->setAboutInfo('Foobar desc');

        $param1 = new RequestParam('param_1', 'string');
        $action->addParam($param1);
        $param2 = new RequestParam('param_2', 'array');
        $param3 = new RequestParam('param_3', 'string');
        $param2->addParam($param3);
        $action->addParam($param2);

        $this->assertNull($action->getParam('foo'));
        $this->assertNull($action->getParam('foo.bar'));
        $this->assertNull($action->getParam('param_2.foobar'));
        $this->assertSame($param1, $action->getParam('param_1'));
        $this->assertSame($param2, $action->getParam('param_2'));
        $this->assertSame($param3, $action->getParam('param_2.param_3'));
    }

    /**
     * @covers Action::setRawData
     * @covers Action::getRawData
     */
    public function testGetSetRawData() {
        $action = new Action('Foobar', IAction::METHOD_POST);
        $action->setAboutInfo('Foobar desc');
        $action->setResponse((new Response('string')));

        $param1 = new RequestParam('param_1', 'integer');
        $action->addParam($param1);

        $rawData = [
            'name' => 'Foobar',
            'about' => 'Foobar desc',
            'method' => IAction::METHOD_POST,
            'response' => [
                'dataType' => 'string',
                'about' => null,
                'attrs' => [],
            ],
            'params' => [
                'param_1' => [
                    'name' => 'param_1',
                    'dataType' => 'integer',
                    'params' => [],
                    'additionalInfo' => [],
                    'required' => true,
                    'transformations' => [
                        ITransformation::TYPE_INPUT => [],
                        ITransformation::TYPE_OUTPUT => [],
                    ]
                ]
            ],
            'additionalInfo' => [],
            'authorization' => [],
            'handler' => null,
            'headers' => [],
        ];
        $this->assertEquals($rawData, $action->getRawData());

        $actionNew = new Action('', '');
        $actionNew->setRawData($rawData);
        $this->assertEquals($action, $actionNew);
    }

}
