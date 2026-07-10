# EasyPHPDatatables

Librería PHP independiente para el procesamiento del lado del servidor de [DataTables.net](https://datatables.net): consultas, paginación, filtrado y ordenamiento. Declaras cada tabla una sola vez como una clase PHP y la librería arma la consulta, la respuesta AJAX y el script de inicialización del cliente por ti.

**No** incluye un framework de UI. Renderiza el esqueleto `<table>` y el JavaScript de inicialización de DataTables, y responde la petición AJAX con JSON.

- **Requisitos:** PHP >= 8.2, base de datos PDO-MySQL.
- **Licencia:** MIT

> ¿Buscas botones de agregar/editar/borrar listos sobre esta librería? Mira [PHPDatatableUIBuilder](https://github.com/hstanleycrow/PHPDatatableUIBuilder).

## Instalación

```bash
composer require hstanleycrow/easyphpdatatables
```

## La arquitectura en una línea

El navegador carga una página que renderiza una `<table>` más un script de inicialización. Ese script apunta su opción `ajax` a **un endpoint que tú controlas** en tu webroot, que llama a `SSP::handle()` y devuelve JSON. Nunca expones archivos dentro de `vendor/`.

## Inicio rápido

### 1. Configura tu entorno (`.env`)

```dotenv
DATABASE_HOST = "127.0.0.1"
DATABASE_NAME = "mi_base"
DATABASE_USERNAME = "root"
DATABASE_PASSWORD = ""

DT_DEFINITIONS_NAMESPACE = 'App\DatatableDefinitions'
DT_LANGUAGE = 'es'
DT_PAGE_LENGTH = 25
DT_DATE_FORMAT = 'd/m/Y'
DT_TABLE_CLASSES = 'table table-striped'
```

Cada clave `DT_*` es opcional y tiene un valor por defecto razonable (ver [Configuración](#configuración)).

### 2. Declara la definición de una tabla

Una clase por tabla, dentro del namespace que pusiste en `DT_DEFINITIONS_NAMESPACE`. El nombre de la clase corresponde al nombre de la definición que solicitas (`user` -> `User`).

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
            new Column('Id',     '`a`.`id`',         'id'),
            new Column('Nombre', '`a`.`name`',       'name'),
            new Column('Correo', '`a`.`email`',      'email', 'mailto'),
            new Column('Desde',  '`a`.`created_at`', 'created_at', 'date'),
        ];
    }

    public function getButtons(): array
    {
        return [
            new ActionButton('edit', 'Editar', '`a`.`id`', 'id', 'edit', 'Editar'),
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

`Column` y `ActionButton` son value objects tipados (ver [Value objects de definición](#value-objects-de-definición)). Por compatibilidad hacia atrás también puedes devolver los arrays asociativos equivalentes (`['view_name' => ..., 'db_name' => ..., 'field' => ..., 'format' => ...]`).

### 3. Crea el endpoint AJAX (en tu propio webroot)

`public/datatables.php`:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\SSP;

Dotenv::createImmutable(__DIR__ . '/..')->load();

SSP::handle();
```

### 4. Renderiza la tabla en tu página

```php
<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\Datatable;

Dotenv::createImmutable(__DIR__)->load();

$datatable = new Datatable('user');
?>
<!DOCTYPE html>
<html lang="es">
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

`setAjaxUrl()` recibe la URL del endpoint del paso 3, relativa a la página. Usa una URL relativa al documento (`datatables.php`) o relativa a la raíz (`/datatables.php`) según dónde lo hayas desplegado.

## API pública

### `Datatable`

| Método | Descripción |
| --- | --- |
| `__construct(string $definition, ?array $disabledButtonIds = [])` | Construye la tabla para la definición dada. |
| `setAjaxUrl(string $url): self` | URL de tu endpoint `SSP::handle()`. **Obligatorio** antes de `autoLoadDatatableJS()`. |
| `setTableId(string $id): self` | `id` HTML de la `<table>`. |
| `addCssClass(string $class): self` | Agrega una clase CSS a la `<table>`. |
| `setDTLanguage(string $language): self` | `en`, `es` o `es-MX`. |
| `setDTRowsPerPage(int $rows = 25): self` | Filas por página. |
| `setFramework(string $framework): void` | Integración de estilos (ver abajo). |
| `render(): string` | El marcado `<table>` con encabezados. |
| `autoLoadCssResources(): string` | Etiquetas `<link>` de DataTables. |
| `autoLoadJsResources(): string` | Etiquetas `<script>` de DataTables. |
| `autoLoadDatatableJS(): string` | El `<script>` de inicialización del DataTable. |

### `SSP`

| Método | Descripción |
| --- | --- |
| `SSP::handle(?\PDO $pdo = null): void` | Lee la petición AJAX, ejecuta la consulta y emite el JSON. Pásale tu propio `PDO` para reusar una conexión existente; omítelo para conectar desde las variables `DATABASE_*`. |

### Value objects de definición

```php
new Column(
    string $viewName,            // texto del encabezado
    string $dbName,              // columna SQL calificada con backticks
    string $field,               // clave del resultado
    string $format = 'text',     // formato de la columna
    ?string $as = null           // alias SQL, usado con JOINs
);

new ActionButton(
    string $buttonId,            // se compara contra los ids de botones deshabilitados
    string $viewName,
    string $dbName,
    string $field,
    string $path,                // segmento de ruta después del model en el href
    string $buttonText,
    ?string $buttonClass = null  // FQCN de una clase de botón renderizable
);
```

Devolver los arrays asociativos equivalentes desde `getColumns()` / `getButtons()` sigue soportado; la librería normaliza cualquiera de las dos formas vía `Column::normalize()` / `ActionButton::normalize()`.

## Valores soportados

- **Formatos de columna** (clave `format`): `text`, `int`, `decimal`, `date`, `datetime`, `time`, `money`, `percentage`, `mailto`, `image`.
- **Idiomas:** `en`, `es`, `es-MX` (los archivos en español vienen incluidos y se incrustan en línea, así que no hay petición cross-origin).
- **Frameworks** (`setFramework`): `bootstrap3`, `bootstrap4`, `bootstrap5` (por defecto), `bulma`, `foundation`, `jquery`, `semantic`.

## Configuración

Toda la configuración se lee una vez desde `$_ENV`. Claves y valores por defecto:

| Clave env | Por defecto | Propósito |
| --- | --- | --- |
| `DT_DEFINITIONS_NAMESPACE` | `hstanleycrow\DatatablesDefinitions` | Namespace de tus clases de definición. |
| `DT_LANGUAGE` | `en` | Idioma por defecto de la tabla. |
| `DT_PAGE_LENGTH` | `25` | Filas por página por defecto. |
| `DT_TABLE_CLASSES` | `table` | Clases CSS por defecto en la `<table>`. |
| `DT_ERROR_MESSAGE` | Mensaje en inglés | Alerta cuando falla la carga AJAX. |
| `DT_DATE_FORMAT` | `d/m/Y` | Formato de `date`. |
| `DT_DATETIME_FORMAT` | `d/m/Y H:i` | Formato de `datetime`. |
| `DT_TIME_FORMAT` | `H:i:s` | Formato de `time`. |
| `DT_MONEY_SYMBOL` | `$` | Prefijo para `money`. |
| `DT_DECIMAL_SEPARATOR` | `.` | Para `money`, `decimal`, `percentage`. |
| `DT_THOUSAND_SEPARATOR` | `,` | Para `money`, `decimal`, `percentage`. |
| `DT_DECIMALS` | `2` | Decimales para formatos numéricos. |
| `DT_PERCENTAGE_POS` | `right` | Posición del `%`: `left` o `right`. |
| `DT_MAILTO_CLASSES` | `btn btn-link` | Clases CSS para `mailto`. |
| `DT_IMAGE_CLASSES` | `img img-responsive` | Clases CSS para `image`. |

Claves de base de datos: `DATABASE_HOST`, `DATABASE_NAME`, `DATABASE_USERNAME`, `DATABASE_PASSWORD`, y las opcionales `DATABASE_PORT` (por defecto `3306`) y `DATABASE_CHARSET` (por defecto `utf8mb4`).

## Pruebas

```bash
composer install
vendor/bin/phpunit
```

## Licencia

MIT. Ver [LICENSE](LICENSE).
