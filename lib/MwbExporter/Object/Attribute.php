<?php

/*
 * The MIT License
 *
 * Copyright (c) 2012-2014 Toha <tohenk@yahoo.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace MwbExporter\Object;

/**
 * PHP 8 Attribute class for generating attribute syntax (#[Attribute]).
 * 
 * This class provides support for generating PHP 8.0+ attributes,
 * which use the #[AttributeName(...)] syntax instead of the older
 * annotation syntax (@AttributeName(...)).
 */
class Attribute extends Base
{
    /**
     * @var string
     */
    protected $attribute = null;

    /**
     * Constructor.
     *
     * @param string $attribute  Attribute name
     * @param mixed  $content    Object content
     * @param array  $options    Object options
     */
    public function __construct($attribute, $content = null, $options = array())
    {
        parent::__construct($content, $options);
        $this->attribute = $attribute;
    }

    /**
     * Decorate code with PHP 8 attribute syntax.
     *
     * @param string $code  The generated code
     * @return string
     */
    protected function decorateCode($code)
    {
        return '#['.$this->attribute.$code.']';
    }

    /**
     * Convert value as code equivalent.
     *
     * @param mixed $value     The value
     * @param bool  $topLevel  Is this method being called from top level
     * @param bool  $inlineList Whether to use inline list syntax
     * @return string
     */
    public function asCode($value)
    {
        $topLevel = true;
        if (func_num_args() > 1 && false === func_get_arg(1)) {
            $topLevel = false;
        }

        $inlineList = false;
        if (func_num_args() > 2) {
            $inlineList = func_get_arg(2);
        }

        // Handle null at top level - return empty string for empty attributes
        if (null === $value && $topLevel) {
            return '';
        }

        if ($value instanceof Attribute) {
            $value = (string) $value;
        } elseif ($value instanceof Annotation) {
            // Support conversion from annotation to attribute
            $value = (string) $value;
        } elseif (is_bool($value)) {
            $value = $value ? 'true' : 'false';
            if ($topLevel) {
                $value = '(' . $value . ')';
            }
        } elseif (is_null($value)) {
            $value = 'null';
            if ($topLevel) {
                $value = '(' . $value . ')';
            }
        } elseif (is_string($value)) {
            // Escape special characters in strings
            $value = '"'.addslashes($value).'"';
            if ($topLevel) {
                $value = '(' . $value . ')';
            }
        } elseif (is_int($value) || is_float($value)) {
            $value = (string) $value;
            if ($topLevel) {
                $value = '(' . $value . ')';
            }
        } elseif (is_array($value)) {
            $tmp = array();
            $useKey = !$this->isKeysNumeric($value);
            
            foreach ($value as $k => $v) {
                // Convert nested values
                $v = $this->asCode($v, false, true);
                
                if ($useKey) {
                    // Named parameters in PHP 8 attributes use colon syntax
                    $tmp[] = sprintf('%s: %s', $k, $v);
                } else {
                    $tmp[] = $v;
                }
            }
            
            $multiline = $this->getOption('multiline') && count($tmp) > 1;
            $separator = $multiline ? ",\n" : ', ';
            $value = implode($separator, $tmp).($multiline ? "\n" : '');
            
            if ($topLevel) {
                // Top level uses parentheses for attribute arguments
                $value = sprintf('(%s)', $value);
            } else {
                // Nested arrays use square brackets
                $value = sprintf('[%s]', $value);
            }
            
            if ($multiline) {
                $value = $this->wrapLines($value, 1);
            }
        }

        return $value;
    }

    /**
     * Get the attribute name.
     *
     * @return string
     */
    public function getAttributeName()
    {
        return $this->attribute;
    }

    /**
     * Set the attribute name.
     *
     * @param string $attribute  The attribute name
     * @return \MwbExporter\Object\Attribute
     */
    public function setAttributeName($attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }
}
