<?php

namespace Ekimik\ApiDesc\Tests;

use Ekimik\ApiDesc\Api;
use Ekimik\ApiDesc\ApiDescriptor;
use Ekimik\ApiDesc\Resource\Action;
use Ekimik\ApiDesc\Resource\Description;
use PHPUnit\Framework\TestCase;

class ApiDescriptorTest extends TestCase {

    /** @var ApiDescriptor */
    protected $object;

    protected function setUp() {
        parent::setUp();
        $this->object = new ApiDesc();
    }

    /**
     * @covers ApiDescriptor::getDescription
     */
    public function testGetDescription() {
        $api = $this->object->getDescription();
        $this->assertEquals('Foo/Bar', $api->getName());
        $this->assertEquals('v1', $api->getVersion());
        $this->assertCount(1, $api->getResources());
        $r = $api->getResources()[0];
        $this->assertEquals('baz', $r->getName());
        $this->assertCount(1, $r->getActions());
        $a = $r->getActions()[0];
        $this->assertEquals('v1/baz/action', $a->getName());
        $this->assertEquals('GET', $a->getMethod());
    }

    /**
     * @covers ApiDescriptor::getHumanDescription
     */
    public function testGetHumanDescription() {
        $description = [
            'name' => 'Foo/Bar',
            'about' => null,
            'version' => 'v1',
            'resources' => [
                [
                    'name' => 'baz',
                    'about' => null,
                    'actions' => [
                        [
                            'name' => 'v1/baz/action',
                            'about' => null,
                            'additionalInfo' => [],
                            'method' => 'GET',
                            'response' => null,
                            'params' => [],
                            'headers' => [],
                        ]
                    ]
                ]
            ],
        ];
        $this->assertEquals($description, $this->object->getHumanDescription());
    }

    /**
     * @covers ApiDescriptor::getResourceDescription
     */
    public function testGetResourceDescription() {
        $r = $this->object->getResourceDescription('Baz');
        $this->assertEquals('baz', $r->getName());
        $this->assertCount(1, $r->getActions());
        $a = $r->getActions()[0];
        $this->assertEquals('v1/baz/action', $a->getName());
        $this->assertEquals('GET', $a->getMethod());

        $r = $this->object->getResourceDescription('Foo');
        $this->assertNull($r);
    }

    /**
     * @covers ApiDescriptor::getAction
     */
    public function testGetAction() {
        $a = $this->object->getAction('GET', 'v1/baz/action');
        $this->assertEquals('v1/baz/action', $a->getName());
        $this->assertEquals('GET', $a->getMethod());

        $this->assertNull($this->object->getAction('POST', 'v1/foo/action'));
    }

}

class ApiDesc extends ApiDescriptor {

    protected $forbiddenActionFields = ['authorization', 'handler'];

    protected function createApi(): Api {
        return new Api('Foo/Bar', 'v1');
    }

    protected function getBazResourceDescription(): Description {
        $r = new Description('baz');
        $action = new Action('v1/baz/action', 'GET');
        $action->setAuthorization('baz', 'read');
        $r->addAction($action);
        return $r;
    }
}
