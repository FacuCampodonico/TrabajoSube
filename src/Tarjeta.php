<?php
namespace TrabajoSube;

class Tarjeta {
    public $saldo;
    public $montoPagado;
    public $montoRestante;

    const TARIFA = 120;
    const CARGAS_VALIDAS = [150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 2000, 2500, 3000, 3500, 4000, 6600];
    const LIMITE_SALDO = 6600;

    public function __construct() {
        $this->saldo = 0;
        $this->montoPagado = 0;
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

    public function contarViajesDelMes() {
        $fechaActual = new \DateTime(); // Obtener la fecha actual
    
        $mesActual = $fechaActual->format('m'); // Obtener el número del mes actual
        $anioActual = $fechaActual->format('Y'); // Obtener el año actual
    
        $viajesDelMes = 0;
    
        // Recorre los boletos registrados y verifica si pertenecen al mes actual
        foreach ($this->boletos as $boleto) {
            $fechaBoleto = $boleto->getFecha();
            $mesBoleto = $fechaBoleto->format('m');
            $anioBoleto = $fechaBoleto->format('Y');
    
            if ($mesBoleto === $mesActual && $anioBoleto === $anioActual) {
                $viajesDelMes++;
            }
        }
    
        return $viajesDelMes;
    }
    

    public function pagarPasaje() {
        $this->montoPagado += self::TARIFA;
        $this->saldo -= self::TARIFA;

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
}
?>


