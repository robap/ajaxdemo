<?php

namespace VerticalTab\AjaxTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $session = new Session();
        $session->start();
        
        $form = $this->getForm();
        
        return $this->render('VerticalTabAjaxTestBundle:Default:contact.form.html.twig', array(
            'form' => $form->createView(),
            'session' => $session,
            'ajax' => false
        ));
    }
    
    public function saveAction()
    {
        $session = new Session();
        $session->start();
        $session->getFlashBag()->set('warning', 'Record Saved');
        
        //since no real work being done, simulate a slight delay
        sleep(1);
        
        return $this->redirect($this->get('router')
                ->generate('vertical_tab_ajax_test_show', array('id' => '1')));
    }
    
    public function showAction($id, Request $request)
    {
        $session = new Session();
        $session->start();
        
        //This simple demo does not persist data.
        //Instead, simply return some static data for any slug
        $form = $this->getForm(array(
            'firstName' => 'Foo',
            'lastName' => 'Bar',
            'phone' => '222.444.5555',
            'street' => '555 Spruce Lane',
            'city' => 'Springfield',
            'state' => 'MO'
        ));
        
        return $this->render('VerticalTabAjaxTestBundle:Default:contact.form.html.twig', array(
            'form' => $form->createView(),
            'session' => $session,
            'ajax' => $request->isXmlHttpRequest()
        ));
    }
    
    private function getForm($defaultData = null)
    {
        return $this->createFormBuilder($defaultData)
                ->add('firstName', 'text', array('label' => 'First Name'))
                ->add('lastName', 'text', array('label' => 'Last Name'))
                ->add('phone', 'text', array('label' => 'Phone'))
                ->add('street', 'text', array('label' => 'Street'))
                ->add('city', 'text', array('label' => 'City'))
                ->add('state', 'text', array('label' => 'State'))
                ->getForm();
    }
}
