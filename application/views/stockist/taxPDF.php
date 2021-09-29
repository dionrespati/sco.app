<?php

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->Open();
$pdf->SetFillColor(255, 255, 255); // background = biru muda
$pdf->SetTextColor(0, 0, 0);     //	font color = black
$pdf->SetDrawColor(0, 0, 0); // border 	   = brown
$pdf->SetLineWidth(.1); // border thickness = 0.3 (maybe in e.m)
//$pdf->AddPage();
//$pdf->setAutoPageBreak(false);

if($pjk != null) {

    foreach($pjk as $a){


        
        $pdf->AddPage();
        $pdf->Image("assets/images/ttd.jpeg", 170, 138, 28, 25);
        //$image1 = "assets/images/ttd.jpeg";
        //$pdf->Image($image1, 10, 230, 300, 300);
        //$pdf->setAutoPageBreak(false);

        $pdf->setFont('Arial', 'B', 8);


        $pdf->SetY(10);
        $pdf->SetX(5);
        $pdf->Cell(0, 35, "", 1, 1, 'C');

        $pdf->SetY(10);
        $pdf->SetX(60);
        $pdf->Cell(100, 25, "", 1, 1, 'C');

        $pdf->SetY(10);
        $pdf->SetX(60);
        $pdf->Cell(0, 35, "", 1, 1, 'C');

        $pdf->SetY(10);
        $pdf->SetX(60);
        $pdf->Cell(0, 25, "", 1, 1, 'C');

        $pdf->SetY(55);
        $pdf->SetX(5);
        $pdf->Cell(0, 30, "", 1, 1, 'C');

        $pdf->SetY(90);
        $pdf->SetX(5);
        $pdf->Cell(40, 25, "KODE OBJEK PAJAK", 1, 1, 'C');

        $pdf->SetY(90);
        $pdf->SetX(45);
        $pdf->Text(58, 99, 'JUMLAH');
        $pdf->Cell(40, 25, "PENGHASILAN BRUTO", 1, 1, 'C');
        $pdf->Text(61, 108, '(RP)');
        $pdf->Text(103, 108, '(RP)');
        $pdf->Text(153, 108, '(%)');
        $pdf->Text(180, 108, '(RP)');
        $pdf->Text(126, 98, 'TARIF LEBIH');
        $pdf->Text(127, 103, 'TINGGI 20%');
        $pdf->Text(126, 108, '(TIDAK BER-');
        $pdf->Text(130, 113, 'NPWP)');


        $pdf->SetY(90);
        $pdf->SetX(85);
        $pdf->Cell(40, 25, "DASAR PENGENAAN PAJAK", 1, 1, 'C');

        $pdf->SetY(90);
        $pdf->SetX(125);
        $pdf->Cell(20, 25, "", 1, 1, 'C');

        $pdf->SetY(90);
        $pdf->SetX(145);
        $pdf->Cell(20, 25, "TARIF", 1, 1, 'C');

        $pdf->SetY(90);
        $pdf->SetX(165);
        $pdf->Cell(0, 25, "PPh DIPOTONG", 1, 1, 'C');

        $pdf->SetY(115);
        $pdf->SetX(5);
        $pdf->Cell(40, 5, "(1)", 1, 1, 'C');

        $pdf->SetY(115);
        $pdf->SetX(45);
        $pdf->Cell(40, 5, "(2)", 1, 1, 'C');

        $pdf->SetY(115);
        $pdf->SetX(85);
        $pdf->Cell(40, 5, "(3)", 1, 1, 'C');

        $pdf->SetY(115);
        $pdf->SetX(125);
        $pdf->Cell(20, 5, "(4)", 1, 1, 'C');

        $pdf->SetY(115);
        $pdf->SetX(145);
        $pdf->Cell(20, 5, "(5)", 1, 1, 'C');

        $pdf->SetY(115);
        $pdf->SetX(165);
        $pdf->Cell(0, 5, "(6)", 1, 1, 'C');

        $pdf->SetY(138);
        $pdf->SetX(5);
        $pdf->Cell(0, 20, "", 1, 1, 'C');

        $pdf->SetY(140);
        $pdf->SetX(160);
        $pdf->Cell(38, 16, "", 1, 1, 'C');

        $pdf->SetY(165);
        $pdf->SetX(5);
        $pdf->SetFillColor(169, 169, 169);
        $pdf->Cell(0, 8, "KODE OBJEK PAJAK PENGHASILAN PASAL 21 (TIDAK FINAL) ATAU PASAL 26", 1, 1, 'C', TRUE);

        $pdf->SetY(173);
        $pdf->SetX(5);
        $pdf->Cell(0, 60, "", 1, 1, 'C');


        $pdf->Image("assets/images/logo-pajak.jpg", 20, 11, 25, 25);
        //$pdf->Image("assets/images/pajak/ttd.jpeg", 170, 138, 28, 25);

        $pdf->Text(11, 40, 'KEMENTERIAN KEUANGAN RI');
        $pdf->Text(10, 43, 'DIREKTORAT JENDERAL PAJAK');

        $pdf->Text(90, 15, 'BUKTI PEMOTONGAN PAJAK');
        $pdf->Text(85, 19, 'PENGHASILAN PASAL 21(TIDAK FINAL)');
        $pdf->Text(100, 23, 'ATAU PASAL 26');

        $pdf->Text(166, 19, 'FORMULIR 1721 - VI');

        $pdf->Text(8, 53, 'A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG');
        $pdf->Text(8, 88, 'B. PPh PASAL 21 DAN/ATAU PASAL 26 YANG DIPOTONG');
        $pdf->Text(8, 135, 'C. IDENTITAS PEMOTONG');

        $pdf->Text(15, 178, 'PPh PASAL 21 TIDAK FINAL ');

        $pdf->Text(15, 221, 'PPh PASAL 26 ');

        $Jumlah_Bruto = number_format($a->Jumlah_Bruto, 0, ".", ",");
        $Jumlah_DPP = number_format($a->Jumlah_DPP, 0, ".", ",");
        $Jumlah_PPh = number_format($a->Jumlah_PPh, 0, ".", ",");
        $Tanggal_Bukti_Potong = str_replace('/', '-', $a->Tanggal_Bukti_Potong);
        $npwp = $a->NPWP;
        $npwp_1 = substr($npwp, 0, 2);
        $npwp_2 = substr($npwp, 2, 3);
        $npwp_3 = substr($npwp, 5, 3);
        $npwp_4 = substr($npwp, 8, 1);
        $npwp_5 = substr($npwp, 9, 3);
        $npwp_6 = substr($npwp, 12, 3);
        $npwp_pemotong = $a->NPWP_Pemotong;
//            $npwp_p1 = substr($npwp_pemotong, 0, 9);
//            $npwp_p2 = substr($npwp_pemotong, 9, 3);
//            $npwp_p3 = substr($npwp_pemotong, 12, 3);
        $npwp_p1 = substr($npwp_pemotong, 0, 2);
        $npwp_p2 = substr($npwp_pemotong, 2, 3);
        $npwp_p3 = substr($npwp_pemotong, 5, 3);
        $npwp_p4 = substr($npwp_pemotong, 8, 1);
        $npwp_p5 = substr($npwp_pemotong, 9, 3);
        $npwp_p6 = substr($npwp_pemotong, 12, 3);
        $tgl1 = substr($Tanggal_Bukti_Potong, 0, 2);
        $tgl2 = substr($Tanggal_Bukti_Potong, 3, 2);
        $tgl3 = substr($Tanggal_Bukti_Potong, 6, 4);

        $txt = "$a->Alamat";
        $pjg = strlen($txt);

        $add1 = null;
        $add2 = null;

        if ($pjg > 70) {
            //echo "karakter lebih dari 70</br>";

            $tags = explode(' ', $txt);
            $jum = count($tags);
            $i = 1;
            foreach ($tags as $key) {
                //echo $i.'**'.$key .'</br>';

                if ($jum > 12) {
                    if ($i <= 12) {
                        $add1 = "$add1 $key";
                    } else {
                        $add2 = "$add2 $key";
                    }
                    $i++;
                } else {
                    $pot = $pjg / 2;
                    $add1 = substr($txt, 0, $pot + 1);
                    $add2 = substr($txt, $pot + 1, $pot);
                }

            }
        } else {
            //echo "karakter kurang atau sama dengan 70</br>";
            $add1 = $txt;
        }

        //echo "1. $add1</br>";
        //echo "2. $add2";


        $pdf->setFont('Arial', 'B', 8);
        $pdf->Text(65, 41, "NOMOR : ");
        $pdf->Text(8, 60, "1. NPWP : ");
        $pdf->Text(100, 60, "2. NIK/NO.PASPOR : ");
        $pdf->Text(8, 65, "3. NAMA : ");
        $pdf->Text(8, 70, "4. ALAMAT : ");
        $pdf->Text(8, 80, "5. WAJIB PAJAK LUAR NEGERI : ");
        $pdf->Text(100, 80, "6. KODE NEGARA DOMISILI : ");

        $pdf->Text(8, 145, "1. NPWP : ");
        $pdf->Text(8, 153, "2. NAMA : ");
        $pdf->Text(100, 145, "3. TANGGAL DAN TANDA TANGAN");

        $pdf->setFont('Arial', 'U', 8);
        $pdf->Text(85, 41, "$a->Nomor_Bukti_Potong");
        $pdf->Text(33, 60, "$npwp_1" . "." . "$npwp_2" . "." . "$npwp_3" . "." . "$npwp_4" . "-" . "$npwp_5" . "." . "$npwp_6");
        $pdf->Text(33, 65, "$a->Nama");
        $pdf->Text(33, 70, "$add1");
        $pdf->Text(33, 75, "$add2"); //alamat 2


        if ($a->NPWP = "000000000000000") {
            $x = "X";
        }

        $pdf->setFont('Arial', '', 8);
        $pdf->SetY(120);
        $pdf->SetX(5);
        $pdf->Cell(40, 8, "$a->Kode_Pajak", 1, 1, 'C');

        $pdf->SetY(120);
        $pdf->SetX(45);
        $pdf->Cell(40, 8, "$Jumlah_Bruto", 1, 1, 'C');

        $pdf->SetY(120);
        $pdf->SetX(85);
        $pdf->Cell(40, 8, "$Jumlah_DPP", 1, 1, 'C');

        $pdf->SetY(120);
        $pdf->SetX(125);
        $pdf->Cell(20, 8, "$x", 1, 1, 'C');

        $pdf->SetY(120);
        $pdf->SetX(145);
        $pdf->Cell(20, 8, "", 1, 1, 'C');

        $pdf->SetY(120);
        $pdf->SetX(165);
        $pdf->Cell(0, 8, "$Jumlah_PPh", 1, 1, 'C');

        $pdf->Text(120, 153, "-");
        $pdf->Text(128, 153, "-");

        $pdf->setFont('Arial', 'U', 8);
        $pdf->Text(33, 145, "$npwp_p1" . "." . "$npwp_p2" . "." . "$npwp_p3" . "." . "$npwp_p4" . "-" . "$npwp_p5" . "." . "$npwp_p6");
        $pdf->Text(33, 153, "$a->Nama_Pemotong");
        $pdf->Text(115, 153, "$tgl1");
        $pdf->Text(123, 153, "$tgl2");
        $pdf->Text(131, 153, "$tgl3");



        $pdf->setFont('Arial', '', 6);
        $pdf->Text(8, 183, '1. Upah Pegawai Tidak Tetap atau Tenaga Kerja Lepas ');
        $pdf->Text(8, 186, '2. 21-100-04 Imbalan Kepada Distributor Multi Level Marketing (MLM) ');
        $pdf->Text(8, 189, '3. 21-100-05 Imbalan Kepada Petugas Dinas Luar Asuransi ');
        $pdf->Text(8, 192, '4. 21-100-06 Imbalan Kepada Penjaja Barang Dagangan');
        $pdf->Text(8, 195, '5. 21-100-07 Imbalan Kepada Tenaga Ahli');
        $pdf->Text(8, 198, '6. 21-100-08 Imbalan Kepada Bukan Pegawai yang Menerima Penghasilan yang Bersifat Berkesinambungan');
        $pdf->Text(8, 201, '7. 21-100-09 Imbalan Kepada Bukan Pegawai yang Menerima Penghasilan yang Tidak Bersifat Berkesinambungan');
        $pdf->Text(8, 204, '8. 21-100-10 Honorarium atau Imbalan Kepada Anggota Dewan Komisaris atau Dewan Pengawas yang tidak Merangkap sebagai Pegawai Tetap ');
        $pdf->Text(8, 207, '9. 21-100-11 Jasa Produksi, Tantiem, Bonus atau Imbalan Kepada Mantan Pegawai ');
        $pdf->Text(8, 210, '10. 21-100-12 Penarikan Dana Pensiun oleh Pegawai');
        $pdf->Text(8, 213, '11. 21-100-13 Imbalan Kepada Peserta Kegiatan');
        $pdf->Text(8, 216, '12. 21-100-99 Objek PPh Pasal 21 Tidak Final Lainnya ');

        $pdf->Text(8, 224, '1. 27-100-99 Imbalan sehubungan dengan jasa, pekerjaan dan kegiatan, hadiah dan penghargaan, pensiun dan pembayaran berkala lainnya yang dipotong PPh Pasal 26');
        //$pdf->Text(25, 227, 'yang dipotong PPh Pasal 26');

        $pdf->Text(115, 156, "(dd - mm - yyyy)");

        $pdf->Text(78, 41, "H.01");
        $pdf->Text(23, 60, "A.01");
        $pdf->Text(130, 60, "A.02");
        $pdf->Text(23, 65, "A.03");
        $pdf->Text(26, 70, "A.04");
        $pdf->Text(54, 80, "A.05");
        $pdf->Text(68, 80, "YA");
        $pdf->Text(142, 80, "A.06");
        $pdf->Text(23, 145, "C.01");
        $pdf->Text(23, 153, "C.02");
        $pdf->Text(105, 153, "C.03");

        $pdf->setFont('Arial', '', 5);
        $pdf->Text(163, 24, "Lembar ke-1 : untuk Penerima Penghasilan");
        $pdf->Text(163, 27, "Lembar ke-2 : untuk Pemotong");

        $pdf->SetY(13);
        $pdf->SetX(185);
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(2, 2, "", 1, 1, 'C', TRUE);

        $pdf->SetY(13);
        $pdf->SetX(188);
        $pdf->Cell(2, 2, "", 1, 1, 'C');

        $pdf->SetY(13);
        $pdf->SetX(191);
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(2, 2, "", 1, 1, 'C', TRUE);

        $pdf->SetY(13);
        $pdf->SetX(194);
        $pdf->Cell(2, 2, "", 1, 1, 'C');

        $pdf->SetY(11);
        $pdf->SetX(5);
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(3, 1, "", 1, 1, 'C', TRUE);

        $pdf->SetY(11);
        $pdf->SetX(194);
        $pdf->SetFillColor(0,0,0);
        $pdf->Cell(3, 1, "", 1, 1, 'C', TRUE);

        $pdf->SetY(77);
        $pdf->SetX(60);
        $pdf->Cell(5, 5, "", 1, 1, 'C');

        $pdf->SetY(122);
        $pdf->SetX(132);
        $pdf->Cell(5, 5, "", 1, 1, 'C');



    }
}

