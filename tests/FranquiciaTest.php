<?php

namespace TrabajoSube;

use PHPUnit\Framework\TestCase;

class FranquiciaTest extends TestCase {

    public function testDiscountsRetiree() {
        $tarjetaRetiree = new Jubilado();
        $tarjetaRetiree->saldo = 1000;

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjetaRetiree->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjetaRetiree->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjetaRetiree->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM

        $tarjetaRetiree->pagarPasaje();
        $this->assertEquals($tarjetaRetiree->getSaldo(), 1000);
    }

    public function testDiscountsFreeTicket() {
        $freeTicket = new BoletoGratuito();
        $freeTicket->saldo = 1000;

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $freeTicket->hoy = new \DateTime(); // Crear un objeto DateTime
        $freeTicket->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $freeTicket->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM

        $freeTicket->pagarPasaje();
        $this->assertEquals($freeTicket->getSaldo(), 1000);
    }           

    public function testDiscountsHalfTicket() {
        $halfTicket = new MedioBoleto();
        $halfTicket->saldo = 1000;

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $halfTicket->hoy = new \DateTime(); // Crear un objeto DateTime
        $halfTicket->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $halfTicket->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM
         
        $halfTicket->pagarPasaje();
        $this->assertEquals($halfTicket->getSaldo(), 940);
    }

    public function testMedioBoletoMinimumIntervalBetweenTrips() {
        $colectivo = new Colectivo('Linea 1','no');
        $tarjeta = new MedioBoleto();
        $tarjeta->cargarSaldo(600);
        $fecha1 = '1.1.1';
        $fecha2 = '1.1.1';

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjeta->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjeta->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjeta->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM
    
        $colectivo->pagarCon($tarjeta, $fecha1);
    
        // Intentar pagar otro viaje en menos de 5 minutos debe lanzar una excepción
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Debes esperar al menos 5 minutos antes de realizar otro viaje.");
        $colectivo->pagarCon($tarjeta, $fecha2);
        
    }
    
    public function testMedioBoletoMaximumTripsPerDay() {
        $colectivo = new Colectivo('Linea 1','no');
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
        $colectivo = new Colectivo('Linea 1','no');
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
        $colectivo = new Colectivo('Linea 1','no');
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

    public function testDescuentoPorDia() {
        $tarjeta = new Tarjeta();

        // Cargar suficiente saldo
        $tarjeta->cargarSaldo(6600);

        // Realizar 29 viajes
        for ($i = 0; $i < 29; $i++) {
            $tarjeta->pagarPasaje();
        }

        // Realizar el viaje 30, debería aplicarse un descuento del 20%
        $saldo = $tarjeta->getSaldo();
        $tarjeta->pagarPasaje();
        $this->assertEquals($saldo - $tarjeta->getSaldo(), Tarjeta::TARIFA * 0.8);

        $tarjeta->cargarSaldo(6600); //cargo mas saldo
        for ($i = 0; $i < 50; $i++) {
            $tarjeta->pagarPasaje();
        }

        // Realizar el viaje 80, debería aplicarse un descuento del 25%
        $saldo = $tarjeta->getSaldo();
        $tarjeta->pagarPasaje();
        $this->assertEquals($saldo - $tarjeta->getSaldo(), Tarjeta::TARIFA * 0.75);
    }

    public function testFuncionDiaDeSemanaEnHora(){
        $tarjeta = new MedioBoleto();

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjeta->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjeta->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjeta->hoy->setTime(9, 0, 0); // Establecer la hora a las 9:00 AM

        $this->assertTrue($tarjeta->diaDeSemanaEntre6amY10pm());
    }  
    
    public function testFuncionDiaFueraDeSemanaEnHora(){
        $tarjeta = new MedioBoleto();

        // hardcodeamos la fecha y hora para que el test no tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjeta->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjeta->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjeta->hoy->setTime(5, 0, 0); // Establecer la hora a las 9:00 AM

        $this->assertFalse($tarjeta->diaDeSemanaEntre6amY10pm());
    } 

    public function testHorariosNoPermitidosJubilados(){
        $tarjeta = new Jubilado();
        $tarjeta->saldo = 1000;

        // hardcodeamos la fecha y hora para que el tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjeta->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjeta->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjeta->hoy->setTime(5, 0, 0); // Establecer la hora a las 5:00 AM

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No se encuentra en el intervalo de tiempo permitido para utilizar la tarjeta.");
        $tarjeta->pagarPasaje();
    }

    public function testHorariosPermitidosJubilados(){
        $tarjeta = new Jubilado();
        $tarjeta->saldo = 1000;
        $colectivo = new Colectivo('Linea 1','no');
        
        // hardcodeamos la fecha y hora para que el tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjeta->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjeta->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjeta->hoy->setTime(16, 0, 0); // Establecer la hora a las 6:00 PM

        $boleto = $colectivo->pagarCon($tarjeta, '1.1.1');
        $this->assertInstanceOf(Boleto::class, $boleto);
        
    }

    public function testInterUrbano() {
        $tarjeta = new Tarjeta();
        $tarjeta->saldo = 1000;
        $fecha = '1.1.1';
        $colectivo = new Colectivo('Linea 1','si');

        $saldo1 = $tarjeta->getSaldo();
        $colectivo->pagarCon($tarjeta, $fecha);
        $saldo2 = $tarjeta->getSaldo();

        $this->assertEquals($saldo1 - $saldo2, 184);
    }

    public function testInterUrbanoMeidoBoleto() {
        $tarjeta = new MedioBoleto();
        $tarjeta->saldo = 1000;
        $fecha = '1.1.1';
        $colectivo = new Colectivo('Linea 1','si');

        // hardcodeamos la fecha y hora para que el tire error cuando lo probamos en un dia u hora no adeacuados
        $tarjeta->hoy = new \DateTime(); // Crear un objeto DateTime
        $tarjeta->hoy->setISODate(date('Y'), date('W'), 2); // Establecer el día de la semana (lunes)
        $tarjeta->hoy->setTime(16, 0, 0); // Establecer la hora a las 6:00 PM

        $saldo1 = $tarjeta->getSaldo();
        $colectivo->pagarCon($tarjeta, $fecha);
        $saldo2 = $tarjeta->getSaldo();

        $this->assertEquals($saldo1 - $saldo2, 92);
    }

}
