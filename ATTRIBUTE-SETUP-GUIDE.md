# PHP 8 Attribute 設定指南

## 概述

MySQL Workbench Schema Exporter 現在支持 PHP 8 的 Attribute 語法，可以生成使用 `#[Attribute]` 語法的代碼，取代舊的註解（Annotation）語法 `@Annotation`。

## 系統需求

- **PHP 8.0 或更高版本** (Attribute 是 PHP 8.0 引入的新特性)
- **Doctrine ORM 2.10+** (如果使用 Doctrine，需要支持 Attribute 的版本)
- **docker2-exporter** 已安裝 (doctrine2-exporter package)
- Composer

## 安裝步驟

### 1. 安裝必要的包

```bash
# 安裝基礎框架
php composer.phar require --dev mysql-workbench-schema-exporter/mysql-workbench-schema-exporter

# 安裝 Doctrine 2 Exporter（包含 Attribute 支援）
php composer.phar require --dev mysql-workbench-schema-exporter/doctrine2-exporter
```

### 2. 驗證安裝

```bash
# 列出可用的 exporter
php bin/mysql-workbench-schema-export --list-exporter
```

你應該會看到列表中包含 `doctrine2-attribute`（或 `doctrine2-annotation` 等）。

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

## 常見問題

### Q: 我可以混用 Annotation 和 Attribute 嗎？
**A**: 不建議。請選擇一種格式並在整個項目中保持一致。

### Q: 我的 PHP 版本低於 8.0 怎麼辦？
**A**: 使用 `doctrine2-annotation` 格式代替 `doctrine2-attribute`。

### Q: 我收到 "Unknown formatter" 錯誤怎麼辦？
**A**: 確保你已經安裝了 doctrine2-exporter：
```bash
php composer.phar require --dev mysql-workbench-schema-exporter/doctrine2-exporter
```
然後執行 `--list-exporter` 列出可用的 formatter。

### Q: 我的 exporter 中沒有看到 "doctrine2-attribute" 怎麼辦？
**A**: 這表示你的 doctrine2-exporter 版本可能不支持 Attribute。請更新到最新版本：
```bash
php composer.phar update mysql-workbench-schema-exporter/doctrine2-exporter
```

如果更新後仍然沒有，可能是 doctrine2-exporter 還不支持 Attribute 格式。在這種情況下：
1. 檢查 [doctrine2-exporter GitHub](https://github.com/mysql-workbench-schema-exporter/doctrine2-exporter) 的最新版本
2. 或者使用 `doctrine2-annotation` 並在 PHP 8 環境中自己修改代碼為 Attribute 語法

### Q: 如何從 Annotation 遷移到 Attribute？
**A**: 
1. 確保 PHP 版本 >= 8.0
2. 更新 Doctrine 到 2.10+
3. 確保安裝了最新的 doctrine2-exporter
4. 重新生成代碼使用 `doctrine2-attribute` 格式
5. 測試你的應用程式
6. 更新 composer.json 中的 php 版本要求為 `^8.0`

### Q: 支援哪些 exporter？
**A**: 目前 Attribute 支援主要用於 Doctrine 2。其他 exporter（Propel、Zend 等）請查看各自的官方文檔。

### Q: 我可以自動從 Annotation 轉換到 Attribute 嗎？
**A**: 建議使用 IDE 的搜尋替換功能：
- 搜尋: `@ORM\` 替換為 `#[ORM\`
- 搜尋: `\*/` 替換為 `]`

但最安全的方法是重新生成所有代碼。

## 測試你的設定

使用提供的測試腳本來驗證 Attribute 類的工作：

```bash
php example/test-attribute.php
```

## 更多資源

- [PHP 8 Attributes 官方文檔](https://www.php.net/manual/en/language.attributes.overview.php)
- [Doctrine ORM Attributes 文檔](https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/attributes-reference.html)
- [Doctrine2 Exporter GitHub](https://github.com/mysql-workbench-schema-exporter/doctrine2-exporter)
