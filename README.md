# Libreria Facile

## Caricamento dati nel database
```
mysql -u root -ppassword -h 127.0.0.1 moneylog < facile.sql
```

## Esecuzione test
Prima di ogni esecuzione dei test caricare nuovamente i dati sul DB quindi eseguire il seguente comando
```
bin/phpunit
``` 
