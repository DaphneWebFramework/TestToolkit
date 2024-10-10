<?php
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
 * @codeCoverageIgnore
 */
class AccessHelper
{
	/**
	 * Sets the value of a non-public (private or protected) property in an
	 * object.
	 *
	 * @param object $object
	 *   The object in which to set the non-public property value.
	 * @param string $propertyName
	 *   The name of the non-public property.
	 * @param mixed $propertyValue
	 *   The value to set for the specified property.
	 * @throws \ReflectionException
	 *   If the property does not exist or cannot be accessed.
	 */
	public static function SetNonPublicProperty($object, $propertyName,
		$propertyValue)
	{
		$reflectionClass = new \ReflectionClass($object);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($object, $propertyValue);
	}

	/**
	 * Retrieves the value of a non-public (private or protected) property from
	 * an object.
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
	public static function GetNonPublicProperty($object, $propertyName)
	{
		$reflectionClass = new \ReflectionClass($object);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);
		$reflectionProperty->setAccessible(true);
		return $reflectionProperty->getValue($object);
	}

	/**
	 * Sets the value of a non-public (private or protected) static property in
	 * a class.
	 *
	 * @param string $className
	 *   The name of the class in which to set the non-public static property
	 *   value.
	 * @param string $propertyName
	 *   The name of the non-public static property.
	 * @param mixed $propertyValue
	 *   The value to set for the specified property.
	 * @throws \ReflectionException
	 *   If the property does not exist or cannot be accessed.
	 */
	public static function SetNonPublicStaticProperty($className, $propertyName,
		$propertyValue)
	{
		$reflectionClass = new \ReflectionClass($className);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue(null, $propertyValue);
	}

	/**
	 * Retrieves the value of a non-public (private or protected) static
	 * property from a class.
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
	public static function GetNonPublicStaticProperty($className, $propertyName)
	{
		$reflectionClass = new \ReflectionClass($className);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);
		$reflectionProperty->setAccessible(true);
		return $reflectionProperty->getValue(null);
	}

	/**
	 * Sets the value of a non-public (private or protected) property in a mock
	 * object.
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
	 * @throws \ReflectionException
	 *   If the property does not exist or cannot be accessed.
	 */
	public static function SetNonPublicMockProperty($className, $mockObject,
		$propertyName, $propertyValue)
	{
		$reflectionClass = new \ReflectionClass($className);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($mockObject, $propertyValue);
	}

	/**
	 * Retrieves the value of a non-public (private or protected) property from
	 * a mock object.
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
	public static function GetNonPublicMockProperty($className, $mockObject,
		$propertyName)
	{
		$reflectionClass = new \ReflectionClass($className);
		$reflectionProperty = $reflectionClass->getProperty($propertyName);
		$reflectionProperty->setAccessible(true);
		return $reflectionProperty->getValue($mockObject);
	}

	/**
	 * Invokes the non-public (private or protected) constructor of a given
	 * object or class.
	 *
	 * If the first argument is an object, it calls the non-public constructor
	 * of that object. If it's a string, it treats it as a class name and
	 * creates a new instance of the class before calling its constructor.
	 * In both cases, the object with the constructor invoked is returned.
	 *
	 * @param object|string $objectOrClassName
	 *   An object or a fully qualified class name.
	 * @param array $args (Optional)
	 *   An array of arguments to pass to the constructor.
	 * @return object
	 *   The object with its constructor invoked.
	 * @throws \ReflectionException
	 *   If the constructor cannot be accessed, or if the class does not exist
	 *   when a class name is provided.
	 */
	public static function CallNonPublicConstructor($objectOrClassName,
		$args = [])
	{
		$reflectionClass = new \ReflectionClass($objectOrClassName);
		$object = \is_string($objectOrClassName)
			? $reflectionClass->newInstanceWithoutConstructor()
			: $objectOrClassName;
		$reflectionMethod = $reflectionClass->getConstructor();
		$reflectionMethod->setAccessible(true);
		if (empty($args)) {
			$reflectionMethod->invoke($object);
		} else {
			$reflectionMethod->invokeArgs($object, $args);
		}
		return $object;
	}
}
