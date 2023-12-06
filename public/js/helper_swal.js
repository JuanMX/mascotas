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