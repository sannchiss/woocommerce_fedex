<?php
if (!defined('ABSPATH')) {
    die();
}

require_once PLUGIN_DIR_PATH . 'required/confirmedShipments.php';

$reponse = new confirmedShipments();
$retreats = $reponse->index();
?>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h1></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading mb-3">
                    <h3 class="panel-title">Ordenes de retiro</h3>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover shippingList">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>N° Manifiesto</th>                                            
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($retreats as $retreat) {?>
                                        <tr>
                                            <td><?php echo '<span class="badge bg-light text-dark">'.$i.'</span>' ?>
                                            </td>
                                            <td><?php echo '<span class="badge bg-light text-dark">'.$retreat['manifestDate']. '</span>' ?>
                                            </td>
                                            <td><?php echo '<span class="badge bg-light text-dark">'. $retreat['manifestNumber'].'</span>'?>
                                            </td>
                                            <td><?php echo '<span class="badge bg-light text-dark">Asignado</span>' ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary printManifest" data-manifest=<?php echo $retreat['manifestNumber']; ?>><i class="fas fa-print"></i></button>
                                            </td>                                            

                                        </tr>
                                        <?php $i++; }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>