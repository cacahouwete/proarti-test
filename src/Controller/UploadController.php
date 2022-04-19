<?php

namespace App\Controller;

use App\Csv\FileUploader;
use App\Csv\UploadType;
use App\Interfaces\csv\CsvManagerInterface;
use App\Repository\DonationRepository;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use App\Repository\RewardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/', name: 'upload')]
    public function import(Request $request, FileUploader $fileUploader, CsvManagerInterface $csvManager, ProjectRepository $projectRepository, DonationRepository $donationRepository, PersonRepository $personRepository, RewardRepository $rewardRepository): Response
    {
        $form = $this->createForm(UploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('name')->getData();


            if ($csvFile) {
                $csvFileName = $fileUploader->upload($csvFile);
                $this->addFlash('success', 'Fichier csv importé avec succès');
                $file = new \SplFileObject($fileUploader->getTargetDirectory().'/'.$csvFileName);
                $csvManager->import($file);
            }

            return $this->render('upload/index.html.twig', [
                'form' => $form->createView(),
                'projects' => $projectRepository->findAll(),
                'persons' => $personRepository->findAll(),
                'rewards' => $rewardRepository->findAll(),
                'donations' => $donationRepository->findAll(),
            ]);
        }

        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
            'projects' => $projectRepository->findAll(),
            'persons' => $personRepository->findAll(),
            'rewards' => $rewardRepository->findAll(),
            'donations' => $donationRepository->findAll(),
        ]);
    }
}
