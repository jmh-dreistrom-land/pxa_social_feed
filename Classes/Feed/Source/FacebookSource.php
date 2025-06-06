<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Feed\Source;

class FacebookSource extends BaseFacebookSource
{
    /**
     * Load feed source
     *
     * @return array Feed items
     */
    public function load(): array
    {
        $pageAccessToken = $this->getConfiguration()->getToken();

        $fb = $pageAccessToken->getFb();
        $endPointEntry = $this->getConfiguration()->getEndPointEntry();
        if (!in_array($endPointEntry, ['feed', 'posts'])) {
            $endPointEntry = 'feed';
        }

        $socialId = $pageAccessToken->isInstagramType() || $pageAccessToken->isFacebookType() ? $this->getConfiguration()->getSocialId() : $pageAccessToken->getFbSocialId();

        $endPointUrl = $this->generateEndPoint($socialId, $endPointEntry);
        $response = $this->requestFactory->request(
            $fb::BASE_GRAPH_URL .
            self::GRAPH_VERSION . '/' . $endPointUrl
        );
        $response = (string)$response->getBody();
        $response = json_decode($response, true);

        return $this->getDataFromResponse($response);
    }

    /**
     * Return fields for endpoint request
     *
     * @return array
     */
    protected function getEndPointFields(): array
    {
        return [
            'reactions.summary(true).limit(0)',
            'message',
            'attachments',
            'permalink_url',
            'created_time',
            'updated_time',
        ];
    }
}
