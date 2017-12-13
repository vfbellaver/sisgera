<?php

namespace Sisgera\Http\Controllers\Api;


use PDF;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Sisgera\Http\Controllers\Controller;
use Sisgera\Models\Requerimento;
use Sisgera\Models\User;

class PdfController extends Controller
{
    public function pdf(Requerimento $requerimento)
    {
        ini_set('max_execution_time', 5000);

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        //612 × 792 points
        $gen = new Mpdf([
            'fontDir'       => array_merge($fontDirs, [
                base_path() . '/resources/fonts/Roboto',
            ]),
            'fontdata'      => $fontData + [
                    'Roboto' => [
                        'R'  => 'Roboto-Regular.ttf',
                        'I'  => 'Roboto-Italic.ttf',
                        'B'  => 'Roboto-Bold.ttf',
                        'BI' => 'Roboto-BoldItalic.ttf',
                    ],
                ],
            'default_font'  => 'Roboto',
            'mode'          => 'c',
            'format'        => 'Letter',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 2,
            'margin_bottom' => 2,
        ]);

        $gen->SetTitle('Requerimento '.$requerimento->usuario->name);

        $requerente = $requerimento->usuario->toArray();
        $historicos = $requerimento->historicos->toArray();

        $hist = $historicos[count($historicos)-1];
        $parecer = User::query()->findOrFail($hist['user_id']);
        $content = view('requerimento.pdf', [
            'requerimento' => $requerimento->toArray(),
            'requerente' => $requerente,
            'parecer' => $parecer
        ]);

        $gen->WriteHTML($content);
        $pdf = $gen->Output('requerimento.pdf', 'S');

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Length', strlen($pdf))
            ->header('Content-Disposition', "attachment; filename=\"requerimento.pdf\"");
    }

    public function pdfDom(Requerimento $requerimento)
    {
        $requerente = $requerimento->usuario->toArray();
        $historicos = $requerimento->historicos->toArray();

        $hist = $historicos[count($historicos)-1];
        $parecer = User::query()->findOrFail($hist['user_id']);

            $pdf = PDF::loadView('requerimento.dompdf',
            [
                'requerimento' => $requerimento->toArray(),
                'requerente' => $requerente,
                'parecer' => $parecer,
            ]);

        return $pdf->stream('requerimento.pdf');
//        return view('requerimento.dompdf', [
//            'requerimento' => $requerimento->toArray(),
//            'requerente' => $requerente,
//
//        ]);
    }
}