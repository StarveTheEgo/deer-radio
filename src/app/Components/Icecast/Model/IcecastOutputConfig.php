<?php

declare(strict_types=1);

namespace App\Components\Icecast\Model;

class IcecastOutputConfig
{
    /** @var string */
    private string $host;

    /** @var string */
    private string $port;

    /** @var string */
    private string $user;

    /** @var string */
    private string $password;

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return IcecastOutputConfig
     */
    public function setHost(string $host): IcecastOutputConfig
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param string $port
     * @return IcecastOutputConfig
     */
    public function setPort(string $port): IcecastOutputConfig
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return IcecastOutputConfig
     */
    public function setUser(string $user): IcecastOutputConfig
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return IcecastOutputConfig
     */
    public function setPassword(string $password): IcecastOutputConfig
    {
        $this->password = $password;
        return $this;
    }
}
