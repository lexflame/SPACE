<?php
namespace App\Libraries;

use Exception;

class Argumentation
{
    protected array $arrArg = [];

    public function __construct()
    {
        if (isset($_SERVER['argv'])) {
            $this->arrArg = $_SERVER['argv'];
        }
    }

    public function getArg(int|string $key = 0): string
    {
        if (isset($this->arrArg[$key])) {
            return $this->arrArg[$key];
        }

        throw new Exception('Element not found: ' . $key);
    }

    public function titleLink(string $link = ''): string
    {
        $arrLink = explode('/', $link);
        return $this->stringFormatted(trim(array_pop($arrLink)));
    }

    public function stringFormatted(string $string = ''): string
    {
        $string = str_replace(':', ' - ', $string);
        $string = str_replace('_', ' ', $string);
        return $string;
    }

    public function sara(?string $isNot = null, bool $pos = false): string
    {
        $str = '';
        $n = 0;

        foreach ($_GET as $key => $value) {
            if (stripos($key, 'debug') === false && !is_int($value) && $isNot !== $key) {
                $apersn = ($pos === true) ? ($n === 0 ? '?' : '&') : '&';
                $str .= $apersn . $key . '=' . $value;
            }
            $n++;
        }

        return $str;
    }
}
