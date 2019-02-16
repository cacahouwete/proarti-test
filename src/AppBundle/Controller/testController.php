<?php
/**
 * Created by PhpStorm.
 * User: hasse
 * Date: 2/13/2019
 * Time: 2:23 PM
 */
namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class testController extends Controller
{
    /**
     * @Route ("/test")
     */
    public function helloAction(){
        return $this->render('test/test.html.twig');

    }

    /**
     * @Route ("/loadzz")
     */
    public function testAction(Request $request){

        $file = $request->files->get('url');
        $rootPath = $this->get('kernel')->getRootDir();
        $rootPath = str_replace("\\app", "", $rootPath);
        $rootPath = str_replace("\\", "\/", $rootPath);

        $file->move($rootPath, "data_exam.csv");
        $command=$rootPath."//test.bat ".$rootPath."//data_exam.csv";


        exec($command);

        return $this->render('test/load.html.twig');

    }
}