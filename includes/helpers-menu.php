<?php

function addMenusPanel( $menus ){

    if( is_array($menus) ){ // Si es un array

        for ( $i= 0; $i < count($menus); $i++ ) { 
            /*  add_menu_page():Nos permite crear el menú en el panel de administración de Wordpress y agregarle un HTML especifico
                add_menu_page():recibe 7 parametros
                1: TITULO DE LA PAGINA,
                2: TITULO DE LA PAGINA,
                3: LAS CAPACIDADES NECESARIAS PARA PORDER VER ESTE MENÚ
                4: EL SLUG QUE VA APARECER EN LA URL
                5: LA FUNCION QUE VAMOS A UTILIZAR PARA MOSTRAR TODO LO QUE ES EL HTML
                6: URL O LA RUTA DONDE VAMOS A TENER EL ICONO QUE VA TENER EL MENÚ
                7: EL LA POSICIÓN QUE VA A TENER EL MENÚ

 */
            add_menu_page(
                $menus[$i]['pageTitle'],
                $menus[$i]['menuTitle'],
                $menus[$i]['capability'],
                $menus[$i]['menuSlug'],
                $menus[$i]['functionName'],
                $menus[$i]['iconUrl'],
                $menus[$i]['position']

            );
            
        }


    }

}


function addSubmenusPanel( $submenus ){


    if(is_array( $submenus )){
        

        for ($i=0; $i < count( $submenus ); $i++) { 
            # code...


            add_submenu_page(
              $submenus[$i]['parent_slug'],     
              $submenus[$i]['page_title'],     
              $submenus[$i]['menu_title'],     
              $submenus[$i]['capabality'],     
              $submenus[$i]['menu_slug'],     
              $submenus[$i]['functionName']   

            );    



        }

    }

}

