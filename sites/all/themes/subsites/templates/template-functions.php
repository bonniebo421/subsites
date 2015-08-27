<?php

/**
 * removes string pieces that maytch the regex
 *
 * @author chase <cisabelle@mos.org>
 * @param string $regex is the pattern
 * @param string $string is the string to reomve the mached patern from
 * @return string the modified sting
 */
function mos_preg_remove($regex, $string) {
    return preg_replace($regex, '', $string);
}

/**
 * fix for drupal's render function for field collections
 * gives you the value for a single field collection item
 *
 * @param string $field is the name of the field - machine name
 * @param mixed[] $item is some morbidly obese drupal data struct
 * @param string $value is the nameof the value - usually just 'value' but could be like 'uri' or 'alt' for images
 * @param int $index is the index of the value for the item you want
 * @param mixed $default is the value to return if no value is found
 * @return mixed hopefully your value 
 */
function mos_field_collection_item_value($field, $item, $value = 'value', $index = 0, $default = null) {
    return ($values = mos_field_collection_item_values($field, $item, $value, $default)) ? array_shift($values) : $default;
}

/**
 * gets all the values for all the items in a single field collection item
 *
 * @param string $field machine name of field
 * @param mixed[] a bloated drupal array
 * @param string $value
 * @param mixed $default
 * @return mixed drinks
 */
function mos_field_collection_item_values($field, $item, $value = 'value', $default = null) {
    $items  = mos_array_first($item['entity']['field_collection_item']);
    $field  = 'field_' . mos_preg_remove('/^field_/', $field);
    $values = array();

    foreach (mos_array_get(mos_array_get($items, $field, array()), '#items', array()) as $item) {
        if (!array_key_exists($value, $item)) {
            trigger_error('Value ' . mos_spy($value) . ' does not exist for item ' . mos_spy($item) . '.');
        }

        $values[] = mos_array_get($item, $value, $default);
    }

    return $values;
}

/**
 * fix to get values from nested field collections nested in other field collections 
 *
 * @param string[] $fields is an array of strings that represents the levels of the nest - so array('content_column', 'column_title') woud be for content title in content column
 * @param mixed[] $collection is a...it's "drupal magic" which is more like drupal voodoo and the tpls are the pin-cusion dolls linked to your soul and every time you hack them it causes physical pain
 * @param string $value is a bunch of who-cares
 * @param mixed $default is null - recently a stackoverflow.com survey listed back-end web devs as the least satisfied with their jobs that is not a joke but right now is making it sad and funny at the same time
 * @return mixed[]
 */
function mos_field_collection_nested_values($fields, $collection, $value = 'value', $default = null) {
    $collection  = mos_array_first($collection['entity']['field_collection_item']);
    $values      = array();
    $collections = array();
    $fields      = array_values($fields);

    foreach ($fields as $key => $field) {
        $fields[$key] = 'field_' . mos_preg_remove('/^field_/', $field);
    }

    foreach (mos_array_get(mos_array_get($collection, $fields[0], array()), '#items', array()) as $item) {
        //$collections[] = mos_array_first(mos_object_to_array(entity_load('field_collection_item', array($item['value']))));
        $collections[] = mos_object_to_array(entity_revision_load('field_collection_item', $item['revision_id']));
    }

    foreach ($collections as $key => $collection) {
        $values[$key] = array();

        foreach (mos_array_get(mos_array_get($collection, $fields[1], $default), 'und', array()) as $key2 => $nested) {
            $values[$key][$key2] = $nested[$value];
        }
    }

    return $values;
}

/**
 * gets the url for the museum map - use this func whenever map url is needed to keep consitency and ease of swap
 */
function mos_map_url() {
    return '/sites/dev-elvis.mos.org/themes/mos_responsive/img/map.pdf';
}

/**
 * @param int[] $indexes
 */
function mos_field_collection_nested_value($fields, $collection, $value = 'value', $indexes = array(0, 0), $default = null) {
    $indexes = array_values($indexes);
    $values  = mos_field_collection_nested_values($fields, $collection, $value, $default);
    
    foreach ($values as $key => $value) {
        $values[$key] = mos_array_get($value, $indexes[1], $default);
    }

    return mos_array_get($values, $indexes[0], $default);
}

/**
 * gets the value for a field collection field item
 *
 * @param mixed[] $items is the array of items from the field tpl
 * @param string $name is the name of teh field (mahcine name)
 * @param mixed $default is what to return if the item doesn't exist
 * @return mixed the value for teh field
 */
