<?php

namespace Mehio\WhmUtil\IpReporter\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class IpReport.
 *
 * @ORM\Table(name="ip_report")
 * @ORM\Entity()
 */
class IpReport
{
    const STATUS_OBSERVED = 11;
    const STATUS_REPORTED = 12;

    const TYPE_HULK = 21;
    const TYPE_MODSEC = 22;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_observed", type="datetime")
     */
    private $dateObserved;

    /**
     * @var string
     * @ORM\Column(name="ip_address", type="string", length=15)
     */
    private $ipAddress;

    /**
     * @var int
     * @ORM\Column(name="report_id", type="integer", nullable=true)
     */
    private $reportId;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var null|int
     * @ORM\Column(name="rule_id", type="integer", nullable=true)
     */
    private $ruleId;

    /**
     * @var null|string
     * @ORM\Column(name="reason", type="text", nullable=true)
     */
    private $reason;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $status;

    public function __construct()
    {
        $this->dateObserved = new \DateTime();
        $this->status = self::STATUS_OBSERVED;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDateObserved(): \DateTime
    {
        return $this->dateObserved;
    }

    public function setDateObserved(\DateTime $dateObserved): IpReport
    {
        $this->dateObserved = $dateObserved;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): IpReport
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getReportId(): int
    {
        return $this->reportId;
    }

    public function setReportId(int $reportId): IpReport
    {
        $this->reportId = $reportId;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): IpReport
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): IpReport
    {
        $this->status = $status;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): IpReport
    {
        $this->reason = $reason;

        return $this;
    }

    public function getRuleId(): ?int
    {
        return $this->ruleId;
    }

    public function setRuleId(?int $ruleId): IpReport
    {
        $this->ruleId = $ruleId;

        return $this;
    }
}
