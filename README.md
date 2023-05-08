# Decodificatore di Eventi
Questo codice PHP decodifica le informazioni di un evento preso dal server API di TicketSMS da un link di TicketSms.it. 
Inserisci il link dell'evento per ottenere informazioni come la descrizione dell'evento, la data, il genere musicale e molto altro ancora! 😎🎉

## Utilizzo

1. Avvia il codice in una console PHP. (facendo ```php dati.php```)
2. Inserisci il link completo dell'evento quando richiesto.
3. Attendi il caricamento delle informazioni.
4. In basso ho inserito le variabili di Output, le quali hanno le informazioni
5. Ecco a te l'informazioni dell'evento! 🎵🕺

## Output

| Dato | Variabile | Esempio Output |
| --- | --- | --- |
| Nome evento | $nome_evento | Bello figo Evento assurdo |
| Nome del posto | $postocompleto | Discoteca uao / Milano |
| Data(Timestamp UNIX) | $quando | 1676208600 |
| Età Minima | $anni | 18 |
| Fascia d'età | $fascia | Giovani/Adulti |
| Genere del Evento | $genere | Dance |
| Nome del locale | $nome_locale | Discoteca uao |
| Mese del evento | $mese | Gennaio |
| Link Immagine Evento | $immagine_link | ```https://d2fa23zcjd5klo.cloudfront.net/square/event/13513075-23a9-4f34-a0ba-906fd3cb9d65.jpg``` |
| Descrizione Evento in HTML | $descrizione_html | `<strong>evento ASSURDO</strong><br><p>Con Giasin</p>` |
| Biglietti disponibili | $biglietti | Release 2ND |
| Link ticketsms | $link_ticket | ```https://www.ticketsms.it/event/Clpatone-Kings-Jesolo-opening-summer-2023``` |
| Biglietti Esauriti | $esaurito | si/no |
