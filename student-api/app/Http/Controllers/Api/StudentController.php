<?php

namespace App\Http\Controllers\Api;

use App\Exports\StudentExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;


class StudentController extends Controller
{
    public function index(){

        $students = Student::all();    
        if($students->count() > 0)
        {
            return StudentResource::collection($students);
        // return response()->json([
        //     'status' => 200,
        //     'students' => $students
        // ], 200);
        }else{
            
            return response()->json([
                'status' => 404,
                'message' => 'No Record Found'
            ], 404);
            }
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'address' => 'required|string|max:191',
            'course' => 'required|string|max:191',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()

            ], 422);
        }else{
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'course' => $request->course,

            ]);

            if($student){

                return response()->json([
                    'status' => 200,
                    'message' => "Student created successfully"
                ], 200);

            }else{
                return response()->json([
                    'status' => 500,
                    'message' => "Something went wrong..."
                ], 500);

            }
        }
    }

    public function show($id){
        $students = Student::find($id);    
        if($students)
        {
        return response()->json([
            'status' => 200,
            'students' => $students
        ],200);
    }else{ 
        return response()->json([
            'status' => 404,
            'message' => 'No such Student Found'
        ],404);
        }
    }

    
    public function edit($id){
        $students = Student::find($id);    
        if($students)
        {
        return response()->json([
            'status' => 200,
            'students' => $students
        ],200);
    }else{ 
        return response()->json([
            'status' => 404,
            'message' => 'No such Student Found'
        ],404);
        }
    }

    public function update(Request $request, int $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'address' => 'required|string|max:191',
            'course' => 'required|string|max:191',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()

            ], 422);
        }else{

            $student = Student::find($id);
            if($student){
                $student->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'course' => $request->course,
    
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "Student updated successfully"
                ], 200);

            }else{
                return response()->json([
                    'status' => 404,
                    'message' => "No such Student Found..."
                ], 404);

            }
        }
    }


    public function destroy($id){
        $student = Student::find($id);
        if($student){

            $student->delete();
            return response()->json([
                'status' => 200,
                'message' => "Student Delete Successfully"
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "No such Student Found..."
            ], 404);

        }
    }

    public function search(Request $request){
        // Get the search query from the request
        $query = $request->input('query');

        // Perform the search query
        $results = Student::where('name', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->get();

        // Check if there are no results
        if ($results->isEmpty()) {
            return response()->json(['message' => 'No Student data found.'], 404);
        }

        // Return the search results as JSON
        return response()->json(['data' => $results], 200);
        
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        $file = $request->file('file');
    
        Excel::import(new StudentImport, $file);
    
        return response()->json(['message' => 'Data imported successfully']);
    }

    public function export()
    {
        return Excel::download(new StudentExport, 'students.xlsx');
    }
    
}
