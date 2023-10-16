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

        $tarifa = $this->tipoLinea === 'interurbana' ? 184 : 120;
        
        $viajesRealizados = $tarjeta->contarViajesDelMes();
    
        // Calcula el descuento basado en la cantidad de viajes
        if ($viajesRealizados >= 1 && $viajesRealizados <= 29) {
            $descuento = 0; // Sin descuento
        } elseif ($viajesRealizados >= 30 && $viajesRealizados <= 79) {
            $descuento = $tarifa * 0.20; // 20% de descuento
        } else {
            $descuento = $tarifa * 0.25; // 25% de descuento
        }
    
        $tarifaConDescuento = $tarifa - $descuento;
    
        if ($tarjeta->getSaldo() >= $tarifaConDescuento) {
            // Realiza el viaje con descuento
            $tarjeta->pagarPasaje($tarifaConDescuento);
            $tarjeta->actualizarTiempoUltimoViaje();
    
            return new Boleto($this, $tarjeta, $fecha, $tarifaConDescuento, $tarjeta->getSaldo());
        } else {
            // Realiza un viaje plus si no hay saldo suficiente
            if ($tarjeta->getSaldo() >= $tarifa) {
                $tarjeta->realizarViajePlus();
                return new Boleto($this, $tarjeta, $fecha, $tarifa, $tarjeta->getSaldo());
            } else {
                throw new \Exception("Saldo insuficiente para realizar un viaje plus.");
            }
        }
    }
    

    public function getLinea() {
        return $this->linea;
    }
}
