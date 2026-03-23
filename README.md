# Tema WordPress Custom: Santagatesi nel Mondo

Benvenuto nel tema custom per **Santagatesi nel Mondo**. Questo tema è stato sviluppato con un approccio modulare, un design moderno "Glassmorphism" e layout responsivi.

## Gestione delle Immagini

Per mantenere le massime prestazioni e un controllo totale sul design, le immagini statiche del layout (come sfondi e banner di sezione) sono gestite tramite codice.

### Come caricare le immagini:
1. Accedi ai file del tuo sito (tramite FTP, cPanel o un gestore file del tuo hosting).
2. Naviga nella cartella del tema: `wp-content/themes/tuo-tema/`
3. Troverai (o dovrai creare) una cartella chiamata `images`.
4. Carica qui le tue immagini in formato `.jpg`, `.png` o `.webp` (es. `sfondo-hero.jpg`).

### Come modificare le immagini nel codice:
Se vuoi cambiare un'immagine di sfondo o un banner mostrato nel sito, devi modificare i file `.php` del tema (es. `template-parts/hero-section.php` o `page-chi-siamo.php`).

Cerca nel codice una riga simile a questa:
```php
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/nome-vecchia-immagine.jpg" alt="...">
```
Oppure per gli sfondi (stile inline):
```php
style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/sfondo-hero.jpg');"
```

Ti basterà cambiare `nome-vecchia-immagine.jpg` con il nome esatto del nuovo file che hai caricato nella cartella `/images`.

## Struttura del Tema
- **/template-parts/**: Contiene i blocchi della Home Page (Hero, Webcam, News, Video, Community).
- **page-chi-siamo.php**: Template per la pagina "Chi Siamo".
- **page-video.php**: Template per la griglia dei video YouTube (configura l'API key nel codice).
- **page-eventi.php**: Template per il calendario eventi (mostra articoli della categoria 'eventi').
- **page-libro-saluti.php**: Template per il Guestbook, che utilizza i commenti di WordPress stilizzati.
- **home.php**: Layout moderno a griglia per la pagina principale del blog (Artemisium News).
- **style.css**: Contiene tutte le regole di design (Glassmorphism, Striped Layout).