else{

    $pdf=new FPDF('P','mm', 'A4');
    $pdf->Open();
    $pdf->SetFillColor(255,255,255); // background = biru muda
    $pdf->SetTextColor(0,0,0);	 //	font color = black
    $pdf->SetDrawColor(0,0,0); // border 	   = brown
    $pdf->SetLineWidth(.1); // border thickness = 0.3 (maybe in e.m)
    $pdf->AddPage();
    $pdf->setAutoPageBreak(false);

    $pdf->setFont('Arial', 'B', 8);

    $pdf->SetY(10);
    $pdf->SetX(5);
    $pdf->Cell(0, 35, "", 1, 1, 'C');

    $pdf->SetY(10);
    $pdf->SetX(60);
    $pdf->Cell(100, 25, "", 1, 1, 'C');

    $pdf->SetY(10);
    $pdf->SetX(60);
    $pdf->Cell(0, 35, "", 1, 1, 'C');

    $pdf->SetY(10);
    $pdf->SetX(60);
    $pdf->Cell(0, 25, "", 1, 1, 'C');

    $pdf->SetY(55);
    $pdf->SetX(5);
    $pdf->Cell(0, 30, "", 1, 1, 'C');

    $pdf->SetY(90);
    $pdf->SetX(5);
    $pdf->Cell(40, 25, "KODE OBJEK PAJAK", 1, 1, 'C');

    $pdf->SetY(90);
    $pdf->SetX(45);
    $pdf->Text(58, 99, 'JUMLAH');
    $pdf->Cell(40, 25, "PENGHASILAN BRUTO", 1, 1, 'C');
    $pdf->Text(61, 108, '(RP)');
    $pdf->Text(103, 108, '(RP)');
    $pdf->Text(153, 108, '(%)');
    $pdf->Text(180, 108, '(RP)');
    $pdf->Text(126, 98, 'TARIF LEBIH');
    $pdf->Text(127, 103, 'TINGGI 20%');
    $pdf->Text(126, 108, '(TIDAK BER-');
    $pdf->Text(130, 113, 'NPWP)');


    $pdf->SetY(90);
    $pdf->SetX(85);
    $pdf->Cell(40, 25, "DASAR PENGENAAN PAJAK", 1, 1, 'C');

    $pdf->SetY(90);
    $pdf->SetX(125);
    $pdf->Cell(20, 25, "", 1, 1, 'C');

    $pdf->SetY(90);
    $pdf->SetX(145);
    $pdf->Cell(20, 25, "TARIF", 1, 1, 'C');

    $pdf->SetY(90);
    $pdf->SetX(165);
    $pdf->Cell(0, 25, "PPh DIPOTONG", 1, 1, 'C');

    $pdf->SetY(115);
    $pdf->SetX(5);
    $pdf->Cell(40, 5, "(1)", 1, 1, 'C');

    $pdf->SetY(115);
    $pdf->SetX(45);
    $pdf->Cell(40, 5, "(2)", 1, 1, 'C');

    $pdf->SetY(115);
    $pdf->SetX(85);
    $pdf->Cell(40, 5, "(3)", 1, 1, 'C');

    $pdf->SetY(115);
    $pdf->SetX(125);
    $pdf->Cell(20, 5, "(4)", 1, 1, 'C');

    $pdf->SetY(115);
    $pdf->SetX(145);
    $pdf->Cell(20, 5, "(5)", 1, 1, 'C');

    $pdf->SetY(115);
    $pdf->SetX(165);
    $pdf->Cell(0, 5, "(6)", 1, 1, 'C');

    $pdf->SetY(138);
    $pdf->SetX(5);
    $pdf->Cell(0, 20, "", 1, 1, 'C');

    $pdf->SetY(140);
    $pdf->SetX(160);
    $pdf->Cell(38, 16, "", 1, 1, 'C');

    $pdf->SetY(165);
    $pdf->SetX(5);
    $pdf->SetFillColor(169, 169, 169);
    $pdf->Cell(0, 8, "KODE OBJEK PAJAK PENGHASILAN PASAL 21 (TIDAK FINAL) ATAU PASAL 26", 1, 1, 'C', TRUE);

    $pdf->SetY(173);
    $pdf->SetX(5);
    $pdf->Cell(0, 60, "", 1, 1, 'C');


    $pdf->Image("assets/images/pajak/logo-pajak.jpg", 20, 11, 25, 25);
    $pdf->Text(11, 40, 'KEMENTERIAN KEUANGAN RI');
    $pdf->Text(10, 43, 'DIREKTORAT JENDERAL PAJAK');

    $pdf->Text(90, 15, 'BUKTI PEMOTONGAN PAJAK');
    $pdf->Text(85, 19, 'PENGHASILAN PASAL 21(TIDAK FINAL)');
    $pdf->Text(100, 23, 'ATAU PASAL 26');

    $pdf->Text(166, 19, 'FORMULIR 1721 - VI');

    $pdf->Text(8, 53, 'A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG');
    $pdf->Text(8, 88, 'B. PPh PASAL 21 DAN/ATAU PASAL 26 YANG DIPOTONG');
    $pdf->Text(8, 135, 'C. IDENTITAS PEMOTONG');

    $pdf->Text(15, 178, 'PPh PASAL 21 TIDAK FINAL ');

    $pdf->Text(15, 221, 'PPh PASAL 26 ');

    $pdf->setFont('Arial', 'B', 8);
    $pdf->Text(65, 41, "NOMOR : ");
    $pdf->Text(8, 60, "1. NPWP : ");
    $pdf->Text(100, 60, "2. NIK/NO.PASPOR : ");
    $pdf->Text(8, 65, "3. NAMA : ");
    $pdf->Text(8, 70, "4. ALAMAT : ");
    $pdf->Text(8, 80, "5. WAJIB PAJAK LUAR NEGERI : ");
    $pdf->Text(100, 80, "6. KODE NEGARA DOMISILI : ");

    $pdf->Text(8, 145, "1. NPWP : ");
    $pdf->Text(8, 153, "2. NAMA : ");
    $pdf->Text(100, 145, "3. TANGGAL DAN TANDA TANGAN");

    $pdf->setFont('Arial', 'U', 8);
    $pdf->Text(85, 41, "");
    $pdf->Text(33, 60, "");
    $pdf->Text(33, 65, "");
    $pdf->Text(33, 70, "");
    $pdf->Text(33, 75, ""); //alamat 2

    $pdf->setFont('Arial', '', 8);
    $pdf->SetY(120);
    $pdf->SetX(5);
    $pdf->Cell(40, 8, "", 1, 1, 'C');

    $pdf->SetY(120);
    $pdf->SetX(45);
    $pdf->Cell(40, 8, "", 1, 1, 'C');

    $pdf->SetY(120);
    $pdf->SetX(85);
    $pdf->Cell(40, 8, "", 1, 1, 'C');

    $pdf->SetY(120);
    $pdf->SetX(125);
    $pdf->Cell(20, 8, "", 1, 1, 'C');

    $pdf->SetY(120);
    $pdf->SetX(145);
    $pdf->Cell(20, 8, "", 1, 1, 'C');

    $pdf->SetY(120);
    $pdf->SetX(165);
    $pdf->Cell(0, 8, "", 1, 1, 'C');

    //$pdf->Text(120, 153, "-");
    //$pdf->Text(128, 153, "-");

    $pdf->setFont('Arial', 'U', 8);
    $pdf->Text(33, 145, "");
    $pdf->Text(33, 153, "");
    $pdf->Text(115, 153, "");
    $pdf->Text(123, 153, "");
    $pdf->Text(131, 153, "");

    $pdf->setFont('Arial', '', 6);
    $pdf->Text(8, 183, '1. Upah Pegawai Tidak Tetap atau Tenaga Kerja Lepas ');
    $pdf->Text(8, 186, '2. 21-100-04 Imbalan Kepada Distributor Multi Level Marketing (MLM) ');
    $pdf->Text(8, 189, '3. 21-100-05 Imbalan Kepada Petugas Dinas Luar Asuransi ');
    $pdf->Text(8, 192, '4. 21-100-06 Imbalan Kepada Penjaja Barang Dagangan');
    $pdf->Text(8, 195, '5. 21-100-07 Imbalan Kepada Tenaga Ahli');
    $pdf->Text(8, 198, '6. 21-100-08 Imbalan Kepada Bukan Pegawai yang Menerima Penghasilan yang Bersifat Berkesinambungan');
    $pdf->Text(8, 201, '7. 21-100-09 Imbalan Kepada Bukan Pegawai yang Menerima Penghasilan yang Tidak Bersifat Berkesinambungan');
    $pdf->Text(8, 204, '8. 21-100-10 Honorarium atau Imbalan Kepada Anggota Dewan Komisaris atau Dewan Pengawas yang tidak Merangkap sebagai Pegawai Tetap ');
    $pdf->Text(8, 207, '9. 21-100-11 Jasa Produksi, Tantiem, Bonus atau Imbalan Kepada Mantan Pegawai ');
    $pdf->Text(8, 210, '10. 21-100-12 Penarikan Dana Pensiun oleh Pegawai');
    $pdf->Text(8, 213, '11. 21-100-13 Imbalan Kepada Peserta Kegiatan');
    $pdf->Text(8, 216, '12. 21-100-99 Objek PPh Pasal 21 Tidak Final Lainnya ');

    $pdf->Text(8, 224, '1. 27-100-99 Imbalan sehubungan dengan jasa, pekerjaan dan kegiatan, hadiah dan penghargaan, pensiun dan pembayaran berkala lainnya yang dipotong PPh Pasal 26');
    //$pdf->Text(25, 227, 'yang dipotong PPh Pasal 26');

    $pdf->Text(115, 156, "(dd - mm - yyyy)");

    $pdf->Text(78, 41, "H.01");
    $pdf->Text(23, 60, "A.01");
    $pdf->Text(130, 60, "A.02");
    $pdf->Text(23, 65, "A.03");
    $pdf->Text(26, 70, "A.04");
    $pdf->Text(54, 80, "A.05");
    $pdf->Text(68, 80, "YA");
    $pdf->Text(142, 80, "A.06");
    $pdf->Text(23, 145, "C.01");
    $pdf->Text(23, 153, "C.02");
    $pdf->Text(105, 153, "C.03");

    $pdf->setFont('Arial', '', 5);
    $pdf->Text(163, 24, "Lembar ke-1 : untuk Penerima Penghasilan");
    $pdf->Text(163, 27, "Lembar ke-2 : untuk Pemotong");

    $pdf->SetY(13);
    $pdf->SetX(185);
    $pdf->SetFillColor(0,0,0);
    $pdf->Cell(2, 2, "", 1, 1, 'C', TRUE);

    $pdf->SetY(13);
    $pdf->SetX(188);
    $pdf->Cell(2, 2, "", 1, 1, 'C');

    $pdf->SetY(13);
    $pdf->SetX(191);
    $pdf->SetFillColor(0,0,0);
    $pdf->Cell(2, 2, "", 1, 1, 'C', TRUE);

    $pdf->SetY(13);
    $pdf->SetX(194);
    $pdf->Cell(2, 2, "", 1, 1, 'C');

    $pdf->SetY(11);
    $pdf->SetX(5);
    $pdf->SetFillColor(0,0,0);
    $pdf->Cell(3, 1, "", 1, 1, 'C', TRUE);

    $pdf->SetY(11);
    $pdf->SetX(194);
    $pdf->SetFillColor(0,0,0);
    $pdf->Cell(3, 1, "", 1, 1, 'C', TRUE);

    $pdf->SetY(77);
    $pdf->SetX(60);
    $pdf->Cell(5, 5, "", 1, 1, 'C');

    $pdf->SetY(122);
    $pdf->SetX(132);
    $pdf->Cell(5, 5, "", 1, 1, 'C');

}

$title = "tax_report.pdf";
$pdf->SetTitle($title);
$pdf->Output();

//if($month < 10){
//    $title = $year."0".$month."-".$idmember.".pdf";
//    $pdf->SetTitle($title);
//    $pdf->Output($title, 'I');
//}else{
//    $title = $year.$month."-".$idmember.".pdf";
//    $pdf->SetTitle($title);
//    $pdf->Output($title, 'I');
//}

?>