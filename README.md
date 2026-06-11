# Spletna aplikacija za upravljanje cenitev nepremičnin

## Opis projekta

To je preprosta spletna aplikacija za upravljanje cenitev nepremičnin. Aplikacija omogoča registracijo in prijavo uporabnika ter delo s cenitvami: pregled, dodajanje, urejanje, brisanje, iskanje, filtriranje in razvrščanje.

Vsak prijavljen uporabnik lahko dostopa samo do svojih cenitev. Projekt je izdelan kot demonstracija osnovnega razvoja spletne aplikacije z uporabo PHP, MySQL, HTML, CSS in JavaScript.

---

## Funkcionalnosti

Aplikacija omogoča:

- registracijo uporabnika,
- prijavo uporabnika,
- odjavo uporabnika,
- pregled vseh cenitev prijavljenega uporabnika,
- dodajanje nove cenitve,
- urejanje obstoječe cenitve,
- brisanje cenitve,
- iskanje cenitev,
- filtriranje cenitev,
- razvrščanje cenitev.

Brisanje cenitve je izvedeno z uporabo JavaScript `fetch` zahtevka, zato se izbrana cenitev odstrani brez osvežitve celotne strani.

---

## Podatki cenitve

Vsaka cenitev vsebuje naslednje podatke:

| Podatek | Tip vnosa |
|---|---|
| Naziv naročnika | Ročni vnos |
| Naslov naročnika | Ročni vnos |
| Namen cenitve | Izbira iz vnaprej določenih možnosti |
| Podlaga vrednosti | Izbira iz vnaprej določenih možnosti |
| Premisa vrednosti | Izbira iz vnaprej določenih možnosti |
| Prvi ogled | Datum in ura |

### Možnosti pri namenu cenitve

- zavarovano posojanje,
- sodni postopek,
- stečajni postopek,
- računovodsko poročanje,
- davčni postopek,
- poslovna odločitev naročnika.

### Možnosti pri podlagi vrednosti

- tržna vrednost,
- likvidacijska vrednost,
- tržna najemnina,
- pravična vrednost.

### Možnosti pri premisi vrednosti

- sedanja ali obstoječa uporaba,
- najgospodarnejša uporaba,
- redna likvidacija.

---

## Dodatne izboljšave

Poleg osnovnih funkcionalnosti aplikacija vključuje tudi nekaj dodatnih izboljšav:

- osnovna validacija vnosnih podatkov,
- validacija oblike naslova naročnika,
- primeri in pojasnila pri ročnih vnosih,
- pametni pregled cenitve z opozorili in priporočili,
- odzivno oblikovanje za različne velikosti zaslona,
- bolj pregleden prikaz cenitev na manjših zaslonih,
- potrditveno vprašanje pred brisanjem cenitve.

Pametni pregled cenitve je pravili temelječ pomočnik, ki uporabnika opozori na morebitne nepopolne ali manj smiselne podatke. Namen te izboljšave je boljša uporabniška izkušnja in večja kakovost vnesenih podatkov.

---

## Uporabljene tehnologije

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Fetch API
- PDO
- XAMPP / Apache
- MySQL Workbench

---

## Struktura projekta

```text
cenitve-app/
│
├── api/
│   └── valuation_delete.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
│
├── config/
│   └── database.php
│
├── database/
│   └── schema.sql
│
├── includes/
│   ├── auth.php
│   ├── csrf.php
│   ├── footer.php
│   ├── header.php
│   ├── validation.php
│   └── valuation_assistant.php
│
├── public/
│   ├── index.php
│   ├── register.php
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── valuation_create.php
│   └── valuation_edit.php
│
├── .env
├── .env.example
├── .gitignore
└── README.md
```

---

## Zahteve za zagon

Za zagon aplikacije potrebujete:

- PHP,
- Apache strežnik,
- MySQL Server,
- MySQL Workbench ali drugo orodje za delo z MySQL,
- spletni brskalnik.

Najenostavnejša možnost za lokalni zagon je uporaba XAMPP okolja.

---

## Namestitev in zagon

### 1. Prenos projekta

