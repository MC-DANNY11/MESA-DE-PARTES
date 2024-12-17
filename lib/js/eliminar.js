const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

function confirmDelete(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}


// ELIMINAR LIBRO


function EliminarLibro(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}

// ELIMINAR LIBRO


function EliminarEditorial(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}


// ELIMINAR caja o seccion


function EliminarCaja(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}

// ELIMINAR mat3eriales
function EliminarArea(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}
// ELIMINAR usuaurio
function EliminarUsuario(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}

// ELIMINAR socio
function EliminarSocio(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}
// ELIMINAR inventario
function EliminarInventario(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}


// ELIMINAR prestamos
function EliminarPrestamo(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}


// ELIMINAR prestamos
function EliminarPrestamoMaterial(event) {
    event.preventDefault(); // Evita que el enlace realice su acción por defecto

    const url = event.currentTarget.getAttribute('data-href'); // Obtiene la URL del data-href

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡La eliminacion afectara a otros registros!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "¡Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirige a la URL de eliminación
            swalWithBootstrapButtons.fire({
                title: "¡Eliminado!",
                text: "Tu archivo ha sido eliminado.",
                icon: "success"
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Tu archicvo está a salvo :)",
                icon: "error"
            });
        }
    });
}
