<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class PercentageFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        return function ($data, $row) {
            $percentagePosition = $this->getPercentagePosition();
            $decimalSeparator = NumberFormatConfig::getDecimalSeparator();
            $thousandsSeparator = NumberFormatConfig::getThousandsSeparator();
            $decimals = NumberFormatConfig::getDecimals();

            $formattedData = number_format(
                $data,
                $decimals,
                $thousandsSeparator,
                $decimalSeparator
            );

            if ($percentagePosition == 'left') {
                return '%' . $formattedData;
            } else {
                return $formattedData . '%';
            }
        };
    }
    private function getPercentagePosition()
    {
        return $_ENV['DT_PERCENTAGE_POS'] ?? 'right';
    }
}
