<?php

namespace App\Services;

class CertificateGenerationService
{
    /**
     * Generate a share certificate for a user.
     *
     * @param string $userName
     * @param string $certificateNumber
     * @param string $issuedDate
     * @param string $licenseNumber
     * @param int $shares
     * @return string Path to the generated certificate
     */
    public function generateCertificate(string $userName, string $certificateNumber, string $issuedDate, string $licenseNumber = 'Ireland', int $shares = 250000): string
    {
        // Certificate dimensions
        $width = 1920;
        $height = 1080;

        // Create a new image
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Define colors
        $darkBlue = imagecolorallocate($image, 15, 47, 111);      // #0f2f6f
        $navyBlue = imagecolorallocate($image, 30, 58, 138);      // #1e3a8a
        $white = imagecolorallocate($image, 255, 255, 255);       // #ffffff
        $cream = imagecolorallocate($image, 248, 245, 240);       // #f8f5f0
        $golden = imagecolorallocate($image, 201, 169, 97);       // #c9a961
        $lightGolden = imagecolorallocate($image, 218, 194, 139); // #dac28b
        $brightGolden = imagecolorallocate($image, 255, 215, 0);  // #ffd700
        $textGray = imagecolorallocate($image, 240, 235, 225);    // Light beige for texture

        // Fill background with dark blue
        imagefilledrectangle($image, 0, 0, $width, $height, $darkBlue);

        // Draw white inner rectangle (certificate body)
        imagefilledrectangle($image, 50, 50, $width - 50, $height - 50, $cream);

        // Add subtle texture to the certificate body
        $this->addTexture($image, 50, 50, $width - 50, $height - 50, $cream, $textGray);

        // Draw golden border frame
        $this->drawGoldenBorderFrame($image, $width, $height, $golden, $lightGolden);

        // Add decorative corner elements (elaborate design)
        $this->drawElaborateCorners($image, $width, $height, $golden, $lightGolden);

        // Add curved decorative ribbons from corners (like the reference)
        $this->drawCurvedCornerRibbons($image, $width, $height, $golden, $lightGolden);

        // Add logo at top (if exists) or placeholder
        $this->addLogo($image, $width);

        // Add text content
        $this->addTextContent($image, $width, $height, $userName, $certificateNumber, $issuedDate, $licenseNumber, $shares, $darkBlue, $golden);

        // Add decorative seal/badge with ribbons
        $this->drawElaborateSeal($image, $width, $height, $golden, $lightGolden, $brightGolden);

        // Save the certificate directly in public folder
        $fileName = 'documents/certificates/' . uniqid('cert_') . '.png';
        $publicPath = public_path($fileName);

        // Ensure directory exists
        if (!file_exists(dirname($publicPath))) {
            mkdir(dirname($publicPath), 0755, true);
        }

        imagepng($image, $publicPath);
        imagedestroy($image);

        return $fileName;
    }

    /**
     * Add subtle texture to certificate background (aged paper effect).
     */
    private function addTexture($image, int $x1, int $y1, int $x2, int $y2, $baseColor, $textureColor): void
    {
        // Add random noise for vintage texture effect - more density
        for ($i = 0; $i < 5000; $i++) {
            $x = rand($x1, $x2);
            $y = rand($y1, $y2);
            $size = rand(1, 2);
            $alpha = rand(10, 40); // Semi-transparent spots
            imagefilledellipse($image, $x, $y, $size, $size, $textureColor);
        }
        
        // Add some slightly larger spots for more visible texture
        for ($i = 0; $i < 300; $i++) {
            $x = rand($x1, $x2);
            $y = rand($y1, $y2);
            $size = rand(3, 6);
            imagefilledellipse($image, $x, $y, $size, $size, $textureColor);
        }
    }

