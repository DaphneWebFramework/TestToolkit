<?php declare(strict_types=1);
/**
 * AccessHelper.php
 *
 * (C) 2024 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace TestToolkit;

/**
 * Provides access to non-public properties and methods using PHP's reflection
 * API, enabling modification and retrieval of otherwise inaccessible values.
 *
 * While primarily intended for private and protected members, these methods
 * can also be used to access public properties and methods.
 *
 * @codeCoverageIgnore
 */
class AccessHelper
{
    /**
     * Sets the value of a property in an object.
     *
     * @param object $object
     *   The object in which to set the non-public property value.
     * @param string $propertyName
     *   The name of the non-public property.
     * @param mixed $propertyValue
     *   The value to set for the specified property.
     * @return void
     * @throws \ReflectionException
     *   If the property does not exist or cannot be accessed.
     * @throws \Error
     *   If the property is read-only, a fatal error will be thrown when
     *   attempting to modify it.
     */
    public static function SetProperty(
        object $object,
        string $propertyName,
        mixed $propertyValue
    ): void
    {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        $reflectionProperty->setValue($object, $propertyValue);
    }

    /**
     * Retrieves the value of a property from an object.
     *
     * @param object $object
     *   The object from which to retrieve the non-public property value.
     * @param string $propertyName
     *   The name of the non-public property.
     * @return mixed
     *   The value of the specified property.
     * @throws \ReflectionException
     *   If the property does not exist or cannot be accessed.
     */
    public static function GetProperty(
        object $object,
        string $propertyName
    ): mixed
    {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        return $reflectionProperty->getValue($object);
    }

    /**
     * Sets the value of a static property in a class.
     *
     * @param string $className
     *   The name of the class in which to set the non-public static property
     *   value.
     * @param string $propertyName
     *   The name of the non-public static property.
     * @param mixed $propertyValue
     *   The value to set for the specified property.
     * @return void
     * @throws \ReflectionException
     *   If the property does not exist or cannot be accessed.
     * @throws \Error
     *   If the property is read-only, a fatal error will be thrown when
     *   attempting to modify it.
     */
    public static function SetStaticProperty(
        string $className,
        string $propertyName,
        mixed $propertyValue
    ): void
    {
        $reflectionClass = new \ReflectionClass($className);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        $reflectionProperty->setValue(null, $propertyValue);
    }

    /**
     * Retrieves the value of a static property from a class.
     *
     * @param string $className
     *   The name of the class from which to retrieve the non-public static
     *   property value.
     * @param string $propertyName
     *   The name of the non-public static property.
     * @return mixed
     *   The value of the specified static property.
     * @throws \ReflectionException
     *   If the property does not exist or cannot be accessed.
     */
    public static function GetStaticProperty(
        string $className,
        string $propertyName
    ): mixed
    {
        $reflectionClass = new \ReflectionClass($className);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        return $reflectionProperty->getValue(null);
    }

    /**
     * Sets the value of a property in a mock object.
     *
     * This method is specifically designed for use with mock objects in unit
     * tests, allowing for the modification of properties that are not publicly
     * accessible.
     *
     * @param string $className
     *   The name of the original class from which the mock object was
     *   instantiated.
     * @param object $mockObject
     *   The mock object in which to set the non-public property value.
     * @param string $propertyName
     *   The name of the non-public property.
     * @param mixed $propertyValue
     *   The value to set for the specified property.
     * @return void
     * @throws \ReflectionException
     *   If the property does not exist or cannot be accessed.
     * @throws \Error
     *   If the property is read-only, a fatal error will be thrown when
     *   attempting to modify it.
     */
    public static function SetMockProperty(
        string $className,
        object $mockObject,
        string $propertyName,
        mixed $propertyValue
    ): void
    {
        $reflectionClass = new \ReflectionClass($className);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        $reflectionProperty->setValue($mockObject, $propertyValue);
    }

