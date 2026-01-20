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

        // Define colors
        $darkBlue = imagecolorallocate($image, 15, 47, 111);      // #0f2f6f
        $white = imagecolorallocate($image, 255, 255, 255);       // #ffffff
        $golden = imagecolorallocate($image, 201, 169, 97);       // #c9a961
        $lightGolden = imagecolorallocate($image, 240, 180, 40);  // #f0b428
        $brightGolden = imagecolorallocate($image, 255, 204, 51); // #ffcc33

        // Fill background with dark blue
        imagefilledrectangle($image, 0, 0, $width, $height, $darkBlue);

        // Draw white inner rectangle (certificate body)
        imagefilledrectangle($image, 60, 60, $width - 60, $height - 60, $white);

        // Draw golden borders
        $borderWidth = 8;
        imagefilledrectangle($image, 60, 60, $width - 60, 60 + $borderWidth, $golden);                    // Top
        imagefilledrectangle($image, 60, $height - 60 - $borderWidth, $width - 60, $height - 60, $golden); // Bottom
        imagefilledrectangle($image, 60, 60, 60 + $borderWidth, $height - 60, $golden);                   // Left
        imagefilledrectangle($image, $width - 60 - $borderWidth, 60, $width - 60, $height - 60, $golden); // Right

        // Add decorative corner elements
        $this->drawCornerDecorations($image, $width, $height, $golden);

        // Add text content
        $this->addTextContent($image, $width, $height, $userName, $certificateNumber, $issuedDate, $licenseNumber, $shares, $darkBlue, $golden);

        // Add decorative seal/badge
        $this->drawSeal($image, $width, $height, $lightGolden, $brightGolden);

        // Save the certificate
        $fileName = 'certificates/' . uniqid('cert_') . '.png';
        $storagePath = storage_path('app/public/' . $fileName);

        // Ensure directory exists
        if (!file_exists(dirname($storagePath))) {
            mkdir(dirname($storagePath), 0755, true);
        }

        imagepng($image, $storagePath);
        imagedestroy($image);

        return 'storage/' . $fileName;
    }

    /**
     * Draw corner decorations.
     */
    private function drawCornerDecorations($image, int $width, int $height, $golden): void
    {
        // Simple triangular decorations in corners
        $cornerSize = 80;
        
        // Top-left corner
        $points = [40, 40, 40 + $cornerSize, 40, 40, 40 + $cornerSize];
        imagefilledpolygon($image, $points, 3, $golden);
        
        // Top-right corner
        $points = [$width - 40, 40, $width - 40 - $cornerSize, 40, $width - 40, 40 + $cornerSize];
        imagefilledpolygon($image, $points, 3, $golden);
        
        // Bottom-left corner
        $points = [40, $height - 40, 40 + $cornerSize, $height - 40, 40, $height - 40 - $cornerSize];
        imagefilledpolygon($image, $points, 3, $golden);
        
        // Bottom-right corner
        $points = [$width - 40, $height - 40, $width - 40 - $cornerSize, $height - 40, $width - 40, $height - 40 - $cornerSize];
        imagefilledpolygon($image, $points, 3, $golden);
    }

    /**
     * Add text content to the certificate.
     */
    private function addTextContent($image, int $width, int $height, string $userName, string $certificateNumber, string $issuedDate, string $licenseNumber, int $shares, $darkBlue, $golden): void
    {
        $centerX = $width / 2;
        
        // Use default font (can be replaced with TTF fonts if available)
        $font = 5; // Built-in large font
        
        // Title: SHARE CERTIFICATE
        $text = 'SHARE CERTIFICATE';
        $this->drawCenteredText($image, $text, $centerX, 180, 5, $darkBlue);
        
        // Company name
        $text = 'COMPANY NAME: HOLA TAXI IRELAND LIMITED';
        $this->drawCenteredText($image, $text, $centerX, 260, 4, $darkBlue);
        
        // "THIS IS TO CERTIFY THAT"
        $text = 'THIS IS TO CERTIFY THAT';
        $this->drawCenteredText($image, $text, $centerX, 350, 4, $darkBlue);
        
        // User name (larger and emphasized)
        $this->drawCenteredText($image, $userName, $centerX, 450, 5, $darkBlue);
        
        // Underline for name
        imagefilledrectangle($image, $centerX - 400, 480, $centerX + 400, 483, $darkBlue);
        
        // Details text
        $detailsText = "of $licenseNumber holding license number is the registered holder of " . number_format($shares);
        $this->drawCenteredText($image, $detailsText, $centerX, 540, 3, $darkBlue);
        
        $text = 'Class A Ordinary Shares in the capital of Hola Taxi Ireland Limited.';
        $this->drawCenteredText($image, $text, $centerX, 580, 3, $darkBlue);
        
        // Certificate details at bottom left
        $leftX = 200;
        $bottomY = 850;
        
        imagettftext($image, 18, 0, $leftX, $bottomY, $darkBlue, $this->getFont(), 'CERTIFICATE NO:');
        imagettftext($image, 18, 0, $leftX + 200, $bottomY, $darkBlue, $this->getFont(), $certificateNumber);
        
        imagettftext($image, 18, 0, $leftX, $bottomY + 40, $darkBlue, $this->getFont(), 'DATE OF ISSUE:');
        imagettftext($image, 18, 0, $leftX + 200, $bottomY + 40, $darkBlue, $this->getFont(), $issuedDate);
        
        // Director signature section (bottom right)
        $rightX = $width - 350;
        imagettftext($image, 24, 0, $rightX, $bottomY, $darkBlue, $this->getFont(), 'Kamal S Gill');
        
        // Underline for signature
        imagefilledrectangle($image, $rightX - 50, $bottomY + 10, $rightX + 200, $bottomY + 12, $darkBlue);
        
        imagettftext($image, 18, 0, $rightX + 30, $bottomY + 60, $darkBlue, $this->getFont(), 'DIRECTOR');
    }

    /**
     * Draw centered text.
     */
    private function drawCenteredText($image, string $text, int $centerX, int $y, int $font, $color): void
    {
        $fontPath = $this->getFont();
        
        if (file_exists($fontPath)) {
            $fontSize = $font == 5 ? 48 : ($font == 4 ? 32 : 24);
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $x = $centerX - ($textWidth / 2);
            imagettftext($image, $fontSize, 0, $x, $y, $color, $fontPath, $text);
        } else {
            // Fallback to built-in font
            $textWidth = imagefontwidth($font) * strlen($text);
            $x = $centerX - ($textWidth / 2);
            imagestring($image, $font, $x, $y, $text, $color);
        }
    }

    /**
     * Draw decorative seal/badge.
     */
    private function drawSeal($image, int $width, int $height, $lightGolden, $brightGolden): void
    {
        $centerX = $width / 2;
        $sealY = 750;
        $radius = 60;
        
        // Outer circle
        imagefilledellipse($image, $centerX, $sealY, $radius * 2, $radius * 2, $lightGolden);
        
        // Inner circle
        imagefilledellipse($image, $centerX, $sealY, ($radius - 15) * 2, ($radius - 15) * 2, $brightGolden);
        
        // Add ribbon-like decorations
        $ribbonPoints = [
            $centerX - 40, $sealY + 50,
            $centerX - 30, $sealY + 100,
            $centerX - 20, $sealY + 50,
        ];
        imagefilledpolygon($image, $ribbonPoints, 3, $lightGolden);
        
        $ribbonPoints = [
            $centerX + 40, $sealY + 50,
            $centerX + 30, $sealY + 100,
            $centerX + 20, $sealY + 50,
        ];
        imagefilledpolygon($image, $ribbonPoints, 3, $lightGolden);
    }

    /**
     * Get font path (tries multiple common locations).
     */
    private function getFont(): string
    {
        $fontPaths = [
            public_path('fonts/arial.ttf'),
            public_path('fonts/Arial.ttf'),
            'C:/Windows/Fonts/arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];
        
        foreach ($fontPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        // Return first path as fallback (will be handled in drawCenteredText)
        return $fontPaths[0];
    }

    /**
     * Generate a unique certificate number.
     */
    public function generateCertificateNumber(int $userId): string
    {
        return str_pad($userId, 3, '0', STR_PAD_LEFT) . '/' . date('my') . '/RZ';
    }
}
