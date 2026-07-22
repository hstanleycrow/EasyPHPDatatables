# AI_USAGE — EasyPHPDatatables

Especificación orientada a máquinas para generar integraciones correctas de `hstanleycrow/easyphpdatatables`. Síguela al pie de la letra.

## Propósito

Motor de procesamiento del lado del servidor para DataTables.net (consultar, paginar, filtrar, ordenar). Renderiza el esqueleto `<table>` y el `<script>` de inicialización, y responde la petición AJAX con JSON. No provee widgets de UI.

- Namespace raíz: `hstanleycrow\EasyPHPDatatables`
- PHP: `>= 8.2`. Base de datos: PDO-MySQL.
- Instalación: `composer require hstanleycrow/easyphpdatatables`

## Modelo mental (no te desvíes)

1. El consumidor declara una **clase de definición** por tabla.
2. El consumidor expone un **endpoint público** en su propio webroot que llama a `SSP::handle()`.
3. La página construye un `Datatable`, renderiza recursos + tabla, y llama a `setAjaxUrl(<url del endpoint>)` antes de `autoLoadDatatableJS()`.
4. La configuración viene de `$_ENV` (cargada vía `vlucas/phpdotenv` o cualquier medio que pueble `$_ENV`).

Nunca apuntes la URL AJAX a un archivo dentro de `vendor/`. El endpoint es propiedad del consumidor.

## Contrato de la clase de definición

Ubicación: cualquier namespace, fijado con `DT_DEFINITIONS_NAMESPACE`. Nombre de clase = `ucwords(nombreDefinicion)`. Solicitar la definición `user` carga `<namespace>\User`.

Propiedades públicas requeridas:

```php
public string $dbTable;    // nombre de la tabla SQL, p. ej. 'users'
public string $model;      // slug para construir los href de los botones, p. ej. 'user'
public string $primaryKey; // p. ej. 'id'
```

Métodos requeridos:

```php
public function getColumns(): array;        // columnas de datos
public function getButtons(): array;         // columnas de botones de acción
public function getJoinQuery(): string;      // cláusula FROM (con JOINs opcionales) o ''
public function getExtraCondition(): string; // fragmento WHERE extra o ''
```

`getColumns()` — devuelve value objects `Column` (preferido) o los arrays asociativos equivalentes. La librería normaliza cualquiera de las dos formas.

```php
new Column(string $viewName, string $dbName, string $field, string $format = 'text', ?string $as = null);
```

Claves del array asociativo (que consume `Column::fromArray`):

| Clave | Requerido | Significado |
| --- | --- | --- |
| `view_name` | sí | Texto del encabezado. |
| `db_name` | sí | Columna SQL, calificada con backticks, p. ej. `` `a`.`name` ``. |
| `field` | sí | Clave del resultado. |
| `format` | no | Formato de la columna (por defecto `text`). |
| `as` | no | Alias SQL (usado cuando hay un JOIN). |

`getButtons()` — devuelve value objects `ActionButton` (preferido) o los arrays asociativos equivalentes.

```php
new ActionButton(string $buttonId, string $viewName, string $dbName, string $field, string $path, string $buttonText, ?string $buttonClass = null);
```

Claves del array asociativo (que consume `ActionButton::fromArray`):

| Clave | Requerido | Significado |
| --- | --- | --- |
| `button_id` | sí | Identificador; se compara contra el arreglo de botones deshabilitados del constructor. |
| `view_name` | sí | Texto del encabezado (`<th>`) de la columna del botón. No es el texto del enlace. |
| `db_name` | sí | Columna SQL que alimenta el botón (normalmente la PK). |
| `field` | sí | Clave del resultado. |
| `path` | sí | Segmento de ruta añadido después de `model`, p. ej. `edit`. |
| `buttonText` | sí | Texto del `<a>` por defecto, y segundo argumento del constructor de `buttonClass`. |
| `buttonClass` | no | FQCN de una clase de botón renderizable; si no, cae a un `<a>` plano. |

Contrato de `buttonClass`: `__construct(string $href, string $buttonText)` más `render(): string`.
El segundo parámetro es opcional: decláralo para recibir `buttonText`; si no lo declaras, la clase
se construye solo con `$href` (las clases de un solo argumento siguen funcionando igual).

`getJoinQuery()`: devuelve `"FROM \`users\` AS \`a\`"` para una sola tabla, o un `FROM ... JOIN ... ON ...` completo para joins. Cuando hay un JOIN, aliasea las columnas con `as` y refiérelas por el alias en `db_name`.

## Endpoint (webroot del consumidor)

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\SSP;

Dotenv::createImmutable(__DIR__ . '/..')->load();

