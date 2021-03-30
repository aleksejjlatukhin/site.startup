<?php

require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;


$wsWorker = new Worker('websocket://0.0.0.0:2346');

$wsWorker->onConnect = function ($connection) {
    echo "New connection \n";
};

$wsWorker->onMessage = function ($connection, $data) use ($wsWorker) {
    foreach($wsWorker->connections as $clientConnection) {
        $clientConnection->send($data);
    }
    //$connection->send($data);
};

$wsWorker->onClose = function ($connection) {
    echo "Connection closed\n";
};


Worker::runAll();