    /**
     * Retrieves the value of a property from a mock object.
     *
     * This method is particularly useful in unit testing scenarios where there
     * is a need to access properties of mock objects that are not publicly
     * accessible.
     *
     * @param string $className
     *   The name of the original class from which the mock object was
     *   instantiated.
     * @param object $mockObject
     *   The mock object from which to retrieve the non-public property value.
     * @param string $propertyName
     *   The name of the non-public property.
     * @return mixed
     *   The value of the specified property.
     * @throws \ReflectionException
     *   If the property does not exist or cannot be accessed.
     */
    public static function GetMockProperty(
        string $className,
        object $mockObject,
        string $propertyName
    ): mixed
    {
        $reflectionClass = new \ReflectionClass($className);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        return $reflectionProperty->getValue($mockObject);
    }

    /**
     * Invokes the constructor of a given object or class.
     *
     * If the first argument is an object, it calls the non-public constructor
     * of that object. If it's a string, it treats it as a class name and
     * creates a new instance of the class before calling its constructor.
     * In both cases, the object with the constructor invoked is returned.
     *
     * @param object|string $objectOrClassName
     *   An object or a fully qualified class name.
     * @param ?array $args
     *   (Optional) An array of arguments to pass to the constructor.
     * @return object
     *   The object with its constructor invoked.
     * @throws \ReflectionException
     *   If the constructor cannot be accessed, or if the class does not exist
     *   when a class name is provided.
     */
    public static function CallConstructor(
        object|string $objectOrClassName,
        ?array $args = null
    ): object
    {
        $reflectionClass = new \ReflectionClass($objectOrClassName);
        $object = \is_string($objectOrClassName)
            ? $reflectionClass->newInstanceWithoutConstructor()
            : $objectOrClassName;
        $reflectionMethod = $reflectionClass->getConstructor();
        if ($reflectionMethod !== null) {
            if ($args === null) {
                $reflectionMethod->invoke($object);
            } else {
                $reflectionMethod->invokeArgs($object, $args);
            }
        }
        return $object;
    }

    /**
     * Invokes a method on an object.
     *
     * This method allows for the invocation of non-public methods, enabling
     * unit testing or advanced manipulation of otherwise inaccessible logic.
     *
     * @param object $object
     *   The object instance on which to invoke the non-public method.
     * @param string $methodName
     *   The name of the non-public method to invoke.
     * @param ?array $args
     *   (Optional) An array of arguments to pass to the method.
     * @return mixed
     *   The result of the invoked method.
     * @throws \ReflectionException
     *   If the method does not exist or cannot be accessed.
     */
    public static function CallMethod(
        object $object,
        string $methodName,
        ?array $args = null
    ): mixed
    {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionMethod = $reflectionClass->getMethod($methodName);
        $reflectionMethod->setAccessible(true);
        if ($args === null) {
            return $reflectionMethod->invoke($object);
        } else {
            return $reflectionMethod->invokeArgs($object, $args);
        }
    }

    /**
     * Invokes a static method on a class.
     *
     * This method allows for the invocation of non-public static methods,
     * enabling unit testing or advanced manipulation of otherwise inaccessible
     * logic.
     *
     * @param string $className
     *   The name of the class on which to invoke the non-public static method.
     * @param string $methodName
     *   The name of the non-public static method to invoke.
     * @param ?array $args
     *   (Optional) An array of arguments to pass to the method.
     * @return mixed
     *   The result of the invoked method.
     * @throws \ReflectionException
     *   If the method does not exist or cannot be accessed.
     */
    public static function CallStaticMethod(
        string $className,
        string $methodName,
        ?array $args = null
    ): mixed
    {
        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($methodName);
        $reflectionMethod->setAccessible(true);
        if ($args === null) {
            return $reflectionMethod->invoke(null);
        } else {
            return $reflectionMethod->invokeArgs(null, $args);
        }
    }
}
