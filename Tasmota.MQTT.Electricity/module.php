<?php

class ObisTasmotaMQTT extends IPSModule
{

	#================================================================================================
    public function Create()
	#================================================================================================
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyString('MQTTTopic', '');
        $this->RegisterPropertyBoolean('AddMissing', true);
    }

	#================================================================================================
    public function ApplyChanges()
	#================================================================================================
    {
        //Never delete this line!
        parent::ApplyChanges();

        $this->ConnectParent('{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}');
			
        #			Filter setzen
        $filter = ".*\"Topic\":\"".$this->ReadPropertyString("MQTTTopic").".*";
        $this->SendDebug("ReceiveDataFilter", $filter, 0);

        $this->SetReceiveDataFilter($filter);
    }

	#================================================================================================
    public function ReceiveData($JSONString)
	#================================================================================================
    {
        $this->SendDebug("Received", $JSONString, 0);
        $data = json_decode($JSONString);
        $this->SendDebug("Received Payload", $data->Payload, 0);

        $Payload = json_decode($data->Payload,true);
        if(is_array($Payload) && count($Payload) > 1){
            foreach(next($Payload) as $index=>$value){
                $this->SendDebug($index, $value, 0);
                $this->AddValue($index, $value);
            }
        }
    }

	#================================================================================================
    private function GetProfile($type)
	#================================================================================================
    {

        switch ($type) {
            case 81:
                if (!IPS_VariableProfileExists('Angle.EHZ')) {
                    IPS_CreateVariableProfile('Angle.EHZ', 2);
                    IPS_SetVariableProfileIcon('Angle.EHZ', 'Link');
                    IPS_SetVariableProfileText('Angle.EHZ', '', ' °');
                    IPS_SetVariableProfileDigits('Angle.EHZ', 1);
                }
                $Profile = 'Angle.EHZ';
                break;
            case 16:
                $Profile = '~Watt';
                break;
            case 'kw':
            case 'kvar':
            case 'kva':
                $Profile = '~Power';
                break;
            case 1:
            case 2:
                $Profile = '~Electricity';
                break;
            case 31:
            case 51:
            case 71:
                $Profile = '~Ampere';
                break;
            case 32:
            case 52:
            case 72:
                $Profile = '~Volt';
                break;

            case 14:
                $Profile = '~Hertz';
                break;
            
            default:
                $Profile = '';
                break;
        }

        return $Profile;
    }

	#================================================================================================
    private function AddValue($Index, $Value)
	#================================================================================================
    {
        $type = explode('.', $Index)[0];
        if($this->ReadPropertyBoolean('AddMissing')) $this->RegisterVariableFloat(md5($Index), $Index, $this->GetProfile($type));
        if(@$this->GetIDForIdent(md5($Index)) === false)return;
        if($type == 1 || $type == 2)$Value /= 1000;
        $this->SetValue(md5($Index), $Value);
    }
}
