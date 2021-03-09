<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use App\Models\TaskCategory;

class TaskController extends Controller
{
    public function addTask(Request $request){
        $taskName =  trim($request->task);
        $arrCategories =  $request->categories;

        if(!$taskName || $taskName == ''){
            return response()->json(
                [
                    'success' => false,
                    'msg' => "Debe ingresar una Tarea"
                ]
            , 406 );
        }

        if(count($arrCategories) < 1){
            return response()->json(
                [
                    'success' => false,
                    'msg' => "Debe ingresar una Tarea"
                ]
            , 406 );
        }

        if(Task::where('task',$taskName)->first()){
            return response()->json(
                [
                    'success' => false,
                    'msg' => "Ya existe una tarea con ese nombre"
                ]
            , 406 );
        }

        try {
            $task = new Task();
            $task->usuario = 0;
            $task->task = $taskName;
            $task->status = 'ACTIVE';
            $task->save();
            
            foreach($arrCategories as $idCategory=>$value){
                $taskCategory = new TaskCategory();
                $taskCategory->id_task = $task->id;
                $taskCategory->id_category = $idCategory;
                $taskCategory->save(); 
            }

            return response()->json(
                [
                    'success' => true,
                    'msg' => "Task save"
                ]
            );

        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'msg' => $e->getMessage(),
                    'code' => 100
                ]
            , 500);
        }
        
    }

    public function listTaks(Request $request){
        try {
            $tasks = Task::where('status','ACTIVE')->with('categories')
                            ->get();
            return response()->json(
                [
                    'success' => true,
                    'data' => $tasks
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'msg' => $e->getMessage(),
                    'code' => 100
                ]
            , 500);
        }
    }

    public function deleteTask ($id,Request $request){
        try {
            $task = Task::find($id);
            if(!$task){
                return response()->json(
                    [
                        'success' => false,
                        'msg' => "No existe esa tarea"
                    ]
                , 406 );
            }

            //first i need to delete all TaskCategories
            TaskCategory::where('id_task',$id)->delete();
            $task->delete();
            
            return response()->json(
                [
                    'success' => true,
                    'data' => "Tarea Borrada"
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'msg' => $e->getMessage(),
                    'code' => 100
                ]
            , 500);
        }
    }

}
