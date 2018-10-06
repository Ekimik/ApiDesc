<?php

namespace Ekimik\ApiDesc\Tests;

use \Ekimik\ApiDesc\Api;
use \Ekimik\ApiDesc\Resource\Description;
use \Ekimik\ApiDesc\Resource\Action;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class ApiTest extends \PHPUnit\Framework\TestCase {

    /**
     * @covers Api::getDescription
     */
    public function testApi() {
	$api = new Api('test', 'v1');
	$api->setAboutInfo('Some description of API');
	$resource = new Description('Foo resource');
	$resource->setAboutInfo('Some description of foo resource');
	$action = new Action('help', 'GET');
	$resource->addAction($action);
	$api->addResourceDescription($resource);

	$desc = $api->getDescription();
	$this->assertEquals('test', $desc['name']);
	$this->assertEquals('v1', $desc['version']);
	$this->assertEquals('Some description of API', $desc['about']);
	$this->assertSame($resource, $desc['resources'][0]);
    }

    /**
     * @covers Api::setRawData
     * @covers Api::getRawData
     */
    public function setGetRawData() {
	$api = new Api('foobar', 'v2');
	$api->setAboutInfo('Some description of API');
	$resource = new Description('Bar resource');
	$resource->setAboutInfo('Some description of bar resource');
	$action = new Action('help', 'GET');
	$resource->addAction($action);
	$api->addResourceDescription($resource);

	$rawData = $api->getRawData();
	$assert = [
	    'name' => 'foobar',
	    'about' => 'Some description of API',
	    'version' => 'v2',
	    'resources' => [
		[
		    'name' => 'Bar resource',
		    'about' => 'Some description of bar resource',
		    'actions' => [
			[
			    'name' => 'help',
			    'about' => NULL,
			    'additionalInfo' => [],
			    'method' => 'GET',
			    'response' => NULL,
			    'params' => [],
			]
		    ]
		]
	    ],
	];
	$this->assertEquals($assert, $rawData);

	$newApi = new Api('', '');
	$newApi->setRawData($rawData);
	$this->assertSame($api, $newApi);
    }

}