    /**
     * Draw golden border frame around certificate.
     */
    private function drawGoldenBorderFrame($image, int $width, int $height, $golden, $lightGolden): void
    {
        $borderWidth = 10;

        // Outer golden border
        imagefilledrectangle($image, 45, 45, $width - 45, 55, $golden);                    // Top
        imagefilledrectangle($image, 45, $height - 55, $width - 45, $height - 45, $golden); // Bottom
        imagefilledrectangle($image, 45, 45, 55, $height - 45, $golden);                   // Left
        imagefilledrectangle($image, $width - 55, 45, $width - 45, $height - 45, $golden); // Right

        // Inner lighter border for 3D effect
        imagefilledrectangle($image, 75, 75, $width - 75, 80, $lightGolden);                    // Top inner
        imagefilledrectangle($image, 75, $height - 80, $width - 75, $height - 75, $lightGolden); // Bottom inner
        imagefilledrectangle($image, 75, 75, 80, $height - 75, $lightGolden);                   // Left inner
        imagefilledrectangle($image, $width - 80, 75, $width - 75, $height - 75, $lightGolden); // Right inner
    }

    /**
     * Draw elaborate corner decorations.
     */
    private function drawElaborateCorners($image, int $width, int $height, $golden, $lightGolden): void
    {
        $cornerSize = 150;

        // Top-left elaborate corner
        $this->drawFancyCorner($image, 30, 30, $cornerSize, 'top-left', $golden, $lightGolden);

        // Top-right elaborate corner
        $this->drawFancyCorner($image, $width - 30, 30, $cornerSize, 'top-right', $golden, $lightGolden);

        // Bottom-left elaborate corner
        $this->drawFancyCorner($image, 30, $height - 30, $cornerSize, 'bottom-left', $golden, $lightGolden);

        // Bottom-right elaborate corner
        $this->drawFancyCorner($image, $width - 30, $height - 30, $cornerSize, 'bottom-right', $golden, $lightGolden);
    }

    /**
     * Draw a single fancy corner decoration.
     */
    private function drawFancyCorner($image, int $x, int $y, int $size, string $position, $golden, $lightGolden): void
    {
        $large = $size;
        $medium = $size * 0.6;
        $small = $size * 0.3;

        switch ($position) {
            case 'top-left':
                // Large triangle
                $points = [$x, $y, $x + $large, $y, $x, $y + $large];
                imagefilledpolygon($image, $points, 3, $golden);
                // Medium triangle overlay
                $points = [$x + 10, $y + 10, $x + $medium, $y + 10, $x + 10, $y + $medium];
                imagefilledpolygon($image, $points, 3, $lightGolden);
                break;

            case 'top-right':
                $points = [$x, $y, $x - $large, $y, $x, $y + $large];
                imagefilledpolygon($image, $points, 3, $golden);
                $points = [$x - 10, $y + 10, $x - $medium, $y + 10, $x - 10, $y + $medium];
                imagefilledpolygon($image, $points, 3, $lightGolden);
                break;

            case 'bottom-left':
                $points = [$x, $y, $x + $large, $y, $x, $y - $large];
                imagefilledpolygon($image, $points, 3, $golden);
                $points = [$x + 10, $y - 10, $x + $medium, $y - 10, $x + 10, $y - $medium];
                imagefilledpolygon($image, $points, 3, $lightGolden);
                break;

            case 'bottom-right':
                $points = [$x, $y, $x - $large, $y, $x, $y - $large];
                imagefilledpolygon($image, $points, 3, $golden);
                $points = [$x - 10, $y - 10, $x - $medium, $y - 10, $x - 10, $y - $medium];
                imagefilledpolygon($image, $points, 3, $lightGolden);
                break;
        }
    }

    /**
     * Draw curved decorative ribbons from corners (matching reference design).
     */
    private function drawCurvedCornerRibbons($image, int $width, int $height, $golden, $lightGolden): void
    {
        imagesetthickness($image, 6);
        
        // Top-left to top-right curved ribbon
        $this->drawBezierCurve($image, 150, 80, 450, 60, $width - 450, 60, $width - 150, 80, $golden);
        
        // Top-right to bottom-right curved ribbon (right side)
        $this->drawBezierCurve($image, $width - 80, 150, $width - 60, 450, $width - 60, $height - 450, $width - 80, $height - 150, $golden);
        
        // Bottom-right to bottom-left curved ribbon
        $this->drawBezierCurve($image, $width - 150, $height - 80, $width - 450, $height - 60, 450, $height - 60, 150, $height - 80, $golden);
        
        // Bottom-left to top-left curved ribbon (left side)
        $this->drawBezierCurve($image, 80, $height - 150, 60, $height - 450, 60, 450, 80, 150, $golden);
        
        imagesetthickness($image, 1);
    }

