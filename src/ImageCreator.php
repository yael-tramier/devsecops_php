<?php

namespace App;

use Carbon\Carbon;

use function imagecolorallocate;
use function imagecreatetruecolor;
use function imagedestroy;
use function imagefilledrectangle;
use function imagepng;
use function imagettftext;

class ImageCreator
{
    protected $im;
    protected int $white;
    protected int $yourColor;
    protected int $yourColor2;
    protected string $text;
    protected string $text2;
    protected string $font;

    /**
    */
    public function __construct(
        array  $yourColor = [128, 128, 128],
        array  $yourColor2 = [60, 80, 57],
        string $text = "DEVOPS",
        string $text2 = "Une superbe image"
    ) {
        // Création d'une image de 400x200 pixels
        $this->im = imagecreatetruecolor(400, 200);
        $this->white = $this->allocateColor([255, 255, 255]);
        $this->yourColor = $this->allocateColor($yourColor);
        $this->yourColor2 = $this->allocateColor($yourColor2);

        // Le texte
        $this->text = $text . ' - ' . (new Carbon())->format('Y-m-d H:i:s');
        $this->text2 = $text2;

        if (!empty($_ENV['APP_SECRET'])) {
            $this->text2 .= ' (secret: ' . $_ENV['APP_SECRET'] . ')';
        }

        // La police
        $this->font = '/var/www/html/public/font/consolas.ttf';
    }


    /**
     * Allocate Color
     *
     * Allocates a color in the image resource based on the given RGB values.
     *
     * @param array $rgb An array representing the RGB values for the color.
     *
     * @return false|int Returns the allocated color identifier on success, or false on failure.
     */
    private function allocateColor(array $rgb): false|int
    {
        // Uses the GD library function imagecolorallocate to allocate a color in the image.
        // The spread operator (...) is used to unpack the RGB array into individual arguments.
        return imagecolorallocate($this->im, ...$rgb);
    }

    /**
    */
    public function createImage(): void
    {
        // Dessine un double rectangle
        imagefilledrectangle($this->im, 0, 0, 400, 200, $this->yourColor);
        imagefilledrectangle($this->im, 10, 10, 390, 190, $this->yourColor2);

        // Ajout du texte
        imagettftext($this->im, 20, 0, 50, 50, $this->white, $this->font, $this->text);
        imagettftext($this->im, 12, 0, 50, 80, $this->white, $this->font, $this->text2);

        // Sauvegarde l'image
        imagepng($this->im);
        imagedestroy($this->im);
    }
}
