<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class MoneyFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        return function ($data, $row) {
            $moneySymbol = $this->getMoneySymbol();
            $decimalSeparator = NumberFormatConfig::getDecimalSeparator();
            $thousandsSeparator = NumberFormatConfig::getThousandsSeparator();
            $decimals = NumberFormatConfig::getDecimals();

            return $moneySymbol .
                number_format($data, $decimals, $decimalSeparator, $thousandsSeparator);
        };
    }

    private function getMoneySymbol()
    {
        return $_ENV['DT_MONEY_SYMBOL'] ?? '$';
    }
}
