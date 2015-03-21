<?php

namespace Events\Bundle\EventsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Events\Bundle\EventsBundle\Entity\Subscribed;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventtwoType extends AbstractType {
    
    protected $subscribed;
    
    public function __construct($subscribed){
        
        $this->subscribed = $subscribed;
    }



    public function buildForm(FormBuilderInterface $builder, array $options) {
        
       if (!empty($this->subscribed)){
           if($this->subscribed->getEventtype5() == null){
                $eventtype5 = '';   
           }
           else {
               $eventtype5 = $this->subscribed->getEventtype5()->getId();
           }
           if($this->subscribed->getEventtype6() == null){
                $eventtype6 = '';   
           }
           else {
               $eventtype6 = $this->subscribed->getEventtype6()->getId();
           }
           if($this->subscribed->getEventtype7() == null){
                $eventtype7 = '';   
           }
           else {
               $eventtype7 = $this->subscribed->getEventtype7()->getId();
           }    
       }
       else {
           $eventtype5 = '';
           $eventtype6 = '';
           $eventtype7 = '';
       }
       //Eventtype5
        $builder->add('eventtype5','choice',array(
            'choices' => array('17' => 'Study Abroad Oxford Brookes', 
                               '18' => 'Barclays, Singapore(12 to 1:30 pm)',
                               '19' => 'Dual Degree Boston University',
                               '20' => 'Dual Degree China'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Workshop/Conference Events 1, Time 11:30 am - 1 pm',
            'required' => true,
            'empty_value' => 'None',        
            'data' =>  $eventtype5,
        ));
        
       //Eventtype6
        $builder->add('eventtype6','choice',array(
            'choices' => array('21' => 'Travaillez chez Amazon,NY,USA', 
                               '22' => 'Find an internship in Asia/Gulf States/Oceania',
                               '23' => 'How to work with India',
                               '24' => 'Dual Degree Stevens'
                ),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Workshop/Conference Events 2, Time 2:30 pm - 4 pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype6,
        ));
        
       //Eventtype7
        $builder->add('eventtype7','choice',array(
            'choices' => array('25' => 'Study in Denmark-ITU', 
                               '26' => 'Study in China - Northeastern University',
                               '27' => 'Dual Degree Ireland',
                               '28'=> 'CSUMB - Study Abroad California',
                               '29'=> 'Work and Study in Quebec'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Workshop/Conference Events 3, Time 4 pm - 5:30 pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype7,
        ));
        
    }

    public function getDefaultOptions(array $options) {
        return array('csrf_protection' => true);
    }

    public function getName() {
        return 'eventtwo';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
         $resolver->setDefaults(array(
            'data_class' => 'Events\Bundle\EventsBundle\Entity\Subscribed',
        ));
    }
}