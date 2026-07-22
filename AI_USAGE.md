# AI_USAGE — EasyPHPDatatables

Machine-oriented specification for generating correct integrations of `hstanleycrow/easyphpdatatables`. Follow it literally.

## Purpose

Server-side processing engine for DataTables.net (query, paginate, filter, order). It renders the `<table>` skeleton and the init `<script>`, and answers the AJAX request with JSON. It does not provide UI widgets.

- Namespace root: `hstanleycrow\EasyPHPDatatables`
- PHP: `>= 8.2`. Database: PDO-MySQL.
- Install: `composer require hstanleycrow/easyphpdatatables`

## Mental model (do not deviate)

1. The consumer declares one **definition class** per table.
2. The consumer exposes one **public endpoint** in their own webroot that calls `SSP::handle()`.
3. The page constructs a `Datatable`, renders resources + table, and calls `setAjaxUrl(<endpoint url>)` before `autoLoadDatatableJS()`.
4. Configuration comes from `$_ENV` (loaded via `vlucas/phpdotenv` or any means that populates `$_ENV`).

Never point the AJAX URL at a file inside `vendor/`. The endpoint is owned by the consumer.

## Definition class contract

Location: any namespace, set via `DT_DEFINITIONS_NAMESPACE`. Class name = `ucwords(definitionName)`. Requesting definition `user` loads `<namespace>\User`.

Required public properties:

```php
public string $dbTable;    // SQL table name, e.g. 'users'
public string $model;      // slug used to build button hrefs, e.g. 'user'
public string $primaryKey; // e.g. 'id'
```

Required methods:

```php
public function getColumns(): array;        // data columns
public function getButtons(): array;         // action-button columns
public function getJoinQuery(): string;      // FROM clause (with optional JOINs) or ''
public function getExtraCondition(): string; // extra WHERE fragment or ''
```

`getColumns()` — return `Column` value objects (preferred) or the equivalent associative arrays. The library normalizes either form.

```php
new Column(string $viewName, string $dbName, string $field, string $format = 'text', ?string $as = null);
```

Associative-array keys (consumed by `Column::fromArray`):

| Key | Required | Meaning |
| --- | --- | --- |
| `view_name` | yes | Header text. |
| `db_name` | yes | SQL column, backtick-qualified, e.g. `` `a`.`name` ``. |
| `field` | yes | Result key. |
| `format` | no | Column format (default `text`). |
| `as` | no | SQL alias (used when a JOIN is present). |

`getButtons()` — return `ActionButton` value objects (preferred) or the equivalent associative arrays.

```php
new ActionButton(string $buttonId, string $viewName, string $dbName, string $field, string $path, string $buttonText, ?string $buttonClass = null);
```

Associative-array keys (consumed by `ActionButton::fromArray`):

| Key | Required | Meaning |
| --- | --- | --- |
| `button_id` | yes | Identifier; matched against the constructor's disabled-buttons array. |
| `view_name` | yes | Header text of the button column (`<th>`). Not the anchor text. |
| `db_name` | yes | SQL column feeding the button (usually the PK). |
| `field` | yes | Result key. |
| `path` | yes | Path segment appended after `model`, e.g. `edit`. |
| `buttonText` | yes | Anchor text of the default `<a>`, and the second constructor argument passed to `buttonClass`. |
| `buttonClass` | no | FQCN of a renderable button class; falls back to a plain `<a>`. |

`buttonClass` contract: `__construct(string $href, string $buttonText)` plus `render(): string`.
The second parameter is optional — declare it to receive `buttonText`, omit it and the class is
constructed with `$href` alone (one-argument classes keep working unchanged).

`getJoinQuery()`: return `"FROM \`users\` AS \`a\`"` for a single table, or a full `FROM ... JOIN ... ON ...` for joins. When a JOIN is present, alias columns with `as` and reference them with the alias in `db_name`.

## Endpoint (consumer webroot)

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\SSP;

Dotenv::createImmutable(__DIR__ . '/..')->load();

SSP::handle();
```

`SSP::handle(?\PDO $pdo = null): void` reads `$_GET` (`dtDefinition`, optional `db`), runs the query and echoes the JSON DataTables expects. It sets `Content-Type: application/json`. With no argument it connects from the `DATABASE_*` env vars; pass a `PDO` to reuse an existing connection.

## Page

```php
<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\Datatable;

Dotenv::createImmutable(__DIR__)->load();

$datatable = new Datatable('user'); // optional 2nd arg: array of button_ids to disable
?>
<head>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <?= $datatable->autoLoadCssResources(); ?>
</head>
<body>
    <?= $datatable->setTableId('users')->render(); ?>
    <?= $datatable->autoLoadJsResources(); ?>
    <?= $datatable->setAjaxUrl('datatables.php')->autoLoadDatatableJS(); ?>