function mos_field_item_get($items, $name, $default = null) {
    foreach ($items as $entity) {
        if (is_array($entity) && array_key_exists('entity', $entity)) {
            break;
        }
    }

    foreach (mos_array_first($entity['entity']['field_collection_item']) as $key => $item) {
        if ('field_' . mos_preg_remove('/^field/', $name) !== $key) {
            continue;
        }

        if ($item['#field_type'] !== 'field_collection') {
            return $item['#items'][0]['value'];
        }

        return $item;
    }

    if (func_num_args() > 2) {
        return $default;
    }

    trigger_error('Failed to get field data for ' . mos_spy($name) . '.');
}

/**
 * sets a global var - its a god awful hack i had to implement because i have no idea wtf drupal is doing with field collections
 */
function mos_global_set($k, $v) {
    $g = '______MOS______';

    if (!array_key_exists($g, $GLOBALS)) {
        $GLOBALS[$g] = array();
    }

    $GLOBALS['____MOS____'][$k] = $v;
}

/**
 * gets a global var - its a god awful hack i had to implement because i have no idea wtf drupal is doing with field collections
 */
function mos_global_get($k, $d = null) {
    return func_num_args() > 1 ? mos_array_get($GLOBALS, $k, $d) : mos_array_get($GLOBALS, $k);
}

/**
 * Gets the value at the given key in an array. If the key does not exist, a default is returned.
 *
 * @author chase <cisabelle@mos.org>
 * @param array $array is the array.
 * @param mixed $key is the key.
 * @param mixed $default is the default.
 * @return mixed either the value at the key if it exists, or the default value.
 */
function mos_array_get($array, $key, $default) {
    return array_key_exists($key, $array) ? $array[$key] : $default;
}

/**
 * Gets the first value of an array. Like array_shift, except
 * will not modify the array and will not throw warning when
 * arg is nested.
 *
 * @author chase <cisabelle@mos.org>
 * @param array $array is the array.
 * @return mixed the first value in the array.
 */
function mos_array_first($array) {
    return array_shift($array);
}

/**
 * Gets the last value of an array. Like array_pop, except
 * will not modify the array and will not throw warning when
 * arg is nested.
 *
 * @author chase <cisabelle@mos.org>
 * @param array $array is the array.
 * @return mixed the last value in the array.
 */
function mos_array_last($array) {
    return array_pop($array);
}

/**
 * Gets the first key of an array.
 *
 * @author chase <cisabelle@mos.org>
 * @param array $array is the array.
 * @return mixed the first key in the array.
 */
function mos_array_first_key($array) {
    return mos_array_first(array_keys($array));
}

/**
 * Gets the last key of an array.
 *
 * @author chase <cisabelle@mos.org>
 * @param array $array is the array.
 * @return mixed the last key in the array.
 */
function mos_array_last_key($array) {
    return mos_array_last(array_keys($array));
}

/**
 * If value is not an array then value is wrapped in array.
 *
 * @author chase <cisabelle@mos.org>
 * @param mixed $value is the value.
 * @return mixed[] the value as an array if not already array.
 */
function mos_array_value($value) {
    return is_array($value) ? $value : array($value);
}

/**
 * converts object to assic array
 *
 * @author chase <cisabelle@mos.org>
 * @param object $o is the object
 * @return mixed[] the assoc arrya
 */
function mos_object_to_array($o) {
    return json_decode(json_encode($o), 1);
}

/**
 * converts an assoc array to an object
 *
 * @author chase <cisabelle@mos.org>
 * @param mixed[] $a is the array
 * @return object the object
 */
function mos_array_to_object($a) {
    return json_decode(json_encode($a));
}

/**
 * Reads a JSON file, parses the contents, and returns the data as an object/array.
 *
 * @author chase <cisabelle@mos.org>
 * @param string $file is the path to the file.
 * @param bool $array is a flag to tell whether to return object or array (defaults to false).
 * @return mixed the data parsed into an object or array.
 */
function mos_read_json($file, $array = false) {
    return json_decode(file_get_contents($file), $array);
}

/**
 * Writes a data structure as JSON to a file.
 *
 * @author chase <cisabelle@mos.org>
 * @param string $file is the path to the file.
 * @param mixed $data is the data structure (array or object).
 * @return int the number of bytes written (or FALSE upon failure).
 */
function mos_write_json($file, $data) {
    return file_put_contents($file, json_encode($data));
}

/**
 * poops out an exposed format of a value
 *
 * @author chase <cisabelle@mos.org>
 * @param mixed $x is the value
 * @return string the exposed value
 */
