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
    public function testSetFilename()
    {
        $filename = __DIR__ . '/../data/file_3_lines.txt';
        $filePrinter = new FilePrinter();
        $filePrinter->setFilename($filename);
        $this->assertEquals(3, count($filePrinter->file));
    }

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

    // Test limit
    public function testListLines()
    {
        $fileTest = [];
        foreach(range(45, 57) as $line) {
            $fileTest[$line] = "Current line is {$line}.\n"; // Number of line, not index
        }
        $file = $this->bigFilePrinter->listLines(50);
        $this->assertEquals($fileTest, $file);
    }

    // Test print
    public function testPrintCallingShowFile()
    {
        $mock = $this->getMockBuilder('\Dephpug\FilePrinter')
              ->setMethods(['setFilename', 'unformatedShowFile'])
              ->getMock();

        $mock->expects($this->exactly(1))
            ->method('setFilename')
            ->withConsecutive(['/tmp/test']);

        $mock->expects($this->exactly(1))
            ->method('unformatedShowFile')
            ->withConsecutive(['1']);

        $mock->printFileByMessage('lineno="1" filename="file:///tmp/test"');
    }

    public function testPrintValueWithAnInteger()
    {
        $message = 'command="property_get" type="int" <![CDATA[1]]>';
        $filePrinter = new FilePrinter();
        $response = $filePrinter->printValue($message);
        $this->assertEquals(" => (int) 1\n\n", $response);
    }

    public function testPrintValueWithAFloat()
    {
        $message = 'command="property_get" type="float" <![CDATA[3.141]]>';
        $filePrinter = new FilePrinter();
        $response = $filePrinter->printValue($message);
        $this->assertEquals(" => (float) 3.141\n\n", $response);
    }

    public function testPrintValueWithAClass()
    {
        $message = 'command="property_get" classname="stdClass" type="object" <![CDATA[1]]>';
        $filePrinter = new FilePrinter();
        $response = $filePrinter->printValue($message);
        $this->assertEquals(" => (object stdClass) 1\n\n", $response);
    }

    public function testPrintValueWithAnError()
    {
        $message = '<error code="300" <![CDATA[can not get property]]>';
        $filePrinter = new FilePrinter();
        $response = $filePrinter->printValue($message);
        $this->assertEquals("<fg=red;options=bold>Error code: 300 - can not get property</>", $response);
    }

    public function testMixArray()
    {
        // Example of response
        $message = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="2">
   <property name="\$array" fullname="\$array" type="array" children="1" numchildren="13" page="0" pagesize="32">
      <property name="0" fullname="\$array[0]" type="int"><![CDATA[0]]></property>
      <property name="1" fullname="\$array[1]" type="int"><![CDATA[1]]></property>
      <property name="2" fullname="\$array[2]" type="int"><![CDATA[2]]></property>
      <property name="3" fullname="\$array[3]" type="int"><![CDATA[3]]></property>
      <property name="4" fullname="\$array[4]" type="int"><![CDATA[4]]></property>
      <property name="5" fullname="\$array[5]" type="int"><![CDATA[5]]></property>
      <property name="6" fullname="\$array[6]" type="int"><![CDATA[6]]></property>
      <property name="7" fullname="\$array[7]" type="int"><![CDATA[7]]></property>
      <property name="8" fullname="\$array[8]" type="int"><![CDATA[8]]></property>
      <property name="9" fullname="\$array[9]" type="int"><![CDATA[9]]></property>
      <property name="withString" fullname="\$array[&amp;#39;withString&amp;#39;]" type="string" size="10" encoding="base64"><![CDATA[d2l0aFN0cmluZw==]]></property>
      <property name="numbers" fullname="\$array[&amp;#39;numbers&amp;#39;]" type="array" children="1" numchildren="3" />
      <property name="children" fullname="\$array[&amp;#39;children&amp;#39;]" type="array" children="1" numchildren="2" />
   </property>
</response>
EOL;
        $filePrinter = new FilePrinter();
        $response = $filePrinter->printValue($message);

        $jsonPrettyFormat = json_encode(['0','1','2','3','4','5','6','7','8','9','withString' => 'withString', 'numbers' => '(array) [...]', 'children' => '(array) [...]'], JSON_PRETTY_PRINT);

        $this->assertEquals(" => (array) \n{$jsonPrettyFormat}\n\n", $response);
    }

    public function testUnformatedShowFile()
    {
        $unformated = $this->smallFilePrinter->unformatedShowFile(2);
        $content = <<<EOL
\n<fg=blue>[1:3] in file://:2</>
   <fg=yellow>1:</> <fg=white>First line
</><fg=magenta;options=bold>=> </><fg=yellow>2:</> <fg=white>Second line
</>   <fg=yellow>3:</> <fg=white>Third line
</>
EOL;
        $this->assertEquals($content, $unformated);
    }

    // Color codes
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
