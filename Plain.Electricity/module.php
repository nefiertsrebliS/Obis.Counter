<?php

class ObisPlain extends IPSModule
{

	#================================================================================================
    public function Create()
	#================================================================================================
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyInteger('Update', 1);

		#----------------------------------------------------------------------------------------
		# Timer zum Aktualisieren der Daten
		#----------------------------------------------------------------------------------------

		$this->RegisterTimer('Update', 0, 'IPS_RequestAction($_IPS["TARGET"], "OpenFilter", "");');
    }

	#================================================================================================
    public function ApplyChanges()
	#================================================================================================
    {
        //Never delete this line!
        parent::ApplyChanges();

        $this->ForceParent('{AC6C6E74-C797-40B3-BA82-F135D941D1A2}');

        $this->SetReceiveDataFilter("");
        $this->SetTimerInterval("Update", $this->ReadPropertyInteger('Update')*1000);
    }

	#================================================================================================
    public function GetConfigurationForParent() 
	#================================================================================================
    {
        return '{
                    "ParseType":0,
                    "LeftCutChar":"",
                    "LeftCutCharAsHex":true,
                    "RightCutChar":"0D 0A 21 0D 0A",
                    "RightCutCharAsHex":true,
                    "DeleteCutChars":false,
                    "InputLength":0,
                    "SyncChar":"",
                    "SyncCharAsHex":false,
                    "Timeout":0
                }';
    }

	#================================================================================================
	public function RequestAction($Ident, $Value) 
	#================================================================================================
	{
		switch($Ident) {
			case "OpenFilter":
                $this->SetReceiveDataFilter("");
				break;
		}
	}

	#================================================================================================
    public function ReceiveData($JSONString)
	#================================================================================================
    {
        $data = json_decode($JSONString);

        $Payload = utf8_decode($data->Buffer);
        $this->SendDebug("Received", $Payload, 0);

        foreach(explode(chr(13).chr(10), $Payload) as $line){
            $this->SendDebug("Line", $line, 0);
            $result = sscanf($line, "%d-%d:%d.%d.%d*%d(%f*%s");
            if(!isset($result[7])) continue;                    #   kein gültiger String
            if($result[0] != 1) continue;                       #   keine Elektrizität
            if($result[2] < 1 || $result[2] >= 96) continue;    #   kein Datentyp
            $result[7] = str_replace(')', '', $result[7]);

            $Index = vsprintf('%d.%d.%d*%d', array_slice($result, 2, 4));

            $this->RegisterVariableFloat(md5($Index), $Index, $this->GetProfile($result[7]));
            $this->SetValue(md5($Index), $result[6]);
        }
        $this->SetReceiveDataFilter(".*BLOCKED.*");
    }

	#================================================================================================
    private function GetProfile($unit)
	#================================================================================================
    {

        switch (strtolower($unit)) {
            case 'deg':
                if (!IPS_VariableProfileExists('Angle.EHZ')) {
                    IPS_CreateVariableProfile('Angle.EHZ', 2);
                    IPS_SetVariableProfileIcon('Angle.EHZ', 'Link');
                    IPS_SetVariableProfileText('Angle.EHZ', '', ' °');
                    IPS_SetVariableProfileDigits('Angle.EHZ', 1);
                }
                $Profile = 'Angle.EHZ';
                break;
            case 'w':
            case 'var':
            case 'va':
                $Profile = '~Watt';
                break;
            case 'kw':
            case 'kvar':
            case 'kva':
                $Profile = '~Power';
                break;
            case 'wh':
            case 'varh':
                $Profile = '~Electricity.Wh';
                break;
            case 'kwh':
            case 'kvarh':
                $Profile = '~Electricity';
                break;
            case 'a':
                $Profile = '~Ampere';
                break;
            case 'v':
                $Profile = '~Volt';
                break;

            case 'hz':
                $Profile = '~Hertz';
                break;
            
            default:
            $Profile = '';
            break;
        }

        return $Profile;
    }
}
