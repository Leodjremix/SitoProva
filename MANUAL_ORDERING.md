# Guida all'Ordinamento Manuale dei Contenuti (Drag & Drop)

Per gestire in modo intuitivo l'ordine dei componenti sulle pagine del sito "Santagatesi nel Mondo" (es. **Redazione**, **Link Utili**, **Personaggi Illustri**), il tema è stato aggiornato per supportare l'ordinamento manuale.

Tutti i relativi Custom Post Type ora supportano il campo nativo di WordPress `page-attributes`. Questo significa che il frontend mostrerà gli elementi rispettando esattamente l'ordine numerico assegnato nel database (il `menu_order`).

---

## 1. Come Ordinare i Post Senza Plugin (Ordinamento Standard WP)
1. Vai nella modifica del singolo post (es. "Modifica Link" o "Modifica Personaggio").
2. Guarda nella colonna destra dell'editor sotto la voce **"Attributi della pagina"** (Page Attributes).
3. Troverai un campo numerico chiamato **"Ordine"**.
4. Inserisci il numero (0 per il primo, 1 per il secondo, 2 per il terzo, ecc.).
5. Aggiorna.

## 2. Il Metodo Migliore: Plugin per Drag & Drop (Trascinamento)

Per evitare di dover aprire singolarmente ogni post per modificarne il numerino, ti suggerisco di installare un plugin leggerissimo che sblocca il trascinamento direttamente nella schermata riassuntiva del backend.

**Plugin Consigliato: "Simple Custom Post Order"** (o in alternativa "Post Types Order")
- **Leggerezza:** Aggiunge solo una funzione JavaScript nell'admin, non appesantisce il sito pubblico in nessun modo.
- **Gratuito:** Totalmente gratuito sul repository di WordPress.

### Come Installarlo e Configurarlo:
1. Dal Pannello WordPress, vai su **Plugin > Aggiungi nuovo**.
2. Cerca `Simple Custom Post Order`.
3. Installalo e Attivalo.
4. Vai su **Impostazioni > SCPOrder**.
5. Cerca la sezione "Controlla i tipi di post personalizzati" (o *Check to sort Post Types*).
6. Spunta **SOLO** le caselle che ti interessano per evitare di far confusione con articoli e pagine normali. Ad esempio spunta:
   - `santagatesi_team` (Collaboratori)
   - `personaggi_illustri` (Personaggi)
   - `link_utili` (Link Utili)
7. Salva le modifiche.

### Come usarlo
Adesso, se clicchi sulla voce "Team & Staff" nel menu di sinistra e apri la lista dei collaboratori, noterai che **passando il mouse sulle righe** il cursore diventa una croce direzionale.
Ti basta **cliccare, tenere premuto, spostare in alto o in basso la riga** e rilasciare. Il plugin salverà automaticamente l'ordine AJAX senza dover premere alcun tasto salva!

Il nuovo ordine si rifletterà automaticamente e in tempo reale sulla pagina "Chi Siamo".

---

## Note Tecniche (Per i Link Divisi per Categoria)

Nel template della pagina *Link Utili* (`page-utilita-e-links.php`), la struttura prevede la stampa di *Blocchi* separati (uno per ogni categoria, es. "Dove Mangiare", "Servizi Utili").

**Come funziona l'ordinamento in questo caso?**
Il codice del template è già stato impostato per eseguire una `WP_Query` all'interno di ogni categoria. Questo significa che l'ordine che imposterai trascinando le righe nel pannello admin verrà rispettato **all'interno del blocco di quella singola categoria**.

Se tiri su il "Ristorante A", apparirà come primissimo ristorante nel blocco "Dove Mangiare", indipendentemente dall'ordine globale mischiato agli altri tipi di link.