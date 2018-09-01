<?php

/*
 * This file is part of the Tarantula package.
 *
 * (c) Nnergix
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tarantula\Application\Handler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Tarantula\Application\Command\AddLinks;
use Tarantula\Application\Repository\Links;
use Tarantula\Domain\Link\Model\Link;

final class AddLinksHandler implements MessageHandlerInterface
{
    /** @var Links */
    private $links;

    public function __construct(Links $links)
    {
        $this->links = $links;
    }

    public function __invoke(AddLinks $command): void
    {
        $links = $command->links();
        $urls = array_map(function (Link $link) {
            return $link->url();
        }, $links);
        $addedLinks = $this->links->withUrls($urls);
        $indexedAddedLinks = [];

        foreach ($addedLinks as $addedLink) {
            $url = $addedLink->url()->__toString();
            $indexedAddedLinks[$url] = $addedLink;
        }

        foreach ($links as $link) {
            $url = $link->url()->__toString();

            if (!isset($indexedAddedLinks[$url])) {
                $this->links->add($link);

                continue;
            }
            if (!$link->isVisited()) {
                continue;
            }

            $headers = $link->headers();
            $addedLink = $indexedAddedLinks[$url];

            if ($headers === $addedLink->headers()) {
                continue;
            }

            $addedLink->visit($headers);
            $this->links->update($addedLink);
        }

        $this->links->save();
    }
}
