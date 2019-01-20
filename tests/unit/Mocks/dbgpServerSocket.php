<?php

namespace Dephpug\Dbgp;

class GlobalAttribute
{
    public static $startClientValue;
    public static $socketRecv;
    public static $buffer;
    public static $socketWrite;
    public static $socketAccept;
}

function startClient($host = 'localhost', $port = 9005)
{
    return GlobalAttribute::$startClientValue;
}

function socket_recv($socket, &$buffer, $bytes, $n)
{
    $buffer = GlobalAttribute::$buffer;
    return GlobalAttribute::$socketRecv;
}

function socket_write($socket, $command)
{
    return GlobalAttribute::$socketWrite;
}

function socket_strerror($errorSocket)
{
    return 'fake code error';
}

function socket_last_error($socket)
{
    return 'fake error';
}

function socket_accept($socket)
{
    return GlobalAttribute::$socketAccept;
}
