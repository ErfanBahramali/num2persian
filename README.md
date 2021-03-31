# num2persian
Numbers/Digits to Persian words converter

## Usage
### num2persian
```
$num2persian = new num2persian();
$num2persian->numToPersian(193); // output: یکصد و نود و سه
```
```
num2persian::numToPersian(193); // output: یکصد و نود و سه
```
### counting
```
$num2persian = new num2persian();
$num2persian->counting(193); // output: یکصد و نود و سومین
```
```
num2persian::counting(193); // output: یکصد و نود و سومین
```
## Example

```php

// String Prototype
num2persian::numToPersian("2984"); // output: دو هزار و نهصد و هشتاد و چهار

// Non-Digits
num2persian::numToPersian("%3d9s401"); // output: سی و نه هزار و چهارصد و یک
num2persian::numToPersian("5,9"); // output: پنجاه و نه

// Number Prototype
num2persian::numToPersian(564.0); // output: پانصد و شصت و چهار

// Float
num2persian::numToPersian(564.50); // output: پانصد و شصت و چهار ممیز پنج دهم

// Negative numbers
num2persian::numToPersian(-120.1); // output: منفی یکصد و بیست ممیز یک دهم

// Counting 
num2persian::counting(5641); // output: پنج هزار و ششصد و چهل و یکمین
```
