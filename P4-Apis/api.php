<?php
if (isset($_POST['numero_identificacion'])) {
    $numeroIdentificacion = $_POST['numero_identificacion'];
    $tipoIdentificacion = $_POST['tipo_identificacion'];

    // Agregar espacios en blanco adicionales al final del número de identificación
    $numeroIdentificacion = str_pad($numeroIdentificacion, 8, ' ');

    // Codificar el número de identificación para la URL
    $numeroIdentificacionEncoded = rawurlencode($numeroIdentificacion);

    // Codificar el valor del tipo de identificación para la URL
    $tipoIdentificacionEncoded = rawurlencode($tipoIdentificacion);

    // Construir el enlace con los filtros para la primera consulta
    $endpoint1 = 'https://www.datos.gov.co/resource/jr8e-e8tu.json?n_mero_de_identificaci_n=' . $numeroIdentificacionEncoded;
    // Obtener los datos del endpoint1
    $data1 = file_get_contents($endpoint1);
     //proyecto
    // Verificar si se pudo obtener los datos correctamente
    if ($data1 === false) {
        // Manejo de error si falla la obtención de datos
        die('No se pudieron obtener los datos del endpoint1.');
    }

    // Procesar los datos obtenidos para la primera consulta
    $dataArray1 = json_decode($data1, true);

    // Verificar si se pudo decodificar el JSON correctamente
    if ($dataArray1 === null) {
        // Manejo de error si falla la decodificación JSON
        die('No se pudieron decodificar los datos JSON para la primera consulta.');
    }

    // Construir el enlace con los filtros para la segunda consulta
    $endpoint2 = 'https://www.datos.gov.co/resource/iaeu-rcn6.json?$where=numero_identificacion="' . $numeroIdentificacionEncoded . '%20%20%20%20%20%20%20"%20AND%20nombre_tipo_identificacion="' . $tipoIdentificacionEncoded. '"';
    // Obtener los datos del endpoint2
    $data2 = file_get_contents($endpoint2);

    // Verificar si se pudo obtener los datos correctamente
    if ($data2 === false) {
        // Manejo de error si falla la obtención de datos
        die('No se pudieron obtener los datos del endpoint2.');
    }

    // Procesar los datos obtenidos para la segunda consulta
    $dataArray2 = json_decode($data2, true);

    // Mostrar los resultados de ambas consultas en una tabla
    echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados API</title>
    <style>

    body {
        background-image: url("https://cdn.pixabay.com/photo/2017/04/04/17/17/handcuffs-2202224_1280.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
        color: red;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 50px; 
    }
    
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        background-color: white;
        color: black;
    }
    
    th {
        background-color: rgba(0, 195, 255, 0.854);
    }

    #h22{
        margin-top: 210px; 
    }

    h2{
        color: rgba(0, 195, 255, 0.854);
    }

    button{
        padding: 10px 40px;
        border: 2px solid black;
        font-size: 14px;
        background: transparent;
        font-weight: 600;
        cursor: pointer;
        color: black;
        outline: none;
        transition: all 300ms;
        margin-top: 20px;
    }

    button:hover{
        border: 2px solid rgba(0, 195, 255, 0.934);
        color:  rgba(0, 195, 255, 0.934);
    }

</style>
</head>

<body>';
echo '<h2 id="h22">Resultados de la Responsabilidad fiscal:</h2>';

$resultadosResponsabilidadFiscal = !empty($dataArray1); // Variable para verificar si hay resultados

if ($resultadosResponsabilidadFiscal) {
    echo '<table>';
    echo '<tr><th>Tipo de sancion</th><th>Entidad Bancaria</th><th>Motivo</th><th>Numero de identificacion</th><th>Monto de la multa</th></tr>';

    foreach ($dataArray1 as $item) {
        echo '<tr>';
        echo '<td>' . $item['tipo_de_sanci_n_multa'] . '</td>';
        echo '<td>' . $item['raz_n_social_de_la_entidad'] . '</td>';
        echo '<td>' . $item['tema_clasificaci_n_o_motivo'] . '</td>';
        echo '<td>' . $item['n_mero_de_identificaci_n'] . '</td>';
        echo '<td>' . $item['monto_de_la_multa_o_sanci'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else{
    echo 'No se encontraron resultados para los antecedentes del siri<br/>';
}

echo '<h2>Resultados de los antecedentes del siri:</h2>';

$resultadosAntecedentesSiri = !empty($dataArray2); // Variable para verificar si hay resultados

if ($resultadosAntecedentesSiri) {
    echo '<table>';
    echo '<tr><th>Primer Apellido</th><th>Segundo Apellido</th><th>Primer Nombre</th><th>Segundo Nombre</th><th>Tipo inhabilidad</th></tr>';

    foreach ($dataArray2 as $item) {
        echo '<tr>';
        echo '<td>' . $item['primer_apellido'] . '</td>';
        echo '<td>' . $item['segundo_apellido'] . '</td>';
        echo '<td>' . $item['primer_nombre'] . '</td>';
        echo '<td>' . $item['segundo_nombre'] . '</td>';
        echo '<td>' . $item['tipo_inhabilidad'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}else{
    echo 'No se encontraron resultados para los antecedentes del siri<br/>';

}

if (!$resultadosResponsabilidadFiscal && !$resultadosAntecedentesSiri) {
    // No se encontraron resultados en ninguno de los bloques

    echo '<script type="text/javascript">';
    echo 'alert("No se encontraron resultados.");';
    echo 'window.location.href = "index.html";'; // Reemplaza "index.html" con la URL de la página a la que deseas redireccionar
    echo '</script>';
}

echo '<button onclick="window.location.href=\'index.html\'">Volver</button>';
echo '</body>
</html>';
}
?>
