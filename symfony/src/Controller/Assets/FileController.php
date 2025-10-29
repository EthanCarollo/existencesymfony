<?php

declare(strict_types=1);

namespace App\Controller\Assets;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileController extends AbstractController
{
    #[Route('/api/models/{filename}', name: 'serve_model_file')]
    public function serveModelFile(string $filename): BinaryFileResponse
    {
        $filename = basename($filename);

        $filePath = $this->getParameter('kernel.project_dir') . '/public/models/' . $filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found.');
        }

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }
}

