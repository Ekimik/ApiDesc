<?php

namespace Ekimik\ApiDesc\Tests\Resource;

use Ekimik\ApiDesc\Resource\ActionHelper;
use Ekimik\ApiDesc\Resource\Description;
use Ekimik\ApiDesc\Resource\DescriptionBuilder;
use Ekimik\ApiDesc\Resource\IAction;
use PHPUnit\Framework\TestCase;

class DescriptionBuilderTest extends TestCase {

	/**
	 * @covers DescriptionBuilder::
	 */
	public function testDummyResource() {
		$resource = new DummyMockResource(new ActionHelper());

		$description = $resource->get();
		$this->assertInstanceOf(Description::class, $description);
		$this->assertEquals('Foo', $description->getName());
		$this->assertEmpty($description->getActions());
	}

	/**
	 * @covers DescriptionBuilder::
	 */
	public function testResource() {
		$resource = new MockResource(new ActionHelper());

		$description = $resource->get();
		$this->assertInstanceOf(Description::class, $description);
		$this->assertEquals('Foo', $description->getName());
		$this->assertEquals('Foobar', $description->getAboutInfo());
		$this->assertCount(1, $description->getActions());
	}

}

class DummyMockResource extends DescriptionBuilder {

	protected $name = 'Foo';

	protected function configure(Description $desc) {

	}

}

class MockResource extends DescriptionBuilder {

	protected $name = 'Foo';
	protected $about = 'Foobar';

	protected function configure(Description $desc) {
		$desc->addAction($this->ah->createAction('foo', IAction::METHOD_GET));
	}

}