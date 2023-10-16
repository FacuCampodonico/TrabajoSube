<?php

namespace TrabajoSube;

use PHPUnit\Framework\TestCase;

class FranquiciaTest extends TestCase {

    public function testDiscountsRetiree() {
        $tarjetaRetiree = new Jubilado();
        $tarjetaRetiree->saldo = 1000;
        $tarjetaRetiree->pagarPasaje();
        $this->assertEquals($tarjetaRetiree->getSaldo(), 1000);
    }

    public function testDiscountsFreeTicket() {
        $freeTicket = new BoletoGratuito();
        $freeTicket->saldo = 1000;
        $freeTicket->pagarPasaje();
        $this->assertEquals($freeTicket->getSaldo(), 1000);
    }           

    public function testDiscountsHalfTicket() {
        $halfTicket = new MedioBoleto();
        $halfTicket->saldo = 1000;
        $halfTicket->pagarPasaje();
        $this->assertEquals($halfTicket->getSaldo(), 940);
    }

    public function testMedioBoletoMinimumIntervalBetweenTrips() {
        $colectivo = new Colectivo('Linea 1','normal');
        $tarjeta = new MedioBoleto();
        $tarjeta->cargarSaldo(600);
        $fecha1 = '1.1.1';
        $fecha2 = '1.1.1';
    
        $colectivo->pagarCon($tarjeta, $fecha1);
    
        // Intentar pagar otro viaje en menos de 5 minutos debe lanzar una excepción
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Debes esperar al menos 5 minutos antes de realizar otro viaje.");
        $colectivo->pagarCon($tarjeta, $fecha2);
        
    }
    
    public function testMedioBoletoMaximumTripsPerDay() {
        $colectivo = new Colectivo('Linea 1','normal');
        $tarjeta = new MedioBoleto();
        $fecha = '1.1.1';
        $tarjeta->cargarSaldo(600);
    
        // simulamos que hacemos 4 viajes, expandiendo la lista de viajes, ya que sino nos tirar el erros de que no pasaron los 5 minutos
        for ($i = 0; $i < 4; $i++) {
            $tarjeta->listaViajes[] = new \DateTime();
        }
    
        $saldo = $tarjeta->getSaldo();
        $colectivo->pagarCon($tarjeta, $fecha);
        $this->assertEquals($saldo - $tarjeta->getSaldo(), 120);
    }

    public function testDosBoletosGratuitosPorDia() {
        $colectivo = new Colectivo('Linea 1','normal');
        $tarjeta = new BoletoGratuito();
        $fecha = '1.1.1';
        $tarjeta->cargarSaldo(600);
    
        // realizamos dos viajes
        for ($i = 0; $i < 2; $i++) {
            $colectivo->pagarCon($tarjeta, $fecha);
            $this->assertEquals($tarjeta->getSaldo(), 600); 
            //verificamos que pagando dos el mismo dia no se reste nada del saldo
        }
    
        //cuando ya se realizaron los dos viajes que podia hacer en un dia, verifico que se le haya restado el saldo normal
        $saldo = $tarjeta->getSaldo();
        $colectivo->pagarCon($tarjeta, $fecha);
        $this->assertEquals($saldo - $tarjeta->getSaldo(), 120);    
    }

    public function testDosBoletosJubiladosPorDia() {
        $colectivo = new Colectivo('Linea 1','normal');
        $tarjeta = new Jubilado();
        $fecha = '1.1.1';
        $tarjeta->cargarSaldo(600);
    
        // realizamos dos viajes
        for ($i = 0; $i < 2; $i++) {
            $colectivo->pagarCon($tarjeta, $fecha);
            $this->assertEquals($tarjeta->getSaldo(), 600);
            //verificamos que pagando dos el mismo dia no se reste nada del saldo
        }
    
        //cuando ya se realizaron los dos viajes que podia hacer en un dia, verifico que se le haya restado el saldo normal
        $saldo = $tarjeta->getSaldo();
        $colectivo->pagarCon($tarjeta, $fecha);
        $this->assertEquals($saldo - $tarjeta->getSaldo(), 120);    
    }

    /*public function testCargaExceso() {
        $tarjeta = new Tarjeta();
        $tarjeta->cargarSaldo(8000);

        $this->assertEquals($tarjeta->getSaldo(), 6600);
        $this->assertEquals($tarjeta->montoRestante, 8000-6600);
    }*/ //HAY QUE VER COMO HACER PORQUE 8000 NO ES UNA CARGA VALIDA, POR ESO TIRA ERROR

    public function testCargandoSaldoDemas() {
        $tarjeta = new Tarjeta();
        $tarjeta->cargarSaldo(6600);

        $this->assertEquals($tarjeta->getSaldo(), 6600);

        //como el saldo ya tiene la carga maxima, verifico que lo que quiera cargar sea el montoRestante
        $tarjeta->cargarSaldo(3500);
        $this->assertEquals($tarjeta->montoRestante, 3500);

        //pago un pasaje, para que se le reste 120, cuando se le resta 120 le quedan 120 libres en el saldo para cargar y los carga
        //luego verifico que en montoRestante se le hayan restado 120
        $tarjeta->pagarPasaje();
        $this->assertEquals($tarjeta->montoRestante, 3500-120);
    }

}
