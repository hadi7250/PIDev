<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CertificatRepository;
use App\Repository\EventRepository;
use App\Repository\ParticipationRepository;
use App\Repository\RatingRepository;
use App\Service\ReportChartGenerator;
use App\Service\ReportService;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back')]
final class BackOfficeController extends AbstractController
{
    #[Route('/', name: 'app_back_office', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }

    #[Route('/gestion_event', name: 'app_gestion_event', methods: ['GET'])]
    public function gestionEvent(
        EventRepository    $eventRepository,
        CategoryRepository $categoryRepository
    ): Response {
        return $this->render('Back_Office/event-datatable.html.twig', [
            'events'     => [],
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * Export PDF du rapport global des gestions avec synthèse IA.
     */
    #[Route('/report/pdf', name: 'app_back_report_pdf', methods: ['GET'])]
    public function reportPdf(
        EventRepository         $eventRepository,
        CategoryRepository      $categoryRepository,
        CertificatRepository    $certificatRepository,
        ParticipationRepository $participationRepository,
        RatingRepository        $ratingRepository,
        ReportService           $reportService,
        ReportChartGenerator    $chartGenerator,
    ): Response {
        $events              = $eventRepository->findBy([], ['dateDebut' => 'ASC']);
        $categories          = $categoryRepository->findAll();
        $participationsCount = $participationRepository->count([]);
        $ratingsCount        = $ratingRepository->count([]);
        $certificatsCount    = $certificatRepository->count([]);

        $eventsByCategory = [];
        foreach ($categories as $category) {
            $eventsByCategory[$category->getName() ?? ''] = $category->getEvents()->count();
        }

        $stats = [
            'events_count'         => \count($events),
            'categories_count'     => \count($categories),
            'participations_count' => $participationsCount,
            'ratings_count'        => $ratingsCount,
            'certificats_count'    => $certificatsCount,
            'events_by_category'   => $eventsByCategory,
        ];

        // Get AI summary and pre-clean it before the template sees it
        $aiSummary = $reportService->generateSummary($stats) ?? '';
        $aiSummary = $this->cleanForDompdf($aiSummary);

        $chartStatsBase64 = $chartGenerator->barChartBase64(
            ['Evenements', 'Categories', 'Participations', 'Avis', 'Certificats'],
            [
                $stats['events_count'],
                $stats['categories_count'],
                $stats['participations_count'],
                $stats['ratings_count'],
                $stats['certificats_count'],
            ],
            "Vue d'ensemble"
        );

        $chartCategoryBase64 = null;
        if ($eventsByCategory !== []) {
            $chartCategoryBase64 = $chartGenerator->barChartBase64(
                array_keys($eventsByCategory),
                array_values($eventsByCategory),
                'Evenements par categorie'
            );
        }

        $html = $this->renderView('Back_Office/report-pdf.html.twig', [
            'stats'               => $stats,
            'aiSummary'           => $aiSummary,
            'generatedAt'         => new \DateTimeImmutable(),
            'chartStatsBase64'    => $chartStatsBase64,
            'chartCategoryBase64' => $chartCategoryBase64,
        ]);

        // ---------------------------------------------------------------
        // THE DEFINITIVE FIX:
        // Convert every non-ASCII character in the rendered HTML into its
        // numeric HTML entity (e.g. é -> &#233;).  Dompdf calls iconv()
        // internally on the raw byte string we hand it.  If that string
        // is pure ASCII, iconv has nothing to choke on.  Dompdf's own
        // HTML parser then decodes &#NNN; entities and renders the correct
        // glyphs through the DejaVu font.
        // ---------------------------------------------------------------
        $html = $this->htmlToAsciiEntities($html);

        $dompdf = new Dompdf([
            'is_remote_enabled'       => false,
            'is_html5_parser_enabled' => true,
            'default_font'            => 'DejaVu Sans',
        ]);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename   = 'rapport-back-office-' . date('Y-m-d-His') . '.pdf';

        return new Response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length'      => \strlen($pdfContent),
        ]);
    }

    /**
     * Converts every non-ASCII character in a full HTML string into its
     * numeric HTML entity, leaving ASCII bytes, tags, and base64 blobs
     * completely untouched.
     *
     * Strategy: split on tag boundaries so we only encode TEXT nodes.
     * Tag content (attributes, base64 src="data:image/png;base64,…") is
     * never touched — those are already ASCII-safe.
     */
    private function htmlToAsciiEntities(string $html): string
    {
        // Split into alternating text / tag segments.
        // Odd indexes = tags (<...>), even indexes = text nodes.
        $parts = preg_split('/(<[^>]*>)/su', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($parts === false) {
            return $html;
        }

        foreach ($parts as $i => &$part) {
            // Even indexes are text nodes — encode non-ASCII chars
            if ($i % 2 === 0 && $part !== '') {
                $part = mb_encode_numericentity(
                    $part,
                    [0x80, 0x10FFFF, 0, 0xFFFFFF],
                    'UTF-8'
                );
            }
            // Odd indexes are tags — leave completely untouched
        }
        unset($part);

        return implode('', $parts);
    }

    /**
     * Pre-cleans a plain-text string (typically the AI summary):
     *  1. Replaces common LLM Unicode punctuation with safe ASCII equivalents.
     *  2. Strips genuinely invalid / incomplete UTF-8 byte sequences.
     */
    private function cleanForDompdf(string $text): string
    {
        $map = [
            "\u{2018}" => "'",
            "\u{2019}" => "'",
            "\u{201C}" => '"',
            "\u{201D}" => '"',
            "\u{2013}" => '-',
            "\u{2014}" => '--',
            "\u{2026}" => '...',
            "\u{00A0}" => ' ',
            "\u{FEFF}" => '',
        ];
        $text = str_replace(array_keys($map), array_values($map), $text);

        // Robustly clean invalid UTF-8 without triggering iconv() notices.
        // This converts the string to UTF-8 while dropping any invalid byte sequences.
        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }
}
