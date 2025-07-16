<?php

namespace App\Controller;

use App\Entity\DataPoint;
use App\Form\FilterType;
use App\Repository\DataPointRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request, DataPointRepository $repository): Response
    {
        // Création du formulaire de filtrage
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);
        
        // Initialisation des filtres
        $filters = [];
        
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            
            // Export CSV
            if ($form->get('export')->isClicked()) {
                $data = $repository->findByFilters($filters);
                return $this->exportToCsv($data);
            }
        }
        
        // Récupération des données filtrées
        $data = $repository->findByFilters($filters);
        
        // Préparation des données pour les graphiques
        $chartData = $this->prepareChartData($data);
        $stats = $this->calculateStats($data);
        
        return $this->render('dashboard/index.html.twig', [
            'form' => $form->createView(),
            'chartData' => $chartData,
            'stats' => $stats
        ]);
    }
    
    private function prepareChartData(array $data): array
    {
        $grouped = [];
        foreach ($data as $item) {
            $category = $item->getCategory();
            $grouped[$category] = ($grouped[$category] ?? 0) + $item->getValue();
        }
        
        return [
            'labels' => array_keys($grouped),
            'values' => array_values($grouped),
            'colors' => ['#ff6384', '#36a2eb', '#ffce56']
        ];
    }
    
    private function calculateStats(array $data): array
    {
        $total = 0;
        $count = count($data);
        
        foreach ($data as $item) {
            $total += $item->getValue();
        }
        
        return [
            'total' => $total,
            'average' => $count > 0 ? $total / $count : 0,
            'count' => $count
        ];
    }
    
    private function exportToCsv(array $data): Response
    {
        $csv = "ID;Category;Value;Date\n";
        
        foreach ($data as $item) {
            $csv .= sprintf(
                "%d;%s;%.2f;%s\n",
                $item->getId(),
                $item->getCategory(),
                $item->getValue(),
                $item->getDate()->format('Y-m-d H:i:s')
            );
        }
        
        $response = new Response($csv);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_'.date('Ymd_His').'.csv"');
        
        return $response;
    }
}   