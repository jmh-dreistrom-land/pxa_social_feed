<?php

namespace Pixelant\PxaSocialFeed\Controller;

use Pixelant\PxaSocialFeed\Domain\Repository\FeedRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class FeedsController extends ActionController
{
    public function __construct(protected readonly FeedRepository $feedRepository)
    {
    }

    public function listAction(): ResponseInterface
    {
        if (($this->settings['loadType'] ?? 'standard') == 'ajax') {
            return (new ForwardResponse('listAjax'))
                ->withControllerName('Feeds')
                ->withExtensionName('pxa_social_feed');
        }

        $limit = $this->settings['feedsLimit'] ? (int)($this->settings['feedsLimit']) : 10;
        $configurations = GeneralUtility::intExplode(',', $this->settings['configuration'], true);

        $feeds = $this->feedRepository->findByConfigurations($configurations, $limit);

        $this->view->assign('feeds', $feeds);

        return $this->htmlResponse();
    }

    /**
     * Prepare view for later ajax request
     */
    public function listAjaxAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * Load feed with ajax
     */
    public function loadFeedAjaxAction(): JsonResponse {
        $params = $this->request->getQueryParams();

        $feedsLimit = ($params['feedsLimit'] ?? false) ? $params['feedsLimit'] : 10;

        $feeds = $this->feedRepository->findByConfigurations(
            GeneralUtility::intExplode(',', $params['configuration'], true),
            $feedsLimit
        );

        $settings = $this->settings;
        $settings['configuration'] = $params['configuration'];
        $settings['feedsLimit'] = $feedsLimit;
        $settings['partial'] = ($params['partial'] ?? '') == 'FeedItemDynamic' ? 'FeedItemDynamic' : 'FeedItemCard';
        $settings['presentation'] = ($params['presentation'] ?? '') == 'owl-carousel' ? 'owl-carousel' : 'masonry';

        $this->view->assignMultiple(compact('feeds', 'settings'));

        return new JsonResponse(
            [
                'success' => true,
                'html' => $this->view->render(),
            ]
        );
    }
}
