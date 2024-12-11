// Mostrar el popup de derivación con el ID del expediente
function mostrarPopupDerivar(id_expediente) {
  document.getElementById("id_expediente_derivar").value = id_expediente;
  document.getElementById("popupDerivarModal").style.display = "flex";
}
document.getElementById("searchButton").addEventListener("click", function () {
  const searchValue = document.getElementById("searchInput").value.trim();
  if (searchValue) {
      // Redirigir con los parámetros de búsqueda
      window.location.href = `?expediente=${encodeURIComponent(searchValue)}&remitente=${encodeURIComponent(searchValue)}`;
  }
});

// Cerrar el popup de derivación al hacer clic en el botón de cerrar
document.getElementById("closeDerivarPopup").onclick = function () {
  document.getElementById("popupDerivarModal").style.display = "none";
};
// Función para mostrar el modal de "Atender"
function mostrarModal(id_expediente) {
  document.getElementById("id_expediente").value = id_expediente;
  document.getElementById("popupModal").style.display = "flex";
}
// Cerrar el popup de "Atender" al hacer clic en el botón de cerrar
document.getElementById("closePopup").onclick = function () {
  document.getElementById("popupModal").style.display = "none";
};
// Mostrar el popup de eliminación con el ID del expediente
function mostrarPopupEliminar(id_expediente) {
  document.getElementById("id_expediente_eliminar").value = id_expediente;
  document.getElementById("popupEliminarModal").style.display = "flex";
}
// Cerrar el popup de eliminación al hacer clic en el botón de cerrar
document.getElementById("closeEliminarPopup").onclick = function () {
  document.getElementById("popupEliminarModal").style.display = "none";
};
// Función para abrir y cerrar el menú lateral
function toggleMenu() {
  const sidebar = document.querySelector(".sidebar");
  sidebar.classList.toggle("active");
}
// Cerrar el popup si se hace clic fuera del contenido
window.onclick = function (event) {
  // Si se hace clic fuera de un modal específico, lo cerramos
  if (event.target == document.getElementById("popupDerivarModal")) {
    document.getElementById("popupDerivarModal").style.display = "none";
  }
  if (event.target == document.getElementById("popupModal")) {
    document.getElementById("popupModal").style.display = "none";
  }
  if (event.target == document.getElementById("popupEliminarModal")) {
    document.getElementById("popupEliminarModal").style.display = "none";
  }
};
// Seleccionamos los elementos que necesitamos
const menuToggle = document.querySelector(".menu-toggle");
const sidebar = document.querySelector(".sidebar");
// Añadimos el evento click al botón de menú
menuToggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});

//OTRO
document.getElementById("buscar").addEventListener("keyup", function () {
  var query = this.value;

  // Realizar la petición AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "buscar_expedientes.php?query=" + query, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // Obtener los datos JSON que nos devuelve PHP
      var expedientes = JSON.parse(xhr.responseText);

      // Limpiar la tabla antes de mostrar los nuevos resultados
      var table = document.getElementById("tabla_expedientes");
      table.innerHTML = "";

      // Mostrar los resultados
      expedientes.forEach(function (expediente) {
        var row = table.insertRow();
        row.insertCell(0).textContent = expediente.id_expediente;
        row.insertCell(1).textContent = expediente.remitente;
        row.insertCell(2).textContent = expediente.estado;
        // Agregar más celdas según los campos que necesitas
      });
    }
  };
  xhr.send();
});
