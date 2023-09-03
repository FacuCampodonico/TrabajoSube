# Trabajo Sube 2023:
El siguiente trabajo es un enunciado iterativo. Todas las semanas nuevos requerimientos serán agregados y/o modificados para ilustrar la dinámica de desarrollo de software.

## Iteración 1.

Escribir un programa con programación orientada a objetos que permita ilustrar el funcionamiento del transporte urbano de pasajeros de la ciudad de Rosario.

Las clases que interactúan en la simulación son: Colectivo, Tarjeta y Boleto.

Cuando un usuario viaja en colectivo con una tarjeta, obtiene un boleto como resultado de la operación $coletivo->pagarCon($tarjeta);


Para esta iteración se consideran los siguientes supuestos:

- No hay medio boleto de ningún tipo.
- No hay transbordos.
- No hay viajes plus.
- La tarifa básica de un pasaje es de: $120
- Las cargas aceptadas de tarjetas son: (150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 2000, 2500, 3000, 3500, 4000)
- El límite de saldo de una tarjeta es de $6600

Se pide:

- Hacer un fork del repositorio.
- Implementar el código de las clases Tarjeta, Colectivo y Boleto.
- Hacer que el test Boleto.php funcione correctamente con todos los montos de pago listados.
- Enviar el enlace del repositorio al mail del profesor con los integrantes del grupo(Un mail por grupo).


Para instalar el código inicial clonar el repositorio y luego ejecutar:
```
composer install
```

En caso de no contar con composer instalado, descargarlo desde: https://getcomposer.org/
Para correr los tests:
```
./vendor/bin/phpunit
```

Si se agregan nuevas clases al código tal vez sea necesario correr:
```
composer dump-autoload
```

## Iteración 2.
Para esta iteración hay 3 tareas principales. Crear un [issue](https://docs.github.com/es/issues/tracking-your-work-with-issues/creating-an-issue) en github copiando la descripción de cada tarea y completar cada uno en una rama diferente. Éstas serán mergeadas al validar, luego de una revisión cruzada (de ambos integrantes del grupo), que todo el código tiene sentido y está correctamente implementado. Ademas deberan configurar Github Actions.

No es necesario que todo el código para un issue esté funcionando al 100% antes de mergiarlo, pueden crear pull requests que solucionen algún item particular del problema para avanzar más rápido.

Además de las tareas planteadas, cada grupo tiene tareas pendientes de la iteración anterior que debe finalizar antes de comenzar con la iteración 2. Cuando la iteración 1 esté completada, [crear un tag](https://www.youtube.com/watch?v=5DkX3HFgklM) llamado iteracion1: Y subirlo a github.

IMPORTANTE: Como punto de control, alguna de estas dos funcionalidades: "Viaje plus" o "Franquicia de Boleto" tiene que estar lista para revisar a mitad de la iteración. (5 de Septiembre).

## Descuento de saldos.
- Cada vez que una tarjeta paga un boleto, descuenta el valor del monto gastado.
- Si la tarjeta se queda sin saldo, la operación ```$colectivo->pagarCon($tarjeta)``` devuelve FALSE,
- Escribir un test que valide dos casos, pagar con saldo y pagar sin saldo.

## Viaje plus
- Si la tarjeta se queda sin crédito, puede tener un saldo negativo de hasta $211,84.
- Cuando se vuelve a cargar la tarjeta, se descuenta el saldo de lo que se haya consumido en concepto de viaje plus.
- Escribir un test que valide que se pueden dar hasta dos viajes plus.
- Escribir un test que valide que el saldo de la tarjeta descuenta correctamente el/los viaje/s plus otorgado/s.

## Franquicia de Boleto.
- Existen dos tipos de franquicia en lo que refiere a tarjetas, las franquicias parciales, como el medio boleto estudiantil o el universitario, y las completas como las de jubilados(Notar que también existe boleto gratuito para estudiantes).
- Implementar cada tipo de tarjeta como una Herencia de la tarjeta original.
- Para esta iteración considerar simplemente que cuando se paga con una tarjeta del tipo MedioBoleto el costo del pasaje vale la mitad, independientemente de cuántas veces se use y que día de la semana sea.
- Escribir un test que valide que una tarjeta de FranquiciaCompleta siempre puede pagar un boleto.
- Escribir un test que valide que el monto del boleto pagado con medio boleto es siempre la mitad del normal.

Si se agregan nuevas clases al código tal vez sea necesario correr:
```
composer dump-autoload
```
