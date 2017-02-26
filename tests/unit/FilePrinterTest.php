<?php

use Dephpug\FilePrinter;

class FilePrinterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $smallFilePrinter;
    protected $bigFilePrinter;

    protected function _before()
    {
        $this->smallFilePrinter = new FilePrinter();
        $this->bigFilePrinter = new FilePrinter();

        $smallFile = file(__DIR__ . '/../data/file_3_lines.txt');
        $bigFile = file(__DIR__ . '/../data/file_100_lines.txt');

        $this->smallFilePrinter->setFile($smallFile);
        $this->bigFilePrinter->setFile($bigFile);
    }

    protected function _after()
    {
    }

    // tests
    public function testPaginationRangeInMiddle()
    {
        $rangePages = $this->bigFilePrinter->getRangePagination(50);
        $this->assertEquals([44, 56], $rangePages);
    }

    public function testPaginationRangeAtBegin()
    {
        $rangePages = $this->bigFilePrinter->getRangePagination(1);
        $this->assertEquals([0, 7], $rangePages);
    }

    public function testPaginationRangeAtEnd()
    {
        $rangePages = $this->bigFilePrinter->getRangePagination(98);
        $this->assertEquals([92, 99], $rangePages);
    }

    public function testPaginationLimitRangeAtBeginAndEnd()
    {
        $rangePages = $this->smallFilePrinter->getRangePagination(2);
        $this->assertEquals([0, 2], $rangePages);
    }

    //
    public function testListLines()
    {
        $fileTest = [];
        foreach(range(45, 57) as $line) {
            $fileTest[$line] = "Current line is {$line}.\n"; // Number of line, not index
        }
        $file = $this->bigFilePrinter->listLines(50);
        $this->assertEquals($fileTest, $file);
    }

    public function testColorCodeVariables()
    {
        $content = '; $variable = 33';
        $colored = $this->bigFilePrinter->colorCode($content);
        $this->assertEquals('; <fg=cyan>$variable</> = 33', $colored);
    }

    public function testColorCodeFunction()
    {
        $content = 'xdebug_break();';
        $colored = $this->bigFilePrinter->colorCode($content);
        $this->assertEquals('<fg=green;options=bold>xdebug_break</>();', $colored);
    }

    public function testColorCodeReservedWords()
    {
        $content = 'return;';
        $colored = $this->bigFilePrinter->colorCode($content);
        $this->assertEquals('<fg=blue>return</>;', $colored);
    }

    public function testColorCodeConstWords()
    {
        $content = '__DIR__ __FILE__';
        $colored = $this->bigFilePrinter->colorCode($content);
        $this->assertEquals('<fg=red>__DIR__</> <fg=red>__FILE__</>', $colored);
    }

    public function testColorCodeStrings()
    {
        $content = '"Works"; \'works\'';
        $colored = $this->bigFilePrinter->colorCode($content);
        $this->assertEquals('<fg=green>"Works"</>; <fg=green>\'works\'</>', $colored);
    }
}
