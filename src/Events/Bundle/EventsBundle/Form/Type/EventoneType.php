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
       }
       else {
           $eventtype1 = '';
           $eventtype2 = '';
           $eventtype3 = '';
       }
       //Eventtype1
        $builder->add('eventtype1','choice',array(
            'choices' => array('1' => 'Calligraphy 1', 
                               '2' => 'Dabke 1',
                               '3' => 'Footloose 1',
                               '4' => 'Spray Painting 1',
                               '5' => 'Cricket 1',
                               '6' => 'Kabbadi 1',
                               '7' => 'Bingo 1',
                ),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Cultural Events 1, Time 10 am - 11 am',
            'required' => true,        
            'data' =>  $eventtype1,
        ));
        
       //Eventtype2
        $builder->add('eventtype2','choice',array(
            'choices' => array('8' => 'Calligraphy 2', 
                               '9' => 'Dabke 2',
                               '10' => 'Footloose 2',
                               '11' => 'Spray Painting 2',
                               '12'=> 'Cricket 2',
                               '13'=> 'Kabbadi 2',
                               '14'=> 'Bingo 2'
                ),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Cultural Events 2, Time 11 am - 12 noon',
            'required' => true,
            'data' => $eventtype2,
        ));
        
       //Eventtype3
        $builder->add('eventtype3','choice',array(
            'choices' => array('15' => 'Food tasting from 12 to 1 pm(two tickets per person)', 
                               '16' => 'Food tasting from 1 to 2 pm(two tickets per person)',
                ),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Food Tasting Event',
            'required' => true,
            'data' => $eventtype3,
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