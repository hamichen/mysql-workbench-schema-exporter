# 解決 "Unknown formatter" 錯誤

## 問題描述

當執行以下命令時出現錯誤：
```
Unknown formatter "doctrine2-attribute".%
```

## 原因

MySQL Workbench Schema Exporter 主程序本身只提供基礎框架，具體的 formatter（如 Doctrine2）被分離為獨立的包。這樣設計的目的是：

1. **模塊化**: 每個 formatter 可獨立開發和更新
2. **輕量化**: 用戶只需安裝需要的 formatter
3. **靈活性**: 支持多個版本和實現

## 解決方案

### 步驟 1: 安裝 doctrine2-exporter

```bash
php composer.phar require --dev mysql-workbench-schema-exporter/doctrine2-exporter
```

### 步驟 2: 驗證安裝

列出所有可用的 formatter：

```bash
php bin/mysql-workbench-schema-export --list-exporter
```

你應該看到類似的輸出：
```
Available exporters:
  - doctrine1-yaml
  - doctrine2-annotation
  - doctrine2-attribute (如果 exporter 支持)
  - doctrine2-yaml
  - doctrine2-zf2inputfilter
  - propel-xml
  - propel-yaml
  ... 其他
```

### 步驟 3: 使用正確的 formatter

根據安裝的 exporter 版本，使用相應的 formatter：

```bash
# 如果支持 doctrine2-attribute
php bin/mysql-workbench-schema-export --export=doctrine2-attribute model.mwb output/

# 如果只支持 doctrine2-annotation (較舊版本)
php bin/mysql-workbench-schema-export --export=doctrine2-annotation model.mwb output/
```

## 檢查 Formatter 版本

### 檢查 composer.json

查看已安裝的 doctrine2-exporter 版本：

```bash
php composer.phar show mysql-workbench-schema-exporter/doctrine2-exporter
```

輸出範例：
```
name      : mysql-workbench-schema-exporter/doctrine2-exporter
versions  : * 3.0.0
type      : library
description : Doctrine 2 exporter for MySQL Workbench Schema Exporter
...
```

### 檢查 doctrine2-exporter 支持的 Formatter

在已安裝的 doctrine2-exporter 目錄中查看：

```bash
ls vendor/mysql-workbench-schema-exporter/doctrine2-exporter/lib/MwbExporter/Formatter/Doctrine2/
```

應該看到如：
```
Annotation/
Attribute/ (如果版本支持)
Yaml/
ZF2InputFilterAnnotation/
... 其他
```

## 版本相關信息

### doctrine2-exporter 版本支持

| 版本 | PHP 版本 | Attribute 支援 | 說明 |
|-----|---------|-------------|------|
| < 2.0 | 5.4+ | ❌ | 舊版本，不支持 Attribute |
| 2.x | 7.0+ | 可能 | 取決於具體版本 |
| 3.0+ | 7.4+ | ✅ | 完整支持 Attribute |

### 更新到最新版本

```bash
# 更新 doctrine2-exporter 到最新版本
php composer.phar update mysql-workbench-schema-exporter/doctrine2-exporter

# 或指定特定版本
php composer.phar require "mysql-workbench-schema-exporter/doctrine2-exporter:^3.0"
```

## 完整設定示例

### 1. composer.json

```json
{
    "require-dev": {
        "mysql-workbench-schema-exporter/mysql-workbench-schema-exporter": "^5.0",
        "mysql-workbench-schema-exporter/doctrine2-exporter": "^3.0"
    }
}
```

### 2. 命令行用法

```bash
# 安裝依賴
php composer.phar install

# 列出可用 formatter
php bin/mysql-workbench-schema-export --list-exporter

# 使用 doctrine2-attribute
php bin/mysql-workbench-schema-export --export=doctrine2-attribute data/model.mwb output/

# 使用配置文件
php bin/mysql-workbench-schema-export --config=export.json data/model.mwb
```

### 3. export.json 配置

```json
{
    "export": "doctrine2-attribute",
    "zip": false,
    "dir": "output",
    "params": {
        "useAttributePrefix": "ORM\\",
        "bundleNamespace": "App\\Entity",
        "indentation": 4
    }
}
```

## 故障排除

### 問題: 更新後仍然找不到 doctrine2-attribute

**解決:**
1. 清除 composer 緩存: `php composer.phar clear-cache`
2. 重新安裝: `php composer.phar install --no-cache`
3. 驗證安裝: `php bin/mysql-workbench-schema-export --list-exporter`

### 問題: 某個特定版本不支持 doctrine2-attribute

**解決:**
1. 檢查官方倉庫: https://github.com/mysql-workbench-schema-exporter/doctrine2-exporter
2. 查看 CHANGELOG 或 README 了解何時添加了 Attribute 支持
3. 升級到支持該功能的版本

### 問題: doctrine2-exporter 和主框架版本不匹配

**解決:**
檢查兼容性矩陣，更新到相容的版本組合：

```bash
# 查看相容版本
php composer.phar why mysql-workbench-schema-exporter/doctrine2-exporter

# 檢查可用的版本列表
php composer.phar show mysql-workbench-schema-exporter/doctrine2-exporter versions
```

## 資源

- **Doctrine2-Exporter GitHub**: https://github.com/mysql-workbench-schema-exporter/doctrine2-exporter
- **主框架 GitHub**: https://github.com/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter
- **PHP 8 Attributes**: https://www.php.net/manual/en/language.attributes.overview.php
- **Doctrine ORM Attributes**: https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/attributes-reference.html

## 總結

要使用 `doctrine2-attribute` formatter，需要：

1. ✅ PHP 8.0+ 環境
2. ✅ 安裝 `mysql-workbench-schema-exporter`
3. ✅ 安裝支持 Attribute 的 `doctrine2-exporter` (通常是 3.0+)
4. ✅ 在命令行或配置文件中指定 `--export=doctrine2-attribute`
