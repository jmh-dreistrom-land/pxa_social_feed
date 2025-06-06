<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Feed\Source;

use TYPO3\CMS\Core\Utility\HttpUtility;

class InstagramSource extends BaseFacebookSource
{
    public const BASE_INSTAGRAM_GRAPH_URL = 'https://graph.facebook.com/';

    /**
     * Load feed source
     */
    public function load(): array
    {
        $instagramId = $this->getInstagramId();
        $endPointUrl = $this->generateEndPoint($instagramId, 'media');
        $response = $this->requestFactory->request(
            self::BASE_INSTAGRAM_GRAPH_URL .
            self::GRAPH_VERSION . '/' . $endPointUrl
        );
        $response = (string)$response->getBody();
        $response = json_decode($response, true);

        return $this->getDataFromResponse($response);
    }

    /**
     * Fetch instagram ID
     *
     * @return string
     */
    protected function getInstagramId(): string
    {
        try {
            $pageId = $this->getConfiguration()->getSocialId();
            $accessToken = $this->getConfiguration()->getToken()->getAccessToken();

            $accountIds = $this->getAccountIds($accessToken);
            if (!$accountIds) {
                throw new \UnexpectedValueException(
                    'Could not get facebook account IDs. Check you settings.',
                    1562841411121
                );
            }

            $instagramAccountId = $this->getInstagramAccountIdFromAccount($accountIds[0], $accessToken);
            if (!$instagramAccountId) {
                throw new \UnexpectedValueException(
                    'Could not get instagram business account ID for account with ID ' . $accountIds[0] . '. Check you settings.',
                    1562841411122
                );
            }

            return $instagramAccountId;
        } catch (\Exception $exception) {
            throw new \UnexpectedValueException(
                'Could not get instagram business account ID for page with ID ' . $pageId . '. Check you settings.',
                1562841411123
            );
        }
    }

    private function getInstagramAccountIdFromAccount($accountId, string $accessToken): string|false
    {
        $url = self::BASE_INSTAGRAM_GRAPH_URL . self::GRAPH_VERSION . "/{$accountId}";
        $params = [
            'access_token' => $accessToken,
            'fields' => 'instagram_business_account'
        ];

        $response = $this->requestFactory->request($url . '?' . HttpUtility::buildQueryString($params));
        $response = json_decode((string)$response->getBody(), true);

        if (($response['instagram_business_account'] ?? false)) {
            return $response['instagram_business_account']['id'];
        }

        return false;
    }

    private function getAccountIds(string $accessToken): array
    {
        $url = self::BASE_INSTAGRAM_GRAPH_URL . self::GRAPH_VERSION . "/me/accounts";
        $params = [
            'access_token' => $accessToken,
            'fields' => 'id'
        ];

        $response = $this->requestFactory->request($url . '?' . HttpUtility::buildQueryString($params));
        $response = json_decode((string)$response->getBody(), true);

        if (($response['data'] ?? false)) {
            return array_column($response['data'], 'id');
        }

        return [];
    }

    /**
     * Return fields for endpoint request
     */
    protected function getEndPointFields(): array
    {
        return [
            'caption',
            'children',
            'comments',
            'comments_count',
            'id',
            'ig_id',
            'is_comment_enabled',
            'like_count',
            'media_type',
            'media_url',
            'owner',
            'permalink',
            'shortcode',
            'thumbnail_url',
            'timestamp',
            'username',
        ];
    }
}
