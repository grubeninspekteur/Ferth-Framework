<?php
require_once 'PHPUnit/Framework.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'TestSetup.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'DIContainerTestAttachment.php';

class ClassnameTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutParameters()
    {
        $binding = new \Ferth\DIBinding\Classname(new \Ferth\DIContainer, '\DITest\A');

        $object = $binding->getObject();

        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidClassException()
    {
        $binding = new \Ferth\DIBinding\Classname(new \Ferth\DIContainer, 'i_dont_exist');

        $binding->getObject();
    }

    public function testSetParameters()
    {
        $binding = new \Ferth\DIBinding\Classname(new \Ferth\DIContainer, '\DITest\C');
        $binding->setParameters(array(42));

        $object = $binding->getObject();

        $this->assertEquals($object->value, 42);

        // test named parameters
        $binding = new \Ferth\DIBinding\Classname(new \Ferth\DIContainer, '\DITest\C');
        $binding->setParameters(array('value' => 42));

        $object = $binding->getObject();

        $this->assertEquals($object->value, 42);
    }

    public function testDynamicSetParameters()
    {
        $binding = new \Ferth\DIBinding\Classname(new \Ferth\DIContainer, '\DITest\C');

        $object = $binding->getObject(array(42));

        $this->assertEquals($object->value, 42);
    }

     /**
     * @expectedException \BadMethodCallException
     */
    public function testNoParametersSetException()
    {
        $binding = new \Ferth\DIBinding\Classname(new \Ferth\DIContainer, '\DITest\C');

        $binding->getObject();
    }

    public function testTypeHinting()
    {
        $binding = new \Ferth\DIBinding\Classname(new \Ferth\DIContainer, '\DITest\WithParameters');
        $binding->setParameters(array(1 => 42));

        $object = $binding->getObject();

        $this->assertThat($object, $this->isInstanceOf('\DITest\WithParameters'));
        $this->assertEquals($object->value, 42);
    }
}
?>
