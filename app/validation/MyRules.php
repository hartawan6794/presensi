<?php

namespace App\Validation;

use App\Models\UserModel;

class MyRules
{

    public function userExist(string $str, string $fields, array $data)
    {
        $m = new UserModel();
        $d = $m->where('username', $data['username'])->first();
        if (!$d) {
            return true;
        }
        return false;
    }


     // Fungsi validasi khusus
     public function cekSpasi(string $str, string $fields): bool
     {
         if (preg_match('/\s/', $str)) {
             return false; // Return false jika ada spasi
         }
 
         return true; // Return true jika tidak ada spasi
     }
}
