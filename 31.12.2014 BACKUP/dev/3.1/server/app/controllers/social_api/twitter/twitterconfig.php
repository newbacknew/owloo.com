<?php
class TwitterAccessTokens{
    
    private $counter = 0;
    private $counter_data = 0;
    private $code_app = array();
    
    
    function __construct(){
        
        $data = array();
        
        //@DavidDev13
        $data[] = array('user' => 'DavidDev13', 'Consumer_Key' => 'OLaIAjq3K77fgzViSwvw', 'Consumer_Secret' => '73giTNkPYk685LF9xnAI94bnHW0ggXrMCUv1GvNg', 'Access_Token' => '1583028896-8rak2KkxCaHK0UIPck7892xNXDxTFOVNDJ6iuMq', 'Access_Token_Secret' => '6Cr2jqrs5ebKcjfItywvEevey7Ty5CvYwm662Jr8YY');
        $data[] = array('user' => 'DavidDev13', 'Consumer_Key' => 'nRaooQE4mwU8Y1nq50682ovTc', 'Consumer_Secret' => 'khW9GlPeaXO0WKwh8yguZjhlr1lxxDYwCEVDO5TVHdmgubxNAn', 'Access_Token' => '1583028896-oFZ4qPGVAnXKyWo1piOXvQ0h69rQwHYqKKG5vLZ', 'Access_Token_Secret' => '0HvUNN3sFox5IwZCVlKfJkSuKvF1ixjtU3fkG5pMrutu8');
        $data[] = array('user' => 'DavidDev13', 'Consumer_Key' => 'OUKYqTtPqGAEi0IaajI9J3ytm', 'Consumer_Secret' => 'FTZw285j9wlng0T5X6R7KEsphMLCSy00eZC9NepipVZyrzHaIK', 'Access_Token' => '1583028896-n7g4QZ7dYHIa5mzTftvPZ7NgDn9kVCjZuHYyLjP', 'Access_Token_Secret' => 'gl5ue8TxZU8qL30A3XQBEOqWNhLhremrssdsotVt4mdI0');
        //@jcanesse78
        $data[] = array('user' => 'jcanesse78', 'Consumer_Key' => '3VJ0PLVMRQmnsOQAuhqvbw', 'Consumer_Secret' => 'Xkx5PjMFYez3AjeRbOBqcpXRocXdOzGAcmr6T94Yi7M', 'Access_Token' => '1583309112-ZpANrt5JfuLgAQNhb5b8xg3Fsio1yLQJdO7Q9YD', 'Access_Token_Secret' => '5GlNeqckg8FVChPqmHNCeMnalyM16FO0FWF61WZQ');
        $data[] = array('user' => 'jcanesse78', 'Consumer_Key' => 'VTZsdrsc4mjfNfWOLpGUtt5Fd', 'Consumer_Secret' => 'L7c4tJ1ZpU0QhHHLtNpSjYQhCvrfd02TvJlAUgY7iHOyYfAl0t', 'Access_Token' => '1583309112-8CcCLtrcysu1OhbqcqHZuuy3WWbLNuo5E7CByTz', 'Access_Token_Secret' => 'DYzuAIdYJqD7V5mICgzXTqiStLP2kRFKBELmbKwP0xrla');
        $data[] = array('user' => 'jcanesse78', 'Consumer_Key' => '2inFvOTQA4hQfJ7RgnZ5InAhJ', 'Consumer_Secret' => 'zNPToFxUm82wunLnt6AzavttlG0tas0hZimzFJ7OvYvt8ASVvW', 'Access_Token' => '1583309112-wyT56ec5o7kSHNDlzpubfTj27T8S9Rdc7VdZnr7', 'Access_Token_Secret' => 'pdQT82ruJ4AyTZEvfKM47tlifl5y4ASkmxyxlhXtEDOnz');
        //@jamendez567
        $data[] = array('user' => 'jamendez567', 'Consumer_Key' => 'O1Jm9EFa02Gab4PzDS15A', 'Consumer_Secret' => 'ADNe9arpPpOxS9mNXEoBc6kMDhavyUnRf1rL9USY', 'Access_Token' => '1583329698-ppgYU9kX4Hl1z9LIJnHVcqMPlZGS7eeDNhfaIG4', 'Access_Token_Secret' => 'PyAQcTr5ocAeWEJmaV36cW8rQHriqigoV84TtnXG1mQ');
        $data[] = array('user' => 'jamendez567', 'Consumer_Key' => 'ZrgKYkoKfrhCzJ1kip1ulPkLk', 'Consumer_Secret' => 'R1LgyvYgE89bW43Yh4eWGjwrLJOsMRgy47ki2wqoTvqDegWtKD', 'Access_Token' => '1583329698-ZRmfmKyZQaRJ3SgrWZOIz8DxR6ETHwHLM3129hX', 'Access_Token_Secret' => 'aW4XIeTpQVlaDmbM75n0DJFs1CpPx6VPUHm4Q3zb90nKk');
        $data[] = array('user' => 'jamendez567', 'Consumer_Key' => 't8F2H1jAAAe9SAymN1jgHu7KG', 'Consumer_Secret' => 'VaL1X7mBJZH8NVe5MPn0o36jqZRRyxEG9yRi011B0axa0Jnhrx', 'Access_Token' => '1583329698-lo2qfseNQkdMvHY9yZWGYPs4MxVHCPQMcS2N1wn', 'Access_Token_Secret' => 'GephyIq8fw0LeS75XNfRI9goLefg2zhr4qhQESCNPyC9a');
        //@latamowl
        $data[] = array('user' => 'latamowl', 'Consumer_Key' => 'vdBJNMze9lsaaaiUOqp82Q', 'Consumer_Secret' => 'BaXeOD4iCKNOnQiMgCyosT4eDe6S1EAty7l0JiiKZc', 'Access_Token' => '1583347998-WlPwYTdlvPwd97xbDV2mSMSdKbpRQ7qxMs0w7w7', 'Access_Token_Secret' => 'DyQcQgseBPkJnQxxK7yvXvrbyAZUbdnTH3plth3t9TY');
        $data[] = array('user' => 'latamowl', 'Consumer_Key' => 'Lp8st0QwoE2UpsPRMsf9JTKTj', 'Consumer_Secret' => 'RzdjnBu8R5jR1Bpb5LunwJY9Pmejzl9E9JyNYip4Ru2Cl4musO', 'Access_Token' => '1583347998-XLg89SsRYmRsjHydt3rWdhWlBIHUbnhIwlnkUEn', 'Access_Token_Secret' => 'ZjMWdVbX2rNs8jVd9WOU4pGH2bekLXOj7cCgXunma1GM8');
        $data[] = array('user' => 'latamowl', 'Consumer_Key' => 'fzi1YNSsUxAjYiuLJdfniwscV', 'Consumer_Secret' => 'xH7DupSFTzi5cbziHluuChvhyDSVU77mjzwHCdRK99q29HMmwe', 'Access_Token' => '1583347998-ayCFdKlMw8305KUQMMr6IP3yRI1wDpgVCc3SZla', 'Access_Token_Secret' => 'OCHum6X1uN3yYA01066fGu991zbg1JtQPR7h69GvrFqr6');
        //@decanesse
        $data[] = array('user' => 'decanesse', 'Consumer_Key' => 'LWKCxtHD72tSY0NutMGjNw', 'Consumer_Secret' => 'E12eymWuf9S4XY1YiFPNMtq5iddOtrZBeZIKHcaf8', 'Access_Token' => '1583406408-0kBfec1FpMVBcAyEsAhioAMeTgkmYT26luCfZjW', 'Access_Token_Secret' => 'hxVP4huEQmcV0o6yMaC3trrE1BW761IjeiRD6mrkP4');
        $data[] = array('user' => 'decanesse', 'Consumer_Key' => 'q80u7w14vMrNp6iihIRohXuZw', 'Consumer_Secret' => 'iJ4PxEV6cvZsgZUUIVlkkcs53n6vKsqDEUTfTLb3GTJRBBkMFW', 'Access_Token' => '1583406408-noG2uIX25HSFfxCALzHfKAUXgum3bAyuNbARSz5', 'Access_Token_Secret' => 'V7hpPJkRvX62teFhDnfqMoljZXBK9eAreAS6lGl3cNmP0');
        $data[] = array('user' => 'decanesse', 'Consumer_Key' => 'LkZK1j7HE6hInXcNm2O42CFWp', 'Consumer_Secret' => 'gCIYFh28HCbL95fGfQFiP4Uq7Cs41ojXEXHBeXGaWCLtJAMphG', 'Access_Token' => '1583406408-Cpk7IxEFrRvaNy9jyVTOSvs22jhcwjDg2PKBXR9', 'Access_Token_Secret' => 'w57KiX5D2o9y3Kugs6d4kZseleJiSr42yWXOMxyzRgs44');
        //@cccaaaaceres
        $data[] = array('user' => 'cccaaaaceres', 'Consumer_Key' => 'nsDh9lNOvvYV2hTpk6Nhw', 'Consumer_Secret' => 'QfhR0RJWpn0x1ZxprJ0rLbS1ElBrvW5GNgS4NDXKams', 'Access_Token' => '1623585456-tvo2HmP4d1GjBWGbppksUsuruWadllstmOTPJwg', 'Access_Token_Secret' => 'snbJ9c2OeSzEwdAAWK5JTp2mMQJN5zQn4npnmGJF0');
        //@JuanaJcaaastro
        $data[] = array('user' => 'JuanaJcaaastro', 'Consumer_Key' => 'BcWhNxxzK0Dj6lh7ftsg', 'Consumer_Secret' => 'iW00dZRO4NmsoNU6ryOPNFUFqtfdmPLUePxguWgPI', 'Access_Token' => '1623614676-3VtjP1U6a3U5lK54pPHIDFm80KxXux3OHfmQlLj', 'Access_Token_Secret' => 'e5blSIA5rTXeWJu37GmPS731jDwvYC87bCfLbg9fGc');
        //@MarcosdelCasti1
        $data[] = array('user' => 'MarcosdelCasti1', 'Consumer_Key' => '8XPo6roVscH7Q9dI50vQ', 'Consumer_Secret' => '7JSGJ5WzNasdE0s7qstcKZnzQq5jHRJALP87v97N4', 'Access_Token' => '1623687877-Okxh7pmIWt2u5dgyIGXdPKOEXdo6uAbsfUv9tG6', 'Access_Token_Secret' => '6by4OIMg0aRFa7GH3Na58lDf6kf8ierko6nCPOSa4c');
        //@JuanArguell
        $data[] = array('user' => 'JuanArguell', 'Consumer_Key' => 'kYLyF44BdKuXnOdwQIktw', 'Consumer_Secret' => 'U2Z1wiowCII2mCv1CReyX94aRcpJyxEzkO2TMbgzKk', 'Access_Token' => '1623718998-dvt8ClYNuA4XgnlmKKn8eXDgNvTsAY3yzo5OwqY', 'Access_Token_Secret' => 'alXcFKzKJrlJNIWKqW3cUjNRGkvWr4bs3bWVqw3Dc');
        //@jjgizmodo
        $data[] = array('user' => 'jjgizmodo', 'Consumer_Key' => 'sGkFaJ0SogRHPOMgUUTaVA', 'Consumer_Secret' => 'YiaDBA0dUqRZrr7L8kztXkLkOvhxEKbeZlXrrJR8g', 'Access_Token' => '1623770234-kSpKKeSpxamt2z8FqO6Ja6g1Db3Je4BljLKgBPs', 'Access_Token_Secret' => '5KQE4tW3vdBie7x4JnhUaomWKCmG2xso4f3yGDR3TM');
        shuffle($data);
        
        $this->counter_data = count($data);
        
        $this->code_app = $data;
        
    }
    

    public function getDataAccessToken() {
        
        if($this->counter >= $this->counter_data){
            return NULL;
        }
        
        $data = $this->code_app[$this->counter];
        
        $this->counter++;
        
        return $data;
        
    }
}