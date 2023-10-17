<?php
namespace TrabajoSube;

class Colectivo {
    public $linea;
    public $esInterUrbano;
    const TARIFA = 120; // Definimos la tarifa como constante

    public function __construct($linea,$esInterUrbano) {
        $this->linea = $linea;
        $this->esInterUrbano = $esInterUrbano;
    }

    
    public function pagarCon($tarjeta, $fecha) {
        if ($tarjeta->getSaldo() >= self::TARIFA) {
            // Realizamos el viaje y actualizamos el tiempo del último viaje
            if($this->esInterUrbano == 'si'){
                $tarjeta->pagarPasajeInterUrbano();
                $tarjeta->actualizarTiempoUltimoViaje();
            } else{
                $tarjeta->pagarPasaje(self::TARIFA);
                $tarjeta->actualizarTiempoUltimoViaje();
            }

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
