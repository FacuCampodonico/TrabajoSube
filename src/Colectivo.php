<?php
namespace TrabajoSube;

class Colectivo {
    public $linea;
    public $esInterurbana; // Nueva propiedad
    const TARIFA = 120; // Definimos la tarifa como constante

    public function __construct($linea, $esInterurbana) {
        $this->linea = $linea;
        $this->esInterurbana = $esInterurbana; // si es interurbana o no
    }

    
    public function pagarCon($tarjeta, $fecha) {

        $tarifa = $colectivo->tipoLinea === 'interurbana' ? 184 : 120; // se le asigna el valor a la tarifa dependiendo si es inter o no

        if ($tarjeta->getSaldo() >= self::TARIFA) {
            // Realizamos el viaje y actualizamos el tiempo del último viaje
            $tarjeta->pagarPasaje(self::TARIFA);
            $tarjeta->actualizarTiempoUltimoViaje();

            return new Boleto($this, $tarjeta, $fecha, self::TARIFA, $tarjeta->getSaldo());
        } else {
            if ($tarjeta->getSaldo() <= self::TARIFA) {
                if (($tarjeta->getSaldo() - self::TARIFA) >= (-240)) {
                    $tarjeta->realizarViajePlus();
                    return new Boleto($this, $tarjeta, $fecha, self::TARIFA, $tarjeta->getSaldo());
                } else {
                    throw new \Exception("Saldo insuficiente para realizar un viaje plus.");
                }
            }
        }
    }


    public function getLinea() {
        return $this->linea;
    }
}
