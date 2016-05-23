<?php

namespace Models;

use Models\AbstractModel;

class Model extends AbstractModel
{
	protected $id;

	protected $name;

	protected $createdAt;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	protected function loadMetadata()
	{
		$this->_metadata = [
			'name' => 'string',
			'createdAt' => 'DateTime',
		];
	}
}
