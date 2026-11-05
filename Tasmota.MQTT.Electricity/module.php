<?php

class ObisTasmotaMQTT extends IPSModuleStrict
{

	#================================================================================================
    public function Create(): void
	#================================================================================================
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyString('MQTTTopic', '');
        $this->RegisterPropertyString('Counter', '');
        $this->RegisterPropertyBoolean('AddMissing', true);
    }

	#================================================================================================
    public function ApplyChanges(): void
	#================================================================================================
    {
        //Never delete this line!
        parent::ApplyChanges();

        #			Filter setzen
        $topic = $this->ReadPropertyString("MQTTTopic");
        if(strlen($topic) - strlen("SENSOR") - strrpos($topic, "SENSOR") !== 0){
            if(strlen($topic) - strlen("/") - strrpos($topic, "/") !== 0) $topic .= "/";
            $topic .= "SENSOR";
        }
        $filter = ".*\"Topic\":\"(".$topic."|tele/".$topic.").*";
        $this->SendDebug("ReceiveDataFilter", $filter, 0);

        $this->SetReceiveDataFilter($filter);
    }

	#================================================================================================
    public function GetCompatibleParents(): string
	#================================================================================================
    {
		return json_encode([
			'type' => 'require',
			'moduleIDs' => [
				// MQTT Server
				'{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}'
			]
		]);
	}

	#================================================================================================
    public function ReceiveData(string $JSONString): string
	#================================================================================================
    {
        $this->SendDebug("Received", $JSONString, 0);
        $data = json_decode($JSONString);
        $this->SendDebug("Received Payload", $data->Payload, 0);

        $Payload = json_decode($data->Payload);
        if(is_object($Payload)){
            foreach($Payload as $counter=>$counterData){
                if($this->ReadPropertyString("Counter") != '' && $this->ReadPropertyString("Counter") != $counter)continue;
                if(is_object($counterData)){
                    foreach($counterData as $index=>$value){
                        $this->SendDebug($index, $value, 0);
                        $this->AddValue($index, $value);
                    }
                }
            }
        }
        return '';
    }

	#================================================================================================
    private function GetProfile(mixed $type): string
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
    private function AddValue(string $Index, mixed $Value): void
	#================================================================================================
    {
        $type = explode('.', $Index)[0];
        $profile = $this->GetProfile($type);
        if($this->ReadPropertyBoolean('AddMissing')){
            if($profile != ''){
                $this->RegisterVariableFloat(md5($Index), $Index, $profile);
                if($type == 1 || $type == 2)$Value /= 1000;
            }else{
				if(is_bool($Value) || ($Value == "true") || ($Value == "false")){
					$this->RegisterVariableBoolean(md5($Index), $Index, '');
				}elseif(is_numeric($Value)){
					if(is_integer($Value + 0)){
    					$this->RegisterVariableInteger(md5($Index), $Index, '');
					}else{
    					$this->RegisterVariableFloat(md5($Index), $Index, '');
					}
				}else{
					$this->RegisterVariableString(md5($Index), $Index, '');
				}
            }
        } 
        if(@$this->GetIDForIdent(md5($Index)) != false) $this->SetValue(md5($Index), $Value);
    }
}
