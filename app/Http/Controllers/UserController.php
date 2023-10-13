<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\UsersExport;
use Spatie\Permission\Models\Role;
use DataTables;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
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
    $filename = 'product.pdf';

    $users = User::all(); 
    
    // $users = DB::table('users')
    // ->where('id', '>', 3)
    // ->orWhere('name', 'nishant')
    // ->get();

    // $data = [
        
    //     'users' => $users,
    //     'title' => 'Generate PDF using Laravel TCPDF - scube!'
    // ];
   
    $html = view('tcpdf', compact('users'))->render();

    $pdf = new TCPDF;

    $pdf::SetTitle('Hello World');
    $pdf::AddPage();
    $pdf::writeHTML($html, true, false, true, false, '');

    $pdf::Output(public_path($filename), 'f');

    return response()->download(public_path($filename));
}


}