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
        $pdf::SetFont('times', '', 12);

        // Header
        $pdf::Cell(0, 11, 'User Details', 0, 1, 'C');
        $pdf::Ln(); 
        

        // Create the table headers
        $pdf::Cell(30, 10, 'ID', 1, 0, 'C');
        $pdf::Cell(30, 10, 'Name', 1, 0, 'C');
        $pdf::Cell(40, 10, 'Email', 1, 0, 'C');
        $pdf::Cell(40, 10, 'Profile Image', 1, 0, 'C');
        $pdf::Ln(); 

        // Create table rows with data and QR codes
        foreach ($users as $row) {
            $pdf::Cell(30, 10, $row->id, 1, 0, 'C');
            $pdf::Cell(30, 10, $row->name, 1, 0, 'C');
            $pdf::Cell(40, 10, $row->email, 1, 0, 'C');
            $pdf::Cell(40, 10, '', 1, '', 'C');
            $imageX = $pdf::GetX();
            $imageY = $pdf::GetY();
           
            $pdf::Image(public_path('/image/download.png'), $imageX + -20, $imageY + 1, 10, 8, 'PNG'); // Adjust the dimensions as needed
            $pdf::Ln();
            
        }
         // set style for barcode
         $style = array(
            'border' => 1,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false,
            'module_width' => 1,
            // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );


        $pdf::write2DBarcode('dev harsh pvt ltd', 'QRCODE,L', 150, 150, 40, 40, $style, 'N');
        // $pdf::Text(20, 25, 'QRCODE L');
       
        
        // $code = '111011101110111,010010001000010,010011001110010,010010000010010,010011101110010';
        // $pdf::write2DBarcode($code, 'RAW', 100, 150, 40, 40, $style, 'N');
        $code = '[111011101110111][010010001000010][010011001110010][010010000010010][010011101110010]';
        $pdf::write2DBarcode($code, 'RAW2', 100, 150, 40, 40, $style, 'N');

        $pdf::Ln(); 
        // Output the PDF as inline (I) or for download (D)
        return $pdf::Output('example.pdf', 'I');
    }



}