<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\UsersExport;
use Spatie\Permission\Models\Role;

use DB;
use Hash;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Elibyy\TCPDF\Facades\TCPDF;


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
        return Excel::download(new UsersExport, 'omkar-pol.xlsx');
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

        //  ---table----

        // $pdf::SetFont('helvetica', 'b', 9);
        // // $pdf::MultiCell(23, 10, 'Course Code', 1, 'C');
        // // $pdf::MultiCell(23, 10, 'Course'. "\n".'Code', 1, 'C');

        // $pdf::Cell(23, 10, 'Course Code', 1, 0, 'C');
        // $pdf::Cell(75, 10, 'Course', 1, 0, 'C');
        // $pdf::Cell(28, 10, 'Credits Enrolled', 1, 0, 'C');
        // $pdf::Cell(28, 10, 'Credits Earned', 1, 0, 'C');
        // $pdf::Cell(13, 10, 'Grade', 1, 0, 'C');
        // $pdf::Cell(23, 10, 'Grade Points', 1, 1, 'C');


        // $pdf::SetFont('helvetica', '', 9);
        // $pdf::Cell(23, 10, 'PRO202', 'LR', 0, 'R');
        // $pdf::Cell(75, 10, 'ADVANCED MATERIALS & MANUFACTURING', 'LR', 0, 'L');
        // $pdf::Cell(28, 10, '3.0', 'LR', 0, 'C');
        // $pdf::Cell(28, 10, '3.0', 'LR', 0, 'C');
        // $pdf::Cell(13, 10, 'A+', 'LR', 0, 'C');
        // $pdf::Cell(23, 10, '4.00', 'LR', 1, 'C');

        // $pdf::Cell(23, 10, 'PRO203', 'LR', 0, 'R');
        // $pdf::Cell(75, 10, 'ERGONOMICS', 'LR', 0, 'L');
        // $pdf::Cell(28, 10, '3.0', 'LR', 0, 'C');
        // $pdf::Cell(28, 10, '3.0', 'LR', 0, 'C');
        // $pdf::Cell(13, 10, 'B+', 'LR', 0, 'C');
        // $pdf::Cell(23, 10, '3.45', 'LR', 1, 'C');


        // $pdf::Cell(23, 10, 'PRO246', 'LR', 0, 'R');
        // $pdf::Cell(75, 10, 'PRODUCT DESIGN STUDIO-II', 'LR', 0, 'L');
        // $pdf::Cell(28, 10, '6.0', 'LR', 0, 'C');
        // $pdf::Cell(28, 10, '6.0', 'LR', 0, 'C');
        // $pdf::Cell(13, 10, 'A', 'LR', 0, 'C');
        // $pdf::Cell(23, 10, '3.80', 'LR', 1, 'C');

        // $pdf::Cell(23, 10, 'PRO263', 'LR', 0, 'R');
        // $pdf::Cell(75, 10, ' PRODUCT DESIGN RENDERING', 'LR', 0, 'L');
        // $pdf::Cell(28, 10, '3.0', 'LR', 0, 'C');
        // $pdf::Cell(28, 10, '3.0', 'LR', 0, 'C');
        // $pdf::Cell(13, 10, 'B-', 'LR', 0, 'C');
        // $pdf::Cell(23, 10, '3.00', 'LR', 1, 'C');

        // $pdf::Cell(23, 10, 'DES243', 'LR', 0, 'R');
        // $pdf::Cell(75, 10, 'DESIGN OF LIVING CULTURE', 'LR', 0, 'L');
        // $pdf::Cell(28, 10, '2.0', 'LR', 0, 'C');
        // $pdf::Cell(28, 10, '0.0', 'LR', 0, 'C');
        // $pdf::Cell(13, 10, 'F', 'LR', 0, 'C');
        // $pdf::Cell(23, 10, '0.00', 'LR', 1, 'C');

        // $pdf::Cell(23, 10, 'EL2026', 'LR', 0, 'R');
        // $pdf::Cell(75, 10, 'MATERIAL INSIGHT (E)', 'LR', 0, 'L');
        // $pdf::Cell(28, 10, '2.0', 'LR', 0, 'C');
        // $pdf::Cell(28, 10, '2.0', 'LR', 0, 'C');
        // $pdf::Cell(13, 10, 'B+', 'LR', 0, 'C');
        // $pdf::Cell(23, 10, '3.45', 'LR', 1, 'C');


        // $pdf::Cell(23, 10, 'EL2036', 'LR', 0, 'R');
        // $pdf::Cell(75, 10, 'PACKAGING DESIGN (E)', 'LR', 0, 'L');
        // $pdf::Cell(28, 10, '2.0', 'LR', 0, 'C');
        // $pdf::Cell(28, 10, '2.0', 'LR', 0, 'C');
        // $pdf::Cell(13, 10, 'D', 'LR', 0, 'C');
        // $pdf::Cell(23, 10, '2.00', 'LR', 1, 'C');


        // $pdf::Cell(23, 50, '', 'LRB', 0, 'C');
        // $pdf::Cell(75, 50, '', 'LRB', 0, 'C');
        // $pdf::Cell(28, 50, '', 'LRB', 0, 'C');
        // $pdf::Cell(28, 50, '', 'LRB', 0, 'C');
        // $pdf::Cell(13, 50, '', 'LRB', 0, 'C');
        // $pdf::Cell(23, 50, '', 'LRB', 1, 'C');
        // $pdf::Ln(2);



        // $columnWidths = [22, 96, 17,17,17,17];
        // $alignData=['R','L','C','C','C','C'];
        // // Set initial x and y coordinates
        //   $x = 12;
        //   $y = 83;
        //   $pdf::SetFont('helvetica', 'BI', 10);
        //   $columnBorder=['TLB','TLB','TLB','TLB','TLB','TLRB'];
        // $columnNames = ['Course Code', 'Course', 'Credit Enrolled','Credit Earned','Grade','Grade Point'];
        // $pdf::SetXY(12,83);    

        // foreach ($columnNames as $key=> $name) {

        //     $pdf::MultiCell($columnWidths[$key], 10, $name,$columnBorder[$key], 'C');


        //     $x += $columnWidths[$key]; 
        //     $pdf::SetXY($x, $y);  
        // }


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
            
           
            $pdf::MultiCell($columnWidths[$index], $cellHeight, $header, 'LRTB', $alignment);
        
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
                $pdf::MultiCell($columnWidths[$index], $cellHeight, $cell, $border, $alignment);
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

        //    -----second table------
        $pdf::SetX(12);
        $pdf::Cell(93, 8, 'Semester Grade Point Average (SGPA)', 1, 0, 'C');
        $pdf::Cell(93, 8, 'Cumulative Grade Point Average (CGPA)', 1, 1, 'C');

        $pdf::SetX(12);
        $pdf::Cell(25, 10, 'Earned Credits', 1, 0, 'C');
        $pdf::Cell(44, 10, 'Earned Credit Points', 1, 0, 'C');
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::Cell(29, 10, 'SGPA', 1, 0, 'C');
        $pdf::SetFont('helvetica', '', '9');
        $pdf::Cell(30, 10, 'Total Earned Credits', 1, 0, 'C');
        $pdf::Cell(29, 10, 'Total Earned', 1, 0, 'C');
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::Cell(29, 10, 'CGPA', 1, 1, 'C');

        $pdf::SetX(12);
        $pdf::SetFont('helvetica', '', '9');
        $pdf::Cell(25, 11, '19.0', 1, 0, 'C');
        $pdf::Cell(44, 11, '64.80', 1, 0, 'C');
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::Cell(29, 11, '3.41', 1, 0, 'C');
        $pdf::SetFont('helvetica', '', '9');
        $pdf::Cell(30, 11, '75.0', 1, 0, 'C');
        $pdf::Cell(29, 11, '240.70', 1, 0, 'C');
        $pdf::SetFont('helvetica', 'B', '9');
        $pdf::Cell(29, 11, '3.27', 1, 0, 'C');
        $pdf::SetFont('helvetica', 'B', '9');

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

        $pdf::write2DBarcode('NAME: PRIYAANSH OJAS BHATT ', 'QRCODE,H', 10, 252, 25, 23, $style, 'N');
        $pdf::Text(15, 275, 'PD 00034 ');
        $pdf::Text(45, 275, 'Controller of Examination');
        $pdf::Text(135, 275, 'Registrar');



        $pdf::Output('Certificate.pdf', 'I');
    }
}