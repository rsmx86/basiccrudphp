<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Carrega o autoloader da pasta dompdf
require_once APPPATH . 'libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf {
    public function generate($html, $filename = 'relatorio', $paper = 'A4', $orientation = 'portrait') {
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE); // Permite carregar imagens via URL
        $options->set('defaultFont', 'helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();

        // Força o download ou abre no navegador (false = abre, true = download)
        $dompdf->stream($filename . ".pdf", array("Attachment" => false));
    }
}