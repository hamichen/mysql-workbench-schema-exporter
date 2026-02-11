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
 * IMPORTANT: This requires that the doctrine2-exporter package is installed:
 *   php composer.phar require --dev mysql-workbench-schema-exporter/doctrine2-exporter
 * 
 * Requirements:
 *   - PHP 8.0 or higher
 *   - Doctrine ORM 2.10 or higher (for Attribute support)
 *   - doctrine2-exporter package
 * 
 * Usage:
 *   If you have doctrine2-exporter installed, you can use the CLI:
 *   php bin/mysql-workbench-schema-export --export=doctrine2-attribute example/data/test.mwb output/
 * 
 *   Or use the composer script:
 *   php composer.phar require --dev mysql-workbench-schema-exporter/doctrine2-exporter
 */

// bootstrap
require_once dirname(__FILE__).'/util.php';

// NOTE: This example requires doctrine2-exporter to be installed
// use MwbExporter\Formatter\Doctrine2\Attribute\Formatter;

use MwbExporter\Bootstrap;
use MwbExporter\Logger\LoggerConsole;
use MwbExporter\Model\Document;

echo "Doctrin2 Attribute Export Example\n";
echo "==================================\n\n";

echo "To use Doctrine 2 Attribute format, you need to:\n\n";

echo "1. Install the doctrine2-exporter package:\n";
echo "   php composer.phar require --dev mysql-workbench-schema-exporter/doctrine2-exporter\n\n";

echo "2. Use the CLI command:\n";
echo "   php bin/mysql-workbench-schema-export --export=doctrine2-attribute example/data/test.mwb ./output\n\n";

echo "3. Or verify available exporters:\n";
echo "   php bin/mysql-workbench-schema-export --list-exporter\n\n";

echo "Note: The 'doctrine2-attribute' formatter is provided by the doctrine2-exporter package,\n";
echo "not by the base mysql-workbench-schema-exporter framework.\n";

// Formatter setup options (for reference)
//
// Please refer to the formatter documentation for a list of available setup options:
// https://github.com/mysql-workbench-schema-exporter/doctrine2-exporter#formatter-setup-options

$setup = array(
    // Note: Actual configuration keys depend on doctrine2-exporter implementation
    // 'useAttributePrefix'          => 'ORM\\',
    // 'bundleNamespace'             => 'App\\Entity',
    // 'entityNamespace'             => 'Entity',
    // 'repositoryNamespace'         => 'Repository',
    // 'useAutomaticRepository'      => true,
    // 'indentation'                 => 4,
    // 'filename'                    => '%entity%.%extension%',
);

echo "For more information, see ATTRIBUTE-SETUP-GUIDE.md\n";

