<?php
namespace TrabajoSube;

class Tarjeta {
    public $saldo;
    public $montoPagado;
    public $montoRestante;
    public $listaViajesDelMes = [];

    const TARIFA = 120;
    const CARGAS_VALIDAS = [150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 2000, 2500, 3000, 3500, 4000, 6600];
    const LIMITE_SALDO = 6600;

    public function __construct() {
        $this->saldo = 0;
        $this->montoPagado = 0;
        $this->listaViajes = [];
    }

    public function cargarSaldo($monto) {
        if (in_array($monto, self::CARGAS_VALIDAS)) {
            $this->saldo += $monto;
            if ($this->saldo > self::LIMITE_SALDO) {
                $this->montoRestante = $this->saldo - self::LIMITE_SALDO;
                $this->saldo = self::LIMITE_SALDO;
            }
            return true;
        }   
        return false;
    }

    public function pagarPasaje() {
        //$this->saldo -= self::TARIFA;

        //if ($this->montoRestante != 0){
        //    $this->cargarSaldo($this->montoRestante);
        //}

        $hoy = new \DateTime();

        if (count($this->listaViajesDelMes) === 0 || $this->listaViajesDelMes[0]->format('Y-m') !== $hoy->format('Y-m') ) {
            // Si es el primer viaje del mes, reiniciar la lista de viajes
            $this->listaViajesDelMes = [new \DateTime()];
            $this->saldo -= self::TARIFA;
            $this->montoPagado += self::TARIFA;
        } elseif (count($this->listaViajesDelMes) > 1 && count($this->listaViajesDelMes) < 29){
            $this->saldo -= self::TARIFA;
            $this->listaViajesDelMes[] = new \DateTime();
            $this->montoPagado += self::TARIFA;
        } elseif (count($this->listaViajesDelMes) >= 29 && count($this->listaViajesDelMes) < 79){
            $this->saldo -= self::TARIFA * 0.8;
            $this->listaViajesDelMes[] = new \DateTime();
            $this->montoPagado += self::TARIFA * 0.8;
        } else {
            $this->saldo -= self::TARIFA * 0.75;
            $this->listaViajesDelMes[] = new \DateTime();
            $this->montoPagado += self::TARIFA * 0.75;
        }

        if ($this->montoRestante != 0){
            $this->cargarSaldo($this->montoRestante);
        }
    }

    public function getMontoPagado() {
        return $this->montoPagado;
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function realizarViajePlus() {
        $this->saldo -= self::TARIFA;
    }


    public function getUltimoViaje() {
    return $this->ultimoViaje;
}

public function tiempoDesdeUltimoViaje() {
    if ($this->ultimoViaje === null) {
        return PHP_INT_MAX; // Devuelve un valor muy grande si no hay un último viaje registrado
    }

    $now = new \DateTime();
    return $now->getTimestamp() - $this->ultimoViaje->getTimestamp();
}

public function actualizarTiempoUltimoViaje() {
    $this->ultimoViaje = new \DateTime();
}

public function pagarPasajeInterUrbano(){

    $hoy = new \DateTime();

    if (count($this->listaViajesDelMes) === 0 || $this->listaViajesDelMes[0]->format('Y-m') !== $hoy->format('Y-m') ) {
        // Si es el primer viaje del mes, reiniciar la lista de viajes
        $this->listaViajesDelMes = [new \DateTime()];
        $this->saldo -= 184;
        $this->montoPagado +=  184;
    } elseif (count($this->listaViajesDelMes) > 1 && count($this->listaViajesDelMes) < 29){
        $this->saldo -=  184;
        $this->listaViajesDelMes[] = new \DateTime();
        $this->montoPagado +=  184;
    } elseif (count($this->listaViajesDelMes) >= 29 && count($this->listaViajesDelMes) < 79){
        $this->saldo -=  184 * 0.8;
        $this->listaViajesDelMes[] = new \DateTime();
        $this->montoPagado +=  184 * 0.8;
    } else {
        $this->saldo -=  184 * 0.75;
        $this->listaViajesDelMes[] = new \DateTime();
        $this->montoPagado +=  184 * 0.75;
    }

    if ($this->montoRestante != 0){
        $this->cargarSaldo($this->montoRestante);
    }
}

}
?>


