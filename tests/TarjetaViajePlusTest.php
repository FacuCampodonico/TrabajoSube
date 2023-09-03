<?php
namespace TrabajoSube;

require 'src\TarjetaViajePlus.php';

use PHPUnit\Framework\TestCase;

class TarjetaViajePlusTest extends TestCase {
    public function testDosViajesPlus() {
        $tarjeta = new TarjetaViajePlus(0);

        try {
            $tarjeta->realizarViaje();
            $tarjeta->realizarViaje();
        } catch (\Exception $e) {
            // Validamos que se haya lanzado la excepción de saldo insuficiente
            $this->assertEquals("Saldo Insuficiente para realizar un viaje plus.", $e->getMessage());
            return; // Salimos de la prueba si la excepción fue lanzada
        }

        // Si no se lanzó la excepción, la prueba falla
        $this->fail("La excepción de saldo insuficiente no fue lanzada.");
    }

    public function testDescuentoViajePlus() {
        $tarjeta = new TarjetaViajePlus(300);

        $tarjeta->realizarViaje();
        $saldoDespuesViaje = $tarjeta->getSaldo();

        // Validamos que el saldo se descuente correctamente luego de otorgar el viaje plus.
        $this->assertEquals(88.16, $saldoDespuesViaje, '', 0.01); // Usamos una tolerancia de 0.01 debido a posibles errores de redondeo
    }
}
