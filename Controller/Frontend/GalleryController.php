<?php
namespace Neutron\Plugin\GalleryBundle\Controller\Frontend;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Response;


class GalleryController extends ContainerAware
{   
    public function indexAction($slug)
    {   
        $categoryManager = $this->container->get('neutron_mvc.category.manager');
        
        $entity = $categoryManager->findOneByCategorySlug(
            $this->container->getParameter('neutron_gallery.gallery_class'), 
            $slug,
            $this->container->get('request')->getLocale()
        );
        
        if (null === $entity){
            throw new NotFoundHttpException();
        }
        
        if (false === $this->container->get('neutron_admin.acl.manager')->isGranted($entity->getCategory(), 'VIEW')){
            throw new AccessDeniedException();
        }

        $template = $this->container->get('templating')
            ->render($entity->getTemplate(), array(
                'entity'   => $entity,   
            )
        );
    
        return  new Response($template);
    }

}
