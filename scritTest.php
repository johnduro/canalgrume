<?php
date_default_timezone_set('America/Los_Angeles');
include_once('app/Validator.php');
include_once('src/Models/Test.php');
include_once('src/Models/AbstractModel.php');
include_once('src/Models/Collection.php');
include_once('src/Models/Model.php');

$validator = new Validator\Validator();

$testString = 'Models\Test';

$testArray = [
	'name' => 'yolo',
	'createdAt' => new DateTime(),
	'model' => [
		'name' => 'nom1',
		(new DateTime('2015-02-12'))->format('Y-m-d H:i'),
	],
	'collection' => [
		[
			'name' => 'nom2',
			(new DateTime('2016-02-12'))->format('Y-m-d H:i'),
		],
		[
			'name' => 'nom3',
			(new DateTime('2017-02-12'))->format('Y-m-d H:i'),
		]
	]
];

$object = $validator->loadObjectFromArray($testString, $testArray);


var_dump($object);
