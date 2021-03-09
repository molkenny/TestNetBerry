<!-- Modal -->
<div class="modal fade" id="del-task-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="del-task-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                 <h5 class="modal-title" id="del-task-modal-label">Eliminar Tarea</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="del-task-form">
                @csrf
                <div class="modal-body">
                    <div id="del-task-alert"></div>

                    Estas seguro de eliminar esta tarea?
                    <input type="hidden" name="id_task" id="del-task-id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" id="btn-submit-del-task">Borrar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){

        $("#del-task-form").submit(function(e){
            e.preventDefault();
            //Activo loader
            activarBtnLoading('btn-submit-del-task');

             $.ajax({
                    type:       "DELETE",
                    url:        `api/task/`+$('#del-task-id').val(),
                    dataType: "json",
                })
                .done(function(result) {
                    refreshTableTask();
                    desactivarBtnLoading('btn-submit-add-task','Borrar');
                    $('#del-task-modal').modal('hide');
                })
                .fail(function(data, textStatus, xhr) {
                    let mensaje = 'Error al borrar Tarea';
                    if(data.responseJSON || !data.responseJSON.msg)  mensaje = data.responseJSON.msg;
                    crearAlerta('del-task-alert', 'danger', mensaje);
                    desactivarBtnLoading('btn-submit-del-task','Borrar');
                })
        });
    });

    function showModalDeleteTask(idTask){
        $('#del-task-form').trigger("reset");
        $("#del-task-alert").css('display','none');
        $('#del-task-id').val(idTask);
        $('#del-task-modal').modal('show');
    }

</script>