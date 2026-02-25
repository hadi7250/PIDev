<?php

namespace App\Service;

/**
 * Génère des images de graphiques (barres) pour le rapport PDF, via PHP GD.
 */
final class ReportChartGenerator
{
    private const WIDTH = 480;
    private const HEIGHT = 260;
    private const BAR_COLOR = [52, 99, 235]; // bleu
    private const BAR_COLOR_ALT = [34, 197, 94]; // vert
    private const BG_COLOR = [255, 255, 255];
    private const TEXT_COLOR = [51, 51, 51];
    private const GRID_COLOR = [230, 230, 230];

    /**
     * Retourne une image PNG en base64 pour un graphique en barres (labels + valeurs).
     *
     * @param array<string> $labels
     * @param array<int|float> $values
     */
    public function barChartBase64(array $labels, array $values, string $title = ''): ?string
    {
        if ($labels === [] || $values === [] || \count($labels) !== \count($values)) {
            return null;
        }

        $image = $this->drawBarChart($labels, $values, $title);
        if ($image === null) {
            return null;
        }

        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        return $png !== false ? base64_encode($png) : null;
    }

    /**
     * @param array<string> $labels
     * @param array<int|float> $values
     */
    private function drawBarChart(array $labels, array $values, string $title): ?\GdImage
    {
        if (!\function_exists('imagecreatetruecolor')) {
            return null;
        }

        $img = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        if ($img === false) {
            return null;
        }

        $bg = imagecolorallocate($img, self::BG_COLOR[0], self::BG_COLOR[1], self::BG_COLOR[2]);
        $textColor = imagecolorallocate($img, self::TEXT_COLOR[0], self::TEXT_COLOR[1], self::TEXT_COLOR[2]);
        $gridColor = imagecolorallocate($img, self::GRID_COLOR[0], self::GRID_COLOR[1], self::GRID_COLOR[2]);
        $barColor = imagecolorallocate($img, self::BAR_COLOR[0], self::BAR_COLOR[1], self::BAR_COLOR[2]);
        $barColorAlt = imagecolorallocate($img, self::BAR_COLOR_ALT[0], self::BAR_COLOR_ALT[1], self::BAR_COLOR_ALT[2]);

        imagefill($img, 0, 0, $bg);

        $marginLeft = 80;
        $marginRight = 40;
        $marginTop = $title !== '' ? 36 : 20;
        $marginBottom = 50;
        $chartWidth = self::WIDTH - $marginLeft - $marginRight;
        $chartHeight = self::HEIGHT - $marginTop - $marginBottom;

        $maxVal = max(1, max($values));
        $barCount = \count($values);
        $barWidth = min(40, (int)(($chartWidth - ($barCount - 1) * 8) / $barCount));
        $gap = $barCount > 1 ? (int)(($chartWidth - $barCount * $barWidth) / ($barCount + 1)) : (int)(($chartWidth - $barWidth) / 2);

        if ($title !== '') {
            imagestring($img, 3, (int)(self::WIDTH / 2 - strlen($title) * 2.5), 8, $title, $textColor);
        }

        for ($i = 0; $i < $barCount; $i++) {
            $x = $marginLeft + $gap + $i * ($barWidth + $gap);
            $h = (int)(($values[$i] / $maxVal) * $chartHeight);
            $y = $marginTop + $chartHeight - $h;
            $color = $i % 2 === 0 ? $barColor : $barColorAlt;
            imagefilledrectangle($img, $x, $y, $x + $barWidth, $marginTop + $chartHeight, $color);
            imagestring($img, 2, $x, $marginTop + $chartHeight + 4, (string)$values[$i], $textColor);
            $label = $labels[$i];
            if (strlen($label) > 12) {
                $label = substr($label, 0, 10) . '..';
            }
            imagestring($img, 2, max(0, $x - 10), $marginTop + $chartHeight + 18, $label, $textColor);
        }

        return $img;
    }
}
