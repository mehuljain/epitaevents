<?php

namespace Events\Bundle\EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Events\Bundle\EventsBundle\Entity\User;
use Events\Bundle\EventsBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;
use Events\Bundle\EventsBundle\Entity\Subscribed;
use Events\Bundle\EventsBundle\Form\Type\EventoneType;
use Events\Bundle\EventsBundle\Form\Type\EventtwoType;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction() {
       
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('securedhome'));
        }
        return array();
    }

    /**
     * @Route("/register",name="register")
     * @Template()
     */
    public function registerAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        //Check to see if the user has already logged in
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('securedhome'));
        }

        $user = new User();

        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            //Do the needful
            $date = new \DateTime();
            $user->setCreatedon($date);
            $user->setEnabled(TRUE);
            $em->persist($user);
            $em->flush();
            $this->authenticateUser($user);
            $route = 'securedhome';
            $url = $this->generateUrl($route);
            return $this->redirect($url);
        }
        
        return array('form' => $form->createView());
    }

    /**
     * @Route("/secured/home",name="securedhome")
     * @Template()
     */
    public function homeAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('events_events_default_index'));
        }
        $user = $em->getRepository('EventsEventsBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId());

        if (!is_object($user) || !$user instanceof User) {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException('This user does not have access to this section.');
        }

        return array();
    }

    /**
     * @Route("/secured/eventone",name="eventone")
     * @Template()
     */
    public function eventoneAction(Request $request) {
        
        $exists = false;

        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('events_events_default_index'));
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EventsEventsBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId());

        if (!is_object($user) || !$user instanceof User) {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException('This user does not have access to this section.');
        }
        
        $subrecord = $em->getRepository('EventsEventsBundle:Subscribed')->findOneBy(array('user' => $user->getId()));
        
