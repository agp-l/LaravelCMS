# Návod k instalaci a údržbě CMS

Tento dokument slouží jako kompletní průvodce pro nasazení, instalaci a údržbu tohoto Laravel CMS. Obsahuje důležité informace o struktuře projektu, řešení problémů na sdílených hostingových službách a slovníček základních pojmů.

---

# 1. Jak funguje složka `public/` (DŮLEŽITÉ)

Laravel je postaven na moderních bezpečnostních standardech.

**Jediná složka, která má být přístupná z internetu, je složka `public/`.**

Všechny ostatní složky (`app`, `config`, `.env`, atd.) musí zůstat před návštěvníky skryté, aby nikdo nemohl získat přístup k databázovým údajům nebo zdrojovému kódu aplikace.

Ve složce `public/` se nachází:

* `index.php` – hlavní vstupní bod aplikace
* `.htaccess` – pravidla pro hezké URL adresy a směrování požadavků

---

# 2. Příprava na hostingu

Například na sdíleném hostingu můžeš celý projekt nahrát do složky:

```
sub/test/
```

Problém je, že většina hostingů automaticky směřuje návštěvníky do této složky, nikoliv do:

```
sub/test/public/
```

Bez správného nastavení Laravel nebude fungovat.

## Možnost A: Nastavení Document Root (doporučeno)

1. Přihlas se do administrace hostingu.
2. Otevři nastavení domény nebo subdomény.
3. Najdi položku **Document Root** nebo **Cílový adresář**.
4. Nastav cestu tak, aby končila složkou `public`.

Příklad:

```
sub/test/public
```

---

## Možnost B: Přesměrovací `.htaccess`

Pokud hosting změnu Document Root nepodporuje, vytvoř v kořenové složce projektu nový soubor `.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Tento soubor automaticky přesměruje všechny požadavky do složky `public`.

---

# 3. Konfigurace a první instalace

Před spuštěním instalátoru je potřeba připravit konfigurační soubor.

## Vytvoření souboru `.env`

V kořenové složce projektu najdeš například:

```
.env.example
```

nebo vlastní šablonu:

```
.env.easy
```

Soubor zkopíruj nebo přejmenuj na:

```
.env
```

---

## Nastavení oprávnění

Soubor `.env` musí být zapisovatelný, aby do něj mohl instalátor uložit nastavení databáze.

Typicky:

```
CHMOD 664
```

V krajním případě:

```
CHMOD 777
```

---

## Spuštění instalace

1. Otevři hlavní adresu webu.
2. Pokud systém není nainstalovaný, automaticky tě přesměruje na:

```
https://tvojedomena.cz/install
```

3. Vyplň údaje k databázi.
4. Vytvoř administrátorský účet.
5. Dokonči instalaci.

---

# 4. Jak provést čistou reinstalaci CMS

Pokud chceš systém uvést do továrního nastavení, proveď následující kroky.

## 1. Vymazání databáze

Přihlas se do databáze například přes phpMyAdmin a smaž všechny tabulky aplikace.

Nezapomeň odstranit také tabulku:

```
migrations
```

---

## 2. Odstranění instalačního zámku

Ve složce:

```
storage/
```

smaž soubor:

```
installed
```

Dokud tento soubor existuje, instalátor se nespustí.

---

## 3. Vymazání cache konfigurace

Ve složce:

```
bootstrap/cache/
```

smaž soubory:

```
config.php
routes.php
```

(pokud existují)

Jinak může Laravel používat staré nastavení a ignorovat změny v souboru `.env`.

---

## 4. Reset souboru `.env`

Vymaž hodnoty:

```env
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
DB_PREFIX=
```

Po otevření webu se znovu spustí instalační průvodce.

---

# 5. Co je Artisan?

Artisan je příkazová řádka Laravelu.

Pomáhá automatizovat běžné úkoly, například:

* vytváření souborů
* spouštění migrací
* čištění cache
* generování klíčů

Používá se z terminálu:

```bash
php artisan [příkaz]
```

Příklad:

```bash
php artisan migrate
```

---

# 6. Co jsou migrace?

Migrace fungují jako verzování databáze.

Místo ručního vytváření tabulek v phpMyAdminu popíšeš strukturu databáze v PHP souborech.

Například:

* vytvoření tabulky článků
* přidání sloupce `title`
* přidání sloupce `content`

Při instalaci Laravel automaticky vytvoří databázi podle těchto migračních souborů.

Díky tomu mají všichni uživatelé CMS stejnou databázovou strukturu.

---

# 7. Užitečné terminálové příkazy

## Spuštění migrací

```bash
php artisan migrate
```

Vytvoří nebo aktualizuje databázové tabulky.

---

## Kompletní obnova databáze

```bash
php artisan migrate:fresh
```

Smaže všechny tabulky a vytvoří je znovu.

**Pozor: Tento příkaz nenávratně smaže všechna data.**

---

## Vygenerování APP_KEY

```bash
php artisan key:generate
```

Vytvoří nový šifrovací klíč a uloží ho do souboru `.env`.

---

## Vymazání cache konfigurace

```bash
php artisan config:clear
```

Použij při změnách v souboru `.env`.

---

## Vymazání cache rout

```bash
php artisan route:clear
```

Vyčistí uložené routy.

---

## Vymazání cache šablon

```bash
php artisan view:clear
```

Vymaže zkompilované Blade šablony.

---

## Publikování souborů balíčků

```bash
php artisan vendor:publish
```

Zkopíruje konfigurační a další soubory balíčků do projektu, aby je bylo možné upravovat.

Příklad použití:

* Spatie MediaLibrary
* Laravel Passport
* další Laravel balíčky

---

# Doporučení při řešení problémů

Pokud Laravel ignoruje změny v konfiguraci, většinou pomůže:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Případně zkontroluj:

* obsah souboru `.env`
* práva souborů
* správné nastavení složky `public`
* existenci souboru `storage/installed`
