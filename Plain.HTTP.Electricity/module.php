<?php

class ObisHTTP extends IPSModule
{

	#================================================================================================
    public function Create()
	#================================================================================================
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyBoolean('AddMissing', true);
    }

	#================================================================================================
    public function ApplyChanges()
	#================================================================================================
    {
        //Never delete this line!
        parent::ApplyChanges();

        $this->ForceParent('{4CB91589-CE01-4700-906F-26320EFCF6C4}');
    }

	#================================================================================================
    public function ReceiveData($JSONString)
	#================================================================================================
    {
        $data = json_decode($JSONString);

        $Buffer = utf8_decode($data->Buffer);
        $this->SendDebug("Received", $Buffer, 0);

        foreach(explode(chr(13).chr(10), $Buffer) as $Payload){
            $this->SendDebug("Payload", $Payload, 0);
            if($Payload == '!')return;

            $formats = array(
                "%d-%d:%d.%d.%d*%d(%f*%s",
                "%d-%d:%d.%d.%d(%f*%s",
                "%d.%d.%d(%f*%s",
            );

            foreach($formats as $key=>$format){
                $result = sscanf($Payload, $format);
                $count = substr_count($format, '%')-1;
                if(isset($result[$count])){
                    if(strstr($result[$count],'(') !== false)$result[$count] = explode('(',$result[$count])[0];
                    if(strstr($result[$count],')') !== false)$result[$count] = explode(')',$result[$count])[0];
                    switch($key){
                        case 0:
                            $this->AddValue(vsprintf('%d.%d.%d*%d', array_slice($result, 2, 4)), $result[6], $result[7]);
                            break;
                        case 1:
                            $this->AddValue(vsprintf('%d.%d.%d', array_slice($result, 2, 3)), $result[5], $result[6]);
                            break;                
                        case 2:
                            $this->AddValue(vsprintf('%d.%d.%d', array_slice($result, 0, 3)), $result[3], $result[4]);
                            break;                
                    }
                }
            }
        }
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

	#================================================================================================
	private function SendParent($Payload) 
	#================================================================================================
	{
        if(!$this->HasActiveParent()){
            $this->SendDebug('Error', 'Interface not active', 0);
            return;
        }
        $Payload .= chr(13).chr(10);
        $this->SendDataToParent(json_encode([
            'DataID' => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",
            'Buffer' => utf8_encode($Payload),
        ]));
        $this->SendDebug('SendParent', $Payload, 0);
	}

	#================================================================================================
    private function AddValue($Index, $Value, $Profile)
	#================================================================================================
    {
        if($this->ReadPropertyBoolean('AddMissing')) $this->RegisterVariableFloat(md5($Index), $Index, $this->GetProfile($Profile));
        if(@$this->GetIDForIdent(md5($Index)) === false)return;
        $this->SetValue(md5($Index), $Value);
    }
}
