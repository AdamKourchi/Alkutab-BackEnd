<?php

namespace App\Http\Controllers;


use App\Models\EnrollmentCourse;
use App\Models\Path;
use Illuminate\Http\Request;
use App\Models\Enrollment;

class StudentPathController extends Controller
{
  
    public function index()
    {      
        $paths = Path::with(['levels', 'levels.courses.teacher:id,name'])->get();

        return response()->json($paths);

    }


    
}
