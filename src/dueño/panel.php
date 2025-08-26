<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Dueño de Local</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h1>Panel del Dueño de Local</h1>
    <p>Desde aquí podés administrar tus promociones.</p>

    <div class="mt-4">
      <a href="#" class="btn btn-success">Crear nueva promoción</a>
    </div>

    <div class="mt-4">
      <h3>Promociones creadas</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Descripción</th>
            <th>Vigencia</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>20% en efectivo</td>
            <td>01/09/25 - 30/09/25</td>
            <td>
              <a href="#" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
