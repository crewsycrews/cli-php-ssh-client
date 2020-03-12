<?php

namespace App\Commands;

use DivineOmega\SSHConnection\SSHConnection;
use League\CLImate\CLImate;

/**
 * Default command execution class.
 */
class SSHConnectCommand
{
    protected $cliMate;
    protected $connection;
    protected string $host;
    protected int $port;
    protected string $username;
    protected string $password;

    /**
     * Pass the cliMate to use inside commands.
     */
    public function __construct(CLImate $cliMate, SSHConnection $connection)
    {
        $this->cliMate = $cliMate;
        $this->connection = $connection;
        $this->configureArguments();
    }

    /**
     * Actual handling functionality.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->cliMate->arguments->defined('help')) {
            $this->cliMate->usage();

            return;
        }
        try {
            $this->cliMate->arguments->parse();
        } catch (\Throwable $th) {
            $this->cliMate->usage();
            $this->cliMate->error($th->getMessage());
            // exit;
        }

        $this->setCredentials(
            $this->cliMate->arguments->get('user'),
            $this->cliMate->arguments->get('password'),
            $this->cliMate->arguments->get('host'),
            $this->cliMate->arguments->defined('port') ? $this->cliMate->arguments->get('port') : 22
        );
        try {
            $this->connection
                ->to($this->host)
                ->onPort($this->port)
                ->as($this->username)
                ->withPassword($this->password)
                ->connect();
        } catch (\Throwable $th) {
            $this->cliMate->error($th->getMessage());
            exit;
        }
        // $command = $this->connection->run('zsh');
        $command = $this->connection->run('service sockd restart');

        $this->cliMate->backgroundGreen($command->getOutput());
    }

    /**
     * Sets all needed information for SSH connection.
     *
     * @return void
     */
    protected function setCredentials(string $username, string $password, string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Add descriptions for arguments for our tool.
     *
     * @return void
     */
    protected function configureArguments()
    {
        $this->cliMate->arguments->add([
            'help' => [
                'longPrefix' => 'help',
                'description' => 'Prints this message',
                'noValue' => true,
            ],
            'port' => [
                'longPrefix' => 'port',
                'description' => 'Port for the connection',
                'castTo' => 'int',
            ],
            'host' => [
                'prefix' => 'h',
                'longPrefix' => 'host',
                'description' => 'Host machine',
                'required' => true,
            ],
            'user' => [
                'prefix' => 'u',
                'longPrefix' => 'user',
                'description' => 'Username',
                'required' => true,
            ],
            'password' => [
                'prefix' => 'p',
                'longPrefix' => 'password',
                'description' => 'Password',
                'required' => true,
            ],
        ]);
    }
}
