<?php
/**
 * Hack Job plugin for Craft CMS 3.x
 *
 * Used to reduce the template data. Useful for outputting to json.
 *
 * @link      http://www.jesseknowles.com
 * @copyright Copyright (c) 2018 Jesse Knowles
 */

namespace kinoli\hackjob\twigextensions;

use kinoli\hackjob\HackJob;

use Craft;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Jesse Knowles
 * @package   HackJob
 * @since     1.0.0
 */
class HackJobTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'HackJob';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('hackjob', [$this, 'hackjob']),
        ];
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('someFunction', [$this, 'someInternalFunction']),
        ];
    }

    /**
	 * Convert a BaseModel into an array with the specified fields
	 *
	 * @param array  $input  The content being filtered
	 * @param array  $fields An array of the fields to keep
	 * @return array
	 * @throws Exception
	 */
    public function hackjob(array $input, array $fields)
    {
		if ( ! is_array($fields)) {
			throw new Exception(Craft::t('Map parameter needs to be an array.'));
		}
		if ( ! is_array($input)) {
			throw new Exception(Craft::t('Content passed is not an array.'));
		}
		$this->input = $input;
		$this->fields = $fields;
		$output = array();
		foreach ($input as $element) {
			// if ( ! ($element instanceof object)) {
			// 	continue;
			// }
			$output[] = $this->returnPrunedArray($element);
		}
		return $output;
    }

    /**
	 * Given a BaseModel, return an array with only requested fields
	 *
	 * @param BaseModel $item
	 * @return array
	 */
	protected function returnPrunedArray($item)
	{
		$new_item = array();
		foreach ($this->fields as $key) {
			if (isset($item->{$key})) {
				if(is_object($item->{$key}) && method_exists($item->{$key}, 'attributeNames')) {
					$new_item[$key] = new \stdClass();
					foreach($item->{$key}->attributeNames() as $attribute) {
						 $new_item[$key]->$attribute = $item->{$key}->{$attribute};
					} 
				}
				else {
					$new_item[$key] = $item->{$key};
				}
			}
		}
		return $new_item;
	}
}
