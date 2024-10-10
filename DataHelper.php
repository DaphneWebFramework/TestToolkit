<?php
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
 * @codeCoverageIgnore
 */
class DataHelper
{
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
	 * Example usage in PHPUnit data provider:
	 * ```
	 * public function dataProvider() {
	 *     return Helper::Cartesian([1, 2, 3], ['a', 'b']);
	 * }
	 * // Returns:
	 * //   [1, 'a'], [1, 'b'], [2, 'a'], [2, 'b'], [3, 'a'], [3, 'b']
	 * ```
	 *
	 * @param array ...$arrays
	 *   The input arrays for which the Cartesian product will be calculated.
	 * @return array
	 *   The Cartesian product as an array of arrays, representing all
	 *   combinations.
	 *
	 * @see https://stackoverflow.com/a/15973172
	 */
	public static function Cartesian(...$arrays)
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
