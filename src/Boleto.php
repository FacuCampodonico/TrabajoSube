<?php
namespace TrabajoSube;

class Boleto {
    public $colectivo;
    public $tarjeta;
    public $fecha; // Fecha y hora del viaje
    public $montoPagado;
    public $saldoRestante;

    public function __construct($colectivo, $tarjeta, $fecha, $montoPagado, $saldoRestante) {
        $this->colectivo = $colectivo;
        $this->tarjeta = $tarjeta;
        $this->fecha = $fecha;
        $this->montoPagado = $montoPagado;
        $this->saldoRestante = $saldoRestante;
    }

    public function getColectivo(){
        return $this->colectivo;
    }

    public function getTarjeta(){
        return $this->tarjeta;
    }

    public function getFecha(){
        return $this->fecha;
    }

    public function getMontoPagado(){
        return $this->montoPagado;
    }

    public function getSaldoRestante(){
        return $this->saldoRestante;
    }

    public function obtenerInfo() {
        $descripcion = $this->montoPagado < 0 ? "Abona saldo " . abs($this->montoPagado) : "Total pagado: $" . $this->montoPagado;
        return "Fecha: " . $this->fecha . "\n" .
               "Tipo de tarjeta: " . get_class($this->tarjeta) . "\n" .
               "Colectivo: Línea " . $this->colectivo->getLinea() . ", Empresa: " . $this->colectivo->getEmpresa() . "\n" .
               $descripcion . "\n" .
               "Saldo restante: $" . $this->saldoRestante;
    }
}
