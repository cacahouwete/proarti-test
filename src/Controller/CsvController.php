<?php
namespace App\Controller;

use App\Form\UploadFileType;
use App\Repository\ProjectRepository;
use App\Service\CsvService;
use App\Service\DataService;
use App\Service\UploadFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CsvController extends AbstractController {
    /**
     * @Route("/", name="csv_upload")
     */
    public function home(Request $request, UploadFileService $uploadFile, CsvService $csvService, DataService $dataService, ProjectRepository $projectRepository): Response
    {
        $form = $this->createForm(UploadFileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csv')->getData();

            if ($csvFile) {
                $csvFileName = $uploadFile->upload($csvFile);
                $this->addFlash("success", "File uploaded with success");
                $path = $uploadFile->getTargetDirectory().'/'.$csvFileName;
                $rows = $csvService->csvToArray($path);
                $dataService->setAllData($rows);
            }

            
            return $this->render('home.html.twig', [
                'form' => $form->createView(),
                'project' => $projectRepository->findAll(),
            ]);
        }
        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'project' => $projectRepository->findAll(),
        ]);
    }
}