Projektno mapo premaknite v mapo `htdocs` znotraj XAMPP namestitve.

Primer:

```text
C:\xampp\htdocs\cenitve-app
```

Aplikacija bo nato dostopna na naslovu:

```text
http://localhost/cenitve-app/public/index.php
```

---

### 2. Uvoz podatkovne baze

Odprite MySQL Workbench in se povežite na lokalni MySQL strežnik.

Nato odprite in zaženite SQL skripto:

```text
database/schema.sql
```

Skripta ustvari podatkovno bazo:

```text
cenitve_app
```

in potrebni tabeli:

```text
users
valuations
```

---

### 3. Nastavitev `.env` datoteke

V korenski mapi projekta ustvarite datoteko `.env`.

Primer:

```env
DB_HOST=localhost
DB_NAME=cenitve_app
DB_USER=root
DB_PASSWORD=vas_mysql_password
```

Datoteka `.env` vsebuje lokalne nastavitve za povezavo z bazo. Ta datoteka ni namenjena objavi v Git repozitoriju.

V projektu je vključena tudi datoteka `.env.example`, ki prikazuje primer potrebnih nastavitev.

---

### 4. Zagon strežnika

V XAMPP Control Panel zaženite:

```text
Apache
```

Preverite tudi, da MySQL Server deluje.

Če uporabljate samostojno nameščen MySQL Server, XAMPP MySQL ni potreben.

---

### 5. Odpri aplikacijo

V brskalniku odprite:

```text
http://localhost/cenitve-app/public/index.php
```

---

## Uporaba aplikacije

1. Na začetni strani izberite registracijo.
2. Ustvarite uporabniški račun.
3. Prijavite se z e-pošto in geslom.
4. Po prijavi se odpre nadzorna plošča s seznamom cenitev.
5. Dodajte novo cenitev.
6. Na nadzorni plošči lahko cenitve iščete, filtrirate in razvrščate.
7. Posamezno cenitev lahko uredite ali izbrišete.
8. Po končanem delu se lahko odjavite.

---

## Varnostne značilnosti

Aplikacija vključuje osnovne varnostne ukrepe:

- gesla se shranjujejo z uporabo `password_hash`,
- prijava preverja gesla z uporabo `password_verify`,
- gesla imajo določene zahteve glede dolžine in kompleksnosti,
- SQL poizvedbe se izvajajo s pripravljenimi PDO stavki,
- strani za upravljanje cenitev so dostopne samo prijavljenim uporabnikom,
- uporabnik lahko dostopa samo do svojih cenitev,
- izpis uporabniških podatkov uporablja `htmlspecialchars`,
- obrazci uporabljajo CSRF zaščito,
- brisanje prek `fetch` zahtevka uporablja CSRF žeton,
- prijava uporablja osnovno omejevanje neuspešnih poskusov,
- aplikacija uporablja časovno omejitev seje,
- zaščitene strani uporabljajo no-cache glave,
- podatki za povezavo z bazo so shranjeni v lokalni `.env` datoteki.

---

## Omejitve projekta

Aplikacija je zasnovana kot preprosta testna rešitev in ni namenjena neposredni produkcijski uporabi brez dodatnih nadgradenj.

Trenutne omejitve:

- ni potrjevanja registracije prek e-pošte,
- ni ponastavitve pozabljenega gesla,
- ni naprednega upravljanja vlog,
- ni zgodovine sprememb cenitev,
- ni izvoza podatkov,
- ni integracije z zunanjimi GIS sistemi.

Te omejitve so sprejemljive, ker je namen naloge prikaz osnovnega znanja razvoja spletne aplikacije, strukture kode in načina razmišljanja.

---

## Možne nadgradnje

Aplikacijo bi bilo mogoče nadgraditi z naslednjimi funkcionalnostmi:

- potrjevanje uporabniškega računa prek e-pošte,
- ponastavitev pozabljenega gesla,
- izvoz cenitev v PDF ali Excel,
- zgodovina sprememb posamezne cenitve,
- dodajanje priponk k cenitvi,
- naprednejše poročanje,
- integracija z zemljevidom ali GIS podatki,
- vloge uporabnikov.