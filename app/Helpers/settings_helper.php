<?php 
if(!function_exists('settings')){
    
    function segment(){
        $request = \Config\Services::request();

        return $request;
    }

    function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        
        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
     
        if(strlen($tanggal) == 2){
            return $bulan[(int) $tanggal];
        }
        return $tanggal[0] == 0  ? "Belum di set"  : $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }

    function hariIndonesia($englishDay) {
        $hari = array(
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu'
        );
    
        return isset($hari[$englishDay]) ? $hari[$englishDay] : $englishDay;
    }

}
?>