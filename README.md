# Obis-Counter

## Grundsätzliches
Die Bibliothek dient dazu Obis basierte Zähler per Infrarot-Lese/Schreibkopf in IP-Symcon einzubinden. Am weitesten verbreitet sind die elektronischen Hausstromzähler. 

**Sollte der Datenstrom SML-codiert sein, bitte die Bibliothek SML-Counter wählen.**

Typischerweise ist die Infrarot-Schnittstelle vom Netzversorger aus Datenschutzgründen gesperrt. Wer seinen Zähler in den eigenen 4 Wänden hat, oder wen fremde Blicke auf die eigenen Daten nicht stören, kann die Schnittstelle per PIN freischalten. Die PIN gibt es beim Netzversorger.

Für die Datenübertragung vom Zähler zum IP-Symcon-Server wird ein USB-Lese/Schreibkopf eingesetzt.

## Konfiguration

* In den IO-Instanzen einen Serial-Port erzeugen. Bei der Konfiguration der Baudrate bitte die Angaben des Zähler-Herstellers beachten. Typische Werte sind 300 oder 9600. 
* Im Objektbaum eine Obis_Plain-Instanz erzeugen. Hierdurch wird automatisch eine Cutter-Instanz im Splitter-Bereich angelegt und konfiguriert.
* Im letzten Schritt noch die Cutter-Instanz mit dem Serial-Port verbinden.

Fertig!

## Changelog

| Version | Änderungen							                    |
| --------|---------------------------------------------------------|
| V1.00   | Basisversion					            	        |
| V1.01   | Neu: Sende Eröffnungssequenz<br>Neu: weitere Formate   	|

## License

MIT License

Copyright (c) 2022 nefiertsrebliS

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.