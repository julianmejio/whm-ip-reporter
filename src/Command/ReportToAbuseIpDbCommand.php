<?php

namespace Mehio\WhmUtil\IpReporter\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mehio\WhmUtil\IpReporter\Client\AbuseIpDbClient;
use Mehio\WhmUtil\IpReporter\Dbal\Database;
use Mehio\WhmUtil\IpReporter\Entity\IpReport;
use Mehio\WhmUtil\IpReporter\Model\AbuseIpDbEntryModel;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReportToAbuseIpDbCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'report:send:abuseipdb';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var AbuseIpDbClient
     */
    private $abuseClient;

    public function __construct(string $name = null)
    {
        $this->entityManager = (new Database())->getEntityManager();
        $this->abuseClient = new AbuseIpDbClient();
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $reportEntries = [];
        $observationRepository = $this->entityManager->getRepository(IpReport::class);
        $observations = $observationRepository->findBy(['status' => IpReport::STATUS_OBSERVED]);
        /** @var IpReport $observation */
        foreach ($observations as $observation) {
            $observation->setStatus(IpReport::STATUS_REPORTED);
            if (!isset($reportEntries[$observation->getIpAddress()])) {
                $reportEntries[$observation->getIpAddress()] = (new AbuseIpDbEntryModel())
                    ->setIpAddress($observation->getIpAddress())
                ;
            }
            $categories = $reportEntries[$observation->getIpAddress()]->getCategories();
            $comments = $reportEntries[$observation->getIpAddress()]->getComments();
            array_push($categories, $this->getCategory($observation->getRuleId()));
            array_push($comments, $observation->getReason());
            $reportEntries[$observation->getIpAddress()]->setCategories($categories);
            $reportEntries[$observation->getIpAddress()]->setComments($comments);
            $this->entityManager->persist($observation);
        }
        $pb = new ProgressBar($output, count($reportEntries));
        $pb->start();
        foreach ($reportEntries as $entry) {
            $payload = $this->generateModelPayload($entry);
            $pb->advance();
            $this->abuseClient->sendReport($payload);
        }
        $pb->finish();
        $pb->clear();
        $this->entityManager->flush();
    }

    private function generateModelPayload(AbuseIpDbEntryModel $model): string
    {
        $payload = ['ip' => $model->getIpAddress()];
        $payload['categories'] = implode(',', array_unique($model->getCategories()));
        $payload['comment'] = substr(implode(PHP_EOL, array_unique($model->getComments())), 0, 980);

        return http_build_query($payload);
    }

    private function getCategory(int $ruleId): int
    {
        switch ($ruleId) {
            case 913100:
            case 920420:
            case 920440:
            case 933100:
            case 933120:
            case 933130:
            case 933140:
            case 933150:
            case 933170:
            case 941100:
            case 941130:
                return 21;
            case 920100:
            case 920170:
            case 920280:
            case 920340:
            case 930100:
            case 930110:
            case 930120:
            case 930130:
                return 15;
            case 0:
                return 18;
            case 942100:
                return 16;
        }

        return 15;
    }
}
