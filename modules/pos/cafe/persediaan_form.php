<? @session_start(); include "otentik_admin.php"; ?><head>
 
	<script type="text/javascript" src="../assets/jquery-1.2.3.pack.js"></script>
<script type="text/javascript" src="../assets/jquery.validate.pack.js"></script>
 <script language="javascript" src="../assets/thickbox/thickbox.js"></script>
 <link href="../assets/thickbox/thickbox.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="../assets/kalendar_files/jsCalendar.js"></script>
<link href="../assets/kalendar_files/calendar.css" rel="stylesheet" type="text/css">
 
 <script type="text/javascript">

$(document).ready(function(){

	
		$("#pegForm").validate({
		rules: {
			password: "required",
			password_again: {
		equalTo: "#password"
			}
		},
		messages: {
			email: {
				required: "E-mail harus diisi",
				email: "Masukkan E-mail yang valid"
			}
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent("td"));
		}
	});
});
	
</script>

  <script type="text/javascript">

$(document).ready(function(){

	function formatCurrency(num) {
		num = num.toString().replace(/\$|\,/g,'');
		if(isNaN(num))
		num = "0";
		sign = (num == (num = Math.abs(num)));
		num = Math.floor(num*100+0.50000000001);
		cents = num%100;
		num = Math.floor(num/100).toString();
		if(cents<10)
		cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+
		num.substring(num.length-(4*i+3));
		//return (((sign)?'':'-') + '$' + num + '.' + cents);
		return (((sign)?'':'-') + num);
	}
	
    $.each($('.kanan'), function()
    {
       $(this).keyup( function(){ 
	   		$(this).val(formatCurrency($(this).val()));
		} );
    });
			
  $('input[@name=norek]').blur( // beri event pada saat onBlur inputan kode pegawai
	function(){		
		$('#divAlert').text('');		
	  var vNIP = $(this).val();
	  $.get('../include/cari.php?cari=rekening&mode=induk',{id: vNIP},
		function(nama_pegawai){
		  // jika response tidak kosong nilainya maka masukkan nilai ke inputan nama pegawai
		  if(nama_pegawai.length > 0){ 
			$('input[@name=namarekeninginduk]').val(nama_pegawai);	
		  }else {
		   $('#divAlert').text('No Rekening dengan Kode "'+vNIP+'" Tidak Ditemukan').css('color','red');
		   $('input[@name=norek]').val('');
		   $('input[@name=namarekeninginduk]').val('');
		   }
		}
	  );
	  
	}
  );
  
  // beri event pada saat keyup kode pegawai agar kode yang dimasukan font-nya UPPERCASE semua (optional)
  $('input[@name=namarekening]').keyup(
	function(){
	  $(this).val(String($(this).val()).toUpperCase());
	}
  );
});
	
</script>
 

 <script type="text/javascript">
 function selectBuku(no, nama, tipe){
  $('input[@name=norek]').val(no);
  $('input[@name=namarekeninginduk]').val(nama);
  //tb_remove(); // hilangkan dialog thickbox
}

function cekstring(){	
	var checkString = document.pegForm.namabrg.value;
	if (checkString != "") {
		if ( /[^A-Za-z\d]\s/.test(checkString)) {
			alert("Hanya diperbolehkan Karakter Huruf dan Angka");
			$('input[@name=namabrg]').val('');
			return (false);
		}
	}

}
 </script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><style type="text/css">
<!--
body {
	background-image: url(../images/bg2.png);
}
-->
</style></head>

