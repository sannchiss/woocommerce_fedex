<?php
// get file in woocommerce upload folder
$upload_dir = wp_upload_dir();
$upload_dir = wp_upload_dir();
$upload_dir = $upload_dir['basedir'] . '/woocommerce_logs_register_fedex/';

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}else {
    
    $upload_dir = $upload_dir . 'Log_.txt';

    // get file content
    $file = file_get_contents($upload_dir);

    // get file content in array
    $file = explode("\n", $file);

    // get last line
    $last_line = $file[count($file)-2];

}

            echo "<div class='container px-4 text-center'>
            <div class='row gx-5'>
              <div class='col-2'>
               <div class='p-3'>
               <h3><span class='badge bg-secondary'>REGISTRO LOGS</span></h3>               
               </div>
              </div>
              <div class='col'>
                <div class='p-3'>
                
                <button type='button' class='btn btn-danger' onclick='limpiarLog()'>Limpiar Registros</button>
                
                </div>
              </div>
            </div>
          </div>";

// show file in table order recenty to oldest
echo "<div class='panel-body'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='table-responsive'>
                            <table class='table table-bordered table-hover shippingList'>
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
<script>

function limpiarLog() {
    // add sweetalert
    (function ($) {

        Swal.fire({
        title: '¿Deseas eliminar todos los registros?<b>',
        text: "Una vez eliminados no se podrán recuperar",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlos!'
        }).then((result) => {
        if (result.isConfirmed) {

      $.ajax({
            url: "admin-ajax.php",
            type: "POST",
            data: {
              action: "delete_logs",
            },

            beforeSend: function () {
              const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
              });

              Toast.fire({
                icon: "info",
                title: "Eliminando registros",
                text: "Espere un momento...",
              });
            },

            success: function (response) {

                Swal.fire({
                    icon: "error",
                    title: "Registros eliminados",
                    text: "",
                    confirmButtonText: "Cerrar",
                  });

                    setTimeout(function () {
                        location.reload();
                    }, 2000);

     
            },
          });



        }
        })


    })(jQuery);

}
</script>

