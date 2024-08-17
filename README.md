# xampp-add-host.php

## Opis

`xampp\htdocs\dashboard\add-host.php` je alat za upravljanje virtualnim hostovima u XAMPP okruženju. Omogućava korisnicima da lako dodaju, pregledaju i brišu virtualne hostove, kao i da upravljaju SSL sertifikatima za njih.

## Funkcionalnosti

- **Dodavanje Virtualnih Hostova**: Omogućava korisnicima da kreiraju nove virtualne hostove. Prilikom dodavanja novog virtualnog hosta, automatski se kreira odgovarajući folder u `htdocs` direktorijumu i baza podataka sa imenom hosta.
- **Pregled Virtualnih Hostova**: Prikazuje listu svih postojećih virtualnih hostova.
- **Brisanje Virtualnih Hostova**: Omogućava korisnicima da izbrišu postojeće virtualne hostove. Prilikom brisanja, automatski se briše odgovarajući folder u `htdocs` direktorijumu i baza podataka.
- **Upravljanje SSL Sertifikatima**: Omogućava kreiranje i upravljanje SSL sertifikatima za virtualne hostove. UPRAVLJANJE SSL SERTIFIKATIMA TEK DOLAZI 

## Instalacija

1. **Preuzmi i instaliraj XAMPP**: [Preuzmi XAMPP](https://www.apachefriends.org/index.html) i instaliraj ga na svoj računar.

2. **Kloniraj ovaj repozitorijum**:

   ```bash
   git clone https://github.com/vujicicdragan/xampp-add-host.php.git
