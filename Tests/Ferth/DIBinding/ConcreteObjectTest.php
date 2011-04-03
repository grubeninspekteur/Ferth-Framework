<?php

require_once 'PHPUnit/Framework.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'TestSetup.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'DIContainerTestAttachment.php';

class ConcreteObjectTest extends \PHPUnit_Framework_TestCase
{

    public function testGetObject()
    {
        $object = new \DITest\A;
        $binding = new \Ferth\DIBinding\ConcreteObject(new \Ferth\DIContainer, $object);

        $this->assertEquals($object, $binding->getObject());
    }
}
?>
