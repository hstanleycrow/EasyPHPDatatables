<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class ButtonFormatter implements IColumnFormatterGenerator
{
    protected string $model;
    protected string $path;
    protected string $buttonText;
    protected ?string $buttonClass;
    public function __construct(string $model, string $path, string $buttonText, ?string $buttonClass = null)
    {
        $this->model = $model;
        $this->path = $path;
        $this->buttonText = $buttonText;
        $this->buttonClass = $buttonClass;
    }
    public function generate(): callable
    {
        $href = $this->model . '/' . $this->path . '/';
        return function ($d, $row) use ($href) {
            $href .= $d . '/';

            if ($this->buttonClass && class_exists($this->buttonClass)) {
                return (new $this->buttonClass(...$this->buttonArguments($href)))->render();
            }

            return '<a href="' . $href . '" >' . $this->buttonText . '</a>';
        };
    }

    /**
     * The button text is only injected when the class declares a second constructor
     * parameter, so classes written against the previous one-argument contract keep working.
     */
    private function buttonArguments(string $href): array
    {
        $constructor = (new \ReflectionClass($this->buttonClass))->getConstructor();

        return $constructor !== null && $constructor->getNumberOfParameters() >= 2
            ? [$href, $this->buttonText]
            : [$href];
    }
}
