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

namespace Tarantula\Infrastructure\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Tarantula\Application\Command\AddLinks;
use Tarantula\Application\Service\Crawler;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkUrl;

final class GetLinks
{
    /** @var MessageBusInterface */
    private $bus;

    /** @var Crawler */
    private $crawler;

    public function __construct(MessageBusInterface $bus, Crawler $crawler)
    {
        $this->bus = $bus;
        $this->crawler = $crawler;
    }

    public function __invoke(Request $request): Response
    {
        $url = $request->query->get('url');
        $depth = $request->query->get('depth');

        if (null === $url) {
            throw new BadRequestHttpException('Link url is required');
        }
        if (null === $depth) {
            throw new BadRequestHttpException('Depth is required');
        }
        if (!is_numeric($depth) || !is_int($depth = $depth + 0)) {
            throw new BadRequestHttpException('Depth is not an integer number');
        }

        try {
            $url = LinkUrl::fromString($url);
            $links = $this->crawler->getLinksFromUrl($url, $depth);
        } catch (Exception $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        $this->bus->dispatch(AddLinks::with($links));

        $linksArray = array_map(function (Link $link) {
            return $link->toArray();
        }, $links);

        return Response::create(json_encode($linksArray), 200);
    }
}
