# Facile Library 2.0
Il progetto è distribuito con un ambiente dockerizzato, ed è versionato con git, è possibile caricare il repository 
tramite il seguente comando:
```
git clone git@github.com:fabiov/moneylog-api.git
``` 

Per velocizzare lo sviluppo delle API richieste, è stato usato un repository personale già esistente, 
creato per un altro progetto, ma le implemetazioni sono state realizzate nel branch `facile`. 
Quindi una volta clonato il repository entrare nella directory `cd moneylog-api` e spostarsi sul branch con il comando: 
```
git checkout facile
```
A questo punto è possibile avviare i container docker con il seguente comando:
```
docker-compose up -d
```

I container docker espongono delle porte per consentire la comnunicazione col il mondo esterno, ed in particolare:
- container db porta 3306: per permettere l'interazione con il database MySql 
- container nginx porta 8081: per permettere l'accesso alle API HTTP REST ad esempio `http://localhost:8081/api-library/books/2` 

## Installazione dipendenze
Si dovrà quindi procedere installando le dipendenze, entrare quindi nel container `php` con il comando:
```
docker-compose exec php bash
``` 
Da dentro il container quindi eseguire il comando:
```
composer install
```

## Caricamento dati nel database
Una volta avviati i container è possibile caricare i dati sul database, quindi dalla shell del proprio pc lanciare il 
seguente comando (occore avere il client MySql sul propio pc)
```
mysql -u root -ppassword -h 127.0.0.1 moneylog < facile.sql
```

## Esecuzione test
Prima di ogni esecuzione dei test caricare nuovamente i dati sul DB quindi eseguire il seguente comando dalla shell del
container `php`:
```
bin/phpunit --filter=Book
``` 

## Struttura del progetto

### Controller
```
src/Controller/Api/BookController.php
```
Il controller gestisce le richieste HTTP esponendo delle API REST da cui si otterranno delle risposte in formato JSON, 
sono stati implementati i seguenti endpoint:

#### Aggiunta nuovo libro
`{POST} /api-library/books` si aspetta un body json con la seguente struttura
```
{
    "title": "Sei personaggi in cerca d'autore",
    "isbn": "9788806220571",
    "description": "Capolavoro della letteratura del Novecento.",
    "price": "9.5",
    "availability": "53",
    "author": {
        "id": "2"
    }
}
```

#### Lista libri filtrabile
`{GET} /api-library/books` restituisce la lista di tutti i libri disponibili. E' possibile filtrare i risultati usando i 
seguenti parametri in query string:
- title
- isbn
- author.name

I filtri controllano che i campi contengano le parole indicate, nel caso di più filtri devono essere tutti verificati 
contemporaneamente ad esempio:
```
http://localhost:8081/api-library/books?title=m&isbn=97888&author.name=ca
```
#### Dettaglio libro 
`{GET} /api-library/books/{id}` restituisce il dettaglio di uno specifico libro, selezionato per identificativo.  
Ad esempio: `http://localhost:8081/api-library/books/2`  
Restituirà:
```
{
    "id": "2",
    "title": "Gli arancini di Montalbano",
    "isbn": "9788838938511",
    "description": "Venti racconti si dispiegano l'un dietro l'altro.",
    "price": "11.25",
    "availability": "251",
    "author": {
        "id": "1",
        "name": "Camilleri"
    }
}
```

### Organizzazione Directory
E' stata predisposta la directory `src/BookShop/Domain/Book/` con lo scopo di organizzare il codice 
in contesti (es. BookShop) e domini (es. Book). Immaginando il condice come se fosse appartente ad un progetto più ampio. 
Un esempio di un possibile scenario potrebbe essere il seguente 
```
src/BookShop/Domain/Book/ # contenente le logiche del doimio book 
src/BookShop/Domain/Sale/ # con ad esempio le logiche legate alle vendite come fatture, ecc.  
src/BookShop/Domain/Customer/ # con ad esempio le logiche legate ai clienti  
```
Con l'idea di poter replicare questo tipo di organizzazione anche in nuovi "Contesti" 