//        if(is_a($subrecord, 'Subscribed')){
        if(!empty($subrecord)){
            $exists = true;
            if($subrecord->getEventtype1() != null || $subrecord->getEventtype1() != '' ){
                $event1 = $subrecord->getEventtype1()->getId();
            }
            else {
                $event1 = '';
            }
            if(($subrecord->getEventtype2() != null || $subrecord->getEventtype2() != '')){
                $event2 = $subrecord->getEventtype2()->getId();
            }
            else {
                $event2 = '';
            }
            if(($subrecord->getEventtype3() != null || $subrecord->getEventtype3() != '')){
                $event3 = $subrecord->getEventtype3()->getId();
            }
            else {
                $event3 = '';
            }
            if(($subrecord->getEventtype4() != null || $subrecord->getEventtype4() != '' )){
                $event4 = $subrecord->getEventtype4()->getId();
            }
            else {
                $event4 = '';
            }
            
        }
        
        $subscribed = new Subscribed();

        $form = $this->createForm(new EventoneType($subrecord), $subscribed);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //First check the value entered by the user
            if ($subscribed->getEventtype1() != null &&
                    $subscribed->getEventtype2() != null &&
                    $subscribed->getEventtype3() != null &&
                    $subscribed->getEventtype4() != null) {
                //User Chose more than 3 events
                $this->container->get('session')->getFlashBag()->add('error', 'Oh oh! You chose to attend 4 events. But 3 is the limit');
                return array('form' => $form->createView());
            }

            $max = $this->container->getParameter('maximum_participants');
            //Now check for the participants limit
            $qb1 = $em->createQueryBuilder();
            $qb1->select('count(subscribed.id)');
            $qb1->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb1->where('subscribed.eventtype1 = :bar');
            $qb1->setParameter('bar', $subscribed->getEventtype1());

            $total1 = $qb1->getQuery()->getSingleScalarResult();  
                        
            if($exists){
                if($event1 != $subscribed->getEventtype1()){
                    if ($total1 > $max || $total1 == $max ) {
                       $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 1. Please choose another event');
                       return array('form' => $form->createView());
                    }
                }
            }
            else {
                if ($total1 > $max || $total1 == $max ) {
                    $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 1. Please choose another event');
                    return array('form' => $form->createView());
                }
            }            

            $qb2 = $em->createQueryBuilder();
            $qb2->select('count(subscribed.id)');
            $qb2->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb2->where('subscribed.eventtype2 = :bar');
            $qb2->setParameter('bar', $subscribed->getEventtype2());

            $total2 = $qb2->getQuery()->getSingleScalarResult();
            if($exists){
                if($event2 != $subscribed->getEventtype2()){
                    if ($total2 > $max || $total2 == $max) {
                        $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 2.Please choose another event');
                        return array('form' => $form->createView());
                    }                   
                }
            }
            else {
                if ($total2 > $max || $total2 == $max) {
                        $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 2.Please choose another event');
                        return array('form' => $form->createView());
                }                   
            }            

            $qb3 = $em->createQueryBuilder();
            $qb3->select('count(subscribed.id)');
            $qb3->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb3->where('subscribed.eventtype3 = :bar');
            $qb3->setParameter('bar', $subscribed->getEventtype3());

            $total3 = $qb3->getQuery()->getSingleScalarResult();
            
            if($exists){
                if($event3 != $subscribed->getEventtype3()){
                    if ($total3 > $max || $total3 == $max) {
                        $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 3. Please choose another event');
                        return array('form' => $form->createView());
                    }                      
                }
            }
            else {
                if ($total3 > $max || $total3 == $max) {
                   $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 3. Please choose another event');
                   return array('form' => $form->createView());
                }
            }            

            $qb4 = $em->createQueryBuilder();
            $qb4->select('count(subscribed.id)');
            $qb4->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb4->where('subscribed.eventtype4 = :bar');
            $qb4->setParameter('bar', $subscribed->getEventtype4());

            $total4 = $qb4->getQuery()->getSingleScalarResult();
            if($exists){
                if($event4 != $subscribed->getEventtype4()){
                    if ($total4 > $max || $total4 == $max) {
                       $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 4.Please choose another event');
                       return array('form' => $form->createView());
                    }                      
                }
            }
            else {
                if ($total4 > $max || $total4 == $max) {
                       $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Cultural Event 4.Please choose another event');
                       return array('form' => $form->createView());
                }
            }           
            
        }


        if ($form->isValid()) {
            
            $sub = $em->getRepository('EventsEventsBundle:Subscribed')->findOneBy(array('user' => $user->getId()));
            $eventtype1 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype1()));
            $eventtype2 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype2()));
            $eventtype3 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype3()));
            $eventtype4 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype4()));
            if(empty($sub)){
                $subscribed->setUser($user);             
                $subscribed->setEventtype1($eventtype1);
                $subscribed->setEventtype2($eventtype2);
                $subscribed->setEventtype3($eventtype3);
                $subscribed->setEventtype4($eventtype4);
                $em->persist($subscribed);                
                $copy = $subscribed;
            }     
            else {
               $sub->setEventtype1($eventtype1);
               $sub->setEventtype2($eventtype2);
               $sub->setEventtype3($eventtype3);
               $sub->setEventtype4($eventtype4);
               $em->persist($sub);
               $copy = $sub;
            }            
             $em->flush();
             $route = 'securedhome';
             $url = $this->generateUrl($route);
             $this->container->get('session')->getFlashBag()->add('success', 'We have your registrations for the events on Thursday. Thank you!');
                          $message = \Swift_Message::newInstance()
                        ->setSubject('EPITA International - Your Registrations for Thursday, 3rd April 2014')
                        ->setFrom('epitaevents2014@gmail.com')
                        ->setTo($user->getEmailCanonical())
                        ->setContentType("text/html")
                        ->setBody(
                        $this->renderView('EventsEventsBundle:Default:thursdaymail.html.twig',array('row' => $copy)
            ));
             $this->get('mailer')->send($message);
             return $this->redirect($url);
        }      
        
        return array('form' => $form->createView());
    }
    
    
    /**
     * @Route("/secured/eventtwo",name="eventtwo")
     * @Template()
     */
    public function eventtwoAction(Request $request) {
        
        $exists = false;

        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('events_events_default_index'));
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EventsEventsBundle:User')->find($this->get('security.context')->getToken()->getUser()->getId());

        if (!is_object($user) || !$user instanceof User) {
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException('This user does not have access to this section.');
        }
        
        $subrecord = $em->getRepository('EventsEventsBundle:Subscribed')->findOneBy(array('user' => $user->getId()));
        
