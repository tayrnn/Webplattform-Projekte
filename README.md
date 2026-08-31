# UniProjekte – Webplattform für Projektideen

UniProjekte ist eine mit Laravel entwickelte Webanwendung zur Verwaltung und Darstellung von Projektideen. Die Anwendung unterscheidet zwischen den Nutzerrollen Student, Lehrender und Administrator.

## 1. Voraussetzungen

Für die lokale Ausführung werden folgende Programme benötigt:

- Visual Studio Code  
  https://code.visualstudio.com/download

- XAMPP mit MySQL  
  https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.0.30/

- PHP 8.3 oder höher  
  Verwendet: PHP 8.5.6  
  https://www.php.net/downloads.php

- Composer  
  Verwendet: Composer 2.9.7  
  https://getcomposer.org/download/

- Node.js und npm  
  Verwendet: Node.js 24.15.0, npm 11.12.1  
  https://nodejs.org/en/download

- Git  
  Verwendet: Git 2.54.0  
  https://git-scm.com/downloads

- Laravel Framework 13  
  Verwendet: Laravel 13.7.0

Falls unter Windows eine benötigte Visual-C++-Laufzeitbibliothek fehlt:

https://learn.microsoft.com/en-us/cpp/windows/latest-supported-vc-redist?view=msvc-170

### Installation unter Windows

Visual Studio Code kann z. B. als ZIP-Datei heruntergeladen und nach

```text
C:\dev\Programme
```

entpackt werden. Anschließend `Code.exe` ausführen.

XAMPP kann z. B. nach

```text
C:\xampp
```

entpackt und über `xampp-control.exe` gestartet werden.

Bei der Composer-Installation muss gegebenenfalls die verwendete PHP-Datei ausgewählt werden, z. B.:

```text
C:\dev\Programme\php-8.5.10-Win32-vs17-x64\php.exe
```

Der konkrete Pfad kann je nach Installation abweichen.

Falls PHP, Node.js oder MySQL im Terminal nicht gefunden werden, müssen die entsprechenden Pfade gegebenenfalls zur Windows-Umgebungsvariable `Path` hinzugefügt werden.

Beispiel:

```text
C:\dev\Programme\Node.js
C:\xampp\php
C:\xampp\mysql\bin
```

## 2. Einrichtung

### Projekt herunterladen

```bash
git clone -b main https://github.com/tayrnn/Webplattform-Projekte.git ordner_name
cd ordner_name
```

Alternativ kann der Projektordner aus der ZIP-Datei entpackt werden.

Falls ein bereits vorhandener Projektordner verwendet wird, können die Ordner node_modules und vendor gelöscht werden. Sie werden später durch die Installation mit npm und Composer neu erstellt.

### Projekt in VS Code öffnen

```text
File -> Open Folder... -> ordner_name
```

Neues Terminal:

```text
Strg + ö
```

### PHP-Abhängigkeiten installieren

```bash
composer install
```

Typisches Problem: `fileinfo` ist nicht aktiviert.

In der verwendeten `php.ini` nach

```ini
extension=fileinfo
```

suchen und gegebenenfalls das Semikolon davor entfernen.

Beispielpfad:

```text
C:\dev\Programme\php-8.5.10-Win32-vs17-x64\php.ini
```

Danach `composer install` erneut ausführen.

Falls Composer ausdrücklich eine Aktualisierung verlangt, kann gegebenenfalls

```bash
composer update
```

notwendig sein.

### Node-Abhängigkeiten installieren

```bash
npm install
```

Typisches Problem unter Windows PowerShell:

Falls die Skriptausführung blockiert wird:

```powershell
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### `.env` erstellen

```powershell
Copy-Item .env.example .env -Force
```

Danach:

```bash
php artisan key:generate
```

### Datenbank einrichten

XAMPP öffnen und MySQL starten.

Eine Datenbank mit dem Namen

```text
webplattform
```

erstellen.

Die `.env` folgendermaßen konfigurieren:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webplattform
DB_USERNAME=root
DB_PASSWORD=
```

Danach:

```bash
php artisan migrate
```

Für einen vollständigen Neuaufbau inklusive Testdaten:

```bash
php artisan migrate:fresh --seed
```

Achtung: `migrate:fresh` löscht vorhandene Tabellen.

Typisches Problem bei der Datenbankverbindung:

In der verwendeten `php.ini` müssen folgende Erweiterungen aktiviert sein:

```ini
extension=mysqli
extension=pdo_mysql
```

## 3. Starten der Webanwendung

XAMPP öffnen und MySQL starten.

Frontend erstellen:

```bash
npm run build
```

Alternativ während der Entwicklung:

```bash
npm run dev
```

Laravel-Server starten:

```bash
php artisan serve
```

Anschließend die im Terminal angezeigte Adresse im Browser öffnen, z. B.:

```text
http://127.0.0.1:8000
```

Bei `npm run dev` muss der entsprechende Terminal-Prozess weiterlaufen.

## 4. Zugangsdaten und Konfiguration

