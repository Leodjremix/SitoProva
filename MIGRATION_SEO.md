# Guida alla Migrazione SEO per Santagatesi nel Mondo

Quando si migra da un vecchio CMS custom (o sito statico) a WordPress, l'obiettivo principale è **non rompere i vecchi link** (sia quelli indicizzati su Google, sia quelli già condivisi su Facebook negli anni).

Dato che hai migliaia di articoli, la strategia migliore è usare i **Redirect 301** (Moved Permanently).

## Metodo 1: Il Plugin "Redirection" (Consigliato)

Perché usare un plugin come **[Redirection](https://wordpress.org/plugins/redirection/)** invece di scrivere codice a mano nel `.htaccess`?
1. **Interfaccia Grafica:** Puoi vedere quante volte un redirect viene usato (hit counter).
2. **Registro Errori 404:** Il plugin traccia automaticamente chi cerca vecchie pagine che non esistono più, permettendoti di creare un redirect "al volo".
3. **Meno Rischi:** Un errore di sintassi nel file `.htaccess` può mandare offline l'intero sito ("Error 500"). Il plugin è molto più sicuro.

### Come mappare gli URL storici con Regex in Redirection

Se i tuoi vecchi articoli avevano questa struttura:
`https://www.santagatesinelmondo.it/news.php?id=123`

E su WordPress li hai importati con questa struttura:
`https://www.santagatesinelmondo.it/nome-del-nuovo-articolo/`

Dovrai creare delle regole tramite "Espressioni Regolari" (Regex).

1. Vai su **Strumenti > Redirection**.
2. Scorri fino alla sezione **Aggiungi un nuovo reindirizzamento**.
3. Clicca sull'icona a forma di ingranaggio ⚙️ per svelare le opzioni avanzate.
4. Spunta la casella **"Regex"** (Espressione Regolare).

**Caso A: Importazione massiva con lo stesso ID nel link di WordPress**
Se hai configurato i permalink di WordPress per mantenere l'ID alla fine (es. `/%postname%-%post_id%/`), la regola Regex sarebbe:
- **URL di partenza:** `^/news\.php\?id=(\d+)`
- **URL di arrivo:** `/?p=$1` (WordPress reindirizzerà automaticamente `/?p=123` al Permalink SEO corretto).

**Caso B: Mappatura manuale 1-a-1 (Se gli ID non combaciano più)**
Se hai re-inserito gli articoli a mano o con un plugin di importazione che ha sfasato gli ID, dovrai mapparli uno per uno caricando un file CSV nel plugin Redirection:
1. Crea un file Excel/CSV con due colonne:
   - Colonna 1: `/news.php?id=123`
   - Colonna 2: `/festa-patronale-2015/`
2. Usa la funzione "Importa" del plugin Redirection per caricarli tutti in un click.

---

## Metodo 2: Regole dirette nel file `.htaccess` (Per utenti avanzati)

Se preferisci non appesantire il database di WordPress con un plugin e vuoi massimizzare le prestazioni del server (Aruba/Apache), puoi inserire i redirect direttamente in cima al file `.htaccess` situato nella root del tuo spazio FTP.

*Attenzione: Inserisci queste regole PRIMA dei tag `# BEGIN WordPress`.*

### Esempio 1: Redirect di vecchie pagine principali (Index/Archive)
Se devi semplicemente reindirizzare la pagina principale che raccoglieva tutti i personaggi (es. da `https://www.santagatesinelmondo.it/santagatesi_illustri.asp` verso il nuovo archivio in staging `https://lnx.santagatesinelmondo.it/older/wordpress/santagatesi-illustri/`), la regola diretta in cima al file `.htaccess` del vecchio sito è:

```apache
Redirect 301 /santagatesi_illustri.asp https://lnx.santagatesinelmondo.it/older/wordpress/santagatesi-illustri/
```
*(Nota: Quando poi sposterai WordPress sul dominio principale togliendo `/older/wordpress/`, modificherai semplicemente il link di arrivo nell'htaccess in `https://www.santagatesinelmondo.it/santagatesi-illustri/`).*

### Esempio 2: Redirect di schede specifiche tramite Query String (es. Pagine Singole Personaggio)
Se sul vecchio sito ogni personaggio aveva una sua pagina singola generata via `.asp` (ad esempio `santagatesi_illustri.asp?id=12`), la direttiva `Redirect 301` standard *non funziona* perché c'è un punto interrogativo (Query String). Devi usare `mod_rewrite` nel file `.htaccess`:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On

# Redirect della vecchia scheda di "Tony Santagata" (ID 12) verso la nuova pagina WP
RewriteCond %{QUERY_STRING} ^id=12$ [NC]
RewriteRule ^santagatesi_illustri\.asp$ https://lnx.santagatesinelmondo.it/older/wordpress/personaggio-illustre/tony-santagata/? [R=301,L]

# Redirect della vecchia scheda di un altro personaggio (ID 15)
RewriteCond %{QUERY_STRING} ^id=15$ [NC]
RewriteRule ^santagatesi_illustri\.asp$ https://lnx.santagatesinelmondo.it/older/wordpress/personaggio-illustre/antonio-ricci/? [R=301,L]

</IfModule>
```
*(Il `?` finale alla riga RewriteRule serve a non copiare la stringa `?id=123` nel nuovo URL pulito di WordPress).*

### Raccomandazione Finale
Usa il file **.htaccess per le regole strutturali generiche** (es. forzare l'HTTPS, reindirizzare le vecchie categorie principali).
Usa il **Plugin Redirection tramite importazione CSV per mappare migliaia di singoli articoli**, poiché scrivere migliaia di righe RewriteCond nell'.htaccess renderebbe il caricamento di Apache inutilmente lento ad ogni singola richiesta.