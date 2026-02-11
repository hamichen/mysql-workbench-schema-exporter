# Attribute 類實作說明

## 概述

本項目提供了一個 PHP 8 相容的 `Attribute` 類，用於生成 PHP 8 的 Attribute 語法 `#[AttributeName(...)]`。

## 文件位置

- **類文件**: [lib/MwbExporter/Object/Attribute.php](../lib/MwbExporter/Object/Attribute.php)
- **基類**: [lib/MwbExporter/Object/Base.php](../lib/MwbExporter/Object/Base.php) 
- **對標類**: [lib/MwbExporter/Object/Annotation.php](../lib/MwbExporter/Object/Annotation.php)

## 類結構

```
MwbExporter\Object\Base
    └── MwbExporter\Object\Attribute
```

## 主要方法

### `__construct($attribute, $content = null, $options = array())`

建立 Attribute 實例。

**參數:**
- `$attribute` (string): Attribute 名稱，例如 `'Entity'` 或 `'ORM\Entity'`
- `$content` (mixed): Attribute 內容，可以是字符串、數字、布爾值、陣列等
- `$options` (array): 選項數組，支持:
  - `raw` (bool): 是否使用原始內容，預設 `false`
  - `indentation` (string): 縮進字符串，預設 4 個空格
  - `multiline` (bool): 是否啟用多行格式
  - `wrapper` (string): 行包裝格式

**範例:**
```php
$attr = new Attribute('Entity');
$attr = new Attribute('Table', 'users');
$attr = new Attribute('Column', array('type' => 'string', 'length' => 255));
```

### `asCode($value, $topLevel = true, $inlineList = false)`

將值轉換為 PHP Attribute 代碼。

**支持的類型:**
- **布爾值**: `true` → `true`, `false` → `false`
- **字符串**: `"text"` → `"text"` (自動轉義)
- **整數/浮點數**: `255` → `255`
- **NULL**: `null` → `null`
- **陣列**: `['a', 'b']` → `[["a", "b"]]` (嵌套) 或 `(["a", "b"])` (top-level)
- **Attribute/Annotation 對象**: 自動轉換為字符串形式

### `getAttributeName()` / `setAttributeName($attribute)`

獲取或設置 Attribute 名稱。

### `__toString()`

將 Attribute 轉換為 PHP 代碼字符串。

## 使用示例

### 1. 簡單 Attribute

```php
use MwbExporter\Object\Attribute;

$attr = new Attribute('Entity');
echo $attr; // 輸出: #[Entity]
```

### 2. 帶參數的 Attribute

```php
$attr = new Attribute('Table', 'users');
echo $attr; // 輸出: #[Table("users")]
```

### 3. 命名參數

```php
$attr = new Attribute('Table', array(
    'name' => 'users',
    'schema' => 'public'
));
echo $attr; // 輸出: #[Table(name: "users", schema: "public")]
```

### 4. 複雜結構

```php
$attr = new Attribute('ORM\JoinTable', array(
    'name' => 'user_groups',
    'joinColumns' => array(
        array('name' => 'user_id')
    )
));
echo $attr; 
// 輸出: #[ORM\JoinTable(name: "user_groups", joinColumns: [["name": "user_id"]])]
```

### 5. 多行格式

```php
$attr = new Attribute('ORM\Table', array(
    'name' => 'users',
    'indexes' => array('email', 'status')
), array('multiline' => true));
echo $attr; // 格式化為多行
```

## 與 Annotation 的區別

### Annotation 語法（舊，PHP 5.4+）
```php
use MwbExporter\Object\Annotation;

$ann = new Annotation('Entity');
echo $ann; // @Entity
```

### Attribute 語法（新，PHP 8.0+）
```php
use MwbExporter\Object\Attribute;

$attr = new Attribute('Entity');
echo $attr; // #[Entity]
```

## 測試

運行測試腳本驗證實現：

```bash
php example/test-attribute.php
```

## 與 Doctrine2 Exporter 的集成

`Attribute` 類是輔助工具，實際的 Doctrine 2 Attribute Formatter 應由 `doctrine2-exporter` 包提供。

該類設計用於：
1. 直接在代碼中構建 Attribute
2. 被 Doctrine 2 Exporter 內部使用
3. 支持 IDE 和工具鏈集成

## 特性

✅ 支持 PHP 8.0+ Attribute 語法  
✅ 自動轉義字符串值  
✅ 支持命名和位置參數  
✅ 嵌套陣列支援  
✅ 多種數據類型支援  
✅ 多行格式化  
✅ 與 Annotation 類相同的接口  

## 性能和兼容性

- **性能**: 零運行時開銷，只在構建時使用
- **兼容性**: 需要 PHP 8.0+ (Attribute 特性)
- **依賴**: 僅依賴 MwbExporter\Object\Base
- **可用性**: 可在任何 PHP 8+ 項目中使用

## 許可證

MIT License - 詳見本項目的 LICENSE 文件