    /**
     * Draw a bezier curve (cubic).
     */
    private function drawBezierCurve($image, $x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $color): void
    {
        $steps = 100;
        $prevX = $x0;
        $prevY = $y0;
        
        for ($i = 1; $i <= $steps; $i++) {
            $t = $i / $steps;
            $t2 = $t * $t;
            $t3 = $t2 * $t;
            $mt = 1 - $t;
            $mt2 = $mt * $mt;
            $mt3 = $mt2 * $mt;
            
            $x = $mt3 * $x0 + 3 * $mt2 * $t * $x1 + 3 * $mt * $t2 * $x2 + $t3 * $x3;
            $y = $mt3 * $y0 + 3 * $mt2 * $t * $y1 + 3 * $mt * $t2 * $y2 + $t3 * $y3;
            
            imageline($image, $prevX, $prevY, $x, $y, $color);
            $prevX = $x;
            $prevY = $y;
        }
    }

    /**
     * Add logo at the top of certificate.
     */
    private function addLogo($image, int $width): void
    {
        $logoPath = public_path('images/logo.png');
        
        if (file_exists($logoPath)) {
            $logo = imagecreatefrompng($logoPath);
            $logoWidth = imagesx($logo);
            $logoHeight = imagesy($logo);
            
            // Resize logo if needed (max 120x120)
            $maxSize = 120;
            if ($logoWidth > $maxSize || $logoHeight > $maxSize) {
                $ratio = min($maxSize / $logoWidth, $maxSize / $logoHeight);
                $newWidth = $logoWidth * $ratio;
                $newHeight = $logoHeight * $ratio;
                
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefill($resized, 0, 0, $transparent);
                imagecopyresampled($resized, $logo, 0, 0, 0, 0, $newWidth, $newHeight, $logoWidth, $logoHeight);
                
                $logoX = ($width - $newWidth) / 2;
                imagecopy($image, $resized, $logoX, 120, 0, 0, $newWidth, $newHeight);
                imagedestroy($resized);
            } else {
                $logoX = ($width - $logoWidth) / 2;
                imagecopy($image, $logo, $logoX, 120, 0, 0, $logoWidth, $logoHeight);
            }
            imagedestroy($logo);
        }
    }

