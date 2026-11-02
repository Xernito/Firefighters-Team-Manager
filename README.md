# Firefighters Team Manager für Joomla 6

Mitglieder-, Abteilungs-, Dienstgrad-, Ausbildungs- und Terminverwaltung für Feuerwehren (Joomla-Komponente `com_firefighters`).

Dieser Zweig (`joomla6`) ist die Portierung der Joomla-3-Version von [veenmeyer/Firefighters-Team-Manager](https://github.com/veenmeyer/Firefighters-Team-Manager) auf **Joomla 6.x**.

## Voraussetzungen

* Joomla 6.0 oder neuer (entwickelt gegen 6.2)
* PHP 8.3 oder neuer
* MySQL 8.0.13+ / MariaDB 10.4+

## Installation

ZIP-Datei mit dem Inhalt dieses Repositorys (Wurzel = `firefighters.xml`) im Backend unter *System → Erweiterungen → Installieren* hochladen.

> **Beta-Hinweis:** Die Portierung ist nicht auf einer Live-Seite getestet. Vor dem Einsatz Backup von Webspace und Datenbank anlegen.

## Update von Joomla 3

Die Joomla-3-Version lässt sich nicht direkt auf Joomla 6 aktualisieren. Ablauf: Datenbank-Backup, Joomla auf 6.x aktualisieren (bzw. neue Joomla-6-Seite aufbauen, Tabellen `#__firefighters*` übernehmen), diese Version installieren. Das Update-Skript `administrator/sql/updates/mysql/2.0.0.sql` stellt die Tabellen auf utf8mb4 und NULL-fähige Datumsfelder um.

## Änderungen gegenüber der Joomla-3-Version

* Namespace `Firefighters\Component\Firefighters`, MVC über Service-Provider und Dependency Injection
* Neue Verzeichnisstruktur (`src/`, `tmpl/`, `forms/`, `services/`), Medien unter `media/com_firefighters`
* Backend-Listen mit Joomla-Search-Tools statt Sidebar-Filter (Mitglieder-/Termine-Zusatzfilter der Sidebar entfallen vorerst)
* Bootstrap 5, WebAssetManager statt `addScript`/`addStyleSheet`
* Update-Server der Joomla-3-Version entfernt (er würde Joomla-3-Pakete anbieten)
* Validierungsschlüssel: Anfrage an den Server des Autors nur noch, wenn im Backend ein Schlüssel eingetragen ist

## Lizenz

GNU General Public License Version 2 oder später (laut Manifest). Autor des Originals: Ralf Meyer, https://einsatzkomponente.de
