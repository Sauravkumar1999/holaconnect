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
        // Ensure the directory exists
        $fileName = 'documents/certificates/' . uniqid('cert_') . '.jpg'; // Using JPG as background is JPG
        $publicPath = public_path($fileName);

        if (!file_exists(dirname($publicPath))) {
            mkdir(dirname($publicPath), 0755, true);
        }

        // Render the View to HTML
        $html = view('certificates.template', compact('userName', 'certificateNumber', 'issuedDate', 'licenseNumber', 'shares'))->render();

        // Convert HTML to Image using Browsershot (Puppeteer)
        // Make sure to run: composer require spatie/browsershot
        // and: npm install puppeteer

        try {
            if (class_exists(\Spatie\Browsershot\Browsershot::class)) {
                \Spatie\Browsershot\Browsershot::html($html)
                    ->windowSize(1024, 724)
                    ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
                    ->waitUntilNetworkIdle()
                    ->save($publicPath);
            } else {
                throw new \Exception('Spatie\Browsershot is not installed. Please run "composer require spatie/browsershot" and "npm install puppeteer".');
            }
        } catch (\Exception $e) {
            // Fallback or rethrow - Here we rely on the user installing the package as requested
            throw new \Exception('Certificate generation failed: ' . $e->getMessage());
        }

        return $fileName;
    }

    /**
     * Generate a unique certificate number.
     */
    public function generateCertificateNumber(int $userId): string
    {
        return str_pad($userId, 3, '0', STR_PAD_LEFT) . '/' . date('my') . '/RZ';
    }
}
