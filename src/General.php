<?php
namespace MyFunctLib;

/**
 * Возвращает массив строк в которых содержится подстрока $string.
 * Поиск по шаблону типа "./*".
 *
 * @param string $string
 * @param string $pass
 *
 * @return array
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function grep($string, $pass)//первый вариант, неполный
{
    $arr = [];
    $iter = new \GlobIterator($pass);
    foreach ($iter as $item) {
        $handle = @fopen($item->getRealPath(), "r");
        if ($handle) {
            while ($buffer = fgets($handle, 4096)) {
                if (strpos($buffer, $string)) {
                    $arr[] = $buffer . "/" . $item->getFilename();
                }
            }
            if (!feof($handle)) {
                echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
            }
            fclose($handle);
        }
    }
    return $arr;
}

/**
 * ATTENTION!! Удаляет файл или папку и все её содержимое!
 *
 * @param string $dir
 *
 * @return int|bool
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function rrmdir($dir)//ох и опасная же функция, ай йай ай!
{
    if (is_file($dir)) {
        return unlink($dir);
    } else {
        $iter = new \DirectoryIterator($dir);
        foreach ($iter as $item) {
              if (!$item->isDot()) {
                rrmdir($item->getPathname());
              }
        }
        return rmdir($dir);
    }
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function boolToString($item)
{
    if (is_bool($item)) {
        switch ($item) {
            case true:
                return 'true';
            case false:
                return 'false';
        }
    }
    return $item;
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function getExtension($path)
{
    $info = new \SplFileInfo($path);
    return $info->getExtension();
}

function rest($key, $arr) //удаляет элемент массива по ключу и возвращает то, что осталось
{
    unset($arr[$key]);
    return array_values($arr);
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function uniq($arr)
{
    foreach ($arr as $key => $value) {
        foreach ($arr as $k => $v) {
            if (($arr[$k] === $value) && ($key !== $k)) {
                unset($arr[$key]);
            }
        }
    }
    return array_values($arr);
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function flatten($arr)
{
    $result = [];
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, flatten($value));
        } else {
            $result = array_merge($result, [$key => $value]);
        }
    }
    return $result;
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function allVariants($str) //нет проверки на уникальность(((
{
    $arr = str_split($str);
    $acc = [];
    $iter = function ($arr, $parents) use (&$iter, $acc) {
        foreach ($arr as $key=>$val) {
            $rest = rest($key, $arr);
            if (count($rest) === 1) {
                $acc[] = $parents . $val . implode($rest);
            } else {
                $acc[] = $iter($rest, $parents . $val);
            }
        }
        return $acc;
    };
    return flatten($iter($arr, ""));
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function withoutTwoZeros($n ,$l) //гениальнаое решение!!!
{
    if ($n === 1) return $n + $l;
    if ($n === $l + 1) return 1;
    if ($n <= 0 || $l < 0) return 0;
    $result = 0;
    for ($i = $l - 1; $i >= $n - 2; $i--) {
        $result += withoutTwoZeros($n - 1, $i);
    }
    return $result;
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function swapArr($arr, $key1, $key2)//меняет местами $key1 и $key2 Не доделана (Нет проверки)
{
    if ($key2 - 1 > count($arr)) return;
    $from = $arr[$key1];
    $to = $arr[$key2];
    $arr[$key1] = $to;
    $arr[$key2] = $from;
    return array_values($arr);
}

/**
 * Возвращает переданный бул true или false в виде строки 'true' или 'false'
 *
 * @param string $item
 *
 * @return string
 * @author Max Tikhomirov <maxsmakov@gmail.com>
 */
function swapStr($value, $key1, $key2)//меняет местами $key1 и $key2 Не доделана (Нет проверки)
{
    $str = str_split(strval($value));
    $from = $str[$key1];
    $to = $str[$key2];
    $str[$key1] = $to;
    $str[$key2] = $from;
    return implode($str);
}
