function crearAlerta(idElementoPadre, tipo, mensaje) {
    $('#' + idElementoPadre).html('');
    let html = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $('#' + idElementoPadre).html(html);
    $('#' + idElementoPadre).css('display', 'block');
    $('#' + idElementoPadre)[0].scrollIntoView();
}




function activarBtnLoading(idButton) {
    $('#' + idButton).attr("disabled", "disabled");
    $('#' + idButton).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
    );
}

function desactivarBtnLoading(idButton, btnText) {
    $('#' + idButton).removeAttr("disabled");
    $('#' + idButton).html(btnText);
}