    /**
     * Add text content to the certificate.
     */
    private function addTextContent($image, int $width, int $height, string $userName, string $certificateNumber, string $issuedDate, string $licenseNumber, int $shares, $darkBlue, $golden): void
    {
        $centerX = $width / 2;
        $font = $this->getFont();
        $scriptFont = $this->getScriptFont();

        // Title: SHARE CERTIFICATE
        $this->drawTextWithFont($image, 'SHARE CERTIFICATE', $centerX, 280, 72, $font, $darkBlue, 'center');

        // Company name
        $this->drawTextWithFont($image, 'COMPANY NAME: HOLA TAXI IRELAND LIMITED', $centerX, 350, 30, $font, $darkBlue, 'center');

        // "THIS IS TO CERTIFY THAT"
        $this->drawTextWithFont($image, 'THIS IS TO CERTIFY THAT', $centerX, 440, 32, $font, $darkBlue, 'center');

        // User name in script/cursive font (larger and more prominent)
        $this->drawTextWithFont($image, $userName, $centerX, 560, 85, $scriptFont, $darkBlue, 'center');

        // Underline for name with decorative dots and thicker line
        $underlineY = 580;
        imagesetthickness($image, 4);
        imageline($image, $centerX - 550, $underlineY, $centerX + 550, $underlineY, $darkBlue);
        imagesetthickness($image, 1);
        imagefilledellipse($image, $centerX - 550, $underlineY, 12, 12, $darkBlue);
        imagefilledellipse($image, $centerX + 550, $underlineY, 12, 12, $darkBlue);

        // Details text - first line (split for better control)
        $ofText = "of";
        $this->drawTextWithFont($image, $ofText, $centerX - 570, 650, 26, $font, $darkBlue, 'left');
        
        // Ireland with underline
        $irelandX = $centerX - 520;
        $this->drawTextWithFont($image, $licenseNumber, $irelandX, 650, 26, $font, $darkBlue, 'left');
        imagefilledrectangle($image, $irelandX, 655, $irelandX + 75, 657, $darkBlue);
        
        // Rest of text
        $middleText = "holding license number is the registered holder of";
        $this->drawTextWithFont($image, $middleText, $centerX - 400, 650, 26, $font, $darkBlue, 'left');
        
        // Shares with underline
        $sharesX = $centerX + 380;
        $sharesText = number_format($shares);
        $this->drawTextWithFont($image, $sharesText, $sharesX, 650, 26, $font, $darkBlue, 'left');
        imagefilledrectangle($image, $sharesX, 655, $sharesX + 90, 657, $darkBlue);

        // Details text - second line
        $line2 = 'Class A Ordinary Shares in the capital of Hola Taxi Ireland Limited.';
        $this->drawTextWithFont($image, $line2, $centerX, 700, 26, $font, $darkBlue, 'center');

        // Certificate details at bottom left
        $leftX = 175;
        $bottomY = 900;

        $this->drawTextWithFont($image, 'CERTIFICATE NO:', $leftX, $bottomY, 22, $font, $darkBlue, 'left');
        $this->drawTextWithFont($image, $certificateNumber, $leftX + 250, $bottomY, 22, $font, $darkBlue, 'left');

        $this->drawTextWithFont($image, 'DATE OF ISSUE:', $leftX, $bottomY + 50, 22, $font, $darkBlue, 'left');
        $this->drawTextWithFont($image, $issuedDate, $leftX + 250, $bottomY + 50, 22, $font, $darkBlue, 'left');

        // Director signature section (bottom right)
        $rightX = $width - 400;
        $this->drawTextWithFont($image, 'Kamal S Gill', $rightX, $bottomY, 36, $scriptFont, $darkBlue, 'center');

        // Underline for signature
        imagefilledrectangle($image, $rightX - 150, $bottomY + 10, $rightX + 150, $bottomY + 12, $darkBlue);

        $this->drawTextWithFont($image, 'DIRECTOR', $rightX, $bottomY + 70, 22, $font, $darkBlue, 'center');
    }

    /**
     * Draw text with TTF font.
     */
    private function drawTextWithFont($image, string $text, int $x, int $y, int $size, string $fontPath, $color, string $align = 'left'): void
    {
        if (file_exists($fontPath)) {
            $bbox = imagettfbbox($size, 0, $fontPath, $text);
            $textWidth = $bbox[2] - $bbox[0];

            if ($align === 'center') {
                $x = $x - ($textWidth / 2);
            } elseif ($align === 'right') {
                $x = $x - $textWidth;
            }

            imagettftext($image, $size, 0, $x, $y, $color, $fontPath, $text);
        } else {
            // Fallback to built-in font
            $fontNum = 5;
            $textWidth = imagefontwidth($fontNum) * strlen($text);

            if ($align === 'center') {
                $x = $x - ($textWidth / 2);
            } elseif ($align === 'right') {
                $x = $x - $textWidth;
            }

            imagestring($image, $fontNum, $x, $y, $text, $color);
        }
    }

