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

namespace Tarantula\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Tarantula\Application\Repository\Links;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkUrl;

final class DoctrineLinks extends ServiceEntityRepository implements Links
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * @param LinkUrl[] $urls
     *
     * @return Link[]
     */
    public function withUrls($urls)
    {
        return $this->findBy([
            'url' => $urls,
        ]);
    }

    public function add(Link $link): void
    {
        $this->_em->persist($link);
    }

    public function update(Link $link): void
    {
        $this->_em->merge($link);
    }

    public function save(): void
    {
        $this->_em->flush();
    }
}
