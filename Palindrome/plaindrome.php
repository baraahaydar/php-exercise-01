<?php

$string1 = "Baraa";

if(isPalindrome($string1)) echo "$string1 is a palindrome string";
else echo "$string1 is not a palindrome string";
echo "<br/>";

$string2 = "Hello";

if(isPalindrome($string2)) echo "$string2 is a palindrome string";
else echo "$string2 is not a palindrome string";
echo "<br/>";

$string3 = "HowAreYou";

if(isPalindrome($string3)) echo "$string3 is a palindrome string";
else echo "$string3 is not a palindrome string";
echo "<br/>";

$string4 = "aannaa";

if(isPalindrome($string4)) echo "$string4 is a palindrome string";
else echo "$string4 is not a palindrome string";
echo "<br/>";

function isPalindrome($string) {
    $string = strtolower($string);
    $j = strlen($string) - 1;
    for($i = 0; $i < strlen($string) / 2; $i++) {
        if($string[$i] != $string[$j]) {
            return false;
        }
        $j--;
    }
    return true;
}