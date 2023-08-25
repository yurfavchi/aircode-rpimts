<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\ProjectsModel;
use Illuminate\Http\Request;

class PdfController extends Controller
{

    public function generatePDF($data_id)
    {
        // Retrieve data based on the $data_id
        $data = ProjectsModel::findOrFail($data_id);

        // Load the PDF view and pass the data
        $pdf = PDF::loadView('exports.report', ['data' => $data]);

        // Load the header view and set it as a header in the PDF
        // $headerHtml = view('generate.pdf')->render();
        // $pdf->setOption('header-html', $headerHtml);

        // Generate a filename using the project name
        $filename = Str::slug($data->projname) . '.pdf';

        return $pdf->download($filename);
    }


}

