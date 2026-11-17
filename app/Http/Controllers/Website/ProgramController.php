<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        return view('website.programs.index', ['programs' => Program::active()->ordered()->get()]);
    }

    public function show(Program $program): View
    {
        abort_unless($program->is_active, 404);

        return view('website.programs.show', [
            'program' => $program,
            'others' => Program::active()->ordered()->whereKeyNot($program->id)->take(3)->get(),
        ]);
    }
}
