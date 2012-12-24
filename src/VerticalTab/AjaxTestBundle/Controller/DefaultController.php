<?php

namespace VerticalTab\AjaxTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    
    public function saveAction(Request $request)
    {
        $session = new Session();
        $session->start();
        $session->getFlashBag()->set('warning', 'Record Saved');
        
        //since no real work being done, simulate a slight delay
        sleep(1);
        
        $form = $this->getForm();
        $form->bind($request);
        $data = $form->getData();
        $format = urlencode($data['format']);
        
        return $this->redirect($this->get('router')
                ->generate('vertical_tab_ajax_test_show', array('id' => '1', 'format' => $format)));
    }
    
    public function showAction($id, $format, Request $request)
    {
        $session = new Session();
        $session->start();
        
        //This simple demo does not persist data.
        //Instead, return some static data for any id
        $form = $this->getForm(array(
            'id' => $id,
            'firstName' => 'Foo',
            'lastName' => 'Bar',
            'phone' => '222.444.5555',
            'street' => '555 Spruce Lane',
            'city' => 'Springfield',
            'state' => 'MO',
            'format' => $format
        ));
        
        if ($format === 'json') {
            $response = new Response(json_encode($form->getData()), 200, array(
                'Content-Type' => 'application/json'
            ));
            return $response;
        }
        
        return $this->render('VerticalTabAjaxTestBundle:Default:contact.form.html.twig', array(
            'form' => $form->createView(),
            'session' => $session,
            'ajax' => $request->isXmlHttpRequest()
        ));
    }
    
    private function getForm($defaultData = null)
    {
        return $this->createFormBuilder($defaultData)
                ->add('id', 'hidden')
                ->add('firstName', 'text', array('label' => 'First Name'))
                ->add('lastName', 'text', array('label' => 'Last Name'))
                ->add('phone', 'text', array('label' => 'Phone'))
                ->add('street', 'text', array('label' => 'Street'))
                ->add('city', 'text', array('label' => 'City'))
                ->add('state', 'text', array('label' => 'State'))
                ->add('format', 'choice', array(
                    'label' => 'Format',
                    'choices' => array('html' => 'html', 'json' => 'json')
                ))
                ->getForm();
    }
}
