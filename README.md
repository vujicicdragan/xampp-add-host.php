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

   Korišćenje
Dodavanje novog virtualnog hosta:

Otvori http://localhost/dashboard/add-host.php u svom web pregledaču.
Popuni formu za dodavanje novog virtualnog hosta i klikni na "Submit".
Automatski će biti kreiran novi folder u htdocs direktorijumu i nova baza podataka sa imenom hosta.
Pregled postojećih virtualnih hostova:

Poseti http://localhost/dashboard/add-host.php da vidiš listu svih virtualnih hostova.
Brisanje virtualnog hosta:

Koristi opciju za brisanje pored svakog virtualnog hosta na stranici.
Automatski će biti obrisani odgovarajući folder u htdocs direktorijumu i baza podataka.
Konfiguracija
Apache Konfiguracija: Uveri se da su tvoji Apache httpd-vhosts.conf i hosts fajlovi pravilno konfigurirani za rad sa novim virtualnim hostovima.
SSL Sertifikati: Nakon što kreiraš virtualni host, možeš koristiti alat za upravljanje SSL sertifikatima da ih generišeš i dodeliš svom hostu.
Prilozi
Pitanja i Problemi: Ako naiđeš na bilo kakve probleme ili imaš pitanja, slobodno ih postavi na GitHub Issues.
Licenca
Ovaj projekat je licenciran pod MIT License.

Autor
Dragan Vujicic - vujicicdragan
xampp-add-host.php
Description
xampp\htdocs\dashboard\add-host.php is a tool for managing virtual hosts in a XAMPP environment. It allows users to easily add, view, and delete virtual hosts, as well as manage SSL certificates for them.

Features
Add Virtual Hosts: Allows users to create new virtual hosts. When adding a new virtual host, a corresponding folder in the htdocs directory and a database with the host's name are automatically created.
View Virtual Hosts: Displays a list of all existing virtual hosts.
Delete Virtual Hosts: Allows users to delete existing virtual hosts. Corresponding folder in the htdocs directory and database are automatically deleted.
Manage SSL Certificates: Allows for the creation and management of SSL certificates for virtual hosts. SSL CERTIFICATE MANAGEMENT IS COMING SOON
Installation
Download and Install XAMPP: Download XAMPP and install it on your computer.

Clone this Repository:

bash
Copy code
git clone https://github.com/vujicicdragan/xampp-add-host.php.git
Navigate to the Project Directory:

bash
Copy code
cd xampp/htdocs/dashboard
Place add-host.php in the htdocs Directory.

Start XAMPP and ensure that the Apache server is running.

Usage
Add a New Virtual Host:

Open http://localhost/dashboard/add-host.php in your web browser.
Fill out the form to add a new virtual host and click "Submit".
A new folder in the htdocs directory and a database with the host's name will be automatically created.
View Existing Virtual Hosts:

Visit http://localhost/dashboard/add-host.php to see a list of all virtual hosts.
Delete a Virtual Host:

Use the delete option next to each virtual host on the page.
Corresponding folder in the htdocs directory and database will be automatically deleted.
Configuration
Apache Configuration: Make sure your Apache httpd-vhosts.conf and hosts files are properly configured to work with the new virtual hosts.
SSL Certificates: After creating a virtual host, you can use the SSL certificate management tool to generate and assign SSL certificates to your host.
Contributing
Questions and Issues: If you encounter any problems or have questions, feel free to post them on GitHub Issues.
License
This project is licensed under the MIT License.

Author
Dragan Vujicic - vujicicdragan