function mos_spy($x) {
    switch (1) {
        case is_string($x):
            return '"' . $x . '"';
        case is_numeric($x):
            return $x;
        case is_null($x):
            return 'NULL';
        case is_bool($x):
            return $x ? 'TRUE' : 'FALSE';
        case is_resource($x):
            return '(' . get_resource_type($x) . ')' . "$x";
        case is_object($x):
            return get_class($x);
        case is_array($x):
            break;
        case is_scalar($x):
        default:
            return "$x";
    }

    switch (count($x)) {
        case 0:
            return '[]';
        case 1:
            return '[' . mos_spy(mos_array_first_key($x)) . ' => ' . mos_spy(array_pop($x)) . ']';
        case 2:
            return '[' . mos_spy(mos_array_first_key($x)) . ' => ' . mos_spy(array_shift($x)) . ', '
                       . mos_spy(mos_array_last_key($x))  . ' => ' . mos_spy(array_pop($x))   . ']';
        default:
            return '[' . mos_spy(mos_array_first_key($x)) . ' => ' . mos_spy(array_shift($x)) .
                   ', ..., ' .
                         mos_spy(mos_array_last_key($x)) .  ' => ' . mos_spy(array_pop($x)) . ']';
    }

    trigger_error('Something terrible has happened.');
}

/**
 * Loads a drupal block in a single function call
 *
 * @author Arika Prime
 * @param string $blockId Drupal block ID.
 * @return object a drupal rendered object that can be printed in a tpl.php file.
 */
function mos_load_block($block_id) {
    return drupal_render(_block_get_renderable_array(_block_render_blocks(array(block_load('views', $block_id)))));
}

/**
 * Loads a drupal field collection in a single function call
 *
 * @author Arika Prime
 * @param string $fieldCollectionName drupal field collection machine name
 * @param object $node is teh node object - leave null for current node.
 * @return mixed[] The value of the field collection. This will return value in the order that they are entered in Drupal Admin
 */
function mos_getFieldCollectionValue($fieldCollectionName, $node = null) {
    $node            = mos_node($node);
    $valueArray      = array();
    $fieldCollection = field_get_items('node', $node, $fieldCollectionName);

    if (!empty($fieldCollection)) {
        foreach ($fieldCollection as $fieldCollectionKey => $fieldCollectionValue) {
            $field     = field_view_value('node', $node, $fieldCollectionName, $fieldCollectionValue);
            $fieldName = array();

            // Parse array to pull out the field labels of the fields within the field collection
            $fieldCollectionItemIndex = $fieldCollectionKey + 1;

            foreach ($field['entity']['field_collection_item'] as $id => $fieldCollection) {
                foreach ($fieldCollection as $key => $value) {
                    if (!preg_match("/\#/", $key)) {
                        $fieldName[] = $key;
                    }
                }

                // load the field collection item entity
                $fieldCollectionItem = field_collection_item_load($id);

                // wrap the entity and make it easier to get the values of fields
                $fieldWrapper = entity_metadata_wrapper('field_collection_item', $fieldCollectionItem);

                // store values in array which will be returned at end of function
                foreach ($fieldName as $index => $name) {
                    $valueArray[$fieldCollectionKey][$name] = $fieldWrapper->$name->value();
                }
            }
        }
    }

    return $valueArray;
}

/**
 * wrapper for Arika's mos_getFieldCollectionValue() - makes data more understandable
 *
 * @author chase <cisabelle@mos.org>
 * @param string $name is the machine name for the field collection - optionally leave out field_ prefix to minimize code
 * @param object $node is the node - leave null for current node
 * @return array the data as a simple assoc array
 */
function mos_field_collection_fields($name, $node = null) {
    return mos_make_field_data_useful(mos_object_to_array(mos_getFieldCollectionValue(mos_field_name($name), $node)));

    $fields = array();

    foreach (mos_object_to_array(mos_getFieldCollectionValue(mos_field_name($name), $node)) as $index => $field) {
        $values = array();

        foreach ($field as $name => $value) {
            $values[$name] = mos_make_field_data_useful($value);
        }

        ksort($values);

        $fields[$index] = $values;
    }

    return $fields;
}

/**
 * checks if an array is assoc or indexed
 *
 * @param mixed[] $a is the array
 * @return bool
 */
function mos_array_indexed($a) {
    return range(0, count($a) - 1) === array_keys($a);
}

function mos_array_key($a, $v, $s = 1) {
    foreach ($a as $k => $x) {
        if ($s && $x === $v || !$s && $x == $v) {
            return $k;
        }
    }

    return null;
}

/**
 * adds useful data to field valeu array
 *
 * @author chase <cisabelle@mos.org>
 * @param string $name is the machine name of the field
 * @param mixed[] $value is the data array
 * @return mixed[] the data array with extra useful data
 */
