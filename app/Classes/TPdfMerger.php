<?php
namespace App\Classes;

use Illuminate\Support\Facades\App;

/**
 * Cria merge com arquivos PDF
 *
 * Utiliza o recurso SIG-JB-PDFMerger
 */
class TPdfMerger extends TObject
{

    public static function merge(array $pdfPaths, string $outputPath): bool
    {
        // Validações básicas
        if (count($pdfPaths) < 2) {
            throw new \InvalidArgumentException("Pelo menos dois arquivos PDF são necessários para a mesclagem.");
        }
        if (empty($outputPath)) {
            throw new \InvalidArgumentException("O caminho do arquivo de saída não pode estar vazio.");
        }

        // Constrói o comando Python
        $sistema_operacional = PHP_OS;

        if (strpos($sistema_operacional, 'WIN') !== false) {
            $command = escapeshellcmd('C:\tools\pdfmerger\pdfmerger.exe');
        } elseif (strpos($sistema_operacional, 'Linux') !== false) {
            $command = escapeshellcmd("PdfMerger");
        } elseif (strpos($sistema_operacional, 'Darwin') !== false) {
            # TO-DO: Rodando no macOS
        } else {
            # TO-DO: Sistema operacional desconhecido
        }


        foreach ($pdfPaths as $path) {
            $command .= " " . str_replace(chr(39),'',escapeshellarg($path));
        }
        $command .= " -output=" . str_replace(chr(39),'',escapeshellarg($outputPath));

        // Executa o script Python
        $output = [];
        $returnCode = null;
        exec($command, $output, $returnCode);

        // Verifica se houve erros
        if ($returnCode !== 0) {
            throw new \RuntimeException("TPdfMerger: Erro ao processar. [" . implode("\n", $output) . "] | Comando: " . $command);
        }

        return true;
    }
}
