<?php

echo "Inserisci il link del evento: ";
$link = fgets(STDIN);

// Rimuovere eventuali caratteri di nuova riga (\n o \r) dalla stringa inserita dall'utente
$link = str_replace(array("\n", "\r"), '', $link);

$url = $link;
//$url = "https://www.ticketsms.it/event/KNkhkAQU";
$codiceEvento = substr($url, strrpos($url, '/') + 1);
//$codiceEvento = "KNkhkAQU";

echo "hai inserito il codice: ".$codiceEvento."\nAttendi un attimo.\n";

// Variabili di Default
$anniOk = false;
$i = 0;
$anni = 18;
$descrizione_html = '';
$biglietti = NULL;
$esaurito="no";
$contoBigliettiDisponibili = 0;

// Richiesta al server
$url = 'https://backend.prod.ticketsms.it/api/v3/events/' . $codiceEvento; // l'URL del server API
$json = file_get_contents($url); // invia la richiesta e ottiene la risposta in formato JSON
$data = json_decode($json, true); // decodifica il JSON in un array associativo

// Controlla se l'array è vuoto
if (empty($data['data']['body'][0])) {
    echo "Errore";
    exit();
}

// Vari dati principali
$quando = strtotime($data['data']['body'][0]['list'][0]["datetime"]);
$nome_evento = $data['data']['body'][0]['list'][0]["name"];
$descrizione = $data['data']['body'][0]['list'][0]["description"];
$nome_locale = $data['data']['body'][0]['list'][0]["location"]["name"];
$citta_locale = $data['data']['body'][0]['list'][0]["location"]["common"];
$immagine_link = $data['data']['body'][0]['list'][0]["eventAsset"]["covers"][0]["src"][0]["url"];
$link_ticket = "https://www.ticketsms.it/event/" . $data['metadata']['parentCodeUrl'];
$postocompleto = $nome_locale . " / " . $citta_locale;
$disponibilitaBiglietti = $data['data']['body'][2]["componentType"];
$dataDescrizione = json_decode($data['data']['body'][0]['list'][0]["description"], true);



// Controllo validità anni, in caso non venga specificato, il valore predefinito è di 18.
foreach ($data['data']['body'][1]["attributes"] as $attr) {
    $title = $attr["title"];
    if (in_array($title, ['+18', '+16', '+14'])) {
        $anni = (int)substr($title, 1);
        break;
    }
}

// Descrizione del evento in HTML
foreach ($dataDescrizione['ops'] as $op) {
    if (isset($op['insert']))
    {
        $text = $op['insert'];
        // Converti i caratteri speciali HTML
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        // Aggiungi il testo come elemento di testo normale o in grassetto
        if (isset($op['attributes']['bold']) && $op['attributes']['bold'] === true) {
            $descrizione_html .= "<b>{$text}</b>";
        }
        else {
            $descrizione_html .= nl2br($text);
        }
    }
}

// Fascia d'età, il valore predefinito è "Adulti"
if ($anni < 18){
    $fascia = "Giovani";
} else {
    $fascia = "Adulti";
}



// Genere, Non esiste valore Predefinito
$generi = array(
    'Classica',
    'Dance',
    'Pop',
    'House',
    'Hip-Hop',
    'Reggaeton'
);

$genere = null; // Valore di default
foreach ($data['data']['body'][1]["attributes"] as $attr) {
    $title = $attr["title"];
    if (in_array($title, $generi)) {
        $genere = $title;
        break;
    }
}



// Mese, Ovviamente deve esistere
$month = date('n', $quando);

if ($month == 1) {
    $mese = "Gennaio";
}
elseif ($month == 2) {
    $mese = "Febbraio";
}
elseif ($month == 3) {
    $mese = "Marzo";
}
elseif ($month == 4) {
    $mese = "Aprile";
}
elseif ($month == 5) {
    $mese = "Maggio";
}
elseif ($month == 6) {
    $mese = "Giugno";
}
elseif ($month == 7) {
    $mese = "Luglio";
}
elseif ($month == 8) {
    $mese = "Agosto";
}
elseif ($month == 9) {
    $mese = "Settembre";
}
elseif ($month == 10) {
    $mese = "Ottobre";
}
elseif ($month == 11) {
    $mese = "Novembre";
}
elseif ($month == 12) {
    $mese = "Dicembre";
}




// Controllo tipologia di biglietti, sempre se ci sono
if ($disponibilitaBiglietti == 'eventTicket') {
    foreach ($data['data']['body'][2]["list"] as $iBiglietti)
    {
        if ($iBiglietti["stato"] == 'active' || $iBiglietti["stato"] == 'exhaust' || $iBiglietti["stato"] == 'comingSoon') {
            $biglietti = $iBiglietti["notes"];
            $contoBigliettiDisponibili++;
        }
    }
} else {
  $esaurito="si";
}

// Esaurito
if($contoBigliettiDisponibili==0) {
  $esaurito="si";
  $biglietti="ESAURITO";
}


/*

  Nome evento = $nome_evento ES. Bello figo Evento assurdo.
  Nome del posto = $postocompleto ES. Discoteca uao / Milano
  Data(Timestamp UNIX) = $quando ES. 1676208600
  Età Minima = $anni ES. 18
  Fascia d'età = $fascia ES. Giovani/Adulti
  Genere del Evento = $genere ES. Dance
  Nome del locale = $nome_locale ES. Discoteca uao
  Mese del evento = $mese ES. Gennaio
  Link Immagine Evento = $immagine_link ES. https://d2fa23zcjd5klo.cloudfront.net/square/event/13513075-23a9-4f34-a0ba-906fd3cb9d65.jpg
  Descrizione Evento in HTML = $descrizione_html ES. <strong>evento ASSURDO</strong><br><p>Con Giasin</p>
  Biglietti disponibili(Sceglie solo uno tra i tanti biglietti disponibili) = $biglietti ES. Release 2ND
  Link ticketsms = $link_ticket ES. https://www.ticketsms.it/event/Clpatone-Kings-Jesolo-opening-summer-2023
  Biglietti Esauriti = $esaurito ES. si/no

*/
