<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Feed;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractAdditionalFeed implements FeedFactoryInterface
{
    protected const TOKEN_TYPE_ID = 0;
    protected AbstractEntity $token;

    /**
     * Returns the identification ID of the token type associated with
     * the feed. This can be any unique number.
     * see: Domain\Model\Token
     *
     * @return int
     */
    final public static function getTokenTypeId(): int
    {
        if (static::TOKEN_TYPE_ID < 1) {
            throw new \InvalidArgumentException('The tokenTypeId must be a unique integer greater than 0.', 56802463002);
        }

        return static::TOKEN_TYPE_ID;
    }

    final public function setToken(AbstractEntity $token): void
    {
        $this->token = $token;
    }

    /**
     * Indicates whether an access token is already available if the
     * feed requires a OAuth. For feeds that do not use this procedure,
     * true is always returned.
     * see: AdministrationController::isTokensValid()
     *
     * @return bool
     */
    abstract public function isTokensValid(): bool;

    /**
     * This method can be used, for example, to provide information about
     * the companies or people associated with the profile. This can then
     * be used in the Fluid via token.availableSocialIds.
     *
     * @return array
     */
    public function getAvailableSocialIds(): array
    {
        return [];
    }

    /**
     * Indicates whether an access token can expire and if so, when.
     *
     * @return int|bool Timestamp when the token expires. Or, if the token has already expired true. If the token never expires, then false.
     */
    public function checkExpire(): int|bool
    {
        return false;
    }

    /**
     * Is called before a token is saved.
     * see: AdministrationController::updateTokenAction()
     */
    public function updateToken(): void
    {
    }

    /**
     * Is called from the ParseMessageViewHelper.
     * No token object is available in this method.
     */
    public function parseFeedMessage(string $message): string
    {
        return $message;
    }
}