    /**
     * Draw elaborate seal/badge with sun rays and ribbons.
     */
    private function drawElaborateSeal($image, int $width, int $height, $golden, $lightGolden, $brightGolden): void
    {
        $centerX = $width / 2;
        $sealY = 800;
        $radius = 70;

        // Draw sun rays emanating from seal
        $rayCount = 24;
        for ($i = 0; $i < $rayCount; $i++) {
            $angle = ($i / $rayCount) * 2 * M_PI;
            $x1 = $centerX + cos($angle) * ($radius - 10);
            $y1 = $sealY + sin($angle) * ($radius - 10);
            $x2 = $centerX + cos($angle) * ($radius + 30);
            $y2 = $sealY + sin($angle) * ($radius + 30);

            imagesetthickness($image, 3);
            imageline($image, $x1, $y1, $x2, $y2, $golden);
        }
        imagesetthickness($image, 1);

        // Outer circle (darker golden)
        imagefilledellipse($image, $centerX, $sealY, $radius * 2, $radius * 2, $golden);

        // Middle circle (lighter golden)
        imagefilledellipse($image, $centerX, $sealY, ($radius - 10) * 2, ($radius - 10) * 2, $lightGolden);

        // Inner circle (bright golden)
        imagefilledellipse($image, $centerX, $sealY, ($radius - 20) * 2, ($radius - 20) * 2, $brightGolden);

        // Add wavy ribbons at bottom
        $this->drawRibbons($image, $centerX, $sealY, $golden, $lightGolden);
    }

    /**
     * Draw decorative ribbons below seal.
     */
    private function drawRibbons($image, int $centerX, int $sealY, $golden, $lightGolden): void
    {
        // Left ribbon
        $leftRibbon = [
            $centerX - 50, $sealY + 55,
            $centerX - 45, $sealY + 130,
            $centerX - 30, $sealY + 135,
            $centerX - 25, $sealY + 60,
        ];
        imagefilledpolygon($image, $leftRibbon, 4, $golden);

        // Left ribbon fold
        $leftFold = [
            $centerX - 45, $sealY + 130,
            $centerX - 30, $sealY + 135,
            $centerX - 35, $sealY + 145,
        ];
        imagefilledpolygon($image, $leftFold, 3, $lightGolden);

        // Right ribbon
        $rightRibbon = [
            $centerX + 50, $sealY + 55,
            $centerX + 45, $sealY + 130,
            $centerX + 30, $sealY + 135,
            $centerX + 25, $sealY + 60,
        ];
        imagefilledpolygon($image, $rightRibbon, 4, $golden);

        // Right ribbon fold
        $rightFold = [
            $centerX + 45, $sealY + 130,
            $centerX + 30, $sealY + 135,
            $centerX + 35, $sealY + 145,
        ];
        imagefilledpolygon($image, $rightFold, 3, $lightGolden);
    }

    /**
     * Get regular font path (tries multiple common locations).
     */
    private function getFont(): string
    {
        $fontPaths = [
            public_path('fonts/times.ttf'),
            public_path('fonts/TimesNewRoman.ttf'),
            public_path('fonts/georgia.ttf'),
            'C:/Windows/Fonts/times.ttf',
            'C:/Windows/Fonts/timesbd.ttf',
            'C:/Windows/Fonts/georgia.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSerif-Regular.ttf',
            '/System/Library/Fonts/Times New Roman.ttf',
        ];

        foreach ($fontPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Fallback to Arial
        $arialPaths = [
            'C:/Windows/Fonts/arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];

        foreach ($arialPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return $fontPaths[0];
    }

    /**
     * Get script/cursive font path for names.
     */
    private function getScriptFont(): string
    {
        $fontPaths = [
            public_path('fonts/GreatVibes.ttf'),
            public_path('fonts/Pacifico.ttf'),
            public_path('fonts/DancingScript.ttf'),
            public_path('fonts/script.ttf'),
            'C:/Windows/Fonts/SCRIPTBL.TTF', // Script MT Bold
            'C:/Windows/Fonts/BRUSHSCI.TTF', // Brush Script MT
            'C:/Windows/Fonts/FRSCRIPT.TTF', // French Script MT
        ];

        foreach ($fontPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Fallback to regular font with italic
        return $this->getFont();
    }

    /**
     * Generate a unique certificate number.
     */
    public function generateCertificateNumber(int $userId): string
    {
        return str_pad($userId, 3, '0', STR_PAD_LEFT) . '/' . date('my') . '/RZ';
    }
}
