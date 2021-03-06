<?php
/**
 * @author Raúl Caro Pastorino
 * @link http://www.fryntiz.es
 * @copyright Copyright (c) 2018 Raúl Caro Pastorino
 * @license https://www.gnu.org/licenses/gpl-3.0-standalone.html
**/

namespace app\helpers;

use DateTime;


class Fechas
{
    /*
    * Recibe 1 o dos cadenas con forma de fecha y devuelve la diferencia
    * @param String $fecha1 Fecha más baja del rango.
    * @param String $fecha2 Fecha más alta del rango (Es opcional, en su
    * ausencia se entenderá que es la fecha actual).
    */
    public static function calcularDiferencia($fecha1, $fecha2 = 'now')
    {
        $fecha1 = new DateTime($fecha1);
        $fecha2 = new DateTime($fecha2);

        return date_diff($fecha1, $fecha2)->format('%y');
    }
}
