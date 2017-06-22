UYCODEKA actualización Marzo 2017

Nuevas características.


Junio 2017

General: i) Cambio en el manejo de facturas eliminadas, ahora una factura emitida no se puede eliminar ni dar de baja. Se puede eliminar una factura antes de emitirla y se eliminará completamente.


Mayo 2017

General: i) Se mejoro el instalador, ahora al escribir la dirección de uycodeka/instalar el asistente nos permite configurar una nueva instalación o una actualización.
ii) Se optimizo la administración de permisos de los usuarios, ahora se tiene mayor control sobre lo que puede cada usuario realizar en el sistema.
Iii) Se avanzo en la confección del manual de usuario.
iv) Se agrego la posibilidad de seleccionar in impuesto, viéndose esto reflejado en los reportes.
v) Se corrigió el despliegue de detalles del gráfico de torta en el reporte cierre de mes


Abril 2017

General: i) Cambio en los botones, se deja de utilizar imágenes para representar las acciones. 
ii) Se agregaron reportes para el resumen de una cuenta.
iii) Corrección del control de stock al emitir una factura.
iv) Se corrigió la configuración de phpjobScheduler que provocaba funcionamiento aleatorio.
v) Se mejoro la administración de sesiones, cuando se instala en un servidor, aveces no podía acceder a la carpeta temporal.



Menú → Mantenimiento → Datos del sistema: Se agrego la posibilidad de seleccionar entre dos modelos de facturas, la posibilidad de incluir el logo en ellas y la ubicación del símbolo de la moneda a utilizar, a la izquierda o a la derecha del número.


Marzo 2017

Inicio: Cambio de la pantalla de login.

Menú → Mantenimiento → Moneda: Se agrego la posibilidad de seleccionar dos monedas para ser utilizadas en el sistema, una como principal y otra como secundaria.


Febrero 2017

Depuración de código: Se han depurado el código, si bien no afectaban el funcionamiento del sistema, aparecían algunos errores en los logs, como ser variables no definidas.

Menú → Mantenimiento → Tipo de cambio: Se actualizo la forma de obtener la cotización del dolar, ahora se utiliza web services del BCU.

Menú → Documentos:  A la facturas automáticas se le agrego la posibilidad de hacerlas manualmente.

Noviembre 2016

Migrar de mysql a mysqli: Hemos comenzado la migración a mysqli pensando en la próxima actualización a php 7, para ello utilizamos MySQLConverterTool-master e incorporamos una función mysqli_result() para mantener la compatibilidad con el código anterior, lo que nos permitirá ir actualizando el código mientras realizamos mejoras, sin forzar a cambiar todo el código.

Menú: Re-diseño y re-organización completo del menú, agrupando por categoría, siendo mas amigable a la vista.

Impresión → Facturas cliente: Modificamos la impresión de facturas, al utilizar UYCODEKA desde la misma red del servidor y teniendo la impresora de facturación conectada al mismo, la factura se envía directamente a dicha impresora. Si se esta fuera de la red del servidor genera un PDF de la factura para poder imprimir luego.

Menú → Documentos:  Incorporamos la posibilidad de programar facturas automáticas para aquellos service o productos que facturamos normalmente todos los meses, pudiendo seleccionar en que altura del mes se emite. Nota: siempre es a mes vencido.


Abril 2016.

Productos → Artículos en transito:  Se incorporó la posibilidad de gestionar artículos en transito para el traslado de los mismo a clientes, generando vía para el transportista.

Venta clientes → Nota de crédito: Gestión independiente de las notas de crédito, seleccionando cliente, aparecen las facturas de ese cliente y para cada una de esas facturas se pueden seleccionar los elementos.

Tesorería → Recibos:  Nueva forma de gestionar los cobros, donde por cada recibo pueden haber varias facturas y varios documentos de cobro, (aún no trabaja con cobros parciales). Permite en envío por mail de un recibo al cliente con los detalles del mismo.

Reportes → Reportes General: Reporte de liquidación de comisiones exportable a Excel.


Mejoras introducidas.

En general: Ajuste de tablas para la validación de datos en la facturación electrónica en Uruguay.


Rejillas → En General: Se corrigió el despliegue de los ítems de cada rejilla, habilitando mostrar el máximo óptimo posible.

Proveedores → Ingreso de facturas: Se corrigió el ajuste de stock al ingresar nuva factura de combra, y al eliminar factura de compra.

Ventas → Facturas: Para poder seleccionar un producto tiene que haber stock suficiente.

Tesorería → Pago DGI: Se modificó el listado de pagos realizados, así como la búsqueda y la impresión del listado de pagos realizados según opciones de búsqueda.

Reportes → Cierre mes: Se corrigió el cálculo del IVA en el reporte de Cierre anualizado.


Abril 2016.

En general: Resalta la fila al seleccionarla (en varios módulos).
	La ventana de selección de clientes se unifico el tamaño en los módulos.
	Se puede modificar la cantidad de datos a listar, por defecto son 10.
	La cantidad de registros a mostrar se calcula según en tamaño de la ventana de la página inicio.

Mantenimiento → Usuarios: Se agrego la característica de Vendedor a los usuarios.

Mantenimiento → Forma de pago: Se agrego un campo para el ingreso manual de los días equivalente a la descripción.

Mantenimiento → Datos del sistema: Se cambio el editor de texto para el mensaje de email.
			Se puede configurar una impresora remota para la facturación y otra para los reportes, de modo que al abrir el PDF de la factura o reporte éste se imprima directamente.
			Se agrego configurar un servidor auxiliar donde guardar las imágenes.

