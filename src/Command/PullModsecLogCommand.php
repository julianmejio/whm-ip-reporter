<?php

namespace Mehio\WhmUtil\IpReporter\Command;

use Mehio\WhmUtil\IpReporter\Client\WhmClient;
use Mehio\WhmUtil\IpReporter\Dbal\Database;
use Mehio\WhmUtil\IpReporter\Entity\IpReport;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullModsecLogCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'whm:pull-modsec-log';

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
        $this
            ->setDescription('This command gets the modsec log')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->client->call('GET', 'json-api/modsec_get_log?api.version=1');
        $response = json_decode($result->getContent());
        $entityManager = (new Database())->getEntityManager();
        $repository = $entityManager->getRepository(IpReport::class);
        foreach ($response->data->data as $report) {
            $ipReport = $repository->findOneBy(['reportId' => $report->id]);
            if (null !== $ipReport || in_array($report->meta_id, [949110, 980130])) {
                continue;
            }
            $ipReport = (new IpReport())
                ->setDateObserved(new \DateTime($report->timestamp))
                ->setIpAddress($report->ip)
                ->setReason($report->meta_msg)
                ->setReportId($report->id)
                ->setRuleId($report->meta_id)
                ->setType(IpReport::TYPE_MODSEC)
            ;
            $entityManager->persist($ipReport);
        }
        $entityManager->flush();

        return 0;
    }
}
