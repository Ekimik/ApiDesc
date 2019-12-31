<?php

namespace Ekimik\ApiDesc\Resource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiDesc
 */
abstract class DescriptionBuilder {

	/** @var string */
	protected $name;
	/** @var string */
	protected $about;
	/** @var ActionHelper */
	protected $ah;

	public function __construct(ActionHelper $ah) {
		$this->ah = $ah;
	}

	public final function get(): Description {
		$desc = new Description($this->name);

		if (!empty($this->about)) {
			$desc->setAboutInfo($this->about);
		}

		$this->configure($desc);
		return $desc;
	}

	protected abstract function configure(Description $desc);

}