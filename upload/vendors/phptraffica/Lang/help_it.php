<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2009 ZoneO-soft, Butchu (email: butchu with the domain zoneo.net)
 No rips will be tolerated!

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
<title>Guida di phpTrafficA</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css">
</head>
<div class="help">
<div class="top"><div align="right"><a href="javascript:window.close();">Chiudi finestra</a></div>
<h1>Guida di phpTrafficA</h1>
</div>
<div class='text'>
<ul>

<li><A name="blist"></A><strong>Lista nera</strong>: questo modulo è usato per escludere referrer indesiderati. Per esempio, ci sono alcuni motori di ricerca che non permettono di estrarre le parole chiave usati per accedere al sito, e non possono essere inclusi nelle statistiche dei motori di ricerca, ma non dovrebbero neanche essere inclusi tra i referrer. Questa funzione è anche usata per escludere siti che applicano <A href="http://en.wikipedia.org/wiki/Referer_spam" target="_blank">spam di referrer</A>.</li>

<li><A name="domain"></A><strong>Dominio</strong>:  inserisci la root del dominio da analizzare, qualcosa come <code>mioserver.it/miacartella/</code></li>

<li><A name="savehost"></A><strong>Host più recenti</strong>:  phpTrafficA mantiene una tabella con le informazioni compoete degli ultimi visitatori. Dopo qualche tempo sono rimosse per risparmiare spazio e l'informazione viene mantenuta in forma lavorata. Questa tabella è usata nelle <code>Ultime visite</code> e per l'elaborazione dati nell' <code>Analisi del percorso</code>. Se vuoi avere più host inclusi in queste funzioni, aumenta il numero di host salvati. phpTrafficA potrebbe rallentare molto con tabelle molto ampie.</li>

<li><A name="oslist"></A><strong>Lista S.O.</strong>: lista dei sistemi operativi. Questa tabella mostra la lista degli S.O. e come rilevarli nell'identificazione del browser. Ogni linea contiene una stringa di identificazione seguita dal nome del S.O., separata dal simbolo <code>|</code>. Questa tabella è solitamente aggiornata in contemporanea a phpTrafficA.</li>

<li><A name="public"></A><strong>Pubblico</strong>: tutti i visitatori posso vedere le statistiche dei domini contrassegnati come <code>pubblico</code>. Le statistiche per i domini con attributo <code>privato</code> sono disponibili dopo dopo autenticazione.</li>

<li><A name="selist"></A><strong>Lista dei motori di ricerca</strong>: questa tabella mostra la lista dei motori di ricerca, come rilevarli, e come estrarre le parole chiave dalla stringa di referer. Ogni riga contiene informazioni per un motore di ricerca, separate dal simbolo <code>|</code>. Il primo elemento è il nome, il secondo una stringa usata per determinare il motore dalla stringa referer, ed il terzo è la variabile che usa il motore per passare le parole chiave (separate dal simbolo <code>:</code> per le possibilità multiple). Ad esempio, una ricerca in Google sarà rilevata con l'URL <code>google.com</code> e la variabile <code>q</code>. Questa tabella è aggiornata assieme a phpTrafficA</li>

<li><A name="table"></A><strong>Tabella</strong>: prefisso per le tabelle sql. Vengono create diverse tabelle per ogni sito analizzato da phpTrafficA. Il loro nome inizia sempra con la stringa qui specificata.</li>

<li><A name="trim"></A><strong>Tronca URL</strong>:  se questa impostazione è <code>true</code>, l'URL usato nelle statistiche viene troncato. Per esempio, <code>index.php?lang=en</code> e <code>index.php?lang=it</code> verranno entrambi registrati come <code>index.php</code>. Questa è l'impostazione di base di phpTrafficA. Attensione a mantenere l'URL completo, le combinazioni possono diventare moltissime per siti completamente dinamici. Inoltre, <strong>non è raccomandabile cambiare questo parametro una volta che phpTrafficA è utilizzato già da tempo</strong>.</li>

<li><A name="wblist"></A><strong>Lista dei browser</strong>: questa tabella mostra la lista dei browser e come rilevarli nella stringa di idenitificazione. Ogni riga contiene una stringa di idenitificazione seguita dal nome del browser, separati dal simbolo <code>|</code>. Questa tabella è aggiornata assieme a phpTrafficA.</li>

