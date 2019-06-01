<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

//fungsi format tanggal
function tgl_indo($tgl)
{
    $tanggal    = substr($tgl,8,2);
    $bulan      = getBulan(substr($tgl,5,2));
    $tahun      = substr($tgl,0,4);
    return $tanggal.' '.$bulan.' '.$tahun;
}

//fungsi membuat bulan
function getBulan($bln)
{
    switch ($bln) {
        case 1:
            return 'Januari';
            break;
        case 2:
            return 'Februari';
            break;
        case 3:
            return 'Maret';
            break;
        case 4:
            return 'April';
            break;
        case 5:
            return 'Mei';
            break;
        case 6:
            return 'Juni';
            break;
        case 7:
            return 'Juli';
            break;
        case 8:
            return 'Agustus';
            break;
        case 9:
            return 'September';
            break;
        case 10:
            return 'Oktober';
            break;
        case 11:
            return 'November';
            break;
        case 12:
            return 'Desember';
            break;
    }
}

//fungsi input data
function inputtext($name, $table, $field, $primary_key, $selected)
{
    $ci = get_instance();
    $data = $ci->db->get($table)->result();
    foreach ($data as $t) {
        if ($selected == $t->$primary_key) {
            $txt = $t->$field;
        }
    }
    return $txt;
}

//fungsi tampil data combobox
function combobox($name, $table, $field, $primary_key, $selected)
{
    $ci = get_instance();
    $cmb = "<select name='$name' class='form-control'>";
    $data = $ci->db->get($table)->result();
    $cmb .="<option value=''>-- PILIH --</option>";
    foreach ($data as $d) {
        $cmb .="<option value='".$d->$primary_key."'";
        $cmb .= $selected==$d->$primary_key?"selected='selected'":'';
        $cmb .=">".strtoupper($d->$field)."</option>";
    }
    $cmb .="</select>";
    return $cmb;
}

//fungsi konversi angka ke abjad
function skorNilai($nilai, $sks)
{
    if ($nilai == 'A') {
        $skor=4*$sks;
    }elseif ($nilai == 'B') {
        $skor=3*$sks;
    }elseif ($nilai == 'C') {
        $skor=2*$sks;
    }elseif ($nilai == 'D') {
        $skor=1*$sks;
    }else {
        $skor =0;
    }
    return $skor;
}

//fungsi untuk melakukan cek nilai
function cekNilai($nim,$kode,$nilKhs)
{
    $ci = get_instance();
    $ci->load->model('M_transkrip');

    $ci->db->select('*');
    $ci->db->from('transkrip');
    $ci->db->where('nim', $nim);
    $ci->db->where('kode_matakuliah'. $kode);
    $query = $ci->db->get()->row();
    if ($query!=null) {
        if ($nilKhs < $query->nilai) {
            $ci->db->set('nilai',$nilKhs)->where('nim',$nim)->where('kode_matakuliah'. $kode)->update('transkrip');
        }
    } else {
        $data = array(
                'nim'             => $nim,
                'nilai'           => $nilKhs,
                'kode_matakuliah' => $kode
        );
    }
}

?>