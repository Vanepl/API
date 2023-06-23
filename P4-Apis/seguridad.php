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

    // Verificar si se pudo decodificar el JSON correctamente
    if ($dataArray2 === null) {
        // Manejo de error si falla la decodificación JSON
        die('No se pudieron decodificar los datos JSON para la segunda consulta.');
    }

    // Mostrar los resultados de ambas consultas en una tabla
    echo '<h2>Resultados de la Responsabilidad fiscal:</h2>';
    if (empty($dataArray1)) {
        echo "<p>No se encontraron resultados para la Responsabilidad fiscal.</p>";
    } else {
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
    }



    
    echo '<h2>Resultados de los antecedentes del siri:</h2>';
    if (empty($dataArray2)) {
        echo "<p>No se encontraron resultados para los antecedentes del siri.</p>";
    } else {
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
    }
} else {
    // El campo de número de identificación no se envió
    echo 'Por favor, ingresa un número de identificación.';
}
?>
