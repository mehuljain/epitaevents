<?php

namespace Events\Bundle\EventsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Events\Bundle\EventsBundle\Entity\Subscribed;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventoneType extends AbstractType {
    
    protected $subscribed;
    
    public function __construct($subscribed){
        
        $this->subscribed = $subscribed;
    }



    public function buildForm(FormBuilderInterface $builder, array $options) {
        
       if (!empty($this->subscribed)){
           if($this->subscribed->getEventtype1() == null){
                $eventtype1 = '';   
           }
           else {
               $eventtype1 = $this->subscribed->getEventtype1()->getId();
           }
           if($this->subscribed->getEventtype2() == null){
                $eventtype2 = '';   
           }
           else {
               $eventtype2 = $this->subscribed->getEventtype2()->getId();
           }
           if($this->subscribed->getEventtype3() == null){
                $eventtype3 = '';   
           }
           else {
               $eventtype3 = $this->subscribed->getEventtype3()->getId();
           }
           if($this->subscribed->getEventtype4() == null){
                $eventtype4 = '';   
           }
           else {
               $eventtype4 = $this->subscribed->getEventtype4()->getId();
           }
       }
       else {
           $eventtype1 = '';
           $eventtype2 = '';
           $eventtype3 = '';
           $eventtype4 = '';
       }
       //Eventtype1
        $builder->add('eventtype1','choice',array(
            'choices' => array('1' => 'Ethiopian Food(P10)', 
                               '2' => 'Ecuadorian Food(IP12B)',
                               '3' => 'South Korean Food(P03)',
                               '4' => 'Serbian Food(P04)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Cultural Events 1, Time 12 noon - 1 pm',
            'required' => true,
            'empty_value' => 'None',        
            'data' =>  $eventtype1,
        ));
        
       //Eventtype2
        $builder->add('eventtype2','choice',array(
            'choices' => array('5' => 'Indian Food(P10)', 
                               '6' => 'Finnish Food(IP12B)',
                               '7' => 'Brazilian Food(P03)',
                               '8' => 'Vietnamese Food(P04)',
                               '9'=> 'Lebanese Food(P05)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Cultural Events 2, Time 1 pm - 2 pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype2,
        ));
        
       //Eventtype3
        $builder->add('eventtype3','choice',array(
            'choices' => array('10' => 'Game Bamboo Dance(P10)', 
                               '11' => 'Painting on Eggs(IP12B)',
                               '12' => 'Nigerian Music(P04)',
                               '13' => 'Ecuador Story Telling(P03)',
                               '14'=> 'Bingo Mexicano(P05)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Cultural Events 3, Time 3 pm - 4 pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype3,
        ));
        
       //Eventtype4
        $builder->add('eventtype4','choice',array(
            'choices' => array('15' => 'Dabke Dance(P10)', 
                               '16' => 'Calligraphy(IP12B)',
                               '17' => 'Brazilian Music(P05)',
                               '18' => 'Langori Indian Game(P04)',
                               '19'=> 'Holi(P06)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Cultural Events 4, Time 4 pm - 5 pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype4,
        ));
        
     
        
    }

    public function getDefaultOptions(array $options) {
        return array('csrf_protection' => true);
    }

    public function getName() {
        return 'eventone';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
         $resolver->setDefaults(array(
            'data_class' => 'Events\Bundle\EventsBundle\Entity\Subscribed',
        ));
    }
}