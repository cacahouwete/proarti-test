<?php

namespace TestBundle\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use TestBundle\Entity\Donation;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TestBundle:Default:index.html.twig');
    }

    /**
     * @Route("/create",name="creat")
     */
    public function donationAction()
    {
        $donation=new Donation();

        $donation->setFirstName("aaze");
        $donation->setLastName("zdz");
        $donation->setAmount(6);
        $donation->setProjectName("dqzq");
        $donation->setReward("qdqd");
        $donation->setRewardQuantity(7);

        $em = $this->getDoctrine()->getManager();
        $em->persist($donation);
        $em->flush();

        //return Response("Saved".$donation->getId());
        return $this->render('@Test/Default/index.html.twig');
    }
}
