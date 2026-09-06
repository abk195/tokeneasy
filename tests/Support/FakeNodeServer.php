<?php

namespace Tests\Support;

/**
 * Boots the stub node service (tests/Support/Stub/node-stub-server.php) on a
 * local port and points config('app.nodeApp') at it.
 *
 * The real callNodeOperations() helper builds its own Guzzle client, so it
 * cannot be mocked from the outside. Standing up a real HTTP endpoint instead
 * means the tests exercise the genuine request/response path, including the
 * helper's habit of throwing when the node reports anything but status=success.
 *
 * One server is shared by the whole run; its state is reset per test.
 */
class FakeNodeServer
{
    const SECURITY_CONTRACT = '0xSecurityTokenContractAddress00000000001';
    const UTILITY_CONTRACT  = '0xUtilityTokenContractAddress000000000002';

    /** @var resource|null */
    private static $process;

    /** @var string|null */
    private static $baseUrl;

    /** @var string|null */
    private static $dir;

    public static function start(): string
    {
        if (self::$baseUrl !== null) {
            return self::$baseUrl;
        }

        self::reset();

        $port   = self::findFreePort();
        $router = __DIR__ . DIRECTORY_SEPARATOR . 'Stub' . DIRECTORY_SEPARATOR . 'node-stub-server.php';

        // Array form (PHP >= 7.4) bypasses the shell, which matters on Windows:
        // cmd.exe mangles a string command whose first token is quoted.
        $command = [PHP_BINARY, '-S', '127.0.0.1:' . $port, $router];

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', self::$dir . '/server-out.log', 'a'],
            2 => ['file', self::$dir . '/server-err.log', 'a'],
        ];

        // proc_open() only accepts scalar environment values; $_SERVER carries
        // arrays such as argv.
        $environment = ['NODE_STUB_DIR' => self::$dir];
        foreach ($_SERVER as $key => $value) {
            if (is_scalar($value) && !isset($environment[$key])) {
                $environment[$key] = (string) $value;
            }
        }

        self::$process = proc_open($command, $descriptors, $pipes, null, $environment);

        if (!is_resource(self::$process)) {
            throw new \RuntimeException('Unable to start the stub node server.');
        }

        self::waitUntilListening($port);

        self::$baseUrl = 'http://127.0.0.1:' . $port;

        register_shutdown_function([self::class, 'stop']);

        return self::$baseUrl;
    }

    public static function stop()
    {
        if (is_resource(self::$process)) {
            proc_terminate(self::$process);
            proc_close(self::$process);
            self::$process = null;
        }
        self::$baseUrl = null;
    }

    public static function baseUrl(): string
    {
        return self::start();
    }

    public static function dir(): string
    {
        if (self::$dir === null) {
            self::$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'tokeneasy-node-stub-' . getmypid();
        }

        if (!is_dir(self::$dir)) {
            mkdir(self::$dir, 0777, true);
        }

        return self::$dir;
    }

    /**
     * Clear recorded traffic and reinstall the happy-path responses.
     */
    public static function reset()
    {
        $dir = self::dir();

        file_put_contents($dir . '/requests.jsonl', '');
        file_put_contents($dir . '/responses.json', json_encode(self::defaultResponses()));
    }

    /**
     * Happy-path node behaviour: keystores readable, gas available, deploys and
     * transfers succeed.
     */
    public static function defaultResponses(): array
    {
        return [
            '/generateNewPrivateKey' => [
                'status'     => 'success',
                'privateKey' => '0xpriv-generated',
                'address'    => '0xGeneratedPublicAddress0000000000000001',
            ],
            '/savePrivateKeytodisk' => [
                'status'   => 'success',
                'filename' => 'keystore-stub.json',
                'address'  => '0xGeneratedPublicAddress0000000000000001',
            ],
            '/readPrivateKeyFromDisk' => [
                'status'     => 'success',
                'privatekey' => '0xissuer-private-key',
                'publicKey'  => '0xIssuerPublicAddress00000000000000000001',
            ],
            // KeystoreController::createAndSaveKeyStoreFile() reads publicAddress;
            // address is carried too because other callers use that key.
            '/getPublicAddressFromPrivateKey' => [
                'status'        => 'success',
                'publicAddress' => '0xIssuerPublicAddress00000000000000000001',
                'address'       => '0xIssuerPublicAddress00000000000000000001',
            ],
            '/native_balance' => [
                'status'  => 'success',
                'balance' => 5,
            ],
            '/deploySecurityToken' => [
                'status'   => 'success',
                'contract' => ['contract' => ['address' => self::SECURITY_CONTRACT]],
                'txHash'   => '0xdeploy-security-tx',
            ],
            '/deployUtilityToken' => [
                'status'   => 'success',
                'contract' => ['contract' => ['address' => self::UTILITY_CONTRACT]],
                'txHash'   => '0xdeploy-utility-tx',
            ],
            '/tokendetails' => [
                'status' => 'success',
                'name'   => 'Stub Token',
            ],
            '/getKey' => [
                'status' => 'success',
                'key'    => '0xissuer-private-key',
            ],
            '/whitelist' => [
                'status' => 'success',
                'txHash' => '0xwhitelist-tx',
            ],
            '/transfer' => [
                'status' => 'success',
                'txHash' => '0xtransfer-tx',
            ],
            '/balance' => [
                'status'  => 'success',
                'balance' => 1000,
            ],
        ];
    }

    /**
     * Override the response for a single node endpoint.
     */
    public static function setResponse(string $path, array $response)
    {
        $responses        = self::responses();
        $responses[$path] = $response;
        file_put_contents(self::dir() . '/responses.json', json_encode($responses), LOCK_EX);
    }

    /**
     * Script a sequence of responses for one endpoint, consumed one per call.
     */
    public static function queueResponses(string $path, array $sequence)
    {
        self::setResponse($path, ['__queue' => array_values($sequence)]);
    }

    public static function responses(): array
    {
        $file = self::dir() . '/responses.json';

        return is_file($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
    }

    /**
     * Every request the platform made to the node, in order.
     */
    public static function requests($path = null): array
    {
        $file = self::dir() . '/requests.jsonl';
        if (!is_file($file)) {
            return [];
        }

        $requests = [];
        foreach (explode("\n", trim(file_get_contents($file))) as $line) {
            if ($line === '') {
                continue;
            }
            $decoded = json_decode($line, true);
            if ($decoded && ($path === null || $decoded['path'] === $path)) {
                $requests[] = $decoded;
            }
        }

        return $requests;
    }

    public static function lastRequest($path = null)
    {
        $requests = self::requests($path);

        return $requests ? end($requests) : null;
    }

    public static function callCount(string $path): int
    {
        return count(self::requests($path));
    }

    private static function findFreePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
        if (!$socket) {
            throw new \RuntimeException("Unable to find a free port: {$errstr}");
        }
        $name = stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr($name, strrpos($name, ':') + 1);
    }

    private static function waitUntilListening(int $port)
    {
        $deadline = microtime(true) + 15;
        while (microtime(true) < $deadline) {
            $connection = @stream_socket_client("tcp://127.0.0.1:{$port}", $errno, $errstr, 0.2);
            if ($connection) {
                fclose($connection);

                return;
            }
            usleep(50000);
        }

        throw new \RuntimeException("Stub node server did not start listening on port {$port}.");
    }
}
