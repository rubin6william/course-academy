<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $inputStr = '{}()';

        $valid = false;

        $i = 0;

        do {
            if ((($inputStr[$i] === '(') && (@$inputStr[$i+1] === ')')) ||
                (($inputStr[$i] === '[') && (@$inputStr[$i+1] === ']')) ||
                (($inputStr[$i] === '{') && (@$inputStr[$i+1] === '}'))) {
                $valid = true;
            }

            echo 'i: ' . $inputStr[$i] . '   i+1: ' . @$inputStr[$i + 1] . "\n";

            $i = $i + 2;
        } while($i < strlen($inputStr));

        var_dump($valid);
    }
}
