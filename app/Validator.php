<?php

namespace Validator;

class Validator
{

    public function getCountFromPath($array, $path, $index = array())
    {
        if (count($path) === 1) {
            $ret = count($array[$path[0]]);
        } else {
            $key = array_shift($path);
            if ($key === '*') {
                $key = array_shift($index);
            }
            $ret = $this->getCountFromPath($array[$key], $path, $index);
        }
        return $ret;
    }

    public function retrieveCollection($key, $id)
    {
        //ici on recupere une collection carrement
        $add1 = new Collection();
        $add1->setName('yolo');
        $add1->setCreatedAt(new \DateTime);

        $add2 = new Collection();
        $add2->setName('swag');
        $add2->setCreatedAt(new \DateTime);

        $ret = [];

        array_push($ret, $add1);
        array_push($ret, $add2);
        return $ret;
    }

    public function retrieveObject($key, $id)
    {
        //Ici requete part : on fait partir la bonne fonction et on recupere le model
        $add1 = new Model();
        $add1->setName('yolo');
        $add1->setCreatedAt(new \DateTime);
        return $add1;
    }

    // public function retrieveObject($key, $id)
    // {
        // $managerFactory = new \Managers\ManagerFactory($this->app);
        // $objectManager = $managerFactory->create($key);
        // /*
        // *    Si c'est un array avec id => recherche db methode Type
        // *    Si c est une string => recherche db direct (methode par default)
        // **/
        // if (is_array($id)) {
        //     $this->app['log']->info('Call to object Manage special method for '.$key);
        //     return $objectManager->getByExternalId($id['id']);
        // }
        // $this->app['log']->info('Call to object Manager default method for '.$key);
        // return $objectManager->getByExternalId($id);

        // We had an exemple working with doctrine (have to fin something simpler)
    // }

    public function loadObjectFromArray($object, $arrayModel)
    {
        $object = new $object();
        foreach ($arrayModel as $key => $val) {
            /*
            *    If the field is an object (another model)
            *       => If it's an array collection (check if it is un numerical array, else throw exeption)
            *           => iterate on each object and add to the collection
            *       => Else if it's an array with the key 'id' or if it's a string
            *           => It's suposed to be an existing object in the db that we can call with retrieveObject()
            *       => Else it's an associative array (check else throw exeption exeption)
            *           => recursive call
            *    Else => normal setter.
            **/
            if ($object->keyIsAssociatedObject($key)) {
                if ($object->keyIsCollection($key) && is_array($val) && !array_key_exists('id', $val)) {
                    foreach ($val as $k => $v) {
                        if (!is_numeric($k)) {
                            throw new \Exception('Error mapping : Expect num array for collection '.$key, 1);
                        }
                        $add = 'add'.ucfirst($key);
                        $stringObj = $object->getEntityName($key);
                        if (is_array($v) && !array_key_exists('id', $v)) {
                            $object->$add($this->loadObjectFromArray($stringObj, $v));
                        } else {
                            $object->$add($this->retrieveObject($key, $v));
                        }
                    }
                } elseif ($object->keyIsCollection($key)) {
                    $add = 'add'.ucfirst($key);
                    $object->$add($this->retrieveCollection($key, $val));
                } elseif ($object->keyIsModel($key) && is_array($val) && array_key_exists('id', $val)) {
                    $val = $this->retrieveObject($key, $val);
                    $setter = 'set'.ucfirst($key);
                    $object->$setter($val);
                } elseif ($object->keyIsModel($key)) {
                    if (!is_array($val)) {
                        throw new \Exception('Error mapping : Expect associative array, somethings wrong ', 1);
                    }
                    $stringObj = $object->getEntityName($key);
                    $setter = 'set'.ucfirst($key);
                    $object->$setter($this->loadObjectFromArray($stringObj, $val, $mdf));
                } else {
                    throw new \Exception('Unknown error ', 1);
                }
            } else {
                $setter = 'set'.ucfirst($key);
                $object->$setter($val);
            }
        }
        return $object;
    }
}
