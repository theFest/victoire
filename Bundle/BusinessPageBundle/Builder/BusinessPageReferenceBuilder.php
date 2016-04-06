<?php

namespace Victoire\Bundle\BusinessPageBundle\Builder;

use Doctrine\ORM\EntityManager;
use Victoire\Bundle\BusinessPageBundle\Entity\BusinessPage;
use Victoire\Bundle\CoreBundle\Entity\View;
use Victoire\Bundle\ViewReferenceBundle\Builder\BaseReferenceBuilder;
use Victoire\Bundle\ViewReferenceBundle\Helper\ViewReferenceHelper;
use Victoire\Bundle\ViewReferenceBundle\ViewReference\BusinessPageReference;
use Victoire\Bundle\ViewReferenceBundle\ViewReference\ViewReference;

/**
 * BusinessPageReferenceBuilder.
 */
class BusinessPageReferenceBuilder extends BaseReferenceBuilder
{
    /**
     * @param BusinessPage  $businessPage
     * @param EntityManager $em
     *
     * @return BusinessPageReference|ViewReference
     */
    public function buildReference(View $businessPage, EntityManager $em)
    {
        $referenceId = ViewReferenceHelper::generateViewReferenceId($businessPage);
        $businessPageReference = new BusinessPageReference();
        $businessPageReference->setId($referenceId);
        $businessPageReference->setLocale($businessPage->getLocale());
        $businessPageReference->setName($businessPage->getName());
        $businessPageReference->setViewId($businessPage->getId());
        $businessPageReference->setTemplateId($businessPage->getTemplate()->getId());
        $businessPageReference->setSlug(
            $businessPage->getStaticUrl() != '' ?
                $businessPage->getStaticUrl() :
                $businessPage->getSlug()
        );
        $businessPageReference->setEntityId($businessPage->getBusinessEntity()->getId());
        $businessPageReference->setEntityNamespace($em->getClassMetadata(get_class($businessPage->getBusinessEntity()))->name);
        $businessPageReference->setViewNamespace($em->getClassMetadata(get_class($businessPage))->name);
        if ($parent = $businessPage->getParent()) {
            $businessPageReference->setParent(ViewReferenceHelper::generateViewReferenceId(
                $parent->setTranslatableLocale($businessPage->getLocale()))
            );
        }

        return $businessPageReference;
    }
}
