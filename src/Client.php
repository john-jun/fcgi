<?php
declare(strict_types=1);
namespace Air\FCgi;

use Air\SocketClient\Exception\ConnectException;
use Air\SocketClient\Exception\TimeoutException;
use Air\SocketClient\Exception\WriteFailedException;
use Air\SocketClient\NetAddressInterface;
use Air\SocketClient\Socket;

/**
 * Class Client
 * @package Air\FCgi
 */
class Client
{
    /**
     * @var Socket
     */
    protected $socket;

    /**
     * Client constructor.
     * @param NetAddressInterface $netAddress
     */
    public function __construct(NetAddressInterface $netAddress)
    {
        $this->socket = new Socket($netAddress);
    }


    public function execute(Request $request, float $timeout = -1): ?Response
    {
        $this->socket->connect($timeout);
        $this->socket->send((string)$request, $timeout);

        do {
            if ($header = $this->socket->recv(FastCGI::HEADER_LEN)) {
                $packet = unpack(FastCGI::HEADER_FORMAT, $header);
                var_dump($packet);
            }
        } while (true);

        return null;
    }
}
