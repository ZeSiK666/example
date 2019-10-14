<?php
declare(strict_types=1);

namespace Example\Competitors\Module\Saver;

use Example\AnalyticsTop100\Module\DataLoader\HostRatioLoader;
use Example\AnalyticsTop100\Module\Command\InsertDataCommand;
use Example\Base\ConsumerCommandInterface;

class HostRatioDiffSaver implements ConsumerCommandInterface
{
    /**
     * @var InsertDataCommand
     */
    private $insertCommand;
    /**
     * @var HostRatioLoader
     */
    private $loader;
    
    public function __construct(InsertDataCommand $insertCommand, HostRatioLoader $loader)
    {
        $this->insertCommand = $insertCommand;
        $this->loader        = $loader;
    }
    
    public function execute(array $messageAsArray): void
    {
        $this->loader->flush();
        $this->loader->configure(
            [
                HostRatioLoader::CONF_HOST_FROM_ID => $messageAsArray['hostFrom'][\Host::FIELD__ID],
                HostRatioLoader::CONF_HOSTS_TO_ID  => $messageAsArray['hostsToIds'],
            ]
        );
        $this->loader->prepare();
        $hostRatio = $this->loader->getData();
        
        $hostRatioIds = array_column($hostRatio, \HostRatio::FIELD__HOST_TO_ID);
        $hostDiff     = array_diff($messageAsArray['hostsToIds'], $hostRatioIds);
        
        if (!empty($hostDiff)) {
            $this->insertHostRatio($messageAsArray['hostFrom'], $hostDiff);
        }
    }
    
    private function insertHostRatio(array $hostFrom, array $hostDiff): void
    {
        $dataInsert = [];
        foreach ($hostDiff as $host) {
            $dataInsert[] = [
                \HostRatio::FIELD__HOST_FROM_ID => $hostFrom[\Host::FIELD__ID],
                \HostRatio::FIELD__HOST_TO_ID   => $host,
                \HostRatio::FIELD__PROJECT_ID   => $hostFrom[\Host::FIELD__PROJECT_ID],
            ];
        }
        
        $this->insertCommand->execute(
            [
                InsertDataCommand::PARAM_TABLE => \HostRatio::TABLE_NAME,
                InsertDataCommand::PARAM_DATA  => $dataInsert,
            ]
        );
    }
}