<?php
namespace TrabajoSube;
date_default_timezone_set('America/Argentina/Buenos_Aires'); // configuro la zona horaria, para que me registre bien la fecha

class MedioBoleto extends Tarjeta {
    public $mitadTarifa;
    public $ultimoViaje = 0; // Guarda el timestamp del último viaje
    public $listaViajes = [];
    public $hoy;

    public function __construct() {
        parent::__construct(); // utiliza el constructor del padre
        $this->mitadTarifa = self::TARIFA / 2;
        $this->hoy = new \DateTime();
    }

    public function pagarPasaje() {
        //$hoy = new \DateTime();
        // Verificamos el tiempo transcurrido desde el último viaje

        if ($this->diaDeSemanaEntre6amY10pm()){
            if ($this->tiempoDesdeUltimoViaje() < 300) { // 300 segundos = 5 minutos
                throw new \Exception("Debes esperar al menos 5 minutos antes de realizar otro viaje.");
            } else {
                if (count($this->listaViajes) === 0 || $this->listaViajes[0]->format('Y-m-d') !== $this->hoy->format('Y-m-d') ) {
                    // Si es el primer viaje del día, reiniciar la lista de viajes
                    $this->listaViajes = [new \DateTime()];
                    $this->saldo -= $this->mitadTarifa;
                    $this->actualizarTiempoUltimoViaje();
                } elseif (count($this->listaViajes) < 4){
                    $this->saldo -= $this->mitadTarifa;
                    $this->actualizarTiempoUltimoViaje();
                    $this->listaViajes[] = new \DateTime();
                } else {
                    $this->saldo -= self::TARIFA;
                    $this->actualizarTiempoUltimoViaje();
                    $this->listaViajes[] = new \DateTime();
                }
            }
        } else {
            throw new \Exception("No se encuentra en el intervalo de tiempo permitido para utilizar la tarjeta.");
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

    public function diaDeSemanaEntre6amY10pm(){
        //$hoy = new \DateTime();

        // Verificar si es un día de la semana (de lunes a viernes)
        $esDiaDeSemana = $this->hoy->format('N') >= 1 && $this->hoy->format('N') <= 5;

        $hora = $this->hoy->format('H'); // Obtener la hora en formato de 24 horas
        $horaValida = $hora >= 6 && $hora <= 22;

        return $esDiaDeSemana && $horaValida;
    }

    public function pagarPasajeInterUrbano() {
        //$hoy = new \DateTime();
        // Verificamos el tiempo transcurrido desde el último viaje

        if ($this->diaDeSemanaEntre6amY10pm()){
            if ($this->tiempoDesdeUltimoViaje() < 300) { // 300 segundos = 5 minutos
                throw new \Exception("Debes esperar al menos 5 minutos antes de realizar otro viaje.");
            } else {
                if (count($this->listaViajes) === 0 || $this->listaViajes[0]->format('Y-m-d') !== $this->hoy->format('Y-m-d') ) {
                    // Si es el primer viaje del día, reiniciar la lista de viajes
                    $this->listaViajes = [new \DateTime()];
                    $this->saldo -= 92;
                    $this->actualizarTiempoUltimoViaje();
                } elseif (count($this->listaViajes) < 4){
                    $this->saldo -= 92;
                    $this->actualizarTiempoUltimoViaje();
                    $this->listaViajes[] = new \DateTime();
                } else {
                    $this->saldo -= 184;
                    $this->actualizarTiempoUltimoViaje();
                    $this->listaViajes[] = new \DateTime();
                }
            }
        } else {
            throw new \Exception("No se encuentra en el intervalo de tiempo permitido para utilizar la tarjeta.");
        }
    }
}