</body>
```

`setAjaxUrl()` is mandatory before `autoLoadDatatableJS()`; omitting it throws `Ajax URL is required`. Pass a document-relative or root-relative URL that resolves to your endpoint.

## Public API

```php
// hstanleycrow\EasyPHPDatatables\Datatable
__construct(string $DTDefinition, ?array $dtDisabledIdButtons = [])
setAjaxUrl(string $ajaxUrl): self
setTableId(string $tableId): self
addCssClass(string $class): self
setDTLanguage(string $language): self       // 'en' | 'es' | 'es-MX'
setDTRowsPerPage(int $rowsPerPage = 25): self
setDefaultOrder(int $column, string $dir = 'asc'): self  // initial sort; column is the 0-based index
setFramework(string $framework): void       // see frameworks
render(): string
autoLoadCssResources(): string
autoLoadJsResources(): string
autoLoadDatatableJS(): string

// hstanleycrow\EasyPHPDatatables\SSP
static handle(?\PDO $pdo = null): void  // omit to connect from DATABASE_* env; pass a PDO to inject a connection

// hstanleycrow\EasyPHPDatatables\Config
static instance(): Config
static fromEnv(): Config
static use(Config $config): void  // override the shared instance (tests / programmatic config)
static reset(): void
__construct(array $overrides = [])

// hstanleycrow\EasyPHPDatatables\Column
__construct(string $viewName, string $dbName, string $field, string $format = 'text', ?string $as = null)
static fromArray(array $column): Column
static normalize(Column|array $column): Column

// hstanleycrow\EasyPHPDatatables\ActionButton
__construct(string $buttonId, string $viewName, string $dbName, string $field, string $path, string $buttonText, ?string $buttonClass = null)
static fromArray(array $button): ActionButton
static normalize(ActionButton|array $button): ActionButton
```

`Config` overrides use short keys: `definitions_namespace`, `language`, `page_length`, `table_classes`, `error_message`, `date_format`, `datetime_format`, `money_symbol`, `decimal_separator`, `thousand_separator`, `decimals`, `percentage_position`, `mailto_classes`, `image_classes`.

## Enumerations

- Column `format`: `text`, `int`, `decimal`, `date`, `datetime`, `time`, `money`, `percentage`, `mailto`, `image`.
- `setDTLanguage`: `en`, `es`, `es-MX`. Spanish translation JSON is bundled and inlined into the init script (no cross-origin fetch, no CORS).
- `setFramework`: `bootstrap3`, `bootstrap4`, `bootstrap5` (default), `bulma`, `foundation`, `jquery`, `semantic`.

## Environment keys

DB: `DATABASE_HOST`, `DATABASE_NAME`, `DATABASE_USERNAME`, `DATABASE_PASSWORD`; optional `DATABASE_PORT` (default `3306`), `DATABASE_CHARSET` (default `utf8mb4`).

Library (all optional, defaults in parentheses): `DT_DEFINITIONS_NAMESPACE` (`hstanleycrow\DatatablesDefinitions`), `DT_LANGUAGE` (`en`), `DT_PAGE_LENGTH` (`25`), `DT_TABLE_CLASSES` (`table`), `DT_ERROR_MESSAGE`, `DT_DATE_FORMAT` (`d/m/Y`), `DT_DATETIME_FORMAT` (`d/m/Y H:i`), `DT_TIME_FORMAT` (`H:i:s`), `DT_MONEY_SYMBOL` (`$`), `DT_DECIMAL_SEPARATOR` (`.`), `DT_THOUSAND_SEPARATOR` (`,`), `DT_DECIMALS` (`2`), `DT_PERCENTAGE_POS` (`right`), `DT_MAILTO_CLASSES` (`btn btn-link`), `DT_IMAGE_CLASSES` (`img img-responsive`).

## Constraints and gotchas

- The endpoint must be reachable by URL and must load the Composer autoloader before calling `SSP::handle()`.
- `setDefaultOrder()` takes the 0-based index of the rendered column (button columns are appended after the data columns). Direction must be `asc` or `desc`; anything else throws `InvalidArgumentException`. Without it the `order` option is not emitted and DataTables applies its own default.
- Column `dt` indices are assigned automatically and are isolated per `Datatable` instance; multiple tables per page do not interfere.
- All values injected into the generated JavaScript are passed through `json_encode`; do not manually quote them.
- `getExtraCondition()` returns a raw SQL fragment; only build it from trusted input.
- Filtering values are always bound via PDO placeholders inside the library.
