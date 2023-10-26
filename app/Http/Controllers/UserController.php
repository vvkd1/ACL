<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Spatie\Permission\Models\Role;

use DB;
use Hash;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Elibyy\TCPDF\Facades\TCPDF;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.index', compact('data'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show($id)
    {

        $user = User::find($id);
        return view('users.show', compact('user'));

    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();

        if (!empty($input['password'])) {

            $input['password'] = Hash::make($input['password']);

        } else {
            $input = Arr::except($input, ['password']);
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }


    public function export()
    {
        return Excel::download(new UsersExport, 'user.xlsx');
    }

    public function tcpdf(Request $request)
    {
        $filename = 'users.pdf';

        $users = User::all();

        $html = view('tcpdf', compact('users'))->render();

        $pdf = new TCPDF;

        $pdf::SetTitle('Hello World');
        $pdf::AddPage();

        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output(public_path($filename), 'f');

        return response()->download(public_path($filename));

    }



    public function generateTcpdf()
    {
        $users = User::all();
        // Create a new TCPDF instance
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf::AddPage();
        $pdf::SetFont('times', 'b', 12);

        // Header
        $pdf::Cell(0, 11, 'User Details', 0, 1, 'C');
        $pdf::Ln();


        // Create the table headers
        $pdf::Cell(28, 10, 'ID', 1, 0, 'C');
        $pdf::Cell(28, 10, 'Name', 1, 0, 'C');
        $pdf::Cell(45, 10, 'Email', 1, 0, 'C');
        $pdf::Cell(45, 10, 'Profile Image', 1, 0, 'C');
        $pdf::Ln();

        // Create table rows with data and QR codes
        foreach ($users as $row) {
            $pdf::Cell(28, 10, $row->id, 1, 0, 'C');
            $pdf::Cell(28, 10, $row->name, 1, 0, 'C');
            $pdf::Cell(45, 10, $row->email, 1, 0, 'C');
            $pdf::Cell(45, 10, '', 1, '', 'C');
            $imageX = $pdf::GetX(-20);
            $imageY = $pdf::GetY(1);

            $pdf::Image(public_path('/image/image2.webp'), $imageX + -23, $imageY + 1, 10, 8, ); // Adjust the dimensions as needed
            $pdf::Ln();

        }
        // set style for barcode
        $style = array(
            'border' => 1,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 145),
            'bgcolor' => false,
            'module_width' => 1,
            // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        //  -----QR CODE-----
        $pdf::write2DBarcode('dev harsh pvt ltd', 'QRCODE,L', 130, 130, 45, 45, $style, 'N');


        // -----BARCODE-----  
        $pdf::setBarcode(date('Y-m-d H:i:s'));
        $pdf::SetFont('helvetica', '', 5);
        $pdf::Cell(0, 0, 'CODE 39 + CHECKSUM', 0, 1);
        $pdf::write1DBarcode('4502', 'C39+', '', '', '', 18, 0.4, $style, 'N');
        $pdf::Ln();


        // Output the PDF as inline (I) or for download (D)
        return $pdf::Output('example.pdf', 'I');
    }


    public function certificate()
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



        $pdf::AddPage();
        $pdf::SetFont('helvetica', 'B', 9.2);

        //    ---BG IMAGE---
        $imagePath1 = public_path('image/BG.jpeg');

        $bMargin = $pdf::getBreakMargin();
        $pdf::SetAutoPageBreak(false, 0);
        // set bacground image
        $pdf::Image($imagePath1, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        $pdf::SetAutoPageBreak('', $bMargin);
        $pdf::setPageMark();


        $pdf::SetFont('helvetica', ' ', 18);
        $pdf::SetXY(0, 35);
        $pdf::Cell(0, 6, 'Semester Academic Report', 0, 1, 'C');
        $pdf::Cell(0, 6, 'Spring 2020', 0, 1, 'C');
        $pdf::Ln(2);

        //  ---student NAME-----
        $pdf::SetDrawColor(0, 0, 0);
        $pdf::SetLineWidth(0.1);


        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::Cell(36, 6, 'Student Name :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(11, 6, 'PRIYAANSH OJAS BHATT', 0, 1, 'L');

        // ---student profile---
        $imagePath1 = public_path('image/student.png');
        $pdf::Image($imagePath1, 166, 48, 32, 34, );


        //  ---student ID-----
        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::Cell(36, 6, 'Student ID No :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(11, 6, '413906', 0, 0, 'L');


        //  ---student Date of Birth :-----
        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::Cell(62, 6, 'Date of Birth :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(93, 6, '10 June 2000', 0, 1, 'R');

        //  ---student BATCH-----

        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::Cell(36, 6, 'Batch :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(0, 6, '2018', 0, 0, 'L');


        //  ---student Programme:-----

        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::SetX(73);
        $pdf::Cell(45, 6, 'Programme :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(102, 6, 'Bachelor of Design', 0, 1, 'R');



        //  ---student Semester-----

        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::Cell(36, 6, 'Semester :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(0, 6, 'Semester-IV', 0, 0, 'L');

        //  ---student Major:-----
        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::SetX(73);
        $pdf::Cell(45, 6, 'Major :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(97, 6, 'Product Design', 0, 1, 'R');


        //  ---student Report Issue Date:-----

        $pdf::SetFont('helvetica', 'B', 9.4);
        $pdf::Cell(36, 6, 'Report Issue Date :', 0, 0, 'R');
        $pdf::SetFont('helvetica', '', 9);
        $pdf::SetX(45);
        $pdf::Cell(11, 6, ' 16 July 2022', 0, 1, 'L');
        $pdf::Ln();



        // Table data
        $headers = array('Course' . "\n" . 'Code', 'Course', 'Credit Enrolled', 'Credit Earned', 'Grade', 'Grade Point');
        $rows = array(
            array('PRO202', 'ADVANCED MATERIALS & MANUFACTURING', '3.0', '3.0', 'A+', '4.00'),
            array('PRO203', 'ERGONOMICS', '3.0', '3.0', 'B+', '3.40'),
            array('PRO246', 'PRODUCT DESIGN STUDIO-II', '6.0', '6.0', 'A', '3.80'),
            array('PRO263', 'PRODUCT DESIGN RENDERING', '3.0', '3.0', 'B-', '3.00'),
            array('DES243', 'DESIGN OF LIVING CULTURE', '2.0', '0.0', 'F', '0.00'),
            array('EL2026', 'MATERIAL INSIGHT (E)', '2.0', '2.0', 'B+', '3.40'),
            array('EL2029', 'PACKAGING DESIGN (E)', '2.0', '2.0', 'D', '2.00'),

        );

        // Set font
        $pdf::SetFont('helvetica', 'B', 9);
        $cellHeight = 10;

        // Define custom column widths
        $columnWidths = [22, 96, 17, 17, 17, 17];

        // Set initial x and y positions
        $x = 12;
        $y = 85;

        // Create table header with left and right borders
        foreach ($headers as $index => $header) {
            $pdf::SetXY($x, $y);

            $alignment = ($index === 0) ? 'C' : (($index === 1) ? 'C' : 'C');


            $pdf::MultiCell($columnWidths[$index], $cellHeight, $header, 'LRTB', $alignment, 0, 1, '', '', true, 0, false, true, 11, 'M');

            // Calculate the height for vertical centering
            $textHeight = $pdf::getStringHeight($columnWidths[$index], $header);
            $verticalCenter = ($cellHeight - $textHeight) / 2;

            // Adjust the Y position for vertical centering
            $pdf::SetXY($x, $y + $verticalCenter);

            $x += $columnWidths[$index];
        }

        $x = 10; // Reset x position
        $y += $cellHeight;

        $pdf::SetFont('helvetica', '', 9);
        // Create table rows with left and right borders
        $rightAlign = true;
        foreach ($rows as $row) {
            $x = 12; // Reset x position
            foreach ($row as $index => $cell) {
                $pdf::SetXY($x, $y);
                $border = ($index === 0) ? 'LR' : 'LR';
                $alignment = ($index === 0) ? 'R' : (($index === 1) ? 'L' : 'C');
                $pdf::MultiCell($columnWidths[$index], $cellHeight, $cell, $border, $alignment, 0, 1, '', '', true, 0, false, true, 11, 'M');
                $x += $columnWidths[$index];
            }
            $y += $cellHeight;
        }

        $pdf::SetXY(12, 165);
        $pdf::Cell(22, 50, '', 'LRB', 0, 'C');
        $pdf::Cell(96, 50, '', 'LRB', 0, 'C');
        $pdf::Cell(17, 50, '', 'LRB', 0, 'C');
        $pdf::Cell(17, 50, '', 'LRB', 0, 'C');
        $pdf::Cell(17, 50, '', 'LRB', 0, 'C');
        $pdf::Cell(17, 50, '', 'LRB', 1, 'C');
        $pdf::Ln(2);



        $pdf::SetFont('helvetica', '', '9');
        $pdf::SetXY(12, 218);
        // $pdf::MultiCell(93,8,'Semester Grade Point Average (SGPA)',1,'C',$verticalAlignment);
        $pdf::MultiCell(93, 8, 'Semester Grade Point Average (SGPA)', 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');
        $pdf::SetXY(105, 218);
        $pdf::MultiCell(93, 8, 'Cumulative Grade Point Average (CGPA)', 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');

        $pdf::SetXY(12, 226);
        $pdf::MultiCell(30, 11, 'Earned Credits', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetFont('dejavusans', '', '7.9');
        $pdf::SetXY(42, 226);
        $pdf::MultiCell(36, 11, 'Earned Credit Points' . "\n" . 'Σ(Credit X Grade Points)', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::SetXY(78, 226);
        $pdf::MultiCell(30, 11, 'SGPA', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetFont('helvetica', '', '9');
        $pdf::SetXY(108, 226);
        $pdf::MultiCell(30, 11, 'Total' . "\n" . 'Earned Credits ', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetXY(138, 226);
        $pdf::MultiCell(30, 11, 'Total Earned' . "\n" . 'Credit Points', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetXY(168, 226);
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::MultiCell(30, 11, 'CGPA', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

        $pdf::SetFont('helvetica', '', '9');
        $pdf::SetXY(12, 237);
        $pdf::MultiCell(30, 11, '19.0', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetXY(42, 237);
        $pdf::MultiCell(36, 11, '64.80', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::SetXY(78, 237);
        $pdf::MultiCell(30, 11, '3.41' . "\n" . 'Very Good', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetFont('helvetica', '', '9');
        $pdf::SetXY(108, 237);
        $pdf::MultiCell(30, 11, '75.0', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetXY(138, 237);
        $pdf::MultiCell(30, 11, '240.70', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::SetXY(168, 237);
        $pdf::MultiCell(30, 11, '3.27' . "\n" . 'Very Good', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


        // ----QR Code-----

        $pdf::SetFont('helvetica', '', '9');
        $style = array(
            'border' => false,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false,

            'module_width' => 1,

            'module_height' => 1
        );

        $pdf::write2DBarcode('NAME: PRIYAANSH OJAS BHATT ', 'QRCODE,H', 10, 252, 28, 23, $style, 'N');
        $pdf::Text(15, 275, 'PD 00034 ');
        $pdf::Text(45, 275, 'Controller of Examination');
        $pdf::Text(135, 275, 'Registrar');

        $pdf::Output('Certificate.pdf', 'I');
    }


    public function import(Request $request)
    {

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf::AddPage();
        $pdf::SetFont('helvetica', 'B', 9.2);

        //    ---BG IMAGE---
        $imagePath1 = public_path('image/BG.jpeg');

        $bMargin = $pdf::getBreakMargin();
        $pdf::SetAutoPageBreak(false, 0);

        // set bacground image
        $pdf::Image($imagePath1, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        $pdf::SetAutoPageBreak('', $bMargin);
        $pdf::setPageMark();

        // PAGE HEADER
        $spreadsheet = IOFactory::load(public_path('excel_file/GC_Sample.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();
        dd($data);

        // Initialize a flag to skip the first row (header)
        $skipFirstRow = true;

        foreach ($data as $row) {
            // Skip the first row (header)
            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue;
            }
            $pdf::setFont('helvetica', 'I', 15);
            $pdf::SetXY(65, 35);
            $pdf::MultiCell(75, 11, 'Semester Academic Report', 0, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(65, 42);
            $pdf::MultiCell(75, 11, 'Spring 2020', 0, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            //    student name
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(23, 52);
            $pdf::Cell(25, 11, 'Student Name :', 0, 'C');
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(48, 52);
            $pdf::Cell(20, 11, $row[2], 0, 'C');
            $pdf::Ln(10);
            $pdf::SetXY(64, 52);
            $pdf::Cell(20, 11, $row[3], 0, 'C');

            //student id
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(23, 58);
            $pdf::Cell(25, 11, 'Student ID No :', 0, 'C');
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(48, 58);
            $pdf::Cell(20, 11, $row[1], 0, 'C');

            //batch
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(35.3, 64);
            $pdf::Cell(12, 11, 'Batch :', 0, 'C');
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(48, 64);
            $pdf::Cell(20, 11, $row[8], 0, 'C');

            //semester
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(29.5, 70);
            $pdf::Cell(18, 11, 'Semester :', 0, 'C');
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(48, 70);
            $pdf::Cell(20, 11, $row[9], 0, 'C');

            //issue date
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(16.5, 76);
            $pdf::Cell(32, 11, 'Report Issue Date :', 0, 'C');
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(48, 76);
            $pdf::Cell(20, 11, $row[130], 0, 'C');

            // Date of Birth
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(82, 58);
            $pdf::Cell(32, 11, 'Date of Birth :', 0, 'C');
            $pdf::SetXY(105, 58);
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::Cell(32, 11, $row[5], 0, 'C');
            
            // programme
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(83, 64);
            $pdf::Cell(32, 11, 'Programme :', 0, 'C');
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(105, 64);
            $pdf::Cell(32, 11, $row[10], 0, 'C');

            //major
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(92.5, 70);
            $pdf::Cell(32, 11, 'Major :', 0, 'C');
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(105, 70);
            $pdf::Cell(32, 11, $row[11], 0, 'C');


             //table header
            $pdf::setFont('helvetica', 'B', 9.2);
            $pdf::SetXY(12, 90);
            $pdf::MultiCell(22, 11, 'Course' . "\n" . 'Code', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 90);
            $pdf::MultiCell(96, 11, 'Course', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 90);
            $pdf::MultiCell(17, 11, 'Credit' . "\n" . 'Enrolled', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 90);
            $pdf::MultiCell(17, 11, 'Credit' . "\n" . 'Earned', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 90);
            $pdf::MultiCell(17, 11, 'Grade ', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 90);
            $pdf::MultiCell(17, 11, 'Grade' . "\n" . 'Point', 1, 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');



            // table data
            $pdf::setFont('helvetica', '', 9.2);
            $pdf::SetXY(12, 101);
            $pdf::MultiCell(22, 11, $row[30], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 101);
            $pdf::MultiCell(96, 11, $row[31], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 101);
            $pdf::MultiCell(17, 11, $row[32], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 101);
            $pdf::MultiCell(17, 11, $row[33], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 101);
            $pdf::MultiCell(17, 11, $row[34], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 101);
            $pdf::MultiCell(17, 11, $row[35], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            $pdf::SetXY(12, 112);
            $pdf::MultiCell(22, 11, $row[36], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 112);
            $pdf::MultiCell(96, 11, $row[37], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 112);
            $pdf::MultiCell(17, 11, $row[38], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 112);
            $pdf::MultiCell(17, 11, $row[39], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 112);
            $pdf::MultiCell(17, 11, $row[40], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 112);
            $pdf::MultiCell(17, 11, $row[41], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf::SetXY(12, 123);
            $pdf::MultiCell(22, 11, $row[42], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 123);
            $pdf::MultiCell(96, 11, $row[43], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 123);
            $pdf::MultiCell(17, 11, $row[44], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 123);
            $pdf::MultiCell(17, 11, $row[45], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 123);
            $pdf::MultiCell(17, 11, $row[46], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 123);
            $pdf::MultiCell(17, 11, $row[47], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            $pdf::SetXY(12, 134);
            $pdf::MultiCell(22, 11, $row[48], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 134);
            $pdf::MultiCell(96, 11, $row[49], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 134);
            $pdf::MultiCell(17, 11, $row[50], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 134);
            $pdf::MultiCell(17, 11, $row[51], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 134);
            $pdf::MultiCell(17, 11, $row[52], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 134);
            $pdf::MultiCell(17, 11, $row[53], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');



            $pdf::SetXY(12, 145);
            $pdf::MultiCell(22, 11, $row[54], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 145);
            $pdf::MultiCell(96, 11, $row[55], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 145);
            $pdf::MultiCell(17, 11, $row[56], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 145);
            $pdf::MultiCell(17, 11, $row[57], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 145);
            $pdf::MultiCell(17, 11, $row[58], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 145);
            $pdf::MultiCell(17, 11, $row[59], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            $pdf::SetXY(12, 156);
            $pdf::MultiCell(22, 11, $row[60], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 156);
            $pdf::MultiCell(96, 11, $row[61], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 156);
            $pdf::MultiCell(17, 11, $row[62], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 156);
            $pdf::MultiCell(17, 11, $row[63], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 156);
            $pdf::MultiCell(17, 11, $row[64], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 156);
            $pdf::MultiCell(17, 11, $row[65], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

            $pdf::SetXY(12, 167);
            $pdf::MultiCell(22, 11, $row[66], 'LR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 167);
            $pdf::MultiCell(96, 11, $row[67], 'LR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 167);
            $pdf::MultiCell(17, 11, $row[68], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 167);
            $pdf::MultiCell(17, 11, $row[69], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 167);
            $pdf::MultiCell(17, 11, $row[70], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 167);
            $pdf::MultiCell(17, 11, $row[71], 'LR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


            $pdf::SetXY(12, 168);
            $pdf::MultiCell(22, 40, '', 'LBR', 'R', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(34, 168);
            $pdf::MultiCell(96, 40, '', 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(130, 168);
            $pdf::MultiCell(17, 40, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(147, 168);
            $pdf::MultiCell(17, 40, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(164, 168);
            $pdf::MultiCell(17, 40, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');
            $pdf::SetXY(181, 168);
            $pdf::MultiCell(17, 40, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');


        }

        $pdf::Output('450113_Ashlesha Anchan_Semester-I_M18_FNDN_Marksheet.pdf', 'I');
    }



}