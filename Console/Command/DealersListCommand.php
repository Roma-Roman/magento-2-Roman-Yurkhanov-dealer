<?php
/**
 * Roman Yurkhanov
 *
 * Email :: ferrumdp@gmail.com
 * Linkedin :: https://www.linkedin.com/in/roman-yurkhanov-322035122/
 *
 * Copyright 2024-present Roman Yurkhanov. All rights reserved.
 * See license.txt for license details.
 */

namespace RomanYurkhanov\Dealer\Console\Command;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem\Io\File;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;

class DealersListCommand extends Command
{
    const XML_DEALER_LIST_PATH = 'romanyurkhanov/dealer/list/';
    const XML_DEALER_LIST_WITH_REVIEWS_PATH = 'romanyurkhanov/dealer/list/reviews/';

    private DealerRepositoryInterface $dealerRepository;
    private DealerReviewsRepositoryInterface $dealerReviewsRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private DirectoryList $directoryList;
    private StoreManagerInterface $storeManager;
    private File $file;

    public function __construct(
        DealerRepositoryInterface        $dealerRepository,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        SearchCriteriaBuilder            $searchCriteriaBuilder,
        DirectoryList                    $directoryList,
        StoreManagerInterface            $storeManager,
        File                             $file
    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->directoryList = $directoryList;
        $this->storeManager = $storeManager;
        $this->file = $file;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('dealer:list');
        $this->setDescription('Generate Dealers List XML file. Options example :: --limit=1 --dealers=romanyurkhanov --include-reviews ');
        $this->addOption(
            'limit',
            null,
            InputOption::VALUE_REQUIRED,
            'Limit the number of dealers in the file'
        )
            ->addOption(
                'dealers',
                null,
                InputOption::VALUE_REQUIRED,
                'Provide dealer codes to include in the file'
            )
            ->addOption(
                'include-reviews',
                null,
                InputOption::VALUE_NONE,
                'Include reviews in the file'
            );

        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get dealer list
        $dealerList = $this->getDealerList($input, $output);

        // Get command options
        $limit = $input->getOption('limit');
        $dealers = $input->getOption('dealers');
        $includeReviews = $input->getOption('include-reviews');

        // Generate XML content for each dealer
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<dealer_list>
    <timestamp>' . date('Y-m-d | H:i:s') . '</timestamp>
    <dealers>';

foreach ($dealerList as $dealer) {
    // Get dealer reviews list
    $dealerId = $dealer->getDealerId();
    $searchCriteria = $this->searchCriteriaBuilder
        ->addFilter('dealer_id', $dealerId, 'in')
        ->create();
    $dealerReviewsList = $this->dealerReviewsRepository->getList($searchCriteria)->getItems();

        $xmlContent .= '
        <dealer>
            <dealer_id>' . $dealer->getDealerId() . '</dealer_id>
            <name>' . $dealer->getName() . '</name>
            <code>' . $dealer->getCode() . '</code>';

        // Include dealer reviews if enabled
        if ($includeReviews) {
            $xmlContent .= '
                <reviews>';
                        foreach ($dealerReviewsList as $dealerReview) {
                            $xmlContent .= '
                    <review>
                        <review_id>' . $dealerReview->getReviewId() . '</review_id>
                        <title>' . $dealerReview->getTitle() . '</title>
                        <rate>' . $dealerReview->getRate() . '</rate>
                        <message>' . $dealerReview->getMessage() . '</message>
                    </review>';
                        }
                        $xmlContent .= '
                </reviews>';
        }

        $xmlContent .= '
        </dealer>';
}

$xmlContent .= '
    </dealers>
</dealer_list>';

        // Generate file name with timestamp
        $timestamp = date('Y_m_d__H_i_s');
        $fileName = !$includeReviews ?
            'Dealer_list_' . $timestamp . '.xml' :
            'Dealer_list_with_reviews_' . $timestamp . '.xml';

        // Get the base media directory path
        $mediaPath = $this->directoryList->getPath(DirectoryList::MEDIA);

        // Get the base URL
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        // Create the full directory path for the XML file
        $xmlDirectory = !$includeReviews ?
            $mediaPath . '/' . self::XML_DEALER_LIST_PATH :
            $mediaPath . '/' . self::XML_DEALER_LIST_WITH_REVIEWS_PATH;

        $this->file->checkAndCreateFolder($xmlDirectory);

        // Save XML file
        $filePath = $xmlDirectory . $fileName;
        file_put_contents($filePath, $xmlContent);

        // Return the file download link
        $downloadLink = !$includeReviews ?
            $baseUrl . self::XML_DEALER_LIST_PATH . $fileName :
            $baseUrl . self::XML_DEALER_LIST_WITH_REVIEWS_PATH . $fileName;

        try {
            if ($downloadLink) {
                $output->writeln('<info>XML file generated and saved.</info>');
                $output->writeln('<comment>Download link: ' . $downloadLink . '</comment>');
            }
            else {
                throw new LocalizedException(__('An error occurred. Please check "DealersListCommand" class'));
            }
        } catch (LocalizedException $e) {
            $output->writeln(sprintf(
                '<error>%s</error>',
                $e->getMessage()
            ));
        }
    }

    private function getDealerList(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('dealers')) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $searchCriteria->setPageSize($input->getOption('limit'))->setCurrentPage($input->getOption('limit'));
        } else {
            $dealerCodes = explode(",", $input->getOption('dealers'));
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('code', $dealerCodes, 'in')
                ->create();
            $searchCriteria->setPageSize($input->getOption('limit'))->setCurrentPage($input->getOption('limit'));
        }

        return $this->dealerRepository->getList($searchCriteria)->getItems();
    }
}
