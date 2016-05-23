<?php

namespace Models;


include_once('app/Validator.php');
include_once('src/Models/Test.php');
include_once('src/Models/AbstractModel.php');
include_once('src/Models/Collection.php');
include_once('src/Models/Model.php');


use Models\AbstractModel;

class Test extends AbstractModel
{
    protected $id;

    protected $name;

    protected $createdAt;

    protected $collection;

    protected $model;

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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function addCollection($collection)
    {
        array_push($this->collection, $collection);
        return $this;
    }

    public function removeCollection($collection)
    {
        array_diff($this->collection, [$collection]);
        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    protected function loadMetaData()
    {
        $this->_metadata = [
            'name' => 'string',
            'createdAt' => 'DateTime',
            'collection' => ['collection', 'Models\Collection'],
            'model' => ['model', 'Models\Models'],
        ];
    }
}
