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
        $this->RegisterPropertyBoolean('sendOpeningSequence', false);

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
                if($this->ReadPropertyBoolean('sendOpeningSequence'))$this->SendOpeningSequence();
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

            $formats = array(
                "%d-%d:%d.%d.%d*%d(%f*%s",
                "%d-%d:%d.%d.%d(%f*%s",
                "%d.%d.%d(%f*%s",
            );

            foreach($formats as $key=>$format){
                $result = sscanf($line, $format);
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
	 
    #=====================================================================================
    public function GetConfigurationForm() 
    #=====================================================================================
    {
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'));
        foreach($form->elements as &$element){
            if(isset($element->items)){
                foreach($element->items as &$item){
                    $this->SendDebug('Item', json_encode($item), 0);
                    if(isset($item->name) && $item->name == "Update"){
                        $item->minimum = $this->ReadPropertyBoolean('sendOpeningSequence')?5:1;
                    }
                }
            }
        }
        return json_encode($form);
    }

	#================================================================================================
	private function SendOpeningSequence() 
	#================================================================================================
	{
        $this->SendDataToParent(json_encode([
            'DataID' => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",
            'Buffer' => utf8_encode('/?!'.chr(13).chr(10)),
        ]));
	}

	#================================================================================================
    private function AddValue($Index, $Value, $Profile)
	#================================================================================================
    {
        if(@$this->GetIDForIdent(md5($Index)) === false){
            $this->RegisterVariableFloat(md5($Index), $Index, $this->GetProfile($Profile));
        }
        $this->SetValue(md5($Index), $Value);
    }
}
