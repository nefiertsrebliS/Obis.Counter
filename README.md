# Obis-Counter

## Grundsätzliches
Die Bibliothek dient dazu Obis basierte Zähler per Infrarot-Lese/Schreibkopf oder beliebige, Tasmota-kompatible Smart-Meter über ein Tasmota-Interface in IP-Symcon einzubinden. 

Typischerweise ist die Infrarot-Schnittstelle vom Netzversorger aus Datenschutzgründen gesperrt. Wer seinen Zähler in den eigenen 4 Wänden hat, oder wen fremde Blicke auf die eigenen Daten nicht stören, kann die Schnittstelle per PIN freischalten. Die PIN gibt es beim Netzversorger.

## Changelog

| Version | Änderungen							                    |
| --------|---------------------------------------------------------|
| V1.00   | Basisversion					            	        |
| V1.01   | Neu: Sende Eröffnungssequenz<br>Neu: weitere Formate   	|
| V1.04   | Optimierung der Programmstruktur                       	|
| V1.05   | Neu: Option zum Anlegen fehlender Variablen         	|
| V2.00   | Neu: OBIS-Tasmota-MQTT-Interface                     	|

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