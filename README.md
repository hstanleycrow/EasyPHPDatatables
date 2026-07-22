# EasyPHPDatatables

Free, standalone PHP library for the server-side processing of [DataTables.net](https://datatables.net): querying, pagination, filtering and ordering. You declare each table once as a PHP class, and the library builds the query, the AJAX response and the client-side init script for you.

It does **not** ship a UI framework. It renders the `<table>` skeleton and the DataTables init JavaScript, and answers the AJAX request with JSON.

- **Requirements:** PHP >= 8.2, a PDO-MySQL database.
- **License:** MIT

> Looking for ready-made add/edit/delete buttons on top of this? See [PHPDatatableUIBuilder](https://github.com/hstanleycrow/PHPDatatableUIBuilder).

## Installation

```bash
composer require hstanleycrow/easyphpdatatables
```

## Architecture in one line

The browser loads a page that renders a `<table>` plus an init script. That script points its `ajax` option at **an endpoint you own** in your webroot, which calls `SSP::handle()` and returns JSON. You never expose files inside `vendor/`.

## Quick start

### 1. Configure your environment (`.env`)

```dotenv
DATABASE_HOST = "127.0.0.1"
DATABASE_NAME = "my_database"
DATABASE_USERNAME = "root"
DATABASE_PASSWORD = ""

DT_DEFINITIONS_NAMESPACE = 'App\DatatableDefinitions'
DT_LANGUAGE = 'es'
DT_PAGE_LENGTH = 25
DT_DATE_FORMAT = 'd/m/Y'
DT_TABLE_CLASSES = 'table table-striped'
```

Every `DT_*` key is optional and falls back to a sensible default (see [Configuration](#configuration)).

### 2. Declare a table definition

One class per table, inside the namespace you set in `DT_DEFINITIONS_NAMESPACE`. The class name matches the definition name you request (`user` -> `User`).

```php
<?php

namespace App\DatatableDefinitions;

use hstanleycrow\EasyPHPDatatables\Column;
use hstanleycrow\EasyPHPDatatables\ActionButton;

class User
{
    public string $dbTable = 'users';
    public string $model = 'user';
    public string $primaryKey = 'id';

    public function getColumns(): array
    {
        return [
            new Column('Id',    '`a`.`id`',         'id'),
            new Column('Name',  '`a`.`name`',       'name'),
            new Column('Email', '`a`.`email`',      'email', 'mailto'),
            new Column('Since', '`a`.`created_at`', 'created_at', 'date'),
        ];
    }

    public function getButtons(): array
    {
        return [
            new ActionButton('edit', 'Edit', '`a`.`id`', 'id', 'edit', 'Edit'),
        ];
    }

    public function getJoinQuery(): string
    {
        return "FROM `users` AS `a`";
    }

    public function getExtraCondition(): string
    {
        return "";
    }
}
```

`Column` and `ActionButton` are typed value objects (see [Definition value objects](#definition-value-objects)). For backward compatibility you may also return the equivalent associative arrays (`['view_name' => ..., 'db_name' => ..., 'field' => ..., 'format' => ...]`).

### 3. Create the AJAX endpoint (in your own webroot)

`public/datatables.php`:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\SSP;

Dotenv::createImmutable(__DIR__ . '/..')->load();

SSP::handle();
```

> Already have a database connection in your app? Pass it in and skip the `DATABASE_*` env vars: `SSP::handle($existingPdo);`.

### 4. Render the table in your page

```php
<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\Datatable;

Dotenv::createImmutable(__DIR__)->load();

$datatable = new Datatable('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <?= $datatable->autoLoadCssResources(); ?>
</head>
<body>
    <div class="container">
        <?= $datatable->setTableId('users')->render(); ?>
    </div>
    <?= $datatable->autoLoadJsResources(); ?>
    <?= $datatable->setAjaxUrl('datatables.php')->autoLoadDatatableJS(); ?>
</body>
</html>
```

`setAjaxUrl()` takes the URL of the endpoint from step 3, relative to the page. Use a document-relative URL (`datatables.php`) or a root-relative one (`/datatables.php`) that matches where you deployed it.

## Public API

### `Datatable`

| Method | Description |
| --- | --- |
| `__construct(string $definition, ?array $disabledButtonIds = [])` | Builds the table for the given definition. |
| `setAjaxUrl(string $url): self` | URL of your `SSP::handle()` endpoint. **Required** before `autoLoadDatatableJS()`. |
| `setTableId(string $id): self` | HTML `id` of the `<table>`. |
| `addCssClass(string $class): self` | Appends a CSS class to the `<table>`. |
| `setDTLanguage(string $language): self` | `en`, `es` or `es-MX`. |
| `setDTRowsPerPage(int $rows = 25): self` | Page length. |
| `setDefaultOrder(int $column, string $dir = 'asc'): self` | Initial sort, by 0-based column index. Omit it and DataTables applies its own default. |
| `setFramework(string $framework): void` | Styling integration (see below). |
| `render(): string` | The `<table>` markup with headers. |
| `autoLoadCssResources(): string` | DataTables `<link>` tags. |
| `autoLoadJsResources(): string` | DataTables `<script>` tags. |
| `autoLoadDatatableJS(): string` | The DataTable init `<script>`. |

### `SSP`

| Method | Description |
| --- | --- |
| `SSP::handle(?\PDO $pdo = null): void` | Reads the AJAX request, runs the query and echoes the JSON. Pass your own `PDO` to reuse an existing connection; omit it to connect from the `DATABASE_*` env vars. |

### Definition value objects

```php
new Column(
    string $viewName,            // header text
    string $dbName,              // backtick-qualified SQL column
    string $field,               // result key
    string $format = 'text',     // column format
    ?string $as = null           // SQL alias, used with JOINs
);

new ActionButton(
    string $buttonId,            // matched against the disabled-button ids
    string $viewName,            // header text of the button column
    string $dbName,
    string $field,
    string $path,                // path segment after the model in the href
    string $buttonText,          // anchor text, also passed to the button class
    ?string $buttonClass = null  // FQCN of a renderable button class
);
```

A `buttonClass` must expose `render(): string` and a constructor of the form
`__construct(string $href, string $buttonText)`. The second parameter is optional: declare it to
receive `buttonText`, omit it and the class is constructed with `$href` alone.

Returning the equivalent associative arrays from `getColumns()` / `getButtons()` is still supported; the library normalizes either form via `Column::normalize()` / `ActionButton::normalize()`.

## Supported values

- **Column formats** (`format` key): `text`, `int`, `decimal`, `date`, `datetime`, `time`, `money`, `percentage`, `mailto`, `image`.
- **Languages:** `en`, `es`, `es-MX` (Spanish files are bundled and inlined, so there is no cross-origin request).
- **Frameworks** (`setFramework`): `bootstrap3`, `bootstrap4`, `bootstrap5` (default), `bulma`, `foundation`, `jquery`, `semantic`.

## Configuration

All configuration is read once from `$_ENV`. Keys and defaults:

| Env key | Default | Purpose |
| --- | --- | --- |
| `DT_DEFINITIONS_NAMESPACE` | `hstanleycrow\DatatablesDefinitions` | Namespace of your definition classes. |
| `DT_LANGUAGE` | `en` | Default table language. |
| `DT_PAGE_LENGTH` | `25` | Default rows per page. |
| `DT_TABLE_CLASSES` | `table` | Default CSS classes on the `<table>`. |
| `DT_ERROR_MESSAGE` | English message | Alert shown when the AJAX load fails. |
| `DT_DATE_FORMAT` | `d/m/Y` | `date` format. |
| `DT_DATETIME_FORMAT` | `d/m/Y H:i` | `datetime` format. |
| `DT_TIME_FORMAT` | `H:i:s` | `time` format. |
| `DT_MONEY_SYMBOL` | `$` | Prefix for `money`. |
| `DT_DECIMAL_SEPARATOR` | `.` | For `money`, `decimal`, `percentage`. |
| `DT_THOUSAND_SEPARATOR` | `,` | For `money`, `decimal`, `percentage`. |
| `DT_DECIMALS` | `2` | Decimal places for numeric formats. |
| `DT_PERCENTAGE_POS` | `right` | `%` position: `left` or `right`. |
| `DT_MAILTO_CLASSES` | `btn btn-link` | CSS classes for `mailto`. |
| `DT_IMAGE_CLASSES` | `img img-responsive` | CSS classes for `image`. |

Database keys: `DATABASE_HOST`, `DATABASE_NAME`, `DATABASE_USERNAME`, `DATABASE_PASSWORD`, and optional `DATABASE_PORT` (default `3306`) and `DATABASE_CHARSET` (default `utf8mb4`).

## Testing

```bash
composer install
vendor/bin/phpunit
```

## License

MIT. See [LICENSE](LICENSE).
