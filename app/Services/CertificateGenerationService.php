<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

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
    public function generateCertificate(string $userName, string $certificateNumber, string $issuedDate, string $licenseNumber, $shares): string
    {
        // Ensure the directory exists
        $fileName = 'documents/certificates/' . uniqid('cert_') . '.pdf';
        $publicPath = public_path($fileName);

        if (!file_exists(dirname($publicPath))) {
            mkdir(dirname($publicPath), 0755, true);
        }

        // Get settings for certificate
        $companyName = \App\Models\Setting::get('company_name', 'Hola Taxi Ireland Limited');
        $companyLogo = \App\Models\Setting::get('company_logo_path', 'images/hola-logo.jpeg');
        $directorName = \App\Models\Setting::get('director_name', 'Kamal S Gill');
        $directorSignature = \App\Models\Setting::get('director_signature_path');

        try {
            $pdf = Pdf::loadView('certificates.template', compact(
                'userName',
                'certificateNumber',
                'issuedDate',
                'licenseNumber',
                'shares',
                'companyName',
                'companyLogo',
                'directorName',
                'directorSignature'
            ));

            // Set custom paper size (defined as portrait [0, 0, width, height], then rotated to landscape)
            $pdf->setPaper([0, 0, 543, 768], 'landscape');

            $pdf->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'dpi' => 96
            ]);

            $pdf->save($publicPath);
        } catch (\Exception $e) {
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
