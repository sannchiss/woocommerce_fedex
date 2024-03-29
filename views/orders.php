<?php
include PLUGIN_DIR_PATH . 'views/modal/orderItems.php';


if (!defined('ABSPATH')) {
    die();
}

// get all orders by shipping FedEx
$orders = wc_get_orders(array(    
    'limit' => -1,
    'shipping_method' => 'FedEx Express',
    'orderby' => 'date',
    'order' => 'DESC',
));
?>
<div class="container-xxl">

    <nav class="navbar bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://assets.turbologo.com/blog/es/2019/12/19132841/Fedex-logo.png" alt="" width="100"
                    height="60">
            </a>

        </div>
    </nav>

    <div class="d-flex justify-content-end">

        <div class="row ">
            <div class="col-md-12 mb-5">
    
                <div class="float-right">
                    <button type="button" class="btn btn-primary btn-sm confirmSend">
                        Confirma Entrega
                        <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <!--icon add-->
    
                </div>
            </div>
        </div>

    </div>

    <div class="panel">

        <div class="panel-body">


                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover shippingList">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Ciudad</th>
                                    <th>Cod.Estado</th>
                                    <th>Orden</th>
                                    <th>Flete</th>
                                    <th>Items</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                        foreach ($orders as $order) {
                                            // get name rate shipping
                                            $rate = $order->get_shipping_method();
                                        if ($rate == 'FedEx Express') { ?>
                                <tr>
                                    <td><?php echo '<span class="badge bg-light text-dark">'.$i.'</span>' ?>
                                    </td>
                                    <td><?php echo '<span class="badge bg-light text-dark">'.$order->get_date_created()->date('d/m/Y'). '</span>' ?>
                                    </td>
                                    <td><?php echo '<span class="badge bg-light text-dark">'. $order->get_billing_first_name(). " " .$order->get_billing_last_name().'</span>'
                                            ?>
                                    </td>
                                    <td><?php echo '<span class="badge bg-light text-dark">'.$order->get_billing_city() != null ? $order->get_billing_city() : get_post_meta($order->get_order_number(), '_billing_comuna', true). '</span>' ?>
                                    </td>
                                    <td><?php echo '<span class="badge bg-light text-dark">'.$order->get_billing_state(). '</span>' ?>
                                    </td>
                                    <td><?php echo '<span class="badge bg-light text-dark">'.$order->get_order_number(). '</span>' ?>
                                    </td>
                                    <td><?php echo '<span class="badge bg-light text-dark">'.get_post_meta($order->get_order_number(), '_order_shipping', true). ' $</span>' ?>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                            <button type="button"
                                                class="btn btn-primary position-relative btn-xs itemsOrder"
                                                data-bs-toggle="modal" data-bs-target="#modal-itemsOrder"
                                                data-order="<?php echo $order->get_order_number(); ?>">
                                                Orden
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                    <?php echo $order->get_item_count(); ?>
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                    <td><?php
                                            
                                            if($order->get_status() == 'pending'){
                                                echo '<span class="badge bg-warning">Pendiente</span>';
                                            }elseif($order->get_status() == 'processing'){
                                                echo '<span class="badge bg-success">Procesado</span>';
                                            }elseif($order->get_status() == 'on-hold'){
                                                echo '<span class="badge bg-primary">En espera</span>';
                                            }elseif($order->get_status() == 'completed'){
                                                echo '<span class="badge bg-success">Completado</span>';
                                            }elseif($order->get_status() == 'cancelled'){
                                                echo '<span class="badge bg-danger">Cancelado</span>';
                                            }elseif($order->get_status() == 'refunded'){
                                                echo '<span class="badge bg-danger">Reembolsado</span>';
                                            }elseif($order->get_status() == 'failed'){
                                                echo '<span class="badge bg-danger">Fallido</span>';
                                            }elseif($order->get_status() == 'procesado-fedex'){
                                                echo '<span class="badge bg-primary"><i class="fas fa-shipping-fast"></i> Procesando con FedEx</span>';                                               
                                            }elseif($order->get_status() == 'confirmado-fedex'){
                                                echo '<span class="badge bg-success"><i class="fas fa-shipping-fast"></i> Enviado con FedEx</span>';
                                            }else{
                                                echo '<span class="badge bg-success"><i class="fas fa-shipping-fast"></i>'.$order->get_status().'</span>';
                                            }?>
                                             </td>
                                    <td>
                                            <?php if($order->get_status() == STATUS_CREATE_ORDER ){?>

                                        <div class="btn-group btn-group-sm" role="group" aria-label="...">

                                            <button type="button" class="btn btn-primary printOneLabel"
                                                data-order="<?php echo $order->get_order_number(); ?>"><i
                                                    class="fas fa-print"></i></button>

                                            <button type="button" class="btn btn-danger delete_shipping"
                                                data-order="<?php echo $order->get_order_number(); ?>"><i
                                                    class="fas fa-trash"></i></button>

                                            <input type="checkbox" name="checkOrden[]"
                                                id=<?php echo "checkOrden".$order->get_order_number(); ?>
                                                value="<?php echo $order->get_order_number(); ?>" class="btn-check"
                                                id="btn-check-outlined" autocomplete="off">
                                            <label class="btn btn-outline-primary"
                                                for=<?php echo "checkOrden".$order->get_order_number(); ?>>Confirmar</label>

                                            <?php }elseif($order->get_status() == STATUS_CONFIRM_ORDER){ ?>
                                              
                                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                            <button type="button" class="btn btn-warning trackShipment"
                                                data-order="<?php echo $order->get_order_number(); ?>">
                                                <i class="fas fa-truck"></i>                                                
                                                </button>

                                                <button type="button" class="btn btn-primary printOneLabel"
                                                data-order="<?php echo $order->get_order_number(); ?>"><i
                                                    class="fas fa-print"></i></button>

                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php  $i++; }  } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>