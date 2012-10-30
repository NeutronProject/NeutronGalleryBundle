<?php
namespace Neutron\Plugin\GalleryBundle\Controller\Frontend;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Response;


class GalleryController extends ContainerAware
{   
    public function indexAction(CategoryInterface $category)
    {   
        $manager = $this->container->get('neutron_gallery.gallery_manager');
        $entity = $manager->findOneBy(array('category' => $category));
        
        if (null === $entity){
            throw new NotFoundHttpException();
        }

        $template = $this->container->get('templating')
            ->render($entity->getTemplate(), array(
                'entity'   => $entity,   
            )
        );
    
        return  new Response($template);
    }

}
