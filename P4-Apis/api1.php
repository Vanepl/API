<?php
if (isset($_POST['placa'])) {
    $placa = $_POST['placa'];
    //proyecto
    // Codificar el número de identificación para la URL
    $placaEncoded = rawurlencode($placa);

    // Construir el enlace con los filtros
    $endpoint = 'https://www.datos.gov.co/resource/x8g3-nn2c.json?placa=' . $placaEncoded;
    // Obtener los datos del endpoint
    $data = file_get_contents($endpoint);

    // Verificar si se pudieron obtener los datos correctamente
    if ($data === false) {
        // Manejo de error si falla la obtención de datos
        die('No se pudieron obtener los datos del endpoint.');
    }

    // Procesar los datos obtenidos (aquí puedes realizar las transformaciones y manipulaciones necesarias)
    // Por ejemplo, puedes convertir los datos en un array asociativo utilizando json_decode()
    $dataArray = json_decode($data, true);

    // Verificar si se pudo decodificar el JSON correctamente
    if ($dataArray === null) {
        // Manejo de error si falla la decodificación JSON
        die('No se pudieron decodificar los datos JSON.');
    } else if (empty($dataArray)) {
        echo "<h1>No se encontraron multas.</h1>";

        // Generar código JavaScript para mostrar ventana emergente y redireccionar
        echo '<script type="text/javascript">';
        echo 'alert("No se encontraron multas.");';
        echo 'window.location.href = "index.html";'; // Reemplaza "index.html" con la URL de la página a la que deseas redireccionar
        echo '</script>';
    } else {
        // Hacer algo con los datos obtenidos, como mostrarlos en el navegador o almacenarlos en la base de datos
        echo '<style>

        body {
            background-image: url("https://cdn.pixabay.com/photo/2016/07/23/17/04/hammer-1537123_1280.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: red;
        }

                table {
                    border-collapse: collapse;
                    width: 100%;

                }
                th, td {
                    padding: 8px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                    background-color: white;
                    color: black;
                }
                th {
                    background-color: rgba(0, 195, 255, 0.854);;
                }

                #h22{
                    margin-top: 20%; 
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

            </style>';

        echo '<h2 id="h22">Resultados de multas: </h2>';    
        echo '<table>';
        echo '<tr><th>Fecha de la multa</th><th>Numero de la placa</th><th>Ciudad</th><th>Monto de la multa</th><th>Pago</th></tr>';

        foreach ($dataArray as $item) {
            echo '<tr>';
            echo '<td>' . $item['fecha_multa'] . '</td>';
            echo '<td>' . $item['placa'] . '</td>';
            echo '<td>' . $item['ciudad'] . '</td>';
            echo '<td>' . '$' . $item['valor_multa'] . '</td>';
            echo '<td>' . $item['pagado_si_no'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '<button onclick="window.location.href=\'index.html\'">Volver</button>';
    }
} else {
    // El campo de número de identificación no se envió
    echo 'Por favor, ingresa el número de la placa.';
}
?>
