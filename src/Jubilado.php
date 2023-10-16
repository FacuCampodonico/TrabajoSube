<?php
namespace TrabajoSube;

class Jubilado extends Tarjeta {
    public $ultimoViaje = 0; // Guarda el timestamp del último viaje
    public $listaViajes = [];

    public function pagarPasaje() {
        // Verificamos el tiempo transcurrido desde el último viaje
        $hoy = new \DateTime();

         // Verificar si el día actual es un día hábil (Lunes a Viernes) y está dentro de la franja horaria
         if ($hoy->format('N') >= 1 && $hoy->format('N') <= 5 && $hoy->format('H') >= 6 && $hoy->format('H') < 22) {
        if (count($this->listaViajes) === 0 || $this->listaViajes[0]->format('Y-m-d') !== $hoy->format('Y-m-d') ) {
            // Si es el primer viaje del día, reiniciar la lista de viajes
            $this->listaViajes = [new \DateTime()];
            $this->actualizarTiempoUltimoViaje();
        } elseif (count($this->listaViajes) < 2){
            $this->actualizarTiempoUltimoViaje();
            $this->listaViajes[] = new \DateTime();
        } else {
            $this->saldo -= self::TARIFA;
            $this->actualizarTiempoUltimoViaje();
            $this->listaViajes[] = new \DateTime();
        }
    }
}

    public function realizarViajePlus() {
        $this->saldo -= self::TARIFA;
    }

    public function tiempoDesdeUltimoViaje() {
        return time() - $this->ultimoViaje;
    }

    public function actualizarTiempoUltimoViaje() {
        $this->ultimoViaje = time();
    }
}
