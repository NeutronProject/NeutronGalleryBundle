<?php
namespace Neutron\Plugin\GalleryBundle\Model;

use Neutron\Bundle\FormBundle\Model\MultiImageInterface;

interface GalleryInterface
{
    public function getId();
    
    public function getTitle();
    
    public function setTitle($title);
    
    public function getTemplate();
    
    public function setTemplate($template);
    
    public function addImage(MultiImageInterface $image);
    
    public function setImages(array $images);
    
    public function getImages();
    
    public function removeImage(MultiImageInterface $image);
}