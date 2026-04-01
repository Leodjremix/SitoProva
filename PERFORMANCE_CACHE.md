# Guida all'Ottimizzazione delle Prestazioni e Gestione Cache

Per rendere il portale "Santagatesi nel Mondo" leggerissimo e scattante, specialmente considerando la presenza massiccia di embed video (YouTube) e stream (Webcam H24), è fondamentale applicare una strategia a tre livelli:

1. **Caching e Ottimizzazione Lato Server (.htaccess)**
2. **Page Caching lato WordPress (Plugin)**
3. **Lazy Loading intelligente dei Media**

---

## 1. Ottimizzazione Lato Server (.htaccess)
La prima cosa da fare è istruire il server (es. Aruba, SiteGround, ecc.) a comprimere i file di testo (HTML, CSS, JS) e a dire ai browser dei visitatori di memorizzare localmente immagini e loghi per non doverli scaricare di nuovo.

Copia e incolla queste regole in cima al file `.htaccess` situato nella cartella principale del tuo sito (prima di `# BEGIN WordPress`):

```apache
<IfModule mod_deflate.c>
    # Abilita la compressione GZIP per rendere i file più leggeri
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json application/xml image/svg+xml
</IfModule>

<IfModule mod_expires.c>
    # Abilita il Browser Caching (Salva le risorse statiche nel PC dell'utente)
    ExpiresActive On

    # Immagini, Video, Audio e Font: Conservali per 1 anno
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"

    # CSS e Javascript: Conservali per 1 mese
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"

    # HTML: Non salvare in cache (assicura che vedano i nuovi articoli)
    ExpiresByType text/html "access plus 0 seconds"
</IfModule>
```

---

## 2. Scelta del Plugin di Caching per WordPress

Per un tema personalizzato leggero come il nostro, ti consiglio uno di questi due stack a seconda del tuo Hosting:

### Opzione A: LiteSpeed Cache (Altamente Consigliato)
**Se il tuo server utilizza LiteSpeed Web Server** (es. Hostinger o alcuni piani SiteGround/Aruba), scarica il plugin gratuito **LiteSpeed Cache**.
* **Perché:** Dialoga direttamente a livello server. È il più potente sul mercato.
* **Configurazione ideale:**
  * Abilita la "Cache delle Pagine".
  * Vai su *Ottimizzazione Pagina* -> *CSS Settings* -> Abilita "Minify CSS".
  * Vai su *Media Settings* -> Abilita "Lazy Load Images".

### Opzione B: WP Super Cache (L'alternativa sicura per server Apache)
Se il tuo server usa Apache classico, **WP Super Cache** è la scelta migliore.
* **Perché:** A differenza di W3 Total Cache (che è molto complesso e può rompere temi custom se configurato male), WP Super Cache genera semplicemente dei file HTML statici "fotocopia" delle tue pagine PHP.
* **Configurazione ideale:**
  * Vai in Impostazioni -> WP Super Cache.
  * Seleziona *Caching On*.
  * Nel tab *Advanced*, seleziona "Enable cache delivery with mod_rewrite" (molto veloce).
  * Spunta "Don't cache pages for known users" (Così non vedi la cache quando sei loggato per scrivere articoli).

---

## 3. Gestione del Lazy Loading (Video e Webcam)

Le iframe di YouTube e delle Webcam pesano moltissimo (possono caricare da 1 a 3 MB di script a pagina vuota).

### Lazy Loading Nativo delle Immagini
WordPress integra già il Lazy Loading per le immagini (aggiunge `loading="lazy"` in automatico). Il nostro tema lo sfrutta anche nella galleria personalizzata:
```html
<img src="..." loading="lazy" class="...">
```
Questo assicura che le foto degli articoli vengano scaricate dal browser solo quando l'utente fa scroll verso di esse.

### Il Lazy Loading Aggressivo per Video e Webcam (Già implementato!)
Nel tema che abbiamo appena sviluppato, i video pesanti **NON si caricano mai all'apertura della pagina**. Abbiamo utilizzato un approccio noto come *Facade* accoppiato alla gestione GDPR:

1. **Per YouTube (Video):**
   * Sulla home e nella galleria mostriamo solo una semplice immagine JPEG (la thumbnail del video) o un segnaposto grigio.
   * C'è un tasto "Play" virtuale in SVG.
   * L'iframe vero e proprio pesa centinaia di KB, ma il browser **non lo scarica** finché l'utente non clicca espressamente sull'immagine. A quel punto si apre il Lightbox Vanilla JS che inietta il codice YouTube.

2. **Per le Webcam:**
   * Le iframe di SkylineWebcams sono salvate come attributo `data-src="..."` e non `src="..."`.
   * Il browser ignora `data-src`, quindi la pagina si carica istantaneamente a peso "Zero".
   * Solo quando l'utente accetta il Banner Cookie GDPR, uno script JavaScript sposta il contenuto di `data-src` dentro `src`, accendendo le telecamere.

Questa architettura rende il tuo sito estremamente reattivo e perfettamente in linea con i nuovi standard "Core Web Vitals" di Google.
