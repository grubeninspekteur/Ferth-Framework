<?php

require_once 'PHPUnit/Framework.php';

require_once 'TestSetup.php';

require_once 'DIContainerTestAttachment.php';

class DIContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ferth\DIContainer
     */
    protected $container;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->container = new \Ferth\DIContainer;
    }

    protected function tearDown()
    {
        unset($this->container);
    }

    /**
     * An object of the named class must be created. Parameters are optional
     * and will go into the constructor.
     */
    public function testSimpleObjectForClassCreation()
    {
        $object = $this->container->getObject('\DITest\A');
        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));

        $object = $this->container->getObject('\DITest\C', array(true));
        $this->assertEquals($object->value, true);
    }

    /**
     * Even if a concrete class has been named, it is possible to redirect
     * it.
     */
    public function testBindClassToClass()
    {
        $this->container->bindImplementation('\DITest\A', '\DITest\B');
        $this->container->save();

        $object = $this->container->getObject('\DITest\A');
        $this->assertThat($object, $this->isInstanceOf('\DITest\B'));
    }

    public function testBindInterfaceToClass()
    {
        $this->container->bindImplementation('\DITest\iA', '\DITest\A');
        $this->container->save();

        $object = $this->container->getObject('\DITest\iA');
        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));

        // Bindings can be overwritten.
        $this->container->bindImplementation('\DITest\iA', '\DITest\B');
        $this->container->save();

        $object = $this->container->getObject('\DITest\iA');
        $this->assertThat($object, $this->isInstanceOf('\DITest\B'));
    }

    public function testBindInterfaceToObject()
    {
        $a = new \DITest\A;
        $a->test = true;

        $this->container->bindImplementation('\DITest\iA', $a);
        $this->container->save();

        $object = $this->container->getObject('\DITest\iA');

        $this->assertEquals($object, $a);
    }

    public function testBindCallback()
    {
        $this->container->bindCallback('\DITest\iA', '\DITest\callback_function');
        $this->container->save();

        $object = $this->container->getObject('\DITest\iA');
        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));

        // The same for anonymous functions
        $function = function() {return new \DITest\A;};
        $this->container->bindCallback('\DITest\iA', $function);
        $this->container->save();

        $object = $this->container->getObject('\DITest\iA');
        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));
    }

    /**
     * The container should test callbacks, but for syntax only.
     * 
     * @expectedException \Ferth\Exceptions\DIContainer
     */
    public function testBindCallbackWrong()
    {
        $this->container->bindCallback('Throwaway', array('this', 'is', 'certainly', 'invalid'));
    }

    /**
     * @depends testBindInterfaceToClass
     */
    public function testConstructorInjection()
    {
        $this->container->bindImplementation('\DITest\iA', '\DiTest\A');
        $this->container->save();

        $object = $this->container->getObject('\DITest\ExpectsAniA');
        $this->assertThat($object->object, $this->isInstanceOf('\DITest\A'));
    }

    /**
     * It is possible to add named parameters for the constructor / callback.
     * @depends testConstructorInjection
     */
    public function testParameterBinding()
    {
        $this->container->bindImplementation('\DITest\iA', '\DITest\WithParameters');
        $this->container->withParameters(array('value' => true));
        $this->container->save();
        
        $object = $this->container->getObject('\DITest\iA');
        $this->assertEquals($object->value, true);
    }

    /**
     * @depends testBindInterfaceToClass
     */
    public function testMakeChild()
    {
        $this->container->bindImplementation('\DITest\iA', '\DITest\A');
        $this->container->save();

        $container2 = $this->container->createChild();

        // The child should use it's parent's bindings when none has been set.
        $object = $container2->getObject('\DITest\iA');
        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));

        // The child can override it's parent's bindings.
        $container2->bindImplementation('\DITest\iA', '\DITest\B');
        $container2->save();

        $object = $container2->getObject('\DITest\iA');
        $this->assertThat($object, $this->isInstanceOf('\DITest\B'));

        // The original container should still return A.
        $object = $this->container->getObject('\DITest\iA');
        $this->assertThat($object, $this->isInstanceOf('\DITest\A'));
    }

    /**
     * @depends testConstructorInjection
     */
    public function testForTag()
    {
        $this->container->bindImplementation('\DITest\iA', '\DITest\A');
        $this->container->save();

        $this->container->bindImplementation('\DITest\iA', '\DITest\B');
        $this->container->forTag('\DITest\TagInterface');
        $this->container->save();

        $object = $this->container->getObject('\DITest\ExpectsAniA');
        $this->assertThat($object->object, $this->isInstanceOf('\DITest\A'));

        $object = $this->container->getObject('\DITest\ExpectsAniATagged');
        $this->assertThat($object->object, $this->isInstanceOf('\DITest\B'));
    }
}

?>
