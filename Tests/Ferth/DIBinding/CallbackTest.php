<?php

require_once 'PHPUnit/Framework.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'TestSetup.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'DIContainerTestAttachment.php';

class CallbackTest extends \PHPUnit_Framework_TestCase
{

    public function testWithoutParameters()
    {
        $binding = new \Ferth\DIBinding\Callback(new \Ferth\DIContainer, '\DITest\callback_function');

        $object = $binding->getObject();

        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCallbackException()
    {
        $binding = new \Ferth\DIBinding\Callback(new \Ferth\DIContainer, 'i_dont_exist');

        $binding->getObject();
    }

    public function testSetParameters()
    {
        $binding = new \Ferth\DIBinding\Callback(new \Ferth\DIContainer, '\DITest\create_object_for');
        $binding->setParameters(array('\DITest\A'));

        $object = $binding->getObject();

        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));

        // test named
        $binding = new \Ferth\DIBinding\Callback(new \Ferth\DIContainer, '\DITest\create_object_for');
        $binding->setParameters(array('classname' => '\DITest\A'));

        $object = $binding->getObject();

        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));
    }

    public function testDynamicSetParameters()
    {
        $binding = new \Ferth\DIBinding\Callback(new \Ferth\DIContainer, '\DITest\create_object_for');

        $object = $binding->getObject(array('\DITest\A'));

        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testNoParametersSetException()
    {
        $binding = new \Ferth\DIBinding\Callback(new \Ferth\DIContainer, '\DITest\create_object_for');

        $binding->getObject();
    }

    public function testTypeHinting()
    {
        $container = new \Ferth\DIContainer;
        $container->bindCallback('\DITest\iA', '\DITest\create_object_for')
            ->withParameters(array('\DITest\A'))
            ->save();

        $binding = new \Ferth\DIBinding\Callback($container, '\DITest\resolve_me');

        $object = $binding->getObject();

        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));
    }
}
?>