SSP::handle();
```

`SSP::handle(?\PDO $pdo = null): void` lee `$_GET` (`dtDefinition`, opcional `db`), ejecuta la consulta y emite el JSON que DataTables espera. Fija `Content-Type: application/json`. Sin argumento conecta desde las variables `DATABASE_*`; pásale un `PDO` para reusar una conexión existente.

## Página

```php
<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\Datatable;

Dotenv::createImmutable(__DIR__)->load();

$datatable = new Datatable('user'); // 2do arg opcional: arreglo de button_ids a deshabilitar
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

`setAjaxUrl()` es obligatorio antes de `autoLoadDatatableJS()`; omitirlo lanza `Ajax URL is required`. Pasa una URL relativa al documento o a la raíz que resuelva a tu endpoint.

## API pública

```php
// hstanleycrow\EasyPHPDatatables\Datatable
__construct(string $DTDefinition, ?array $dtDisabledIdButtons = [])
setAjaxUrl(string $ajaxUrl): self
setTableId(string $tableId): self
addCssClass(string $class): self
setDTLanguage(string $language): self       // 'en' | 'es' | 'es-MX'
setDTRowsPerPage(int $rowsPerPage = 25): self
setDefaultOrder(int $column, string $dir = 'asc'): self  // orden inicial; column es el índice base 0
setFramework(string $framework): void       // ver frameworks
render(): string
autoLoadCssResources(): string
autoLoadJsResources(): string
autoLoadDatatableJS(): string

// hstanleycrow\EasyPHPDatatables\SSP
static handle(?\PDO $pdo = null): void  // omite para conectar desde DATABASE_* env; pásale un PDO para inyectar la conexión

// hstanleycrow\EasyPHPDatatables\Config
static instance(): Config
static fromEnv(): Config
static use(Config $config): void  // sobrescribe la instancia compartida (tests / config programática)
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

Los overrides de `Config` usan claves cortas: `definitions_namespace`, `language`, `page_length`, `table_classes`, `error_message`, `date_format`, `datetime_format`, `money_symbol`, `decimal_separator`, `thousand_separator`, `decimals`, `percentage_position`, `mailto_classes`, `image_classes`.

## Enumeraciones

- `format` de columna: `text`, `int`, `decimal`, `date`, `datetime`, `time`, `money`, `percentage`, `mailto`, `image`.
- `setDTLanguage`: `en`, `es`, `es-MX`. El JSON de traducción al español viene incluido y se incrusta en el script de inicialización (sin fetch cross-origin, sin CORS).
- `setFramework`: `bootstrap3`, `bootstrap4`, `bootstrap5` (por defecto), `bulma`, `foundation`, `jquery`, `semantic`.

## Claves de entorno

BD: `DATABASE_HOST`, `DATABASE_NAME`, `DATABASE_USERNAME`, `DATABASE_PASSWORD`; opcionales `DATABASE_PORT` (por defecto `3306`), `DATABASE_CHARSET` (por defecto `utf8mb4`).

Librería (todas opcionales, valores por defecto entre paréntesis): `DT_DEFINITIONS_NAMESPACE` (`hstanleycrow\DatatablesDefinitions`), `DT_LANGUAGE` (`en`), `DT_PAGE_LENGTH` (`25`), `DT_TABLE_CLASSES` (`table`), `DT_ERROR_MESSAGE`, `DT_DATE_FORMAT` (`d/m/Y`), `DT_DATETIME_FORMAT` (`d/m/Y H:i`), `DT_TIME_FORMAT` (`H:i:s`), `DT_MONEY_SYMBOL` (`$`), `DT_DECIMAL_SEPARATOR` (`.`), `DT_THOUSAND_SEPARATOR` (`,`), `DT_DECIMALS` (`2`), `DT_PERCENTAGE_POS` (`right`), `DT_MAILTO_CLASSES` (`btn btn-link`), `DT_IMAGE_CLASSES` (`img img-responsive`).

## Restricciones y detalles

- El endpoint debe ser alcanzable por URL y debe cargar el autoloader de Composer antes de llamar a `SSP::handle()`.
- `setDefaultOrder()` recibe el índice base 0 de la columna renderizada (las columnas de botones van después de las de datos). La dirección debe ser `asc` o `desc`; cualquier otra lanza `InvalidArgumentException`. Si no se llama, no se emite la opción `order` y DataTables aplica su propio default.
- Los índices `dt` de columna se asignan automáticamente y están aislados por instancia de `Datatable`; múltiples tablas por página no interfieren.
- Todos los valores inyectados en el JavaScript generado pasan por `json_encode`; no los entrecomilles manualmente.
- `getExtraCondition()` devuelve un fragmento SQL crudo; constrúyelo solo a partir de entrada confiable.
- Los valores de filtrado siempre se enlazan vía placeholders PDO dentro de la librería.
