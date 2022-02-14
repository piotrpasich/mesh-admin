var mqtt = require('mqtt')
const { exec } = require("child_process");

var options = {
    host: '9ae9e5289d084555b42f28d4ca8650f5.s1.eu.hivemq.cloud',
    port: 8883,
    protocol: 'mqtts',
    username: 'server',
    password: 'QWerty08!!'
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
    console.log("\n\n========================================================================\n\n")
    console.log('Received message:', message.toString(), topic);
    exec('../bin/console  QueueListener \'' + message.toString().trim() + '\' \'' + topic + '\'',(error, stdout, stderr) => {
        // if (error) {
        //     console.log(`error: ${error.message}`);
        //     return;
        // }
        // if (stderr) {
        //     console.log(`stderr: ${stderr}`);
        //     return;
        // }
        console.log(stdout, error, stderr)
        console.log("\n\n========================================================================\n\n")
    });
});

client.subscribe('network');
