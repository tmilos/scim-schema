<?php

namespace Tests\Tmilos\ScimSchema;

use PHPUnit\Framework\TestCase;

abstract class TestHelper
{
    /**
     * @param string $filename
     *
     * @return array
     */
    public static function getExpected($filename)
    {
        $json = trim(file_get_contents(__DIR__.'/resources/'.$filename));
        $result = json_decode($json, true);
        if (!$result) {
            throw new \RuntimeException("Error json decoding file '$filename': ".json_last_error_msg());
        }

        return $result;
    }

    public static function compare(array $expected, array $actual, TestCase $testCase)
    {
        $expectedArr = explode("\n", str_replace("\r", '', json_encode($expected, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)));
        $actualArr = explode("\n", str_replace("\r", '', json_encode($actual, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)));

        $minSize = min(count($expectedArr), count($actualArr));
        for ($i=0; $i<$minSize; $i++) {
            if ($expectedArr[$i] != $actualArr[$i]) {
                $expectedArr[$i] = '!'.$expectedArr[$i];
                $actualArr[$i] = '!'.$actualArr[$i];
                print "\nMismatch at line $i:\n";
                $start = $i - 10;
                if ($start < 0) { $start = 0; }
                $expectedBuffer = implode("\n", array_slice($expectedArr, $start, 20));
                $actualBuffer = implode("\n", array_slice($actualArr, $start, 20));
                print "Expected:\n$expectedBuffer\n\nbut got:\n$actualBuffer\n";

                $testCase->fail();
            }
        }

        $testCase->assertEquals($expected, $actual);
    }
}
