<?php
declare(strict_types=1);
/**
 * @param $input
 * @return array
 */
function cartesian_product($input)
{
    $result = [];

    foreach ($input as $key => $values) {
        // If a sub-array is empty, it doesn't affect the cartesian_product product
        if (empty($values)) {
            continue;
        }

        // Seeding the product array with the values from the first sub-array
        if (empty($result)) {
            foreach ($values as $value) {
                $result[] = [$key => $value];
            }
        } else {
            // Second and subsequent input sub-arrays work like this:
            //   1. In each existing array inside $product, add an item with
            //      key == $key and value == first item in input sub-array
            //   2. Then, for each remaining item in current input sub-array,
            //      add a copy of each existing array inside $product with
            //      key == $key and value == first item of input sub-array

            // Store all items to be added to $product here; adding them
            // inside the foreach will result in an infinite loop
            $append = [];

            foreach ($result as &$product) {
                // Do step 1 above. array_shift is not the most efficient, but
                // it allows us to iterate over the rest of the items with a
                // simple foreach, making the code short and easy to read.
                $product[$key] = array_shift($values);

                // $product is by reference (that's why the key we added above
                // will appear in the end result), so make a copy of it here
                $copy = $product;

                // Do step 2 above.
                foreach ($values as $item) {
                    $copy[$key] = $item;
                    $append[] = $copy;
                }

                // Undo the side effects of array_shift
                array_unshift($values, $product[$key]);
            }

            // Out of the foreach, we can add to $results now
            $result = array_merge($result, $append);
        }
    }

    return $result;
}

/**
 * @param array $subset
 * @param array $parent
 * @return array
 */
function array_subset_keys(array $subset, array $parent)
{
    $r = [];
    //dd(Option::translateToPaths($b));
    foreach ($parent as $key => $value) {
        in_array($value, $subset) ? ($r[] = $key) : null;
    }

    return $r;
}

/**
 * @param array $contender
 * @param array $parent
 * @param bool $strict
 * @return bool
 */
function is_subset(array $contender, array $parent, $strict = false)
{
    $c_size = count($contender);
    $p_size = count($parent);

    return ($strict ? $c_size < $p_size : $c_size <= $p_size)
        and count(array_subset_keys($contender, $parent)) == $c_size;
}
