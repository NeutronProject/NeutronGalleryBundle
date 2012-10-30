<?php
namespace Neutron\Plugin\GalleryBundle\Controller\Backend;

use Neutron\Plugin\GalleryBundle\Model\GalleryInterface;

use Neutron\SeoBundle\Model\SeoAwareInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\SeoBundle\Model\SeoInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class GalleryController extends ContainerAware
{
    public function indexAction()
    {
        $datagrid = $this->container->get('neutron.datagrid')
            ->get($this->container->getParameter('neutron_gallery.datagrid.gallery_management'));
    
        $template = $this->container->get('templating')->render(
            'NeutronGalleryBundle:Backend\Gallery:index.html.twig', array(
                'datagrid' => $datagrid,
                'translationDomain' =>
                    $this->container->getParameter('neutron_gallery.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function updateAction($id)
    {
        $formType = $this->container->get('neutron_gallery.form.backend.type.gallery');
        $formName = $this->container->getParameter('neutron_gallery.form.backend.name.gallery');
        
        $form = $this->container->get('form.factory')->createNamed($formName, $formType);
        $form->setData($this->getData($id));
        
        $handler = $this->container->get('neutron_gallery.form.backend.handler.gallery');
        $handler->setForm($form);
    
        if (null !== $handler->process()){
            return new Response(json_encode($handler->getResult()));
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronGalleryBundle:Backend\Gallery:update.html.twig', array(
                'form' => $form->createView(),
                'translationDomain' => $this->container->getParameter('neutron_gallery.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {
        $category = $this->getCategory($id);
        $entity = $this->getEntity($category);
    
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->doDelete($entity);
            $redirectUrl = $this->container->get('router')->generate('neutron_mvc.category.management');
            return new RedirectResponse($redirectUrl);
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronGalleryBundle:Backend\Gallery:delete.html.twig', array(
                'entity' => $entity,
                'translationDomain' => $this->container->getParameter('neutron_gallery.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    protected function doDelete(GalleryInterface $entity)
    {
        $this->container->get('neutron_admin.acl.manager')
            ->deleteObjectPermissions(ObjectIdentity::fromDomainObject($entity->getCategory()));
    
        $this->container->get('neutron_gallery.gallery_manager')->delete($entity, true);
    }
    
    protected function getCategory($id)
    {
        $treeManager = $this->container->get('neutron_tree.manager.factory')
            ->getManagerForClass($this->container->getParameter('neutron_mvc.category.category_class'));
    
        $category = $treeManager->findNodeBy(array('id' => $id));
    
        if (!$category){
            throw new NotFoundHttpException();
        }
    
        return $category;
    }
    
    protected function getEntity(CategoryInterface $category)
    {
        $manager = $this->container->get('neutron_gallery.gallery_manager');
        $entity = $manager->findOneBy(array('category' => $category));
    
        if (!$entity){
            throw new NotFoundHttpException();
        }
    
        return $entity;
    }
    
    
    protected function getSeo(SeoAwareInterface $entity)
    {
    
        if(!$entity->getSeo() instanceof SeoInterface){
            $entity->setSeo($this->container->get('neutron_seo.manager')->createSeo());
        }
    
        return $entity->getSeo();
    }
    
    protected function getData($id)
    {
        $category = $this->getCategory($id);
        $entity = $this->getEntity($category);
        $seo = $this->getSeo($entity);
    
        return array(
            'general' => $category,
            'content' => $entity,
            'seo'     => $seo,
            'acl' => $this->container->get('neutron_admin.acl.manager')
                ->getPermissions(ObjectIdentity::fromDomainObject($category))
        );
    }
}