Nach

```bash
php artisan migrate:fresh --seed
```

stehen folgende Testaccounts zur Verfügung:

| Rolle         | E-Mail             | Passwort     |
| ------------- | ------------------ | ------------ |
| Administrator | admin@example.com  | admin12345   |
| Student       | student@test.local | student12345 |
| Lehrender     | lehrer@test.local  | lehrer12345  |

Die Testaccounts werden über die Seeder unter

```text
database/seeders
```

erstellt.

### Mailtrap

Für den Test des E-Mail-Versands wird Mailtrap Sandbox verwendet.

Die E-Mail-Funktion wird unter anderem für das erstmalige Festlegen eines Passworts bei neu angelegten Nutzern sowie für „Passwort vergessen?“ verwendet.

Die persönlichen Mailtrap-Zugangsdaten sind nicht im Repository enthalten und müssen lokal in der `.env` eingetragen werden:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@uniprojekte.test"
MAIL_FROM_NAME="UniProjekte"
```

`MAIL_USERNAME` und `MAIL_PASSWORD` müssen durch die eigenen Mailtrap-SMTP-Zugangsdaten ersetzt werden.

Mailtrap Sandbox fängt Test-E-Mails ab und versendet sie nicht an reale E-Mail-Postfächer.

## 5. Testen der wichtigsten Funktionen

Nach der Einrichtung und dem Start der Anwendung können die wichtigsten Funktionen mit den unter Punkt 4 angegebenen Testaccounts geprüft werden.

### Anmeldung und Rollen

1. Mit einem Testaccount als Student, Lehrender oder Administrator anmelden.
2. Prüfen, ob die zur jeweiligen Rolle gehörende Ansicht angezeigt wird.
3. Prüfen, ob nur die für diese Rolle vorgesehenen Funktionen verfügbar sind.

### Projektverwaltung

1. Als Student anmelden und über „Neue Idee“ ein Projekt erstellen.
2. Projektname, Beschreibung, Kategorien und Sichtbarkeit festlegen und das Projekt speichern.
3. Prüfen, ob das Projekt in der entsprechenden Projektübersicht erscheint.
4. Das eigene Projekt öffnen und Bearbeitung, Statusänderung und Löschen testen.
5. Bei einem privaten Projekt prüfen, ob es nur für den Ersteller sichtbar ist.

### Suche und Filter

1. In der Projektübersicht einen vollständigen oder teilweisen Projektnamen in das Suchfeld eingeben.
2. Alternativ nach dem Namen eines Erstellers suchen.
3. Prüfen, ob die passenden Projekte angezeigt werden.
4. Über „Filter“ Kategorien oder Bearbeitungsstatus auswählen.
5. Prüfen, ob die Projektliste entsprechend gefiltert wird.
6. Suche und Filter können auch gemeinsam getestet werden.

### Diskussionen und Kommentare

1. Ein Projekt öffnen und eine Diskussion beziehungsweise einen Kommentar erstellen.
2. Prüfen, ob der neue Beitrag angezeigt wird.
3. Vorhandene eigene Beiträge bearbeiten beziehungsweise löschen und prüfen, ob die Änderungen übernommen werden.

### Umfragen und Bewertungen

1. Ein Projekt mit einer Umfrage öffnen und eine Antwort auswählen.
2. Abstimmen und prüfen, ob die Stimme übernommen wird.
3. Eine Sternebewertung für ein Projekt abgeben.
4. Prüfen, ob die Bewertung gespeichert und in der Bewertung des Projekts berücksichtigt wird.

### Betreuung von Projekten

1. Als Lehrender anmelden und die öffentlichen Projektideen öffnen.
2. Ein noch nicht betreutes Projekt auswählen und die Betreuung übernehmen.
3. Prüfen, ob das Projekt anschließend unter „Betreute Projekte“ angezeigt wird.

### Benutzerverwaltung

1. Als Administrator anmelden und die Nutzerverwaltung öffnen.
2. Einen neuen Benutzer mit Name, E-Mail-Adresse und Rolle anlegen.
3. Prüfen, ob der Benutzer in der Nutzerverwaltung erscheint.
4. Einen Benutzer bearbeiten beziehungsweise löschen und prüfen, ob die Änderung übernommen wird.

### Passwortfunktionen

1. Auf der Login-Seite „Passwort vergessen?“ auswählen und die E-Mail-Adresse eines vorhandenen Benutzers eingeben.
2. Prüfen, ob die entsprechende E-Mail in der Mailtrap Sandbox erscheint.
3. Über den enthaltenen Link ein neues Passwort festlegen und die Anmeldung damit testen.
4. Beim Anlegen eines neuen Benutzers durch einen Administrator kann auf die gleiche Weise geprüft werden, ob die E-Mail zum erstmaligen Festlegen des Passworts erzeugt wird.

Bei eingerichteter Mailtrap Sandbox kann zusätzlich geprüft werden, ob beim Anlegen eines neuen Nutzers die E-Mail zum Festlegen des Passworts erzeugt wird.
