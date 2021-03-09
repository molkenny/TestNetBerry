@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Gestor de tareas
                    <button 
                        type="button" 
                        class="btn btn-outline-primary btn-sm float-end"
                        onclick="openModalAddTask()"
                    >Agregar Tarea</button>
                </div>

                <div class="card-body">
                    <div id="list-task-alert"></div>
                        <table class="table" >
                            <thead>
                                <tr>
                                <th scope="col">Tarea</th>
                                <th scope="col">Categorias</th>
                                <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="list-task-table-body">
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('pages.task.modals.add');
@include('pages.task.modals.delete');

<script>
    $(document).ready(function(){
         refreshTableTask();
    })

    function refreshTableTask(){

        $.ajax({
            type:       "GET",
            url:        `api/task`,
            dataType: "json",
        })
        .done(function(result) {
            console.log(result);
            let contenedor = $('#list-task-table-body');
            contenedor.html('');
            if(result.success){
                result.data.forEach(task => {
                    let arrCategories = [];
                    task.categories.forEach(category => {
                        arrCategories.push(category.name);
                    });
                    let html = `
                        <tr>
                            <td>${task.task}</td>
                            <td>${arrCategories.join(',')}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm float-end" onclick="openModalDelTask(${task.id})">Borrar</button>
                            </td>
                        </tr>
                    `;
                    contenedor.append(html);
                });

                
            }else{
                crearAlerta('list-task-alert', 'danger', result.msg);
            }
        })
        .fail(function(data, textStatus, xhr) {
            console.log("error", data.status);
            console.log("STATUS: "+xhr);
            crearAlerta('list-task-alert', 'danger', 'Error al obtener lista de tareas');
        })  
    }

    function openModalDelTask(idTask){
        console.log('Borrar Tarea');
        showModalDeleteTask(idTask)
    }

    function openModalAddTask(){
        console.log('Agregar Tarea');
        showModalAddTask();
    }
</script>
@endsection
