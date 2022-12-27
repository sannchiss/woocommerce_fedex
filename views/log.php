<?php

// get file in woocommerce upload folder
$upload_dir = wp_upload_dir();
$upload_dir = wp_upload_dir();
$upload_dir = $upload_dir['basedir'] . '/woocommerce_logs_register_fedex/';
$upload_dir = $upload_dir . 'Log_.txt';

// get file content
$file = file_get_contents($upload_dir);

// get file content in array
$file = explode("\n", $file);

// get last line
$last_line = $file[count($file)-2];

echo "<div class='panel-heading mb-3'>
                <h3 class='panel-title'>Registro Log</h3>
            </div>";

// show file in table order recenty to oldest
echo "<div class='panel-body'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='table-responsive'>
                            <table class='table table-striped table-bordered table-hover shippingList'>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Log</th>
                                    </tr>
                                </thead>
                                <tbody>";

$i = 1;
foreach ($file as $line) {
    echo "<tr>
            <td><span class='badge bg-light text-dark'>".$i."</span></td>
            <td><span class='badge bg-light text-dark'>".$line."</span></td>
        </tr>";
    $i++;
}

echo "</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>";





?>

