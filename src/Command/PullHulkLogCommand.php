<?php

namespace Mehio\WhmUtil\IpReporter\Command;

use Mehio\WhmUtil\IpReporter\Client\WhmClient;
use Mehio\WhmUtil\IpReporter\Dbal\Database;
use Mehio\WhmUtil\IpReporter\Entity\IpReport;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullHulkLogCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'whm:pull-hulk-log';

    /**
     * @var WhmClient
     */
    private $client;

    public function __construct(string $name = null)
    {
        $this->client = new WhmClient();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('This gets the Hulk log');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->client->call('GET', 'json-api/get_cphulk_failed_logins?api.version=1');
        $response = json_decode($result->getContent());
        $entityManager = (new Database())->getEntityManager();
        $repository = $entityManager->getRepository(IpReport::class);
        foreach ($response->data->failed_logins as $report) {
            $ipReport = $repository->findOneBy([
                'ipAddress' => $report->ip,
                'dateObserved' => new \DateTime($report->logintime),
            ]);
            if (null !== $ipReport) {
                continue;
            }
            $ipReport = (new IpReport())
                ->setDateObserved(new \DateTime($report->logintime))
                ->setIpAddress($report->ip)
                ->setReason('Brute force against ' . $report->service . ' service (' . $report->authservice . ')')
                ->setRuleId(0)
                ->setType(IpReport::TYPE_HULK)
            ;
            $entityManager->persist($ipReport);
        }
        $entityManager->flush();

        return 0;
    }
}
