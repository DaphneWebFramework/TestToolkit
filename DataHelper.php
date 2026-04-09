<?php declare(strict_types=1);
/**
 * DataHelper.php
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
 * Provides reusable data sets for PHPUnit, including common value types like
 * non-strings, non-integers, booleans, and combinations of test data (Cartesian
 * product).
 *
 * #### Example
 * ```php
 * use \PHPUnit\Framework\TestCase;
 * use \PHPUnit\Framework\Attributes\DataProviderExternal;
 *
 * use \TestToolkit\DataHelper;
 *
 * class ExampleTest extends TestCase
 * {
 *     #[DataProviderExternal(DataHelper::class, 'NonStringProvider')]
 *     function testConstructorWithNonStringValue($value)
 *     {
 *         $this->expectException(\TypeError::class);
 *         new ExampleClass($value);
 *     }
 * }
 * ```
 *
 * @codeCoverageIgnore
 */
class DataHelper
{
    /**
     * Provides boolean values.
     *
     * @return array
     *   Returns an array of boolean values.
     */
    public static function BooleanProvider(): array
    {
        return [
            'true' => [true],
            'false' => [false]
        ];
    }

    /**
     * Provides non-string, non-Stringable values.
     *
     * @return array
     *   Returns an array of non-string, non-Stringable values.
     */
    public static function NonStringProvider(): array
    {
        return [
            'null' => [null],
            'boolean/true' => [true],
            'boolean/false' => [false],
            'integer' => [12345],
            'float' => [123.45],
            'array' => [['not', 'a', 'string']],
            'object' => [new \stdClass()],
            'callable' => [fn() => 'I am a callable']
        ];
    }

    /**
     * Provides non-string, non-Stringable values, excluding `null`.
     *
     * @return array
     *   Returns an array of non-string, non-Stringable values, excluding `null`.
     */
    public static function NonStringExcludingNullProvider(): array
    {
        $data = self::NonStringProvider();
        unset($data['null']);
        return $data;
    }

    /**
     * Provides non-boolean values.
     *
     * @return array
     *   Returns an array of non-boolean values.
     */
    public static function NonBooleanProvider(): array
    {
        return [
            'null' => [null],
            'integer' => [12345],
            'float' => [123.45],
            'string' => ['I am a string'],
            'string/true' => ['true'],
            'string/false' => ['false'],
            'string/numeric' => ['123'],
            'array' => [['not', 'a', 'boolean']],
            'object' => [new \stdClass()],
            'callable' => [fn() => 'I am a callable']
        ];
    }

    /**
     * Provides non-integer values.
     *
     * @return array
     *   Returns an array of non-integer values.
     */
    public static function NonIntegerProvider(): array
    {
        return [
            'null' => [null],
            'boolean/true' => [true],
            'boolean/false' => [false],
            'float' => [123.45],
            'string' => ['I am a string'],
            'string/numeric' => ['123'],
            'array' => [[1, 2, 3]],
            'object' => [new \stdClass()],
            'callable' => [fn() => 'I am a callable']
        ];
    }

    /**
     * Provides non-integer values, excluding strings that represent integers.
     *
     * @return array
     *   Returns an array of values that are neither integers nor stringified
     *   integers.
     */
    public static function NonIntegerExcludingNumericStringProvider(): array
    {
        $data = self::NonIntegerProvider();
        unset($data['string/numeric']);
        return $data;
    }

    /**
     * Provides non-string, non-integer values.
     *
     * @return array
     *   Returns an array of values that are neither strings nor integers.
     */
    public static function NonStringOrIntegerProvider(): array
    {
        $data = self::NonIntegerProvider();
        unset($data['string'], $data['string/numeric']);
        return $data;
    }

    /**
     * Provides non-array values.
     *
     * @return array
     *   Returns an array of values that are not arrays.
     */
    public static function NonArrayProvider(): array
    {
        return [
            'null' => [null],
            'boolean/true' => [true],
            'boolean/false' => [false],
            'integer' => [123],
            'float' => [123.45],
            'string' => ['I am a string'],
            'object' => [new \stdClass()],
            'callable' => [fn() => 'I am a callable']
        ];
    }

    /**
     * Generates the Cartesian product of multiple arrays.
     *
     * This function is designed to calculate the cartesian product of multiple
     * arrays, which is particularly useful in PHPUnit data provider functions.
     * It is ideal for generating all possible combinations of test data from
     * multiple sets. The cartesian product operation results in an array of
     * arrays, each containing a unique combination of elements from the input
     * arrays.
     *
     * #### Example
     * ```php
     * static function dataProvider() {
     *     return DataHelper::Cartesian([1, 2, 3], ['a', 'b']);
     * }
     *
     * // Returns: [1, 'a'], [1, 'b'], [2, 'a'], [2, 'b'], [3, 'a'], [3, 'b']
     * ```
     *
     * @param array $arrays
     *   The input arrays for which the Cartesian product will be calculated.
     * @return array
     *   The Cartesian product as an array of arrays, representing all
     *   combinations.
     *
     * @link https://stackoverflow.com/a/15973172
     *   Sergiy Sokolenko's answer on StackOverflow
     */
    public static function Cartesian(array ...$arrays): array
    {
        $result = [[]];
        foreach ($arrays as $key => $values) {
            $append = [];
            foreach ($result as $product) {
                foreach ($values as $item) {
                    $product[$key] = $item;
                    $append[] = $product;
                }
            }
            $result = $append;
        }
        return $result;
    }
}
