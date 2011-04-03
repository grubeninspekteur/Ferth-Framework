<?php

require_once 'PHPUnit/Framework.php';

require_once 'TestSetup.php';

class TestClass
{
    public static function aStaticMethod()
    {

    }

    public function aMethod()
    {

    }
}

class SecondTestClass extends TestClass
{
    public static function aStaticMethod()
    {

    }
}

function a_function()
{
    
}

class ReflectionFunctionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFunctionName()
    {
        $reflection = \Ferth\ReflectionFunctionFactory::getReflectionFunction('a_function');

        $this->assertThat($reflection, $this->isInstanceOf('\ReflectionFunction'));
    }

    public function testClosure()
    {
        $function = function() {};

        $reflection = \Ferth\ReflectionFunctionFactory::getReflectionFunction($function);

        // note that closures are all instances of Closure with a magic __invoke method
        $this->assertThat($reflection, $this->isInstanceOf('\ReflectionMethod'));
    }

    public function testStaticMethodArray()
    {
        $reflection = \Ferth\ReflectionFunctionFactory::getReflectionFunction(array('TestClass', 'aStaticMethod'));

        $this->assertThat($reflection, $this->isInstanceOf('\ReflectionMethod'));
    }

    public function testStaticMethodLiteral()
    {
        $reflection = \Ferth\ReflectionFunctionFactory::getReflectionFunction('TestClass::aStaticMethod');

        $this->assertThat($reflection, $this->isInstanceOf('\ReflectionMethod'));
    }

    public function testMemberMethod()
    {
        $object = new TestClass;

        $reflection = \Ferth\ReflectionFunctionFactory::getReflectionFunction(array($object, 'aMethod'));

        $this->assertThat($reflection, $this->isInstanceOf('\ReflectionMethod'));
    }
}
?>
