function swalGenericError(){
    swal.fire({
        title: 'Internal error',
        text: "Something went wrong",
        type: 'error',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
    });
}