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
console.log('PUBLISH: ' + process.argv[2] + ':' + process.argv[3])
client.publish(process.argv[3], process.argv[2], function () {
    client.end()
});
