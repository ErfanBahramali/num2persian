<?php

class num2persian
{
    private static $delimiter = ' و ';
    private static $zero = 'صفر';
    private static $negative = 'منفی ';
    private static $letters = [['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'], ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده', 'بیست'], ['', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'], ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'], ['', ' هزار', ' میلیون', ' میلیارد', ' بیلیون', ' بیلیارد', ' تریلیون', ' تریلیارد', ' کوآدریلیون', ' کادریلیارد', ' کوینتیلیون', ' کوانتینیارد', ' سکستیلیون', ' سکستیلیارد', ' سپتیلیون', ' سپتیلیارد', ' اکتیلیون', ' اکتیلیارد', ' نانیلیون', ' نانیلیارد', ' دسیلیون', ' دسیلیارد']];
    private static $decimalSuffixes = ['', 'دهم', 'صدم', 'هزارم', 'ده‌هزارم', 'صد‌هزارم', 'میلیونوم', 'ده‌میلیونوم', 'صدمیلیونوم', 'میلیاردم', 'ده‌میلیاردم', 'صد‌‌میلیاردم'];

    /** 
     * The numbers are separated by 3 digits
     * @example 132.23 to [012,3.23]
     * @param float $out Numbers to separate
     * @return array separated numbers
     */
    private static function prepareNumber(float $out)
    {
        $NumberLength = mb_strlen($out) % 3;
        if ($NumberLength === 1) {
            $out = "00" . $out;
        } else if ($NumberLength === 2) {
            $out = "0" . $out;
        }
        $out = str_split($out, 3);
        return $out;
    }

    /** 
     * convert three number to letter just three
     * @param float $num 3-digit number
     * @return string converted text
     */
    private static function threeNumbersToLetter(float $num)
    {
        if (intval($num) === 0) {
            return '';
        }
        $parsedInt = intval($num);
        if ($parsedInt < 10) {
            return self::$letters[0][$parsedInt];
        }
        if ($parsedInt <= 20) {
            return self::$letters[1][$parsedInt - 10];
        }
        if ($parsedInt < 100) {
            $_one = $parsedInt % 10;
            $_ten = ($parsedInt - $_one) / 10;
            if ($_one > 0) {
                return self::$letters[2][$_ten] . self::$delimiter . self::$letters[0][$_one];
            }
            return self::$letters[2][$_ten];
        }
        $one = $parsedInt % 10;
        $hundreds = ($parsedInt - $parsedInt % 100) / 100;
        $ten = ($parsedInt - ($hundreds * 100 + $one)) / 10;
        $out = [self::$letters[3][$hundreds]];
        $SecondPart = $ten * 10 + $one;
        if ($SecondPart > 0) {
            if ($SecondPart < 10) {
                $out[] = self::$letters[0][$SecondPart];
            } else if ($SecondPart <= 20) {
                $out[] = self::$letters[1][$SecondPart - 10];
            } else {
                $out[] = self::$letters[2][$ten];
                if ($one > 0) {
                    $out[] = self::$letters[0][$one];
                }
            }
        }
        return implode(self::$delimiter, $out);
    }

    /** 
     * convert Decimal Part
     * @param float $decimalPart Decimal number part
     * @return string converted text
     */
    private static function convertDecimalPart(float $decimalPart)
    {
        $decimalPart = preg_replace("/0*$/", "", $decimalPart);
        if ($decimalPart === '') {
            return '';
        }
        if (mb_strlen($decimalPart) > 11) {
            $decimalPart = mb_substr($decimalPart, 0, 11);
        }
        return ' ممیز ' . self::num2persian($decimalPart) . ' ' . self::$decimalSuffixes[mb_strlen($decimalPart)];
    }

    /** 
     * Numbers/Digits to Persian words converter
     * Ability to process numbers up to 66 integers and 11 decimal places دسیلیارد 
     * @param string|float $num Numbers to convert to letters
     * @example 1 to یک
     * @return string converted text
     */
    public static function num2persian($input)
    {
        $input = preg_replace("/[^0-9.-]/", "", $input);
        $isNegative = false;
        if (is_numeric($input)) {
            $floatParse = $input;
        } else {
            $floatParse = 0;
        }
        if (is_nan($floatParse)) {
            return self::$zero;
        }
        if ($floatParse === 0) {
            return self::$zero;
        }
        if ($floatParse < 0) {
            $isNegative = true;
            $input = preg_replace("/-/", "", $input);
        }
        $decimalPart = '';
        $integerPart = $input;
        $pointIndex = strpos($input, ".");
        if ($pointIndex > -1) {
            $integerPart = mb_substr($input, 0, $pointIndex);
            $decimalPart = mb_substr($input, $pointIndex + 1, mb_strlen($input));
        }
        if (mb_strlen($integerPart) > 66) {
            return 'خارج از محدوده';
        }
        $slicedNumber = self::prepareNumber($integerPart);
        $Output = [];
        $SplitLength = count($slicedNumber);
        for ($i = 0; $i < $SplitLength; $i++) {
            $SectionTitle = self::$letters[4][$SplitLength - ($i + 1)];
            $converted = self::threeNumbersToLetter($slicedNumber[$i]);
            if ($converted !== '') {
                $Output[] = $converted . $SectionTitle;
            }
        }
        if (mb_strlen($decimalPart) > 0) {
            $decimalPart = self::convertDecimalPart($decimalPart);
        }
        return ($isNegative ? self::$negative : '') . implode(self::$delimiter, $Output) . $decimalPart;
    }

    /** 
     * Convert numbers to counting numbers text
     * @example 1 to اولین 
     * @param string|float $num Numbers to convert to letters
     * @return string converted text
     */
    public static function counting($num)
    {
        if ($num == "1") {
            return "اولین";
        }
        $num = self::num2persian($num);
        $split = explode(" ", $num);
        $end = end($split);
        if ($end == "سه") {
            $split[count($split) - 1] = "سومین";
        } else {
            $split[count($split) - 1] = ($end . "مین");
            $split[count($split) - 1] = ($end . ((mb_substr($end, -1) != 'م') ? 'مین' : 'ین'));
        }

        return implode(" ", $split);
    }
}
