function alertaSuccess(title = '', message = '') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonText: 'Aceptar'
    });
}

function alertaError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonText: 'Aceptar'
    });
}

function alertaConfirmacion(message, onConfirm) {
    Swal.fire({
        title: 'Confirmación',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirm();
        }
    });
}

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

function toastSuccess(message) {
    Toast.fire({
        icon: 'success',
        title: message
    });
}

function toastError(message) {
    Toast.fire({
        icon: 'error',
        title: message
    });
}

function toastInfo(message) {
    Toast.fire({
        icon: 'info',
        title: message
    });
}

export default {
    alertaSuccess,
    alertaError,
    alertaConfirmacion,
    toastSuccess,
    toastError,
    toastInfo
};