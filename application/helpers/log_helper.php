<?php

function activity_log($database, $aksi, $keterangan)
{
  $ci = &get_instance();
  $ci->load->model('log_model');
  $ci->load->library('ion_auth');

  $user = $ci->ion_auth->user()->row();

  $data['logPengguna'] = $user->pengNama;
  $data['logTlp'] = $user->pengTlp;
  $data['logDatabase'] = $database;
  $data['logAksi'] = $aksi;
  $data['logKeterangan'] = $keterangan;

  $ci->log_model->create($data);
}


function rupiah($rupiah)
{
  return "Rp. " . number_format($rupiah, 0, ',', '.');
}


function array_date_range($from, $to, $params = null)
{
  if ($from > $to) {
    return false;
  }
  if ($from == null || $to == null) {
    return false;
  }



  $date_end = new DateTime($to);
  $end_date = $date_end->modify('+1 day')->format('Y-m-d');
  $period = new DatePeriod(
    new DateTime($from),
    new DateInterval('P1D'),
    new DateTime($end_date)
  );
  $date_array = iterator_to_array($period);
  $date = json_decode(json_encode($date_array));
  foreach ($date as $key => $value) {
    $date[$key]->tanggal = substr($value->date, 0, 10);
  }
  if ($params != null) {
    if ($params == 'count') return count($date);
  }
  return $date;
}
