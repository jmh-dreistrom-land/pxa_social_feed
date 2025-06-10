<?php

namespace Pixelant\PxaSocialFeed\ViewHelpers;

use Pixelant\PxaSocialFeed\Domain\Model\Token;
use Pixelant\PxaSocialFeed\Event\ProvideAdditionalFeedEvent;
use Pixelant\PxaSocialFeed\Exception\UnsupportedTokenType;
use Pixelant\PxaSocialFeed\Feed\AbstractAdditionalFeed;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ParseMessageViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Arguments initializations
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('message', 'string', 'Feed message', false, '');
        $this->registerArgument('type', 'integer', 'Feed type', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public function render()
    {
        $message = $this->arguments['message'] ?: $this->renderChildren();
        $type = $this->arguments['type'];

        if (!$message) {
            return '';
        }

        return static::parseFeedMessage($message, $type);
    }

    /**
     * @param string $text
     * @param int $type
     * @return mixed
     */
    public static function parseFeedMessage($text, $type)
    {
        // Convert urls to links
        $text = preg_replace(
            '$(\s|^)(https?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i',
            ' <a href="$2" target="_blank" rel="noreferrer">$2</a> ',
            $text
        );
        $text = preg_replace(
            '$(\s|^)(www\.[a-z0-9_./?=&-]+)(?![^<>]*>)$i',
            '<a href="http://$2" target="_blank" rel="noreferrer">$2</a> ',
            $text
        );

        switch ($type) {
            case Token::FACEBOOK_PAGE:
            case Token::FACEBOOK:
                // Convert hashtags to facebook searches in <a> links
                $text = preg_replace_callback(
                    "/#([[:alnum:]\/.]+)/u",
                    function ($matches) {
                        return sprintf(
                            '<a target="_blank" rel="noreferrer" '
                            . 'href="https://www.facebook.com/hashtag/%s?source=feed_text">#%s</a>',
                            rawurlencode($matches[1]),
                            htmlspecialchars($matches[1])
                        );
                    },
                    $text
                );
                break;
            case Token::TWITTER:
            case Token::TWITTER_V2:
                // Convert hashtags to twitter searches in <a> links
                $text = preg_replace_callback(
                    "/#([[:alnum:]\/.]+)/u",
                    function ($matches) {
                        return sprintf(
                            '<a target="_blank" rel="noreferrer" href="https://twitter.com/hashtag/%s">#%s</a>',
                            rawurlencode($matches[1]),
                            htmlspecialchars($matches[1])
                        );
                    },
                    $text
                );

                // Convert @tags to twitter profiles in <a> links
                $text = preg_replace_callback(
                    "/@([[:alnum:]\/._]+)/u",
                    function ($matches) {
                        return sprintf(
                            '<a target="_blank" rel="noreferrer" href="https://www.twitter.com/%s/">@%s</a>',
                            rawurlencode($matches[1]),
                            htmlspecialchars($matches[1])
                        );
                    },
                    $text
                );
                break;
            case Token::INSTAGRAM:
                // Convert hashtags to instagram searches in <a> links
                $text = preg_replace_callback(
                    "/#([[:alnum:]\/.]+)/u",
                    function ($matches) {
                        return sprintf(
                            '<a target="_blank" rel="noreferrer" '
                            . 'href="https://www.instagram.com/explore/tags/%s/">#%s</a>',
                            rawurlencode($matches[1]),
                            htmlspecialchars($matches[1])
                        );
                    },
                    $text
                );
                // Convert @tags to instagram profiles in <a> links
                $text = preg_replace_callback(
                    "/@([[:alnum:]\/._]+)/u",
                    function ($matches) {
                        return sprintf(
                            '<a target="_blank" rel="noreferrer" href="https://www.instagram.com/%s/">@%s</a>',
                            rawurlencode($matches[1]),
                            htmlspecialchars($matches[1])
                        );
                    },
                    $text
                );
                break;
            case Token::YOUTUBE:
                //Convert hashtags to youtube searches in <a> links
                $text = preg_replace_callback(
                    "/#([[:alnum:]\/.]+)/u",
                    function ($matches) {
                        return sprintf(
                            '<a target="_blank" rel="noreferrer" '
                            . 'href="https://www.youtube.com/results?search_query=%s">#%s</a>',
                            rawurlencode('#' . $matches[1]),
                            htmlspecialchars($matches[1])
                        );
                    },
                    $text
                );
                // Convert @tags to youtube profiles in <a> links
                $text = preg_replace_callback(
                    "/@([[:alnum:]\/._]+)/u",
                    function ($matches) {
                        return sprintf(
                            '<a target="_blank" rel="noreferrer" href="https://www.youtube.com/user/%s/">@%s</a>',
                            rawurlencode($matches[1]),
                            htmlspecialchars($matches[1])
                        );
                    },
                    $text
                );
                break;
            default:
                /** @var EventDispatcher $eventDispatcher */
                $eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
                /** @var ProvideAdditionalFeedEvent $additionalFeedsEvent */
                $additionalFeedsEvent = $eventDispatcher->dispatch(new ProvideAdditionalFeedEvent());

                /** @var AbstractAdditionalFeed $feed */
                foreach ($additionalFeedsEvent->getFeeds() as $feed) {
                    if ($feed::getTokenTypeId() == $type) {
                        return $feed->parseFeedMessage($text);
                    }
                }

                throw new UnsupportedTokenType("Token type $type is not supported by view helper", 1564384491599);
        }

        return $text;
    }
}
