<?php

namespace Ekimik\ApiDesc\Tests\Resource;

use Ekimik\ApiDesc\Resource\ActionHelper;
use Ekimik\ApiDesc\Resource\IAction;
use PHPUnit\Framework\TestCase;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class ActionHelperTest extends TestCase {

    /**
     * @covers ActionHelper::createAction
     */
    public function testCreateAction() {
        $helper = new ActionHelper();
        $a = $helper->createAction($helper->createActionPath('foo', 'bar'), IAction::METHOD_GET);
        $this->assertEquals(IAction::METHOD_GET, $a->getMethod());
        $this->assertEquals('foo/bar', $a->getName());
        $this->assertEmpty($a->getParams());

        $p1 = ActionHelper::createRequestParam('foo', 'string');
        $p2 = ActionHelper::createRequestParam('bar', 'integer');
        $helper = new ActionHelper([ActionHelper::OPTION_API_VERSION => 'v1'], [$p1, $p2]);
        $a = $helper->createAction('foobar/barbar', IAction::METHOD_POST, [IAction::OPTION_INFO => 'Foobar info']);
        $this->assertEquals(IAction::METHOD_POST, $a->getMethod());
        $this->assertEquals('foobar/barbar', $a->getName());
        $this->assertEquals([IAction::OPTION_INFO => 'Foobar info'], $a->getAdditionalInfo());
        $this->assertCount(2, $a->getParams());
        $this->assertSame($p1, $a->getParams()['foo']);
        $this->assertSame($p2, $a->getParams()['bar']);

        $a = $helper->createAction(
            $helper->createActionPath('foobar', 'barbar'),
            IAction::METHOD_POST,
            [IAction::OPTION_INFO => 'Foobar info']
        );
        $this->assertEquals(IAction::METHOD_POST, $a->getMethod());
        $this->assertEquals('v1/foobar/barbar', $a->getName());
        $this->assertEquals([IAction::OPTION_INFO => 'Foobar info'], $a->getAdditionalInfo());
        $this->assertCount(2, $a->getParams());
        $this->assertSame($p1, $a->getParams()['foo']);
        $this->assertSame($p2, $a->getParams()['bar']);
    }

    /**
     * @covers ActionHelper::createHelpAction
     */
    public function testCreateHelpAction() {
        $helper = new ActionHelper();
        $a = $helper->createHelpAction('foo');
        $this->assertEquals('foo/help', $a->getName());
        $this->assertEquals(IAction::METHOD_GET, $a->getMethod());
        $this->assertEquals('array', $a->getResponse()->getDataType());
        $this->assertEquals("Description of 'foo' resource", $a->getResponse()->getAboutInfo());
        $this->assertEmpty($a->getParams());
    }

    /**
     * @covers ActionHelper::createActionPath
     */
    public function testCreateActionPath() {
        $helper = new ActionHelper();
        $this->assertEquals('foo/bar', $helper->createActionPath('foo', 'bar'));

        $helper = new ActionHelper([ActionHelper::OPTION_API_VERSION => 'v1']);
        $this->assertEquals('v1/foo/bar', $helper->createActionPath('foo', 'bar'));
    }
}
