<?php

use Dephpug\Dbgp\Client;

class DbgpClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->dbgpClient = new Client();
    }

    public function testAutoIncrementInTransactionId()
    {
        $id = $this->dbgpClient->getTransactionId();
        $this->assertEquals($id+1, $this->dbgpClient->getTransactionId());
    }

    public function testStepIntoExecution()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('step_into -i 0');

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->stepInto();
    }

    public function testStepNextExecution()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('step_over -i 0');

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->next();
    }

    public function testStepContinueExecution()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('run -i 0');

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->continue();
    }

    public function testEvalExecution()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $command = 'str_repeat("a", 10)';
        
        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('eval -i 0 -- '.base64_encode($command));

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->eval($command);
    }

    public function testPropertyGetExecution()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('property_get -i 0 -n varname');

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->propertyGet('varname');
    }

    public function testPropertySetExecution()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('property_set -i 0 -n $varname -- '.base64_encode('value'));

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->propertySet('varname', 'value');
    }

    public function testIfHasMessage()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand');

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->run('command fake');
        $this->assertTrue($dbgpClient->hasMessage());
    }

    public function testGettingCorrectTransactionId()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('command -i 0');

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->run('command -i {id}');
    }

    public function testGetResponseNullIfDoesntHaveMessage()
    {
        $dbgpClient = new Client();
        $response = $dbgpClient->getResponse();
        $this->assertEquals(null, $response);
    }

    public function testGettingMessageWhenSendToDbgp()
    {
        $dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
            ->setMethods(['sendCommand', 'getResponse'])
            ->getMock();

        $dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('fake command');

        $dbgpServer->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue('fake content'));

        $dbgpClient = new Client();
        $dbgpClient->dbgpServer = $dbgpServer;
        $dbgpClient->run('fake command');
        $response = $dbgpClient->getResponse();
        $this->assertEquals('fake content', $response);
    }
}
