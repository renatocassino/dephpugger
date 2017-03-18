<?php

use Dephpug\MessageParse;

class MessageParseTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $messageParse;

    protected function _before()
    {
        $this->messageParse = new MessageParse();
    }

    protected function _after()
    {
    }

    // tests
    public function testRemovingNumbersBeforeXML()
    {
        $message = '400<?xml ...';
        $formatedMessage = $this->messageParse->formatMessage($message);
        $this->assertEquals('<?xml ...', $formatedMessage);
    }

    public function testFileAndLineWithExistValue()
    {
        $message = '<?xml?><response><xdebug:message filename="file:///path/of/file.php" lineno="2"></xdebug:message></response>';
        $fileAndLine = $this->messageParse->getFileAndLine($message);
        $this->assertEquals(['/path/of/file.php', 2], $fileAndLine);
    }

    public function testFileAndLineWithUnexistValue()
    {
        $message = '<?xml?><response><xdebug:message donthavefilenameandnumber="true"></xdebug:message></response>';
        $fileAndLine = $this->messageParse->getFileAndLine($message);
        $this->assertEquals(null, $fileAndLine);
    }
}