function myHelper_swalGenericError(){
    swal.fire({
        title: 'Internal error',
        text: "Something went wrong",
        type: 'error',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
    });
}
function myHelper_swalWorking(){
    Swal.fire({
        title: '<span class="spinner-grow spinner-grow" role="status" aria-hidden="true"></span>&nbsp;Working ...',
        showConfirmButton: false,
        allowOutsideClick: false,
    });
}

function myHelper_toastErrorWithMessage(message="ERROR"){
    $(document).Toasts('create', {
        title: message,
        autohide: true,
        delay: 9999,
        class: 'bg-red',
        icon: 'far fa-times-circle',
    })
}

function myHelper_toastInfoWithMessage(message=""){
    $(document).Toasts('create', {
        title: message,
        autohide: true,
        delay: 4444,
        class: 'bg-info',
        icon: 'fas fa-info-circle',
    })
}