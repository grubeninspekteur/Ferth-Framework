<?php

namespace Ferth\Interfaces;

/**
 * The Dependency Injection Container (DIContainer) binds interfaces to concrete
 * implementations and injects them into newly created objects.
 *
 * Instead of carrying the necessary dependencies from object to object,
 * the container decides which class may fullfill the interface.
 *
 * When creating an object with the DIContainer, it will use the reflection
 * api to introspect the object's dependencies. Then the following rules
 * take effect to determine the needed class:
 *
 * * If the parameter type hints to an interface or class and a specific
 *   implementation has been registered in the DIContainer, this implementation
 *   will be chosen.
 * * If no implementation has been registered, the container will ask the
 *   parent container (if any) for an implementation.
 * * Type hints to concrete classes with no registered implementation will
 *   create an object of this class.
 *
 *
 * @package DIContainer
 */
interface DIContainer
{
    
    /**
     * Binds an interface or class to it's concrete implementation.
     *
     * It is possible to either register the class name (so every time an
     * implementation is required, a new object will be created) or a concrete
     * object (which will be passed through).
     * 
     * Note that it is rather silly to bind a class name to itself. Just saying.
     *
     * @param string $name The dependency's name (full qualified namespace!).
     * @param string|object $implementation The implementation's class
     *        name or a concrete object or a callback for a factory method.
     *
     * @return DIContainer
     */
    public function bindImplementation($name, $implementation);

    /**
     * Binds an interface or class name to a callback.
     *
     * The callback (usually a factory method or singleton::getInstance)
     * has to return an object implementing interface $name respectively
     * extending class $name.
     *
     * @param string $name The dependency's name (full qualified namespace!).
     * @param callback $callback See PHP documentation for valid callbacks.
     *        Check for is_callable is in syntax only.
     *
     * @throws \BadMethodCallException
     *
     * @return DIContainer
     */
    public function bindCallback($name, $callback);

    /**
     * Bind non object parameters to the last registered implementation.
     *
     * Example: Using a database class with parameters such as username, pwd,
     * table and so on.
     *
     * @param Array $parameters The parameters as name => value or position =>
     * value. Note that the first parameter is element 0 in the array. You may
     * skip default parameters and parameters with class or interface type hints
     * - the container will then be used to inject an appropiate implementation.
     *
     * @return DIContainer
     */
    public function withParameters(array $parameters);

    /**
     * Restrict the last registered implementation to a tag interface.
     *
     * Tag interfaces are a nice way to group classes into categories. As it is
     * possible in PHP to implement multiple interfaces in one class, you can
     * (ab)use this as a tagging feature.
     *
     * Use case: You want your configuration for user roles to be in a database
     * for easy editing. Tag it by let the user class implement the
     * "EasyEditing" empty interface. Then bind \Ferth\Interfaces\ConfigLoader
     * to \Ferth\ConfigLoader\ConfigClass for normal classes and to
     * \Ferth\ConfigLoader\ConfigTable for the "EasyEditing"-Tag.
     *
     * @param string $interface_name
     *
     * @return DIContainer
     */
    public function forTag($interface_name);

    /**
     * Saves the current binding.
     */
    public function save();

    /**
     * Returns a DIContainer with it's parent set to this one.
     *
     * @return DIContainer
     */
    public function createChild();

    /**
     * Sets a parent container. Think of it as a kind of inheritance. The
     * container will ask it's parent container for an implementation if
     * no appropiate could be found in the current one.
     */
    public function setParent(DIContainer $container);

    /**
     * Returns an object of the given class and injects necessary dependencies;
     * an interface will return a registered class instead or throw an
     * exception.
     *
     * @param string $name The interface or class name.
     * @param Array $parameters Optional Parameters.
     *
     * @return object
     */
    public function getObject($name, array $parameters = array());

    /**
     * Similar to getObject, but considers tags not only in constructor
     * injection but also for determining which object to chose.
     *
     * @param string $name The interface or class name.
     * @param Array $interfaces A list of TagInterfaces you want to take into
     *        consideration.
     * @param Array $parameters Optional Parameters.
     *
     * @return object
     */
    public function getObjectConsideringTags($name, array $interfaces, array $parameters = array());
}
?>