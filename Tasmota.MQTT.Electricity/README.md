# Obis-Tasmota-MQTT-Counter

## Grundsätzliches
Das Modul dient dazu Smartmeter-Daten per [Tasmota Smartmeter-Interface](https://tasmota.github.io/docs/Smart-Meter-Interface/) in IP-Symcon einzubinden. 

## Konfiguration des Tasmota Smartmeter-Interfaces

* Die grundsätzliche Konfiguration des Tasmota Smartmeter-Interfaces findet man [hier](https://tasmota.github.io/docs/Smart-Meter-Interface/). 
* Beispielhaft sieht die Konfiguration wie folgt aus.
```
>D
>B

=>sensor53 r
>M 1
+1,3,s,0,9600,
1,77070100010800ff@1,Gesamtverbrauch,Wh,1.8.0,2
1,77070100020800ff@1,Gesamteinspeisung,Wh,2.8.0,2
1,77070100100700ff@1,Verbrauch,W,16.7.0,0
#
```
* Für die reibungslose Kommunikation mit IP-Symcon ist wichtig, dass die Namen und die Einheit den Obis-Standards aus folgender Tabelle entsprechen.

|OBIS<br>(C.D.E)|Einheit|Inhalt|
|---------------|-------|------|
|1.8.0|Wh|Zählerstand total +A|
|1.8.1|Wh|Zählerstand total +A, Tarif 1|
|1.8.2|Wh|Zählerstand total +A, Tarif 2|
|2.8.0|Wh|Zählerstand total -A|
|2.8.1|Wh|Zählerstand total -A, Tarif 1|
|2.8.2|Wh|Zählerstand total -A, Tarif 2|
|16.7.0|W|momentane Wirkleistung gesamt mit Vorzeichen|
|32.7.0|V|Spannung L1|
|52.7.0|V|Spannung L2|
|72.7.0|V|Spannung L3|
|31.7.0|A|Strom L1|
|51.7.0|A|Strom L2|
|71.7.0|A|Strom L3|
|81.7.1|°|Phasenwinkel U-L2 zu U-L1|
|81.7.2|°|Phasenwinkel U-L3 zu U-L1|
|81.7.4|°|Phasenwinkel I-L1 zu U-L1|
|81.7.15|°|Phasenwinkel I-L2 zu U-L2|
|81.7.26|°|Phasenwinkel I-L3 zu U-L3|
|14.7.0|Hz|Frequenz|

## Konfiguration in IP-Symcon

* Im Objektbaum eine ObisTasmotaMQTT-Instanz erzeugen. Hierdurch wird automatisch eine  Verbindung zum MQTT-Server angelegt und ggf. konfiguriert.
* Im letzten Schritt noch das MQTT-Topic des Tasmota-Smartmeter-Interfaces eingeben und speichern.

Fertig!

## Changelog

| Version | Änderungen							                    |
| --------|---------------------------------------------------------|
| V2.00   | Basisversion					            	        |

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