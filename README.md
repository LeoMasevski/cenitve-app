# Spletna aplikacija za upravljanje cenitev nepremičnin

## Opis projekta

To je preprosta spletna aplikacija za upravljanje cenitev nepremičnin. Aplikacija omogoča registracijo in prijavo uporabnika ter osnovno delo s cenitvami: dodajanje, pregledovanje, urejanje in brisanje.

Vsak prijavljen uporabnik lahko dostopa samo do svojih cenitev. Brisanje cenitve je izvedeno z uporabo JavaScript `fetch` zahtevka, zato se cenitev izbriše brez osvežitve celotne strani.

Projekt je izdelan kot demonstracija osnovnega razvoja spletne aplikacije z uporabo PHP, MySQL, HTML, CSS in JavaScript.

---

## Funkcionalnosti

- registracija uporabnika,
- prijava uporabnika,
- odjava uporabnika,
- dodajanje nove cenitve,
- pregled vseh cenitev prijavljenega uporabnika,
- urejanje obstoječe cenitve,
- brisanje cenitve brez osvežitve strani,
- osnovna validacija vnosnih podatkov,
- zaščita strani, ki so dostopne samo prijavljenim uporabnikom,
- povezava s podatkovno bazo MySQL,
- uporaba `.env` datoteke za lokalne nastavitve povezave z bazo.

---

## Podatki cenitve

Vsaka cenitev vsebuje naslednje podatke:

- naziv naročnika,
- naslov naročnika,
- namen cenitve,
- podlaga vrednosti,
- premisa vrednosti,
- datum in ura prvega ogleda.

Možnosti pri namenu cenitve:

- zavarovano posojanje,
- sodni postopek,
- stečajni postopek,
- računovodsko poročanje,
- davčni postopek,
- poslovna odločitev naročnika.

Možnosti pri podlagi vrednosti:

- tržna vrednost,
- likvidacijska vrednost,
- tržna najemnina,
- pravična vrednost.

Možnosti pri premisi vrednosti:

- sedanja ali obstoječa uporaba,
- najgospodarnejša uporaba,
- redna likvidacija.

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
│   ├── header.php
│   └── footer.php
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

V projektu je vključena datoteka `.env.example`, ki prikazuje primer potrebnih nastavitev.

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

1. Na začetni strani izberite možnost registracije.
2. Ustvarite uporabniški račun.
3. Prijavite se z e-pošto in geslom.
4. Po prijavi lahko dodate novo cenitev.
5. Na nadzorni plošči lahko vidite seznam svojih cenitev.
6. Posamezno cenitev lahko uredite ali izbrišete.
7. Pri brisanju se prikaže potrditveno vprašanje. Če uporabnik brisanje potrdi, se cenitev izbriše brez osvežitve celotne strani.

---

## Varnostne značilnosti

Aplikacija vključuje osnovne varnostne pristope:

- gesla se shranjujejo z uporabo `password_hash`,
- prijava preverja gesla z uporabo `password_verify`,
- SQL poizvedbe se izvajajo s pripravljenimi stavki PDO,
- strani za upravljanje cenitev so dostopne samo prijavljenim uporabnikom,
- uporabnik lahko dostopa samo do svojih cenitev,
- izpis uporabniških podatkov uporablja `htmlspecialchars`,
- podatki za povezavo z bazo so shranjeni v lokalni `.env` datoteki.