Inter. Comerciales: Se agrego a Clientes y Proveedores mostrar la su ubicación en googlemaps.

Inter. Comerciales → Cliente: Se incorporaron nuevos campos de datos (Recepción de mercadería, Días de pago, Agencias de cargas), selección de ejecutivo de cuentas, para posterior liquidación de comisiones.
	Exportar la lista de clientes directamente a Excel, seleccionando el nombre del archivo.

Inter. Comerciales → Proveedores: Se agrego el cambio de campo utilizando Enter.

Productos → Artículos: Se incorporaron campos nuevos y mejoró la distribución de los campos. Campo Comisión (cada artículo se le asigna un % de comisión para el vendedor).
	Calcula el precio final según el IVA seleccionado y el precio público.
	Genera un código QR con información de cada artículo, como ser datos de la ubicación.
	Se puede guardar las imágenes en otro servidor.
	Exportar directamente a Excel, con varias opciones seleccionando el nombre del archivo.

Productos → Familias de artículos: Se incorporo insertar una imagen descriptiva.

Ventas cliente → Facturas: Se eliminó el ingresar en n.º en forma manual, ahora se asigna en forma automática.
	Se habilito en cambio de campo al presionar Enter, al llegar al Código de artículo ingresa automáticamente a seleccionar uno.
	Se agregó la búsqueda automática al escribir en el campo Descripción.
	Se muestra el % de comisión a la derecha de la acción.
	El nombre del cliente aparece compuesto por el contacto y por la empresa.
	Se corrigió para no modificar una factura emitida al menos de ingresar la clave.
	Se agrego un campo para descuento pronto pago. (Dto. PP).
	Se incorporo la selección de forma de pago y el cálculo automático de la fecha de vencimiento.

Tesorería → Cobros: Se cambio la forma de gestionar los cobros, pasando a ser recibos compuestos por las facturas canceladas y por los documentos de pago.

Tesorería → Recibos: Nuevo sistema para registrar los cobros, manteniendo compatibilidad con el anterior.  Un recibo se puede registrar tanto de la parte de cobros como la de recibos. Al ingresar un nuevo recibo y seleccionar cliente aparecen todas las facturas pendientes de cobro para dicho cliente, se selecciona la/s que se cobrarán y luego se ingresan el o los medios de pago.


Reportes → Ventas: Se mejoraron los reportes Deudores por venta y Estado de cuenta.

Reportes → Cierre Mes:   Se agregó la posibilidad de generar un reporte de cierre anualizado, exportable a Excel.


¿Por qué Software Libre?
Son múltiples las ventajas que aporta el Software Libre a la empresa. Es por ello que desde que comenzamos nuestro proyecto, nos hemos focalizado en la aplicación de soluciones libres en nuestros clientes en áreas relevantes de sus sistemas informáticos, haciéndoles partícipes de todas esas ventajas. Las más relevantes:
Ahorro de costes. La mayoría de las aplicaciones no implican gastos de licencia.
Flexibilidad. La disponibilidad del código permite la adaptación de las aplicaciones a las necesidades específicas del cliente.
Implementación. Las soluciones libres no suelen estar sujetas a sistemas operativos concretos, con lo cual su implementación implica menos cambios, y consecuentemente, menos gastos.
Independencia. Nuestros clientes no están atados a ningún proveedor. No tienen que preocuparse de costes de actualizaciones de licencias, de la desaparición del proveedor o la discontinuidad de los productos. La posesión del código fuente de sus aplicaciones le garantiza no depender de nadie.
Seguridad. Los sistemas operativos libres son, con mucha diferencia, los más seguros existentes debido a su arquitectura. Mientras que un sistema Windows está constantemente en peligro y precisa de soluciones antivirus, los sistemas basados en GNU/Linux son seguros por naturaleza.

Robustez del sistema. La estabilidad es una de las señas del software libre. El hecho de que el código esté visible para todos implica su mayor calidad, ya que no sólo puede ser corregido y mejorado por cualquiera, sino que no tendría sentido publicar un código defectuoso o deficiente. 

1. INTRODUCCIÓN

El sistema UY-CodeKa es una aplicación para controlar la facturación y gestionar el stock de una pequeña o mediana empresa esta basada en codeka.net. Su gran virtud está en la facilidad de uso y en cubrir las necesidades de las PYMES que brindan venta de artículos y service. 
UY-CodeKa es una aplicación bajo licencia GPL. Está desarrollada sobre entorno Web, lo que la hace ser muy versátil. Es independiente del sistema operativo y además permite el trabajo en red. 
Las funciones principales del sistema son: 
Gestión de Clientes y proveedores 
Gestión de Artículos y Familias 
Gestión de Facturas y Ordenes de Compra de los clientes 
Gestión de Facturas y Ordenes de Compra de los proveedores 
Ventas en mostrador, terminal de punto de venta [TPV] 
Gestión de los cobros y pagos [Tesorería] 
Reportes de Ventas y Service. 
Creación y configuración de códigos de barras de los artículos 
Gestión de copias de seguridad 
Listados en formato PDF 
El funcionamiento a través de entorno Web permite su uso multiplataforma, tanto en sistemas operativos Windows como Linux y MAC. 
El software ha sido desarrollado en lenguaje PHP, HTML, Javascript, jQuery y utilizando como motor de base de 
datos MySQL. 
