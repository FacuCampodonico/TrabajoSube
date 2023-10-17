<?php

namespace TrabajoSube;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

    public function testGetLinea() {
        $cole = new Colectivo(103,'no');
        $this->assertEquals($cole->getLinea(), 103);
    }

    public function testComprarTarjeta() {
        $colectivo = new Colectivo('Linea 1','no');
        $tarjeta = new Tarjeta();
        $fecha = '1.1.1';

        $boleto = $colectivo->pagarCon($tarjeta, $fecha);

        $this->assertInstanceOf(Boleto::class, $boleto);
    }

    public function testPagarWithSufficientBalance() {
        $colectivo = new Colectivo('Linea 1','no');
        $tarjeta = new Tarjeta();
        $fecha = '1.1.1';

        $tarjeta->cargarSaldo(Tarjeta::TARIFA);

        $boleto = $colectivo->pagarCon($tarjeta, $fecha);

        $this->assertInstanceOf(Boleto::class, $boleto);
        $this->assertInstanceOf(Colectivo::class, $boleto->getColectivo());
        $this->assertInstanceOf(Tarjeta::class, $boleto->getTarjeta());
        $this->assertEquals($boleto->getFecha(), '1.1.1');
    }

    public function testPagarWithInsufficientBalance() {
        $colectivo = new Colectivo('Linea 1','no');
        $tarjeta = new Tarjeta();
        $fecha = '1.1.1';

        $boleto = $colectivo->pagarCon($tarjeta, $fecha);

        $this->assertInstanceOf(Boleto::class, $boleto);
        $this->assertInstanceOf(Colectivo::class, $boleto->getColectivo());
        $this->assertInstanceOf(Tarjeta::class, $boleto->getTarjeta());
        $this->assertEquals($boleto->getFecha(), '1.1.1');
    }

    public function testBoleto() {
        $colectivo = 115;
        $tarjeta = '4hk32h4k3h2h4';
        $fecha = '24.11.23';
        $montoPagado = 120;
        $saldoRestante= 100;

        $boleto = new Boleto($colectivo, $tarjeta, $fecha, $montoPagado, $saldoRestante);

        $this->assertEquals($boleto->getColectivo(), 115);
        $this->assertEquals($boleto->getTarjeta(), '4hk32h4k3h2h4');
        $this->assertEquals($boleto->getFecha(), '24.11.23');
    }

    public function testCargas() {
        $cargasValidas = [150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 2000, 2500, 3000, 3500, 4000];

        foreach ($cargasValidas as $carga) {
            $tarjeta = new Tarjeta();
            $result = $tarjeta->cargarSaldo($carga);

            $this->assertTrue($result);
            $this->assertEquals($tarjeta->getSaldo(), $carga);
        }
    }

    public function testCargarValidBalance() {
        $tarjeta = new Tarjeta();
        $result = $tarjeta->cargarSaldo(150);

        $this->assertTrue($result);
        $this->assertEquals($tarjeta->getSaldo(), 150);
    }

    public function testCargarInvalidBalance() {
        $tarjeta = new Tarjeta();
        $result = $tarjeta->cargarSaldo(100);

        $this->assertFalse($result);
        $this->assertEquals($tarjeta->getSaldo(), 0);
    }

    public function testDiscountPass() {
        $colectivo = new Colectivo('Linea 1','no');
        $tarjeta = new Tarjeta();
        $fecha = '1.1.1';

        $tarjeta->cargarSaldo(150);

        $boleto = $colectivo->pagarCon($tarjeta, $fecha);

        $this->assertEquals($tarjeta->getSaldo(), 30);
        $this->assertEquals($tarjeta->getMontoPagado(), 120);
    }
    public function testGetFecha() {
        // Crea un Boleto con datos de ejemplo
        $boleto = new Boleto(115, '4hk32h4k3h2h4', '24.11.23', 120, 100);

        // Comprueba si la fecha obtenida es igual a la fecha de ejemplo
        $this->assertEquals($boleto->getFecha(), '24.11.23');
    }

    public function testGetMontoPagado() {
        // Crea un Boleto con datos de ejemplo
        $boleto = new Boleto(115, '4hk32h4k3h2h4', '24.11.23', 120, 100);

        // Comprueba si el monto pagado obtenido es igual al monto de ejemplo
        $this->assertEquals($boleto->getMontoPagado(), 120);
    }

    public function testGetSaldoRestante() {
        // Crea un Boleto con datos de ejemplo
        $boleto = new Boleto(115, '4hk32h4k3h2h4', '24.11.23', 120, 100);

        // Comprueba si el saldo restante obtenido es igual al saldo de ejemplo
        $this->assertEquals($boleto->getSaldoRestante(), 100);
    }
}
