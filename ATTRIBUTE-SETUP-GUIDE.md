# PHP 8 Attribute 設定指南

## 概述

MySQL Workbench Schema Exporter 現在支持 PHP 8 的 Attribute 語法，可以生成使用 `#[Attribute]` 語法的代碼，取代舊的註解（Annotation）語法 `@Annotation`。

## 系統需求

- **PHP 8.0 或更高版本** (Attribute 是 PHP 8.0 引入的新特性)
- Doctrine ORM 2.10+ (如果使用 Doctrine)
- Composer

## 如何設定轉出 Attribute

### 1. 使用命令行（CLI）

```bash
# 基本用法
php bin/mysql-workbench-schema-export --export=doctrine2-attribute example/data/test.mwb ./output

# 壓縮輸出
php bin/mysql-workbench-schema-export --export=doctrine2-attribute --zip example/data/test.mwb
```

### 2. 使用配置文件（export.json）

創建一個 `export.json` 文件：

```json
{
    "export": "doctrine2-attribute",
    "zip": false,
    "dir": "output",
    "params": {
        "backupExistingFile": true,
        "skipPluralNameChecking": false,
        "enhanceManyToManyDetection": true,
        "bundleNamespace": "App\\Entity",
        "entityNamespace": "Entity",
        "repositoryNamespace": "Repository",
        "useAttributePrefix": "ORM\\",
        "useAutomaticRepository": true,
        "indentation": 4,
        "filename": "%entity%.%extension%",
        "quoteIdentifier": false
    }
}
```

然後執行：

```bash
php bin/mysql-workbench-schema-export --config=export.json example/data/test.mwb
```

### 3. 使用 PHP 程式碼

```php
<?php

require_once 'vendor/autoload.php';

use MwbExporter\Formatter\Doctrine2\Attribute\Formatter;
use MwbExporter\Bootstrap;

$setup = array(
    Formatter::CFG_USE_LOGGED_STORAGE      => true,
    Formatter::CFG_INDENTATION             => 4,
    Formatter::CFG_ATTRIBUTE_PREFIX        => 'ORM\\',
    Formatter::CFG_BUNDLE_NAMESPACE        => 'App\\Entity',
    Formatter::CFG_ENTITY_NAMESPACE        => 'Entity',
    Formatter::CFG_AUTOMATIC_REPOSITORY    => true,
);

$bootstrap = new Bootstrap();
$document = $bootstrap->export(
    new Formatter(), 
    'model.mwb', 
    './output', 
    $setup
);
```

## 重要配置參數

### useAttributePrefix
- **預設值**: `"ORM\\"`
- **說明**: Attribute 的前綴，用於生成如 `#[ORM\Entity]` 的語法
- **範例**: 
  - `"ORM\\"` → `#[ORM\Entity]`
  - `""` → `#[Entity]`

### bundleNamespace
- **預設值**: `""`
- **說明**: Symfony Bundle 的命名空間
- **範例**: `"App\\Entity"`

### entityNamespace
- **預設值**: `""`
- **說明**: Entity 類的命名空間
- **範例**: `"Entity"`

### repositoryNamespace
- **預設值**: `""`
- **說明**: Repository 類的命名空間
- **範例**: `"Repository"`

### useAutomaticRepository
- **預設值**: `true`
- **說明**: 自動為實體生成 Repository
- **值**: `true` 或 `false`

## 輸出範例

### Annotation 格式（舊）

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email;
}
```

### Attribute 格式（新）

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private string $email;
}
```

## Attribute 的優勢

1. **原生 PHP 語法**: 不需要解析 docblock
2. **IDE 支援**: 更好的自動完成和類型檢查
3. **編譯時驗證**: 在 PHP 編譯時就能檢查錯誤
4. **更好的性能**: 不需要運行時解析註解
5. **更清晰的代碼**: 語法更簡潔易讀

## 常見問題

### Q: 我可以混用 Annotation 和 Attribute 嗎？
**A**: 不建議。請選擇一種格式並在整個項目中保持一致。

### Q: 我的 PHP 版本低於 8.0 怎麼辦？
**A**: 使用 `doctrine2-annotation` 格式代替 `doctrine2-attribute`。

### Q: 如何從 Annotation 遷移到 Attribute？
**A**: 
1. 確保 PHP 版本 >= 8.0
2. 更新 Doctrine 到 2.10+
3. 重新生成代碼使用 `doctrine2-attribute` 格式
4. 測試你的應用程式

### Q: 支援哪些 exporter？
**A**: 目前 Attribute 主要用於 Doctrine 2，其他 exporter 請查看官方文檔。

## 測試你的設定

使用提供的測試腳本來驗證 Attribute 類的工作：

```bash
php example/test-attribute.php
```

## 更多資源

- [PHP 8 Attributes 官方文檔](https://www.php.net/manual/en/language.attributes.overview.php)
- [Doctrine ORM Attributes 文檔](https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/attributes-reference.html)
- [Doctrine2 Exporter GitHub](https://github.com/mysql-workbench-schema-exporter/doctrine2-exporter)
