<?php
/*
 * var mqtt = require('mqtt')

var options = {
    host: '9ae9e5289d084555b42f28d4ca8650f5.s1.eu.hivemq.cloud',
    port: 8883,
    protocol: 'mqtts',
    username: 'papipl',
    password: '<your-password>'
}

//initialize the MQTT client
var client = mqtt.connect(options);

//setup the callbacks
client.on('connect', function () {
    console.log('Connected');
});

client.on('error', function (error) {
    console.log(error);
});

client.on('message', function (topic, message) {
    //Called each time a message is received
    console.log('Received message:', topic, message.toString());
});

// subscribe to topic 'my/test/topic'
client.subscribe('my/test/topic');

// publish message 'Hello' to topic 'my/test/topic'
client.publish('my/test/topic', 'Hello');
 */

namespace App\Client;

use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;
use Psr\Log\LoggerInterface;

// https://www.emqx.com/en/blog/how-to-use-mqtt-in-php
// https://console.hivemq.cloud/clusters/detail?uuid=9ae9e5289d084555b42f28d4ca8650f5&nav=gettingStarted
class QueueClient
{
    private ConnectionSettings $connectionSettings;
    private MqttClient $mqtt;

    public function __construct(LoggerInterface $logger = null)
    {
        $server   = '9ae9e5289d084555b42f28d4ca8650f5.s1.eu.hivemq.cloud';
        $port     = 8883;
        $clientId = 'papi1'; rand(5, 15);
        $username = 'server';
        $password = 'QWerty08!!';

        $this->connectionSettings  = new ConnectionSettings();
        $this->connectionSettings
            ->setUsername($username)
            ->setPassword($password)
            ->setUseTls(true)
        ;

        $this->mqtt = new MqttClient($server, $port, $clientId, '3.1', null, $logger);
        $w = $this->mqtt->connect($this->connectionSettings);
//        $this->subscribe();
//        $this->mqtt->loop();
        //subscribe
        //publish
    }

    public function subscribe()
    {
        $this->mqtt->subscribe('sensor1', function ($topic, $message) {
            printf("Received message on topic [%s]: %s\n", $topic, $message);
        }, 0);
    }
    public function publish()
    {
        for ($i = 0; $i< 10; $i++) {
            $payload = array(
                'protocol' => 'tcp',
                'date' => date('Y-m-d H:i:s'),
                'url' => 'https://github.com/emqx/MQTT-Client-Examples'
            );
            $this->mqtt->publish(
            // topic
                'emqx/test',
                // payload
                json_encode($payload),
                // qos
                0,
                // retain
                true
            );
            printf("msg $i send\n");
            sleep(1);
        }

        $this->mqtt->loop(true);
    }
}
