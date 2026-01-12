<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function create() {
        return view('Application');
    }

    public function store(Request $request) {
       
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'profession' => 'required',
            'reason' => 'required', 
        ]);

        
        Application::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
            'profession' => $request->profession,
            'user_justification' => $request->reason, 
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Application sent!');
    }
}
?>

