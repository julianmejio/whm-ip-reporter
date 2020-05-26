<?php

namespace Mehio\WhmUtil\IpReporter\Model;

class AbuseIpDbEntryModel
{
    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @var array
     */
    private $categories;

    /**
     * @var array
     */
    private $comments;

    public function __construct()
    {
        $this->categories = [];
        $this->comments = [];
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): AbuseIpDbEntryModel
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): AbuseIpDbEntryModel
    {
        $this->categories = $categories;

        return $this;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function setComments(array $comments): AbuseIpDbEntryModel
    {
        $this->comments = $comments;

        return $this;
    }
}
