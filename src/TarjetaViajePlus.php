<?php

namespace TrabajoSube; // Agregamos el espacio de nombres


class TarjetaViajePlus {
    private $saldo;

    public function __construct($saldoInicial) {
        $this->saldo = $saldoInicial;
    }

    public function cargarSaldo($monto) {
        $this->saldo += $monto;
    }

    public function realizarViaje() {
        if ($this->saldo >= 211.84) {
            $this->saldo -= 211.84;
        } else {
            throw new \Exception("Saldo Insuficiente para realizar un viaje plus.");
        }
    }

    public function getSaldo() {
        return $this->saldo;
    }
}
?>

