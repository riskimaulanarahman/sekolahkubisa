<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::all();
        return view('modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_module' => 'required',
            'file' => 'required|mimes:pdf'
        ]);

        $file = $request->file('file');
        $filename = 'module_' . time() . $file->getClientOriginalName();
        $destination = public_path('upload');
        $path = $file->move($destination, $filename);

        $module = new Module();
        $module->nama_module = $request->nama_module;
        $module->pathfile = $filename;
        $module->save();

        return redirect()->route('modules.index')->with('success', 'Module created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = Module::findOrFail($id);
        return view('modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_module' => 'required',
            'file' => 'nullable|mimes:pdf,doc,docx'
        ]);

        $module = Module::findOrFail($id);
        $module->nama_module = $request->nama_module;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'module_' . time() . $file->getClientOriginalName();
            $destination = public_path('upload');
            $path = $file->move($destination, $filename);
            $module->pathfile = $filename;
        }

        $module->save();

        return redirect()->route('modules.index')->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return redirect()->route('modules.index')->with('success', 'Module deleted successfully.');
    }
}
