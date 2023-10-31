<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {

        $pdf = new \TCPDF('P', 'mm', array('210', '297'), false, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        $pdf->SetAutoPageBreak(false, 0);

        $path = public_path('excel_file/GC_Sample.xlsx');

        $data = Excel::toArray([], $path);
        // Assuming the first sheet is used, you can access the data like this:
        $sheetData = $data[0];
        // echo '<pre>';
        // print_r($sheetData);
        // array_shift($sheetData);
        foreach ($sheetData as $key => $row) {

            if ($key === 0) {
                continue;
            }
            $pdf->AddPage();

            $imagePath1 = public_path('image/bg.jpeg');
            // Use Image() to add the background image
            $pdf->Image($imagePath1, 0, 0, '210', '297', "JPG", '', 'R', true);
            $pdf->setPageMark();
            $pdf->setFont('Arial', 'I', 15);
            $pdf->SetXY(65, 35);
            $pdf->MultiCell(75, 11, 'Semester Academic Report', 0, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(65, 42);
            $pdf->MultiCell(75, 11, 'Spring 2020', 0, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            // ---student profile---
            $imagePath1 = public_path('image/student.png');
            $pdf->Image($row[137], 166, 52, 32, 34, );

            //    student name
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(23, 52);
            $pdf->Cell(25, 11, 'Student Name :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 52);
            $pdf->Cell(20, 11, $row[7], 0, 'C');

            //student id
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(23, 58);
            $pdf->Cell(25, 11, 'Student ID No :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 58);
            $pdf->Cell(20, 11, $row[1], 0, 'C');

            //batch
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(35.3, 64);
            $pdf->Cell(12, 11, 'Batch :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 64);
            $pdf->Cell(20, 11, $row[8], 0, 'C');

            //semester
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(29.5, 70);
            $pdf->Cell(18, 11, 'Semester :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 70);
            $pdf->Cell(20, 11, $row[9], 0, 'C');

            //issue date
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(16.5, 76);
            $pdf->Cell(32, 11, 'Report Issue Date :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 76);
            $pdf->Cell(20, 11, $row[130], 0, 'C');
          
            // Date of Birth
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(82, 58);
            $pdf->Cell(32, 11, 'Date of Birth :', 0, 'C');
            $pdf->SetXY(105, 58);
            $pdf->setFont('Arial', '', 9.2);
            $pdf->Cell(32, 11, $row[5], 0, 'C');

            // programme
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(83, 64);
            $pdf->Cell(32, 11, 'Programme :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(105, 64);
            $pdf->Cell(32, 11, $row[10], 0, 'C');

            //major
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(92.5, 70);
            $pdf->Cell(32, 11, 'Major :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(105, 70);
            $pdf->Cell(32, 11, $row[11], 0, 'C');


            //table header
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(12, 90);
            $pdf->MultiCell(22, 11, 'Course' . "\n" . 'Code', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 90);
            $pdf->MultiCell(96, 11, 'Course', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 90);
            $pdf->MultiCell(17, 11, 'Credit' . "\n" . 'Enrolled', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 90);
            $pdf->MultiCell(17, 11, 'Credit' . "\n" . 'Earned', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 90);
            $pdf->MultiCell(17, 11, 'Grade ', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 90);
            $pdf->MultiCell(17, 11, 'Grade' . "\n" . 'Point', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            // table data
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(12, 101);
            $pdf->MultiCell(22, 11, $row[30], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 101);
            $pdf->MultiCell(96, 11, $row[31], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 101);
            $pdf->MultiCell(17, 11, $row[32], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 101);
            $pdf->MultiCell(17, 11, $row[33], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 101);
            $pdf->MultiCell(17, 11, $row[34], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 101);
            $pdf->MultiCell(17, 11, $row[35], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

             
            $pdf->SetXY(12, 112);
            $pdf->MultiCell(22, 11, $row[36], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 112);
            $pdf->MultiCell(96, 11, $row[37], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 112);
            $pdf->MultiCell(17, 11, $row[38], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 112);
            $pdf->MultiCell(17, 11, $row[39], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 112);
            $pdf->MultiCell(17, 11, $row[40], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 112);
            $pdf->MultiCell(17, 11, $row[41], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 123);
            $pdf->MultiCell(22, 11, $row[42], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 123);
            $pdf->MultiCell(96, 11, $row[43], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 123);
            $pdf->MultiCell(17, 11, $row[44], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 123);
            $pdf->MultiCell(17, 11, $row[45], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 123);
            $pdf->MultiCell(17, 11, $row[46], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 123);
            $pdf->MultiCell(17, 11, $row[47], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 134);
            $pdf->MultiCell(22, 11, $row[48], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 134);
            $pdf->MultiCell(96, 11, $row[49], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 134);
            $pdf->MultiCell(17, 11, $row[50], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 134);
            $pdf->MultiCell(17, 11, $row[51], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 134);
            $pdf->MultiCell(17, 11, $row[52], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 134);
            $pdf->MultiCell(17, 11, $row[53], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            $pdf->SetXY(12, 145);
            $pdf->MultiCell(22, 11, $row[54], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 145);
            $pdf->MultiCell(96, 11, $row[55], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 145);
            $pdf->MultiCell(17, 11, $row[56], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 145);
            $pdf->MultiCell(17, 11, $row[57], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 145);
            $pdf->MultiCell(17, 11, $row[58], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 145);
            $pdf->MultiCell(17, 11, $row[59], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 156);
            $pdf->MultiCell(22, 11, $row[60], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 156);
            $pdf->MultiCell(96, 11, $row[61], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 156);
            $pdf->MultiCell(17, 11, $row[62], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 156);
            $pdf->MultiCell(17, 11, $row[63], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 156);
            $pdf->MultiCell(17, 11, $row[64], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 156);
            $pdf->MultiCell(17, 11, $row[65], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 167);
            $pdf->MultiCell(22, 11, $row[66], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 167);
            $pdf->MultiCell(96, 11, $row[67], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 167);
            $pdf->MultiCell(17, 11, $row[68], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 167);
            $pdf->MultiCell(17, 11, $row[69], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 167);
            $pdf->MultiCell(17, 11, $row[70], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 167);
            $pdf->MultiCell(17, 11, $row[71], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 168);
            $pdf->MultiCell(22, 47, '', 'LBR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 168);
            $pdf->MultiCell(96, 47, '', 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            //second table

            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(12, 218);
            $pdf->MultiCell(93, 8, 'Semester Grade Point Average (SGPA)', 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');
            $pdf->SetXY(105, 218);
            $pdf->MultiCell(93, 8, 'Cumulative Grade Point Average (CGPA)', 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');

            $pdf->SetXY(12, 226);
            $pdf->MultiCell(30, 11, 'Earned Credits', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', '', '8.5');
            $pdf->SetXY(42, 226);
            $pdf->MultiCell(36, 11, 'Earned Credit Points' . "\n" . 'Σ(Credit X Grade Points)', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->SetXY(78, 226);
            $pdf->MultiCell(30, 11, 'SGPA', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(108, 226);
            $pdf->MultiCell(30, 11, 'Total' . "\n" . 'Earned Credits ', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(138, 226);
            $pdf->MultiCell(30, 11, 'Total Earned' . "\n" . 'Credit Points', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(168, 226);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->MultiCell(30, 11, 'CGPA', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(12, 237);
            $pdf->MultiCell(30, 11, $row[29], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(42, 237);
            $pdf->MultiCell(36, 11, $row[27], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->SetXY(78, 237);
            $pdf->MultiCell(30, 11, $row[23] . "\n" . $row[24], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(108, 237);
            $pdf->MultiCell(30, 11, $row[21], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(138, 237);
            $pdf->MultiCell(30, 11, $row[22], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->SetXY(168, 237);
            $pdf->MultiCell(30, 11, $row[25] . "\n" . $row[26], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            //QR CODE
            $pdf->SetFont('Arial', '', '9');
            $style = array(
                'border' => false,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,

                'module_width' => 1,

                'module_height' => 1
            );

            $pdf->write2DBarcode($row[128], 'QRCODE,H', 10, 252, 28, 23, $style, 'N');
            $pdf->Text(15, 275, $row[131]);
            $pdf->Text(45, 275, $row[134]);
            $pdf->Text(135, 275, $row[136]);
        }

        $pdf->Output($row[127] . '.pdf', 'I');
    }

    public function index()
    {
        return view('Import');
    }

    public function uploadExcel(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
           
        ]);
        $pdf = new \TCPDF('P', 'mm', array('210', '297'), false, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);

        // Upload the Excel file to the public folder
        $file = $request->file('excel_file');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('excel_file'), $fileName);
          $path = public_path('excel_file/').$fileName;
          $data = Excel::toArray([], $path);
          $sheetData = $data[0];

          foreach ($sheetData as $key => $row) {

            if ($key == 0) {
                continue;
            }
            $pdf->AddPage();

            $imagePath1 = public_path('image/bg.jpeg');
            // Use Image() to add the background image
            $pdf->Image($imagePath1, 0, 0, '210', '297', "JPG", '', 'R', true);
            $pdf->setPageMark();
            $pdf->setFont('Arial', 'I', 15);
            $pdf->SetXY(65, 35);
            $pdf->MultiCell(75, 11, 'Semester Academic Report', 0, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(65, 42);
            $pdf->MultiCell(75, 11, 'Spring 2020', 0, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            // ---student profile---
            $imagePath1 = public_path('image/student.png');
            $pdf->Image($row[137], 166, 52, 32, 34, );

            //    student name
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(23, 52);
            $pdf->Cell(25, 11, 'Student Name :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 52);
            $pdf->Cell(20, 11, $row[7], 0, 'C');


            //student id
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(23, 58);
            $pdf->Cell(25, 11, 'Student ID No :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 58);
            $pdf->Cell(20, 11, $row[1], 0, 'C');

            //batch
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(35.3, 64);
            $pdf->Cell(12, 11, 'Batch :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 64);
            $pdf->Cell(20, 11, $row[8], 0, 'C');

            //semester
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(29.5, 70);
            $pdf->Cell(18, 11, 'Semester :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 70);
            $pdf->Cell(20, 11, $row[9], 0, 'C');

            //issue date
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(16.5, 76);
            $pdf->Cell(32, 11, 'Report Issue Date :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(48, 76);
            $pdf->Cell(20, 11, $row[130], 0, 'C');

            // Date of Birth
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(82, 58);
            $pdf->Cell(32, 11, 'Date of Birth :', 0, 'C');
            $pdf->SetXY(105, 58);
            $pdf->setFont('Arial', '', 9.2);
            $pdf->Cell(32, 11, $row[5], 0, 'C');

            // programme
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(83, 64);
            $pdf->Cell(32, 11, 'Programme :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(105, 64);
            $pdf->Cell(32, 11, $row[10], 0, 'C');

            //major
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(92.5, 70);
            $pdf->Cell(32, 11, 'Major :', 0, 'C');
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(105, 70);
            $pdf->Cell(32, 11, $row[11], 0, 'C');


            //table header
            $pdf->setFont('Arial', 'B', 9.2);
            $pdf->SetXY(12, 90);
            $pdf->MultiCell(22, 11, 'Course' . "\n" . 'Code', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 90);
            $pdf->MultiCell(96, 11, 'Course', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 90);
            $pdf->MultiCell(17, 11, 'Credit' . "\n" . 'Enrolled', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 90);
            $pdf->MultiCell(17, 11, 'Credit' . "\n" . 'Earned', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 90);
            $pdf->MultiCell(17, 11, 'Grade ', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 90);
            $pdf->MultiCell(17, 11, 'Grade' . "\n" . 'Point', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            // table data
            $pdf->setFont('Arial', '', 9.2);
            $pdf->SetXY(12, 101);
            $pdf->MultiCell(22, 11, $row[30], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 101);
            $pdf->MultiCell(96, 11, $row[31], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 101);
            $pdf->MultiCell(17, 11, $row[32], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 101);
            $pdf->MultiCell(17, 11, $row[33], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 101);
            $pdf->MultiCell(17, 11, $row[34], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 101);
            $pdf->MultiCell(17, 11, $row[35], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            $pdf->SetXY(12, 112);
            $pdf->MultiCell(22, 11, $row[36], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 112);
            $pdf->MultiCell(96, 11, $row[37], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 112);
            $pdf->MultiCell(17, 11, $row[38], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 112);
            $pdf->MultiCell(17, 11, $row[39], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 112);
            $pdf->MultiCell(17, 11, $row[40], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 112);
            $pdf->MultiCell(17, 11, $row[41], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 123);
            $pdf->MultiCell(22, 11, $row[42], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 123);
            $pdf->MultiCell(96, 11, $row[43], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 123);
            $pdf->MultiCell(17, 11, $row[44], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 123);
            $pdf->MultiCell(17, 11, $row[45], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 123);
            $pdf->MultiCell(17, 11, $row[46], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 123);
            $pdf->MultiCell(17, 11, $row[47], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 134);
            $pdf->MultiCell(22, 11, $row[48], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 134);
            $pdf->MultiCell(96, 11, $row[49], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 134);
            $pdf->MultiCell(17, 11, $row[50], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 134);
            $pdf->MultiCell(17, 11, $row[51], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 134);
            $pdf->MultiCell(17, 11, $row[52], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 134);
            $pdf->MultiCell(17, 11, $row[53], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            $pdf->SetXY(12, 145);
            $pdf->MultiCell(22, 11, $row[54], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 145);
            $pdf->MultiCell(96, 11, $row[55], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 145);
            $pdf->MultiCell(17, 11, $row[56], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 145);
            $pdf->MultiCell(17, 11, $row[57], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 145);
            $pdf->MultiCell(17, 11, $row[58], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 145);
            $pdf->MultiCell(17, 11, $row[59], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 156);
            $pdf->MultiCell(22, 11, $row[60], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 156);
            $pdf->MultiCell(96, 11, $row[61], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 156);
            $pdf->MultiCell(17, 11, $row[62], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 156);
            $pdf->MultiCell(17, 11, $row[63], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 156);
            $pdf->MultiCell(17, 11, $row[64], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 156);
            $pdf->MultiCell(17, 11, $row[65], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 167);
            $pdf->MultiCell(22, 11, $row[66], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 167);
            $pdf->MultiCell(96, 11, $row[67], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 167);
            $pdf->MultiCell(17, 11, $row[68], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 167);
            $pdf->MultiCell(17, 11, $row[69], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 167);
            $pdf->MultiCell(17, 11, $row[70], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 167);
            $pdf->MultiCell(17, 11, $row[71], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetXY(12, 168);
            $pdf->MultiCell(22, 47, '', 'LBR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(34, 168);
            $pdf->MultiCell(96, 47, '', 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(130, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(147, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(164, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(181, 168);
            $pdf->MultiCell(17, 47, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            //second table

            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(12, 218);
            $pdf->MultiCell(93, 8, 'Semester Grade Point Average (SGPA)', 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');
            $pdf->SetXY(105, 218);
            $pdf->MultiCell(93, 8, 'Cumulative Grade Point Average (CGPA)', 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');

            $pdf->SetXY(12, 226);
            $pdf->MultiCell(30, 11, 'Earned Credits', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', '', '8.5');
            $pdf->SetXY(42, 226);
            $pdf->MultiCell(36, 11, 'Earned Credit Points' . "\n" . 'Σ(Credit X Grade Points)', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->SetXY(78, 226);
            $pdf->MultiCell(30, 11, 'SGPA', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(108, 226);
            $pdf->MultiCell(30, 11, 'Total' . "\n" . 'Earned Credits ', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(138, 226);
            $pdf->MultiCell(30, 11, 'Total Earned' . "\n" . 'Credit Points', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(168, 226);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->MultiCell(30, 11, 'CGPA', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(12, 237);
            $pdf->MultiCell(30, 11, $row[29], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(42, 237);
            $pdf->MultiCell(36, 11, $row[27], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->SetXY(78, 237);
            $pdf->MultiCell(30, 11, $row[23] . "\n" . $row[24], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', '', '9');
            $pdf->SetXY(108, 237);
            $pdf->MultiCell(30, 11, $row[21], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetXY(138, 237);
            $pdf->MultiCell(30, 11, $row[22], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->SetXY(168, 237);
            $pdf->MultiCell(30, 11, $row[25] . "\n" . $row[26], 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            //QR CODE
            $pdf->SetFont('Arial', '', '9');
            $style = array(
                'border' => false,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,

                'module_width' => 1,

                'module_height' => 1
            );

            $pdf->write2DBarcode($row[128], 'QRCODE,H', 10, 252, 28, 23, $style, 'N');
            $pdf->Text(15, 275, $row[131]);
            $pdf->Text(45, 275, $row[134]);
            $pdf->Text(135, 275, $row[136]);
        }

        $pdf->Output($row[127] . '.pdf', 'I');

      
    }




}