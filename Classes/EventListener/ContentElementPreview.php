<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;

#[AsEventListener(
    identifier: 'pxa-social-feed/content-element-preview-rendering',
)]
class ContentElementPreview
{
    /**
     * Path to layout preview
     * @TODO make it configurable
     *
     * @var string
     */
    private $templatePath = 'PageLayoutView/PluginPreview.html';

    public function __construct(protected readonly ViewFactoryInterface $viewFactory)
    {
    }

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content') {
            return;
        }

        if (($event->getRecord()['list_type'] ?? '') === 'pxasocialfeed_showfeed') {
            $event->setPreviewContent($this->getExtensionInformation($event->getRecord()));
        }
    }

    public function getExtensionInformation($params): string
    {
        $view = $this->getView();
        $settings = $this->getFlexFormService()->convertFlexFormContentToArray($params['pi_flexform'] ?? '');

        if (isset($settings['settings'])) {
            $settings += $settings['settings'];
            unset($settings['settings']);
        }

        // configurations info
        $configurations = '';
        if (isset($settings['configuration']) && $settings['configuration']) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_pxasocialfeed_domain_model_configuration');

            $configurations = $queryBuilder
                ->select('name')
                ->from('tx_pxasocialfeed_domain_model_configuration')->where(
                    $queryBuilder->expr()->in(
                        'uid',
                        $queryBuilder->createNamedParameter(
                            GeneralUtility::intExplode(',', $settings['configuration']),
                            Connection::PARAM_INT_ARRAY
                        )
                    )
                )->executeQuery()
                ->fetchAllAssociative();

            $configurations = array_column($configurations, 'name');
            $configurations = implode(', ', $configurations);
        }

        $view->assignMultiple(compact('settings', 'configurations'));

        return $view->render($this->templatePath);
    }

    protected function getView(): ViewInterface
    {
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: [
                'EXT:pxa_social_feed/Resources/Private/Templates',
            ],
            partialRootPaths: [
                'EXT:pxa_social_feed/Resources/Private/Partials',
            ],
            layoutRootPaths: [
                'EXT:pxa_social_feed/Resources/Private/Layouts',
            ],
            request: $this->getRequest(),
        );

        return $this->viewFactory->create($viewFactoryData);
    }

    protected function getFlexFormService()
    {
        return GeneralUtility::makeInstance(FlexFormService::class);
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
