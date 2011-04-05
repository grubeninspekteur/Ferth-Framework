<?php

require_once 'PHPUnit/Framework.php';

require_once '../TestSetup.php';

class ConfigArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ferth\ConfigLoader\ConfigArray 
     */
    protected $config_loader;

    protected function setUp()
    {
        $container = new \Ferth\DIContainer;
        $this->config_loader = new \Ferth\ConfigLoader\ConfigArray($container);
        $this->config_loader->getDirList()->insert(__DIR__ . '/../ConfigTestFiles/', 0);
    }

    protected function tearDown()
    {
        unset($this->config_loader);
    }

    public function testConfigExists()
    {
        // ConfigTestFiles/array.php
        $this->assertSame($this->config_loader->configExists('array'), true);

        // not existing
        $this->assertSame($this->config_loader->configExists('invalid'), false);
    }

    public function testGetConfig()
    {
        $this->assertEquals($this->config_loader->get('array')->value, 42);
    }

    /**
     * @expectedException \Ferth\Exceptions\Configuration
     */
    public function testGetInvalidConfig()
    {
        $this->config_loader->get('invalid');
    }

    public function testGetInvalidConfigSuppressException()
    {
        $this->assertSame($this->config_loader->get('invalid', false), null);
    }

    public function testCascadingConfigLoading()
    {
        // Register debug folder with higher priority
        $this->config_loader->getDirList()->insert(__DIR__ . '/../ConfigTestFiles/Debug', 100);

        // Should load Debug/array.php instead of array.php
        $this->assertEquals($this->config_loader->get('array')->value, 21);

        // Debug/array2.php does not exist, use lower level
        $this->assertEquals($this->config_loader->get('array2')->value, 'test');
    }
}
?>