//        if(is_a($subrecord, 'Subscribed')){
        if(!empty($subrecord)){
   $exists = true;
            if($subrecord->getEventtype5() != null || $subrecord->getEventtype5() != '' ){
                $event5 = $subrecord->getEventtype5()->getId();
            }
            else {
                $event5 = '';
            }
            if(($subrecord->getEventtype6() != null || $subrecord->getEventtype6() != '')){
                $event6 = $subrecord->getEventtype6()->getId();
            }
            else {
                $event6 = '';
            }
            if(($subrecord->getEventtype7() != null || $subrecord->getEventtype7() != '')){
                $event7 = $subrecord->getEventtype7()->getId();
            }
            else {
                $event7 = '';
            }
            if(($subrecord->getEventtype8() != null || $subrecord->getEventtype8() != '' )){
                $event8 = $subrecord->getEventtype8()->getId();
            }
            else {
                $event8 = '';
            }
        }
        
        $subscribed = new Subscribed();

        $form = $this->createForm(new EventtwoType($subrecord), $subscribed);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //First check the value entered by the user
            if ($subscribed->getEventtype5() != null &&
                    $subscribed->getEventtype6() != null &&
                    $subscribed->getEventtype7() != null &&
                    $subscribed->getEventtype8() != null) {
                //User Chose more than 3 events
                $this->container->get('session')->getFlashBag()->add('error', 'Oh oh! You chose to attend 4 events. But 3 is the limit');
                return array('form' => $form->createView());
            }

            $max = $this->container->getParameter('maximum_friday');
            //Now check for the participants limit
            $qb1 = $em->createQueryBuilder();
            $qb1->select('count(subscribed.id)');
            $qb1->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb1->where('subscribed.eventtype5 = :bar');
            $qb1->setParameter('bar', $subscribed->getEventtype5());

            $total1 = $qb1->getQuery()->getSingleScalarResult();            
            
            if($exists){
                if($event5 != $subscribed->getEventtype5()){
                    if ($total1 > $max || $total1 == $max ) {
                       $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 1. Please choose another event');
                       return array('form' => $form->createView());
                    }
                }
            }
            else {
                if ($total1 > $max || $total1 == $max ) {
                    $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 1. Please choose another event');
                    return array('form' => $form->createView());
                }
            }            

            $qb2 = $em->createQueryBuilder();
            $qb2->select('count(subscribed.id)');
            $qb2->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb2->where('subscribed.eventtype6 = :bar');
            $qb2->setParameter('bar', $subscribed->getEventtype6());

            $total2 = $qb2->getQuery()->getSingleScalarResult();
            if($exists){
                if($event6 != $subscribed->getEventtype6()){
                    if ($total2 > $max || $total2 == $max) {
                        $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 2.Please choose another event');
                        return array('form' => $form->createView());
                    }                   
                }
            }
            else {
                if ($total2 > $max || $total2 == $max) {
                        $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 2.Please choose another event');
                        return array('form' => $form->createView());
                }                   
            }            

            $qb3 = $em->createQueryBuilder();
            $qb3->select('count(subscribed.id)');
            $qb3->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb3->where('subscribed.eventtype7 = :bar');
            $qb3->setParameter('bar', $subscribed->getEventtype7());

            $total3 = $qb3->getQuery()->getSingleScalarResult();
            
            if($exists){
                if($event7 != $subscribed->getEventtype7()){
                    if ($total3 > $max || $total3 == $max) {
                        $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 3. Please choose another event');
                        return array('form' => $form->createView());
                    }                      
                }
            }
            else {
                if ($total3 > $max || $total3 == $max) {
                   $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 3. Please choose another event');
                   return array('form' => $form->createView());
                }
            }            

            $qb4 = $em->createQueryBuilder();
            $qb4->select('count(subscribed.id)');
            $qb4->from('EventsEventsBundle:Subscribed', 'subscribed');
            $qb4->where('subscribed.eventtype8 = :bar');
            $qb4->setParameter('bar', $subscribed->getEventtype8());

            $total4 = $qb4->getQuery()->getSingleScalarResult();
            if($exists){
                if($event8 != $subscribed->getEventtype8()){
                    if ($total4 > $max || $total4 == $max) {
                       $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 4.Please choose another event');
                       return array('form' => $form->createView());
                    }                      
                }
            }
            else {
                if ($total4 > $max || $total4 == $max) {
                       $this->container->get('session')->getFlashBag()->add('error', 'The registrations are full for the selected Workshop/Conference Events 4.Please choose another event');
                       return array('form' => $form->createView());
                }
            }           
            
        }


        if ($form->isValid()) {
            
            $sub = $em->getRepository('EventsEventsBundle:Subscribed')->findOneBy(array('user' => $user->getId()));
            $eventtype5 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype5()));
            $eventtype6 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype6()));
            $eventtype7 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype7()));
            $eventtype8 = $em->getRepository('EventsEventsBundle:Eventtype')->findOneBy(array('id' => $subscribed->getEventtype8()));
            if(empty($sub)){
                $subscribed->setUser($user);             
                $subscribed->setEventtype5($eventtype5);
                $subscribed->setEventtype6($eventtype6);
                $subscribed->setEventtype7($eventtype7);
                $subscribed->setEventtype8($eventtype8);
                $em->persist($subscribed);                
                $copy = $subscribed;
            }     
            else {
               $sub->setEventtype5($eventtype5);
               $sub->setEventtype6($eventtype6);
               $sub->setEventtype7($eventtype7);
               $sub->setEventtype8($eventtype8);
               $em->persist($sub);
               $copy = $sub;
            }            
             $em->flush();
             $route = 'securedhome';
             $url = $this->generateUrl($route);
             $this->container->get('session')->getFlashBag()->add('success', 'We have your registrations for the events on Friday. Thank you!');
                        $message = \Swift_Message::newInstance()
                        ->setSubject('EPITA International - Your Registrations for Friday, 4th April 2014')
                        ->setFrom('epitaevents2014@gmail.com')
                        ->setTo($user->getEmailCanonical())
                        ->setContentType("text/html")
                        ->setBody(
                        $this->renderView('EventsEventsBundle:Default:fridaymail.html.twig',array('row' => $copy)
            ));
             $this->get('mailer')->send($message);
             return $this->redirect($url);
        }

        return array('form' => $form->createView());
    }

    /**
     *
     * @Route("/export/thursday",name="exportthu")
     *      
     */
    public function exportthuAction(){
        
        $format = 'xls';

        $filename = sprintf('export_students_thursday.%s',$format);        
        
        $data = array();
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT s FROM Events\Bundle\EventsBundle\Entity\Subscribed s');
        $data = $query->getResult();
        $content = $this->renderView('EventsEventsBundle:Default:thursday.html.twig', array('data' => $data));
        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);        
        $response->send();
        
        return new Response($content);
    }
    
    /**
     *
     * @Route("/export/friday",name="exportfri")
     *      
     */
    public function exportfriAction(){
        
        $format = 'xls';

        $filename = sprintf('export_students_friday.%s',$format);        
        
        $data = array();
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT s FROM Events\Bundle\EventsBundle\Entity\Subscribed s');
        $data = $query->getResult();
        $content = $this->renderView('EventsEventsBundle:Default:friday.html.twig', array('data' => $data));        
        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();
        
        return new Response($content);
    }

    /**
     * Authenticate the user
     * 
     * @param FOS\UserBundle\Model\UserInterface
     */
    protected function authenticateUser(User $user) {
        try {
            $this->container->get('security.user_checker')->checkPostAuth($user);
        } catch (AccountStatusException $e) {
            // Don't authenticate locked, disabled or expired users
            return;
        }

        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
    }

}