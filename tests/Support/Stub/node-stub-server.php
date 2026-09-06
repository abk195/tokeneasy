<?php
/**
 * Stub of the external node/blockchain service used by callNodeOperations().
 *
 * Runs under `php -S` (see Tests\Support\FakeNodeServer). It is deliberately
 * dumb: every request is appended to requests.jsonl so tests can assert what the
 * platform actually sent to the chain, and every response is looked up in
 * responses.json so tests can script success/failure per endpoint.
 */

$dir = getenv('NODE_STUB_DIR');
if (!$dir || !is_dir($dir)) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['status' => 'error', 'message' => 'NODE_STUB_DIR not configured']);
    return true;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);

file_put_contents(
    $dir . '/requests.jsonl',
    json_encode([
        'path'   => $path,
        'method' => $_SERVER['REQUEST_METHOD'],
        'query'  => $_GET,
        'body'   => is_array($body) ? $body : null,
        'raw'    => $raw,
    ]) . "\n",
    FILE_APPEND | LOCK_EX
);

$responses = [];
if (is_file($dir . '/responses.json')) {
    $responses = json_decode(file_get_contents($dir . '/responses.json'), true) ?: [];
}

$response = isset($responses[$path]) ? $responses[$path] : null;

if ($response === null) {
    header('Content-Type: application/json', true, 404);
    echo json_encode([
        'status'  => 'error',
        'message' => 'No stub response configured for ' . $path,
    ]);
    return true;
}

// A scripted queue: pop one response per call so a test can make the second
// call to the same endpoint behave differently from the first.
if (isset($response['__queue']) && is_array($response['__queue'])) {
    $queue = $response['__queue'];
    $next  = array_shift($queue);
    if (empty($queue)) {
        unset($responses[$path]);
    } else {
        $responses[$path]['__queue'] = $queue;
    }
    file_put_contents($dir . '/responses.json', json_encode($responses), LOCK_EX);
    $response = $next;
}

$status = isset($response['__http']) ? (int) $response['__http'] : 200;
unset($response['__http']);

header('Content-Type: application/json', true, $status);
echo json_encode($response);
return true;
