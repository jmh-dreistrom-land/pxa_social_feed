<?php

// Copyright JAKOTA Design Group GmbH. All rights reserved.
declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Domain\Model;

use League\OAuth2\Client\Provider\FacebookUser as LeagueFacebookUser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FacebookUser extends LeagueFacebookUser
{
    /**
     * @var array<FacebookPage>
     */
    private array $pages;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response)
    {
        parent::__construct($response);

        if (!isset($response['accounts'])
            || !is_array($response['accounts'])
            || !isset($response['accounts']['data'])
            || !is_array($response['accounts']['data'])
        ) {
            $this->pages = [];
        } else {
            $this->pages = array_map(
                function ($page) {
                    return GeneralUtility::makeInstance(FacebookPage::class, $page);
                },
                $response['accounts']['data']
            );
        }
    }

    /**
     * @return array<FacebookPage>
     */
    public function getPages(): array
    {
        return $this->pages;
    }
}
