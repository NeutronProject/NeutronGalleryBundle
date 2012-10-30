<?php
/*
 * This file is part of NeutronGalleryBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Plugin\GalleryBundle\Entity\Repository;

use Gedmo\Translatable\Entity\Repository\TranslationRepository;

class GalleryRepository extends TranslationRepository
{
    public function getQueryBuilderForGalleryManagementDataGrid()
    {
        $qb = $this->createQueryBuilder('g');
        $qb
            ->select('g.id, c.id as category_id, g.title, c.slug, c.title as category, c.enabled, c.displayed')
            ->innerJoin('g.category', 'c')
            ->groupBy('g.id')
        ;
        
        return $qb;
    }
}