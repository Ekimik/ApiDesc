<?php

namespace Ekimik\ApiDesc\Tests\Resource;

use \Ekimik\ApiDesc\Resource\Description,
    \Ekimik\ApiDesc\Resource\Action;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
class DescriptionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Description::setAboutInfo
     * @covers Description::addAction
     * @covers Description::getDescription
     */
    public function testDescription() {
        $desc = new Description('fooResource');

        $descDef = [
            'name' => 'fooResource',
            'about' => null,
            'actions' => []
        ];
        $this->assertEquals($descDef, $desc->getDescription());

        $desc->setAboutInfo('Some resource desc');
        $descDef = [
            'name' => 'fooResource',
            'about' => 'Some resource desc',
            'actions' => []
        ];
        $this->assertEquals($descDef, $desc->getDescription());

	$action1 = new Action('Foo action', 'PUT');
        $desc->addAction($action1);
	$action2 = new Action('Foobar action', 'DELETE');
        $desc->addAction($action2);
        $descDef = [
            'name' => 'fooResource',
            'about' => 'Some resource desc',
            'actions' => [
                $action1,
                $action2,
            ]
        ];
        $this->assertEquals($descDef, $desc->getDescription());
    }

}
