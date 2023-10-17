<?php
namespace TrabajoSube;

use PHPUnit\Framework\TestCase;

class TarjetaViajePlusTest extends TestCase {
    public function testDosViajesPlus() {
        // Creo una tarjeta nueva (comienza con el saldo en 0, es decir, va a tener dos viajes plus)
        $tarjeta = new Tarjeta();
        $colecuop = new Colectivo('linea 115','no');
        $fecha = '1.1.1';
        
        // Pago 2 pasajes con saldo en 0 y verifico que ambos se pagan con el viaje plus
        $colecuop->pagarCon($tarjeta,$fecha);
        $this->assertEquals($tarjeta->getSaldo(),-120);
        $colecuop->pagarCon($tarjeta,$fecha);
        $this->assertEquals($tarjeta->getSaldo(),-240);
        // Agoté los saldos plus que tenía disponibles (2)

        // Intento pagar el tercero, ya con dos viajes plus en negativo y verifico que no me deja
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Saldo insuficiente para realizar un viaje plus.");
        $colecuop->pagarCon($tarjeta,$fecha);
    }

    public function testDescuentoViajePlus() {
        $tarjeta = new Tarjeta();

        // Realizamos un viaje plus
        $tarjeta->realizarViajePlus();

        // Verificamos que el saldo después del viaje plus sea correcto
        $this->assertEquals($tarjeta->getSaldo(), -120); // El saldo debe ser -120 después del viaje plus

        // Realizamos un segundo viaje plus
        $tarjeta->realizarViajePlus();

        // Verificamos que el saldo después del segundo viaje plus sea correcto
        $this->assertEquals($tarjeta->getSaldo(), -240); // El saldo debe ser -240 después del segundo viaje plus
    }
    public function testJubiladoDiscountsOnViajePlus() {
        $tarjetaRetiree = new Jubilado();
        $tarjetaRetiree->saldo = 1000;

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjetaRetiree->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjetaRetiree->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjetaRetiree->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM

        $tarjetaRetiree->realizarViajePlus();
        $this->assertEquals($tarjetaRetiree->getSaldo(), 880); // El saldo debe ser 880 después del viaje plus
    }

    public function testMedioBoletoDiscountsOnViajePlus() {
        $halfTicket = new MedioBoleto();
        $halfTicket->saldo = 1000;

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $halfTicket->hoy = new \DateTime(); // Crear un objeto DateTime
        $halfTicket->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $halfTicket->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM

        $halfTicket->realizarViajePlus();
        $this->assertEquals($halfTicket->getSaldo(), 880); // El saldo debe ser 880 después del viaje plus
    }

    public function testBoletoGratuitoDiscountsOnViajePlus() {
        $freeTicket = new BoletoGratuito();
        $freeTicket->saldo = 1000;

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $freeTicket->hoy = new \DateTime(); // Crear un objeto DateTime
        $freeTicket->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $freeTicket->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM

        $freeTicket->realizarViajePlus();
        $this->assertEquals($freeTicket->getSaldo(), 880); // El saldo debe ser 880 después del viaje plus
    }
}
