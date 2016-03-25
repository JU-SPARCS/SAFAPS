<?php

// Start RabbitMq Server
//shell_exec('rabbitmq-server &');


// Start Rpc
// Don't forget to close the connection in the RabbitMq interface management in table "Connections"
// Or use htop to kill the processus (but it's not install yet on the server)
//shell_exec('./console rabbitmq:rpc-server rabbitMqServer &');

?>