function mos_make_field_data_useful($value) {
    if (!is_array($value)) {
        return $value;
    }

    if (isset($value['und'])) {
        return mos_make_field_data_useful($value['und'][0]);
    }

    if (!empty($value['uri'])) {
        $value['url'] = file_create_url($value['uri']);
    }

    ksort($value);

    foreach ($value as $k => $v) {
        $v = mos_make_field_data_useful($v);

        if (in_array($k, array(), 1)) {
            $v = mos_array_first($v);
        }

        $value[$k] = $v;
    }

    return $value;
}

/**
 * check if field name has field_ prefix - if not it adds it
 *
 * @author chase <cisabelle@mos.org>
 * @param string $name is the field mahcine nae,
 * @return string the actual field name
 */
function mos_field_name($name) {
    return preg_match('/^field_/', $name) ? $name : 'field_' . $name;
}

/**
 * gets data for node field
 *
 * @author chase <cisabelle@mos.org>
 * @param string $name is the field machine name - optionally leave out the field_ prefix to minimize code
 * @param object $node is the node object - leave null for current node
 * @return array the field data
 */
function mos_field_items($name, $node = null) {
    $items = array();

    foreach (field_get_items(mos_node($node), mos_field_name($name)) as $key => $item) {
        $items[$key] = mos_make_field_data_useful($item, $node);
    }

    return $items;
}


/**
 * if node is null get current node
 *
 * @author chase <cisabelle@mos.org>
 * @param object|null $node is the node
 * @return object the node
 */
function mos_node($node = null) {
    return is_null($node) ? menu_get_object() : $node;
}


/**
 * glue keys to values
 *
 * @param string[] $a is the array
 * @param string $g is the glue
 * @param bool $r is to glue the values to the keys (reversed)
 * @return string[]
 */
function mos_array_glue($a, $g = '=', $r = false) {
    foreach ($a as $k => $v) {
        $a[$k] = ($r ? $v : $k) . $g . ($r ? $k : $v);
    }

    return $a;
}

function mos_array_unglue($a, $g = '=', $r = false) {
    $b = array();

    foreach ($a as $v) {
        $a = explode($g, $v);

        $k = $r ? array_pop($a) : array_shift($a);
        $v = $r ? array_shift($a) : array_pop($a);

        $b[$k] = $v;
    }

    return $b;
}

/**
 * this is a weird but useful function - it basically generates a really generic tag
 * you can use it like mos_simple_tag('img', ['src' => '/my/image.jpg', 'alt' => 'bla']) returns '<img src="/my/image.jpg" alt="bla" />'
 * or mos_simple_tag('a', ['href' => 'mos.org'], 'click me') returns '<a href="mos.org">click me</a>'
 *
 * @param string $tag is teh tag name (i.e. img, a, p)
 * @param string[] $attributes is teh array of data attributes
 * @param string $body is the body of the element
 * @return string the generated tag text
 */
function mos_simple_tag($tag, $attributes = array(), $body = '') {
    foreach ($attributes as $key => $value) {
        $attributes[$key] = $key . '="' . addslashes($value);
    }

    return '<' . $tag . ' ' . implode(' ', $attributes) . (empty($body) ? ' /' : '>' . $body . '</' . $tag) . '>';
}

function mos_tpl_field_value($element) {
    switch ($type = $element['#field_type']) {
        case 'list_boolean':
            return $element['#items'][0]['value'] ? true : false;
        case 'text':
        case 'text_long':
            return $element['#items'][0]['safe_value'];
        case 'image':
            return func_num_args() > 1 ? $element['#items'][0][func_get_arg(1)] : file_create_url($element['#items'][0]['uri']);
    }

    trigger_error('Cannot get value for field type ' . mos_spy($type) . '.');
}

function mos_tpl_field_item($items, $name) {
    $item = mos_array_first($items[0]['entity']['field_collection_item']);

    return $item[$name];
}


/**
* Get Slideshow data
**/
function mos_responsive_slider($node = null) {
    $slider = array();

    foreach (mos_field_collection_fields('slide', $node) as $slide) {
        $slider[] = array(
            'url'      => $slide['field_slide_link']['url'],
            'link'     => $slide['field_slide_link']['title'],
            'credit'   => $slide['field_slide_credit']['value'],
            'title'    => $slide['field_slide_title']['value'],
            'subtitle' => $slide['field_slide_subtitle']['value'],
            'image'    => $slide['field_slide_image']['url'],
            'alt'      => $slide['field_slide_image']['alt']
        );
    }

    return $slider;
};

?>