<?php

/*
 * Simple Attribute Class Test (without composer)
 */

// Load classes manually
require_once __DIR__ . '/../lib/MwbExporter/Object/Base.php';
require_once __DIR__ . '/../lib/MwbExporter/Object/Attribute.php';

use MwbExporter\Object\Attribute;

echo "=== Testing MwbExporter\\Object\\Attribute Class ===\n\n";

// Test 1: Simple attribute
echo "Test 1 - Simple attribute:\n";
$attr1 = new Attribute('Entity');
echo "Result: " . $attr1 . "\n";
echo "Expected: #[Entity]\n\n";

// Test 2: Attribute with string parameter
echo "Test 2 - Attribute with string parameter:\n";
$attr2 = new Attribute('Table', 'users');
echo "Result: " . $attr2 . "\n";
echo "Expected: #[Table(\"users\")]\n\n";

// Test 3: Attribute with named parameters
echo "Test 3 - Attribute with named parameters:\n";
$attr3 = new Attribute('Table', array(
    'name' => 'users',
    'schema' => 'public'
));
echo "Result: " . $attr3 . "\n";
echo "Expected: #[Table(name: \"users\", schema: \"public\")]\n\n";

// Test 4: Attribute with ORM prefix
echo "Test 4 - Attribute with ORM prefix:\n";
$attr4 = new Attribute('ORM\Entity');
echo "Result: " . $attr4 . "\n";
echo "Expected: #[ORM\\Entity]\n\n";

// Test 5: Attribute with boolean values
echo "Test 5 - Attribute with boolean values:\n";
$attr5 = new Attribute('Column', array(
    'nullable' => false,
    'unique' => true
));
echo "Result: " . $attr5 . "\n";
echo "Expected: #[Column(nullable: false, unique: true)]\n\n";

// Test 6: Attribute with null value
echo "Test 6 - Attribute with null value:\n";
$attr6 = new Attribute('Column', array(
    'type' => 'string',
    'default' => null
));
echo "Result: " . $attr6 . "\n";
echo "Expected: #[Column(type: \"string\", default: null)]\n\n";

// Test 7: Attribute with numeric values
echo "Test 7 - Attribute with numeric values:\n";
$attr7 = new Attribute('Column', array(
    'length' => 255,
    'precision' => 10,
    'scale' => 2
));
echo "Result: " . $attr7 . "\n";
echo "Expected: #[Column(length: 255, precision: 10, scale: 2)]\n\n";

// Test 8: Attribute with array values
echo "Test 8 - Attribute with array values:\n";
$attr8 = new Attribute('Index', array(
    'columns' => array('email', 'status')
));
echo "Result: " . $attr8 . "\n";
echo "Expected: #[Index(columns: [\"email\", \"status\"])]\n\n";

echo "=== All tests completed ===\n";