<style type="text/css">
* { font: 11px/20px Verdana, sans-serif; }
h4 { font-size: 18px; }
input { padding: 3px; border: 1px solid #999; }
input.error, select.error { border: 1px solid red; }
label.error { color:red; margin-left: 10px; }

.style2 {font-family: "Segoe UI"}
.style6 {font-family: "Segoe UI"; font-size: 12; }
.style7 {font-size: 12}
.style9 {
	font-family: "Segoe UI";
	font-size: 12px;
	font-weight: bold;
	color: #0000FF;
}
input.kanan{ text-align:right; }
</style>
<? 
	include "../include/globalx.php";
	include "../include/functions.php";
?>
  <? $SQL = "select * from stock WHERE kodebrg <> ''";
	 	if ($_GET['id']<>"")
		{ 
			$SQL = $SQL." AND kodebrg = '". $_GET['id']."'";
		}
		$hasil = mysql_query($SQL, $dbh_jogjaide);
		if($_GET['id']<>""){
			while ($baris=mysql_fetch_array($hasil)) {
				$id = $_GET['id'];
				$namabrg = $baris['namabrg'];
				$satuank = $baris['satuank'];
				$isi = $baris['isi'];
				$satuanb = $baris['satuanb'];
				$group = $baris['grup'];
				$modal = $baris['modal'];
				$divisi = $baris['divisi'];
                $expedisi = $baris['expedisi'];
				$hargaeceran = $baris['hargaeceran'];
				$hargapartai = $baris['hargapartai'];
				$tarif = $baris['tarif'];
				$norek = $baris['norek'];
					$SQLc = "SELECT namarek FROM rek WHERE norek = '$norek' AND status = 1";
					$hasilc = mysql_query($SQLc);
					$barisc = mysql_fetch_array($hasilc);
					$namarekeninginduk = $barisc[0];
			}	
		}
	?>
<table width="1140" border="0">
  <tr>
    <td width="40"><img src="../images/vcard_add.png" width="32" height="32" /></td>
    <td width="1090"><span class="style9">FORM PERSEDIAAN
      </span>
      <hr /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><form id="pegForm" method="post" name="pegForm" action="submission_inv.php">
      <? if($_GET['id']<>""){ ?>
      <input type="hidden" name="cmd" value="upd_stok" />
      <input type="hidden" name="id" value="<?=$id?>" />
      <? } else { ?>
      <input type="hidden" name="cmd" value="add_stok" />
      <? } ?>
      <table align="left" class="x1">
        <tr background="../images/impactg.png" height="30">
          <td colspan="3" align="center"><span class="style1">Form Persediaan </span></td>
        </tr>
        <tr>
          <td><span class="style6">Kode   </span></td>
          <td>:</td>
          <td><input type="text" name="kodebrg" id="kodebrg"  value="<?=($id)?>" size="10"  class="required" title="Harap Mengisi Kode Barang Dahulu" />            </td>
        </tr>
        <tr>
          <td><span class="style6">Nama Barang  </span></td>
          <td>:</td>
          <td><input name="namabrg" size="40" type="text" onblur="cekstring();" class="required " id="namabrg"  title="Nama Barang harus terisi" value="<?=$namabrg?>" /></td>
        </tr>
        <tr>
          <td><span class="style6">Satuan Eceran</span></td>
          <td><span class="style6">:</span></td>
          <td><input type="text" name="satuank" id="satuank"  title="Satuan Kecil harus terisi" value="<?=$satuank?>" /></td>
        </tr>
        <tr>
          <td><span class="style6">Satuan Partai </span></td>
          <td><span class="style6">:</span></td>
          <td><input name="satuanb" type="text" id="satuanb"  title="Satuan Besar harus diisi" value="<?=$satuanb?>" /></td>
        </tr>
        <tr>
          <td><span class="style6">Isi </span></td>
          <td>&nbsp;</td>
          <td><input name="isi" type="text" id="isi"  class="required kanan" title="Isi harus diisi" value="<?=number_format($isi)?>" /></td>
        </tr>
        <tr>
          <td>HJE </td>
          <td>:</td>
          <td><input name="hargaeceran" type="text" id="hargaeceran"  class="required kanan" title="Harga eceran harus diisi" value="<?=number_format($hargaeceran)?>" /></td>
        </tr>
        <tr>
          <td>Harga Partai </td>
          <td>:</td>
          <td><input name="hargapartai" type="text" id="hargapartai"  class="required kanan" title="Harga Partai harus diisi" value="<?=number_format($hargapartai)?>" /></td>
        </tr>
        <tr>
          <td>Tarif / Cukai </td>
          <td>:</td>
          <td><input name="tarif" type="text" id="tarif"  class="" title="" value="<?=($tarif)?>" /></td>
        </tr>
        <tr>
          <td><span class="style6">Group</span></td>
          <td>:</td>
          <td><input type="text" name="group"  value="<?=($group)?>" /></td>
        </tr>
        <tr>
          <td><span class="style6">Modal/Harga Beli </span></td>
          <td>:</td>
          <td><input name="modal" type="text" class="required kanan" id="modal"  title="Modal harus diisi" value="<?=number_format($modal)?>" /></td>
        </tr>
        <tr>
          <td><span class="style6">Nomor Rekening </span></td>
          <td>:</td>
          <td><input type="text" name="norek" id="norek" maxlength="10" size="10" readonly="true" class="" title="Harap Mengisi Nomor Rekening Dahulu" value="<?=$norek?>"/>
            <a href="../../accounting/gli/daftar_rekp.php?width=400&amp;height=350&amp;TB_iframe=true" class="thickbox"><img src="../assets/button_search.png" alt="Pilih Akun" border="0" /></a>   <div id="divAlert"></div>         </td>
        </tr>
        <tr>
          <td><span class="style6">Nama Rekening </span></td>
          <td>:</td>
          <td><input type="text" name="namarekeninginduk" value="<?=$namarekeninginduk?>" readonly="true" size="40" class="" title="Nama Rekening Harus Terisi" /></td>
        </tr>
        <tr>
          <td>Divisi</td>
          <td>:</td>
          <td><input type="text" name="divisi" value="01" readonly="true" /></td>
        </tr>
        <tr>
          <td><span class="style6">Expedisi </span></td>
          <td>:</td>
          <td><input type="text" name="expedisi" value="<?=$expedisi?>" class="" title="isi Expedisi" /></td>
        </tr>
        <tr>
          <td><span class="style7"></span></td>
          <td><span class="style7"></span></td>
          <td><span class="style6">
            <? if($_GET['id']<>""){ ?>
            <input name="submit" type="submit" value="Update" />
            <? } else { ?>
            <input name="submit" type="submit" value="Simpan" />
            <? } ?>
            <input name="button" type="button" onClick="javascript:history.back()" value="Batal" />
          </span></td>
        </tr>
      </table>
    </form></td>
  </tr>
</table>

