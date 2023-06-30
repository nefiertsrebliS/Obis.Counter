# Obis-HTTP-Counter

## Grundsätzliches
Das Modul dient dazu Obis basierte Zähler über eine HTTP-Schnittstelle wie z.B. von Tibber in IP-Symcon einzubinden.  

**Sollte der Datenstrom SML-codiert sein, bitte die Bibliothek SML-Counter wählen.**

## Konfiguration

* Im Objektbaum eine ObisHTTP-Instanz erzeugen. Hierdurch wird automatisch eine HTTP-Client-Instanz angelegt.
* In der HTTP-Client-Instanz mindestens die URL und das Update-Interval eintragen und speichern.

Fertig!

## Changelog

| Version | Änderungen							                    |
| --------|---------------------------------------------------------|
| V2.02   | Basisversion					            	        |

## License

MIT License

Copyright (c) 2023 nefiertsrebliS

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