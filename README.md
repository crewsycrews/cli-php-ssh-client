# SHH CLI PHP client

Small client for regular tasks. Requires PHP 7.4

Usage:
```bash
$ php crews-cli-ssh-php-client
Usage: crews-cli-ssh-php-client [--help] [-h host, --host host] [-p password, --password password] [--port port] [-u user, --user user]

Required Arguments:
        -h host, --host host
                Host machine
        -u user, --user user
                Username
        -p password, --password password
                Password

Optional Arguments:
        --help
                Prints this message
        --port port
                Port for the connection
```
Actual command is placed under `app/Commands/SSHConnectCommand.php`. We make it be passed as argument to our tool later.