<li><A name="countbots"></A><strong>Conta i bot</strong>: se attivo, i bot che visitano il sito (googlebot, yahoo slurp e così via) saranno considerati come normali visitatori. Se disattivato, non verrano inclusi nelle statistiche, ma verranno semplicemente inseriti tra gli host recenti.</li>

<li><A name="counter"></A><strong>Contatore</strong>: se ativo, phpTrafficA agirà anche da contatore. Se hai selezionato uno degli script con immagine per registrare le statistiche, l'immagine includerà il numero di contatti dall'avvio della registrazione. se hai selezionato uno script in PHP puro, senza immagine, verrà visualizzato il numero di contatti in formato testo.</li>

<li><A name="magnetindex"></A><strong>Indice calamite</strong>:  l'<code>indice calamit</code> è un utile strumento che misura quanto traffico arriva al sito da una data pagina. Per esempio, le pagine con <code>indice calamita</code> 1, 2, e 3 sono pagine di entrata per 10, 100, 1000 contatti al giorno, rispettivamente. Non va confuso col numero di contatti su una data pagina, bensì indica tutti i contatti del sito che iniziano da questa pagina, e non solo le visite alla pagina.</li>

<li><A name="bouncerate"></A><strong>Frequenza di rimbalzo</strong>: misurazione importante che indica la percentuale di persone che è 'rimbalzata' via dal sito dopo aver visto solo questa pagina.</li>

<li><A name="sereferrers"></A><strong>Motori di ricerca come referer</strong>: se attivo, i motori di ricerca verranno elencati anche nella tabella dei referer. In questo modo verrà registrato l'URL completo delle ricerche che portano al sito, ma d'altra parte la lista di referer potrebbe diventare molto ampia, quindi usare con moderazione, e solo se si ha molto spazio su disco!</li>

<li><A name="visitcutoff"></A><strong>Tempo di chiusura della visita</strong>: Questa opzione imposta il tempo di chiusura della visita, in minuti. Se un visitatore unico (basato sull'IP) non è stato attivo per più di questo ammontare di minuti, il prossimo contatto dallo stesso IP verrà trattato come una nuova visita unica. Default di 15 minuti.</li>

<li><A name="timediff"></A><strong>Differenza di ora</strong>: usa questa opzione se il tuo server non è nella stessa fascia oraria del tuo sito. Inserisci la differenza in ore.</li>

<li><A name="URLTrimFactor"></A><strong>Troncamento URL</strong>: imposta la lunghezza di troncamento di stringe e URL nelle varie pagine di phpTrafficA. Default 10. Se scegli un valore di 20, le stringe e gli URL troncati saranno lunghi il doppio, se invece lo imposti a 5, la metà (mi pare abbastanza ovvio d'altronde NdT)</li>

<li><A name="referrerNewDuration"></A><strong>Tempo per cui un referrer è considerato nuovo</strong>: i nuovi indirizzi nella pagina dei referrer vengono marcati come <code>nuovo</code> fino a quando non si segue il link. I referrer più vecchi di questa impostazione non verranno considerati nuovi anche se non sono stati cliccati i link.</li>

<li><A name="autoCleanRKIP"></A><strong>Pulizia automatica delle tabelle di referrer, parole chiave, IP e percorsi</strong>: se attivi questa opzione, le tabelle di referrer, parole chiave, indirizzi IP e percorsi saranno svuotate regolarmente. Verranno eliminati i dati più vecchi di 2 mesi che sono stati usati solo una volta.</li>

<li><A name="autoCleanAccess"></A><strong>Pulizia automatica delle tabelle di accesso</strong>: Se attivi questa opzione, le tabelle di accesso (pagine viste e numero di visitatori) saranno svuotate regolarmente. Verranno eliminati i dati più vecchi di 2 mesi. Il numero totale di accessi ad ogni pagina e le statistiche del sito saranno mantenute, ma tutti i dati individuali delle pagine antecedenti agli ultimi 2 mesi saranno persi.</li>

</ul>
</div>
</div>
<div id='sign'><a href="http://soft.zoneo.net/phpTrafficA/">phpTrafficA</a> &copy; 2004-2009, ZoneO-soft</div>
</body>
</html>