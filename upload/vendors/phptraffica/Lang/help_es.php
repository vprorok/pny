<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 Spanish (es_ES) translation made by Samuel Aguilera <samuel.aguilera@agamum.net>

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/
// Stylesheet
$stylesheet = "../red.css";
if (isset($HTTP_COOKIE_VARS["phpTrafficA_style"])) {
	$stylesheet = "../".$HTTP_COOKIE_VARS["phpTrafficA_style"].".css";
}
if (!is_file($stylesheet)) {
	$stylesheet = "../red.css";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<title>Tabl&oacute;n de ayuda de phpTrafficA</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Cerrar ventana</a></div>
<h1>Tabl&oacute;n de ayuda de phpTrafficA</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Lista negra</strong>: este formulario se usa para bloquear referenciadores defectuosos. Por ejemplo, hay algunos buscadores que no permiten extraer las palabras clave usadas para acceder a la página web, no se les puede incluir en las estadísticas de buscadores, pero no son ni deberán ser contados como referenciadores. Esta función también se usa para bloquear sitios que realizan <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">referrer spam</A>.</li>

<li><A name="domain"></A><strong>Dominio</strong>: introduce la ruta raíz del dominio que quieres analizar, algo como <code>miservidor.net/midirectorio/</code>.</li>

<li><A name="savehost"></A><strong>Últimos servidores</strong>: phpTrafficA guarda una tabla con información completa de lo últimos visitantes. Después de algún tiempo estos datos se borran para ahorrar espacio y la información guardada en forma procesada. Esta tabla se usa en la pestaña <code>Últimas visitas</code> y para procesar en el <code>Análisis de ruta</code>. Si quieres que se incluyan más servidores en estas funciones, incrementa el número de servidores guardados. phpTrafficA puede volverse extremadamente lento con tablas grandes.</li>

<li><A name="oslist"></A><strong>Lista de S.O.</strong>: lista de sistemas operativos. Esta tabla muestra la lista de sistemas operativos y como detectarlos en la identificación del navegador. Cada línea contiene una cadena de identificación seguida del nombre del S.O., separados por el símbolo <code>|</code>. Esta tabla se actualiza con las actualizaciones de phpTrafficA.</li>

<li><A name="public"></A><strong>Público</strong>: todos los visitantes tienen permiso para ver las estadísticas relacionadas con dominios <code>públicos</code>. Las estadísticas para dominios <code>privados</code> sólo están disponibles cuando te identificas.</li>

<li><A name="selist"></A><strong>Lista de buscadores</strong>: esta tabla muestra la lista de buscadores, como detectarlos, y como extraer las palabras clave en la cadena de referencia. Cada línea contiene información para un buscador, separada por el símbolo <code>|</code>. El primer elemento es un nombre, el segundo es una cadena usada para detectar al buscador en la url de referencia, y el tercer elemento es la varialbe que el buscador usa para pasar las palabras clave (separadas por el símbolo <code>:</code> cuando hay varias posibilidades). Por ejemplo, una búsqueda en google sería detectada con la URL <code>google.com</code> y la variable <code>q</code>. Esta tabla se actualiza con las actualizaciones de phpTrafficA.</li>

<li><A name="table"></A><strong>Tabla</strong>: base del nombre para las tablas sql. Creamos varias tablas para sitio que phpTrafficA analiza. Su nombre siempre empieza con la palabra que se ponga aquí.</li>

<li><A name="trim"></A><strong>Recortar URL</strong>: si esto se pone en <code>Sí</code>, la url usada en las estadísticas se recortará. Por ejemplo, <code>index.php?lang=fr</code> y <code>index.php?lang=en</code> serán almacenadas como <code>index.php</code>. Este es el comportamiento por defecto en phpTrafficA. Se cuidadoso si decides conservar para tus estadísticas la URL completa ya que el número de combinaciones de URL para páginas web completamente dinámicas puede ser inmenso. Además, <strong>no se recomienda cambiar este parámetro una vez que hayas usado phpTrafficA por un tiempo</strong>.</li>

<li><A name="wblist"></A><strong>Lista de navegadores</strong>: lista de navegadores. Esta tabla muestra la lista de navegadores y como detectarlos en la identificación de navegadores. Cada línea contiene una cadena de identificación seguida por el nombre del navegador, separado por el símbolo <code>|</code>. Esta tabla es actualizada cada vez que se actualiza phpTrafficA.</li>

<li><A name="countbots"></A><strong>Contar bots</strong>: si seleccionas esta opción los robots que visiten tu sitio (googlebot, yahoo slurp, y similares) se contarán como visitantes habituales. Si no seleccionas esta opción, no serán incluidos en las estadísticas. Los verás en la tabla de últimos servidores, pero eso es todo.</li>

<li><A name="counter"></A><strong>Contador</strong>: si seleccionas esta opción phpTrafficA actuará también como un contador. Si seleccionaste uno de los scripts con imagen para recopilar tus estadísticas, la imagen incluirá el número de cargas desde el comienzo de la recopilación. Si seleccionaste la técnica de php puro para recopilar tus estadísticas (sin imagen), phpTrafficA mostrará el número total de cargas para la página actual.</li>

<li><A name="magnetindex"></A><strong>Índice Magnético</strong>: el <code>índide magnético</code> es una herramienta útil ya que mide cuanto tráfico es atraído a tu sitio por una página en concreto. Por ejemplo, páginas con un <code>índide magnético</code> de 1, 2, y 3 son páginas con 10, 100, y 1000 cargas por día respectivamente. No mezcles este factor con la media de número de cargas para una página en concreto. Contamos todas las cargas de visitas que comenzaron en esta página, no sólo las visitas a esta página.</li>

<li><A name="bouncerate"></A><strong>Ratio de Rebote</strong>: el <code>ratio de rebote</code> es una medida importante ya que te dice el porcentaje de gente que "rebota" hacia fuera (que se va) de tu página después de ver sólo esta página.</li>

<li><A name="sereferrers"></A><strong>Buscadores como Referenciadores</strong>: Si seleccionas esta opción, las consultas de los buscadores se mostrarán en la tabla de referenciadores como uno más. Esto te permite tener la URL completa de todos los buscadores que llegan a tu sitio. Por otro lado, tu tabla de referenciadores puede llegar a ser bastante larga, así que usa esta opción con cuidado, ¡y sólo si tienes mucho espacio en el disco duro!</li>

<li><A name="visitcutoff"></A><strong>Tiempo límite visita</strong>: Esta opción fija el tiempo límite para las visitas, en minutos. Si un visitante único (basado en la dirección IP) no ha estado activo por un tiempo superior a este valor, la próxima carga desde esta misma dirección IP será tratado como una nueva visita única. El valor por defecto es de 15 minutos.</li>

<li><A name="timediff"></A><strong>Diferencia horaria</strong>: usa esta opción si tu servidor no está en la misma zona horaria que tu sitio web. Fija la diferencia horaria en horas.</li>

<li><A name="URLTrimFactor"></A><strong>Factor de recorte de URL</strong>: usa esta opción para fijar la longitud de cadenas y URLs recortadas en varias páginas de phpTrafficA. El valor predeterminado es 10. Si eliges un valor de 20, las cadenas y URLs recortadas serán el doble de largas de lo que son de manera predeterminada. Si eliges un valor de 5, las cadenas y URLs recortadas serán la mitad de largas que el valor predeterminado.</li>

<li><A name="referrerNewDuration"></A><strong>Tiempo para mantener referenciadores marcados como nuevo</strong>: Las nuevas direcciones en la página de referenciadores serán marcadas como <code>nuevo</code> hasta que hagas clic en el enlace. Los referenciadores más antiguos de ese tiempo no serán marcados como nuevo, incluso aunque no hicieras clic en el enlace.</li>

<li><A name="autoCleanRKIP"></A><strong>Limpieza automática de tablas de referenciadores, palabras clave, lista de IP, y rutas.</strong>: Si eliges esta opción, las tablas con referenciadores, palabras clave, direcciones IP, y rutas serán limpiadas regularmente. Esto eliminará entradas más antiguas de 2 meses y que sólo se hayan usado una vez.</li>

<li><A name="autoCleanAccess"></A><strong>Limpieza automática de tablas de acceso.</strong>: Si eliges esta opción, las tablas de accesos (cargas de página y visitantes únicos) serán limpiadas regularmente de forma automática. Esto eliminará datos más antiguos de dos meses. El número total de accesos a cada página y las estadísticas para el sitio entero se mantendrán, pero todos los datos de acceso a páginas individuales más antiguos de dos meses se perderán.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>