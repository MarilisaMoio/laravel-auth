<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allProjects = Project::all();

        return view('admin.projects.index', compact('allProjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->all();
        $this->validator($formData);
        $formData['slug'] = Str::slug($formData['name'], '-');

        $newProject = new Project();
        $newProject->fill($formData);
        $newProject->save();

        return redirect()->route('admin.projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //validator custom che dovrebbe funzionare sia per edit che per create, andando ad attivare una regola solo se passo l'id
    private function validator($input, $id = false){

        $rules = [
            'name' => [
                'required',
                'min:5',
                'max:150',
            ],
            'client_name' => 'nullable|min:5|max:600',
            'summary' => 'nullable|min:10|max:2000',
        ];

        if ($id) {
            array_push($rules['name'], Rule::unique('projects')->ignore($id));
        }

        $messages = [
            'required' => 'Il campo ":attribute" è vuoto, ma è necessario compilarlo.',
            'min' => 'Il campo ":attribute" necessita di almeno :min caratteri.',
            'max' => [
                'numeric' => 'Il campo ":attribute" accetta un valore massimo di :max.',
                'string' => 'Il campo ":attribute" accetta un massimo di :max caratteri.',
            ],
        ];

        $validator = Validator::make($input, $rules, $messages)->validate();

        return $validator;
    }
}
