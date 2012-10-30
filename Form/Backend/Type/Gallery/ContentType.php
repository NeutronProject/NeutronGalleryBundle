<?php 
namespace Neutron\Plugin\GalleryBundle\Form\Backend\Type\Gallery;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class ContentType extends AbstractType
{

    protected $galleryClass;
    
    protected $imageClass;

    protected $templates;
    
    protected $imageOptions;
    
    protected $translationDomain;
    
    public function setGalleryClass($galleryClass)
    {
        $this->galleryClass = $galleryClass;
    }
    
    public function setImageClass($imageClass)
    {
        $this->imageClass = $imageClass;
    }
    
    public function setTemplates($templates)
    {
        $this->templates = $templates;
    }
    
    public function setImageOptions(array $imageOptions)
    {
        $this->imageOptions = $imageOptions;
    }
    
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {   
        $builder
           ->add('title', 'text', array(
               'label' => 'form.title',
               'translation_domain' => $this->translationDomain
           ))
           ->add('images', 'neutron_multi_image_upload_collection', array(
               'label' => 'form.images',
               'options' => array(
                   'data_class' => $this->imageClass
               ),
               'configs' => array(
                   'minWidth' => $options['image_options']['min_width'],
                   'minHeight' => $options['image_options']['min_height'],
                   'extensions' => $options['image_options']['extensions'],
                   'maxSize' => $options['image_options']['max_size'],
                   'runtimes' => $options['image_options']['runtimes']
               )
           ))
           ->add('template', 'choice', array(
               'choices' => $this->templates,
               'multiple' => false,
               'expanded' => false,
               'attr' => array('class' => 'uniform'),
               'label' => 'form.template',
               'empty_value' => 'form.empty_value',
               'translation_domain' => $this->translationDomain
           ))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $imageOptions = $this->imageOptions;
        
        $resolver->setDefaults(array(
            'data_class' => $this->galleryClass, 
            'image_options' => array(),
            'validation_groups' => function(FormInterface $form){
                return 'default';
            },
        ));    

        $resolver->setNormalizers(array(
            'image_options' => function (Options $options, $value) use ($imageOptions) {
                return array_merge($imageOptions, $value);
            }
        ));
    }
    
    public function getName()
    {
        return 'neutron_backend_gallery_content';
    }
    
}