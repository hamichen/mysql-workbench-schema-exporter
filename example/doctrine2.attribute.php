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

/**
 * Export MySQL Workbench Model to Doctrine 2.0 PHP 8 Attribute Classes
 * 
 * This example shows how to export a MySQL Workbench model to Doctrine 2.0
 * entity classes using PHP 8 Attributes instead of annotations.
 * 
 * Requirements:
 *   - PHP 8.0 or higher
 *   - Doctrine ORM 2.10 or higher (for Attribute support)
 * 
 * Usage:
 *   php example/doctrine2.attribute.php
 */

// bootstrap
require_once dirname(__FILE__).'/util.php';

use MwbExporter\Formatter\Doctrine2\Attribute\Formatter;
use MwbExporter\Bootstrap;
use MwbExporter\Logger\LoggerConsole;
use MwbExporter\Model\Document;

// Formatter setup options
// 
// Please refer to the formatter documentation for a list of available setup options:
// https://github.com/mysql-workbench-schema-exporter/doctrine2-exporter#formatter-setup-options
$setup = array(
    Formatter::CFG_USE_LOGGED_STORAGE           => true,
    Formatter::CFG_INDENTATION                  => 4,
    Formatter::CFG_FILENAME                     => '%entity%.%extension%',
    Formatter::CFG_ATTRIBUTE_PREFIX             => 'ORM\\',  // Use ORM\ prefix for attributes
    Formatter::CFG_BUNDLE_NAMESPACE             => '',
    Formatter::CFG_ENTITY_NAMESPACE             => '',
    Formatter::CFG_REPOSITORY_NAMESPACE         => '',
    Formatter::CFG_AUTOMATIC_REPOSITORY         => true,
    Formatter::CFG_SKIP_GETTER_SETTER           => false,
    Formatter::CFG_GENERATE_ENTITY_SERIALIZATION => true,
    Formatter::CFG_QUOTE_IDENTIFIER             => false,
);

// Lets get some coffee
echo sprintf("Exporting '%s' to Doctrine 2.0 Attribute Schema.\n\n", basename($filename));

// Create bootstrap and use a logger
$bootstrap = new Bootstrap();
$bootstrap->setLogger(new LoggerConsole());

// Load document and export to specified directory
$document = $bootstrap->export(new Formatter(), $filename, $dir, $setup);

echo sprintf("\n\nLook at the generated code in '%s'.\n", $dir);
echo "Done.\n";
