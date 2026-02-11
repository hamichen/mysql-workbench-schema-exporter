<?php

/*
 * Example: Using MwbExporter\Object\Attribute class
 * 
 * This example demonstrates how to use the Attribute class to generate
 * PHP 8 attribute syntax.
 */

require_once __DIR__ . '/../lib/autoload.php';

use MwbExporter\Object\Attribute;

echo "=== PHP 8 Attribute Class Examples ===\n\n";

// Example 1: Simple attribute without parameters
echo "1. Simple attribute:\n";
$attr1 = new Attribute('Entity');
echo $attr1 . "\n\n";
// Output: #[Entity]

// Example 2: Attribute with single parameter
echo "2. Attribute with single parameter:\n";
$attr2 = new Attribute('Table', 'users');
echo $attr2 . "\n\n";
// Output: #[Table("users")]

// Example 3: Attribute with named parameters
echo "3. Attribute with named parameters:\n";
$attr3 = new Attribute('Table', array(
    'name' => 'users',
    'schema' => 'public'
));
echo $attr3 . "\n\n";
// Output: #[Table(name: "users", schema: "public")]

// Example 4: Attribute with prefix
echo "4. Attribute with ORM prefix:\n";
$attr4 = new Attribute('ORM\Entity');
echo $attr4 . "\n\n";
// Output: #[ORM\Entity]

// Example 5: Attribute with complex parameters
echo "5. Attribute with complex parameters:\n";
$attr5 = new Attribute('ORM\Table', array(
    'name' => 'users',
    'indexes' => array(
        array('name' => 'email_idx', 'columns' => array('email')),
        array('name' => 'status_idx', 'columns' => array('status'))
    )
));
echo $attr5 . "\n\n";
// Output: #[ORM\Table(name: "users", indexes: [["name": "email_idx", "columns": ["email"]], ["name": "status_idx", "columns": ["status"]]])]

// Example 6: Attribute with boolean values
echo "6. Attribute with boolean values:\n";
$attr6 = new Attribute('ORM\Column', array(
    'type' => 'string',
    'length' => 255,
    'nullable' => false,
    'unique' => true
));
echo $attr6 . "\n\n";
// Output: #[ORM\Column(type: "string", length: "255", nullable: false, unique: true)]

// Example 7: Attribute with null value
echo "7. Attribute with null value:\n";
$attr7 = new Attribute('ORM\Column', array(
    'type' => 'string',
    'default' => null
));
echo $attr7 . "\n\n";
// Output: #[ORM\Column(type: "string", default: null)]

// Example 8: Multiple attributes for a property
echo "8. Multiple attributes for a property:\n";
$attributes = array(
    new Attribute('ORM\Column', array('type' => 'string', 'length' => 255)),
    new Attribute('Assert\NotBlank'),
    new Attribute('Assert\Email')
);
foreach ($attributes as $attr) {
    echo $attr . "\n";
}
echo "\n";
// Output:
// #[ORM\Column(type: "string", length: "255")]
// #[Assert\NotBlank]
// #[Assert\Email]

// Example 9: Multiline attribute (useful for complex configurations)
echo "9. Multiline attribute:\n";
$attr9 = new Attribute('ORM\JoinTable', array(
    'name' => 'user_groups',
    'joinColumns' => array(
        array('name' => 'user_id', 'referencedColumnName' => 'id')
    ),
    'inverseJoinColumns' => array(
        array('name' => 'group_id', 'referencedColumnName' => 'id')
    )
), array('multiline' => true));
echo $attr9 . "\n\n";
// Output: (formatted across multiple lines)

echo "=== Comparison: Annotation vs Attribute ===\n\n";

echo "Annotation syntax (old):\n";
echo "/**\n";
echo " * @ORM\\Entity\n";
echo " * @ORM\\Table(name=\"users\")\n";
echo " */\n\n";

echo "Attribute syntax (PHP 8+):\n";
echo "#[ORM\\Entity]\n";
echo "#[ORM\\Table(name: \"users\")]\n\n";

echo "Advantages of Attributes:\n";
echo "- Native PHP syntax (no docblock parsing needed)\n";
echo "- IDE support and autocomplete\n";
echo "- Type checking at compile time\n";
echo "- Better performance\n";
echo "- Cleaner, more readable code\n";
