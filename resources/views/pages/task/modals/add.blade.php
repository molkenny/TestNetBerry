<!-- Modal -->
<div class="modal fade" id="add-task-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="add-task-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 40%; max-width: none;">
        <div class="modal-content">
            <div class="modal-header">
                 <h5 class="modal-title" id="add-task-modal-label">Agregar Tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="add-task-alert"></div>
                <form id="add-task-form">
                    @csrf
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Tarea</span>
                            </div>
                            <input type="text" class="form-control" name="task" requiered>
                        </div>

                        <div id='add-task-div-categories'></div>
                    
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="btn-submit-add-task">Guardar</button>
                    </div>
                </form>   
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#add-task-form").submit(function(e){
            e.preventDefault();
            console.log('Submit form add Task');
            let serializedData = $(this).serializeArray();

            activarBtnLoading('btn-submit-add-task');

            let valid = validateInputsAddTask(this);
            if(valid.success){
                console.log(serializedData);
                 $.ajax({
                    type:       "POST",
                    url:        `api/task`,
                    data:       serializedData,
                    dataType: "json",
                })
                .done(function(result) {
                    refreshTableTask();
                    desactivarBtnLoading('btn-submit-add-task','Guardar');
                    $('#add-task-modal').modal('hide');
                })
                .fail(function(data, textStatus, xhr) {
                    console.log(data.responseJSON);
                    let mensaje = 'Error al guardar Tarea';
                    if(data.responseJSON || !data.responseJSON.msg)  mensaje = data.responseJSON.msg;
                    crearAlerta('add-task-alert', 'danger', mensaje);
                    desactivarBtnLoading('btn-submit-add-task','Guardar');
                })

            }else{
                console.log('Error ',valid.msg);
                crearAlerta('add-task-alert', 'danger', valid.msg);
                desactivarBtnLoading('btn-submit-add-task','Guardar');
            }

             
        });

        $('#closeAlertBtn').on('click', function (e) {
            e.preventDefault();
            $("#add-task-alert").css('display','none');
        });
    });
    
    function showModalAddTask(){
        $('#add-task-form').trigger("reset");
        $("#add-task-alert").css('display','none');
        loadCategories();
        $('#add-task-modal').modal('show');
    }

    function loadCategories(){
         $.ajax({
            type:       "GET",
            url:        `api/category`,
            dataType: "json",
        })
        .done(function(result) {
            if(result.success){
                let arrCategories = result.data;
                let container = $('#add-task-div-categories');
                container.html('');
                arrCategories.forEach(element => {
                    let aux = `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categories[${element.id}]" id="check-${element.id}">
                            <label class="form-check-label" for="check-${element.id}">
                                ${element.name}
                            </label>
                        </div>
                    `;
                    container.append(aux);
                });  
            }else{
                crearAlerta('add-task-alert', 'danger', result.msg);
            }
        })
        .fail(function(data, textStatus, xhr) {
            console.log("error", data.status);
            console.log("STATUS: "+xhr);
            crearAlerta('add-task-alert', 'danger', 'Error al guardar Tarea');
            desactivarBtnLoading('btn-submit-add-task','Guardar');
        })
    }

    function validateInputsAddTask(form){
        let valueTarea = $(form).find('input[name="task"]').val().trim();
        console.log('valueTarea',valueTarea);
        if(!valueTarea || valueTarea == ''){
            return {
                success: false,
                msg: "Debe ingresar una Tarea"
            }
        }

        let countCategoriesSelect = $(form).find('input:checkbox:checked').length;
        console.log('countCategoriesSelect',countCategoriesSelect);
        if(!countCategoriesSelect){
            return {
                success: false,
                msg: "Debe seleccionar alguna categoria"
            }
        }

        return {
            success: true
        }

    }   
</script>