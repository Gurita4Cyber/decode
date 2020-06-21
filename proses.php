<?php
//==================== FUNGSI ENKRIPSI ====================
function zippo($string,$key){
	$result	= '';
	for($i=0; $i<strlen($string); $i++){
		$char		= substr($string, $i, 1);
		$keychar	= substr($key, ($i % strlen($key))-1, 1);
		$char		= chr(ord($char)+ord($keychar));
		$result		.=$char;
	}
	return base64_encode($result);
}
//=========================================================

//==================== FUNGSI DESKRIPSI ===================
function cricket($string, $key){
	$result		= '';
	$string		= base64_decode($string);
	for($i=0; $i<strlen($string); $i++) {
		$char	= substr($string, $i, 1);
		$keychar= substr($key, ($i % strlen($key))-1, 1);
		$char	= chr(ord($char)-ord($keychar));
		$result	.=$char;
	}
	return $result;
}
//=========================================================

//==================== AMBIL INPUTAN DARI FORM ============
$tmp		= $_FILES['file']['tmp_name'];
$namafile	= $_FILES['file']['name'];
$tmps		= $_FILES['file']['size'];
$tmpt		= $_FILES['file']['type'];
$tmpe		= $_FILES['file']['error'];
$kunci		= $_POST['key'];
$folder		= $_POST['folder'];
$path		= dirname(__FILE__)."\\".$folder;
//=========================================================

//==================== BUAT FOLDER HASIL ENKRIPSI =========
if(!is_dir($path)){
	mkdir($path);
}
//=========================================================

//==================== BUKA-BACA FILE YG AKAN DIENKRIPSI ==
$handle		= fopen($tmp, "r");
$contents	= fread($handle,filesize($tmp));
fclose($handle);
//=========================================================

//==================== PROSES ENKRIPSI ====================
$encrypted	= zippo($contents,$kunci);

$scriptx	= '<?php
	include("inc.php");
	$klobot		= "'.$encrypted.'";
	$nosmoking	= cricket($klobot, $cigar);
	$marsbrand	= fopen(base64_decode("dGVtcC5waHA="), "w");
	fwrite($marsbrand, $nosmoking);
	fclose($marsbrand);
	include(base64_decode("dGVtcC5waHA="));
	unlink(base64_decode("dGVtcC5waHA="));
?>';
$fp			= fopen($path."/".$namafile,"wb");
fwrite($fp,$scriptx);
fclose($fp);

if(file_exists($path . "/inc.php")){
	echo "<script language='JavaScript'>alert('Fungsi Enkripsi Sudah Ada (inc.php) Dengan KEY :".$kunci."'); window.location = 'index.php';</script>";
}if(!file_exists($path . "/inc.php")){
	$scripty = '<?php
	$cigar		= explode("\n", file_get_contents(\'conf\'));
	$cigar		= $cigar[0];
	function cricket($klobot, $cigar){
		$sebat	= "";
		$klobot	= base64_decode($klobot);
		for($i=0; $i<strlen($klobot); $i++){
			$tobacco	= substr($klobot, $i, 1);
			$cigaro		= substr($cigar, ($i % strlen($cigar))-1, 1);
			$tobacco	= chr(ord($tobacco)-ord($cigaro));
			$sebat.=$tobacco;
		}
		return $sebat;
	}
	?>';
	$fp = fopen($path . "/inc.php","wb");
	fwrite($fp,$scripty);
	fclose($fp);
	echo "<script language='JavaScript'>alert('File Berhasil Ter-Enkripsi'); window.location = 'index.php';</script>";
}

$conf		= fopen($path."\\conf", "w");
fwrite($conf, $kunci);
fclose($conf);
//=========================================================
?>