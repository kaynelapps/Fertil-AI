<?php


namespace App\Traits;

use Yajra\DataTables\Services\DataTable;

trait EncryptionTrait {

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    function getKeyAndIV() 
    {
        $keyUtf8 = env('SECRET_KEY');
        $ivUtf8 = env('VIKEY');
        
        if (empty($keyUtf8) || empty($ivUtf8)) {
            return; 
        }
        
        $key = substr(hash('sha256', $keyUtf8, true), 0, 32);
        $iv = substr(hash('sha256', $ivUtf8, true), 0, 16);
    
        return [$key, $iv];
    }


    public function decryptData($encryptedData)
    {
        if(empty($this->getKeyAndIV())) {
            return __('message.key_value_set');
        }

        $key = substr(hash('sha256', env('SECRET_KEY')), 0, 32); 
        $iv = substr(hash('sha256', env('VIKEY')), 0, 16); 
    
        $decryptedData = openssl_decrypt(
            base64_decode($encryptedData), 
            'AES-256-CBC', 
            $key, 
            OPENSSL_RAW_DATA, 
            $iv
        );

        return $decryptedData;
    }
    public function encryptData($decryptData)
    {
        if(empty($this->getKeyAndIV())) {
            return __('message.key_value_set');
        }

        if(env('DATA_ENCRYPTION')){
            $key = substr(hash('sha256', env('SECRET_KEY')), 0, 32);
        
            $iv = substr(hash('sha256', env('VIKEY')), 0, 16);
            
             $jsonData = json_encode($decryptData);
             $encryptedData = openssl_encrypt(
                  $jsonData, 
                 'AES-256-CBC', 
                 $key, 
                 OPENSSL_RAW_DATA, 
                 $iv
                );
        
            
            return base64_encode($encryptedData);
        }else{
            return $decryptData;
        }

    }
}
