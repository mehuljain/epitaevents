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
           if($this->subscribed->getEventtype8() == null){
                $eventtype8 = '';   
           }
           else {
               $eventtype8 = $this->subscribed->getEventtype8()->getId();
           }
       }
       else {
           $eventtype5 = '';
           $eventtype6 = '';
           $eventtype7 = '';
           $eventtype8 = '';
       }
       //Eventtype5
        $builder->add('eventtype5','choice',array(
            'choices' => array('20' => 'Study at California University(Amphi 4)', 
                               '21' => 'International Mobility(IP12A)',
                               '22' => 'How to work with India(P10)',
                               '23' => 'VIE(P07)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Workshop/Conference Events 1, Time 10 am - 11:30 am',
            'required' => true,
            'empty_value' => 'None',        
            'data' =>  $eventtype5,
        ));
        
       //Eventtype6
        $builder->add('eventtype6','choice',array(
            'choices' => array('24' => 'Find a job in Asia/Gulf States/Oceania(Amphi 4)', 
                               '25' => 'International Mobility(IP12A)',
                               '26' => 'How to work with India(P10)',
                               '27' => 'VIE(P07)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Workshop/Conference Events 2, Time 11:30 am - 1 pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype6,
        ));
        
       //Eventtype7
        $builder->add('eventtype7','choice',array(
            'choices' => array('28' => 'Find a job in Asia/Gulf States/Oceania(Amphi 4)', 
                               '29' => 'Dual Degree Griffith College Dublin(IP12A)',
//                               '30' => 'Dual Degree UQAC(P06)',
                               '31' => 'Study Abroad Oxford Brookes(P05)',
                               '32'=> 'Dual Degree Stevens Institute of Technology(P07)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Workshop/Conference Events 3, Time 2:30 pm - 4 pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype7,
        ));
        
       //Eventtype4
        $builder->add('eventtype8','choice',array(
            'choices' => array('33' => 'Study at Ahlia University(Amphi 4)', 
                               '34' => 'The job market in Ireland Dublin(IP12A)',
                               '35' => 'Exchange Bahcesehir Turkey(P06)',
                               '36' => 'The job market in the US(P05)',
                               '37'=> 'Dual Degree Beijing China(P07)'),
            'expanded' => true,
            'multiple' => false,
            'label' => 'Workshop/Conference Events 3, Time 4 pm - 5:30pm',
            'required' => true,
            'empty_value' => 'None',
            'data' => $eventtype8,
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