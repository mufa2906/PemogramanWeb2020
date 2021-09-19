const tombolCari = document.querySelector('.tombol-cari');
const keyword = document.querySelector('.keyword');
const container = document.querySelector('.container');

//menghilangkan tombol cari
tombolCari.style.display = 'none';


//event ketika kita menuliskan keyword(jadi pas ketik langsung keluar hasilnya)
keyword.addEventListener('keyup', function() {
  //ajax(bagaimana cara melakukan request tanpa merefresh halaman)
  // xmlhttprequest
  // const xhr = new XMLHttpRequest();


  // xhr.onreadystatechange = function() {
  //   if(xhr.readyState === 4 && xhr.status == 200){
  //     container.innerHTML = xhr.responseText;
  //   }
  // };

  // xhr.open('GET','ajax/ajax_cari.php?keyword=' + keyword.value);
  // xhr.send();

  //fetch()
  fetch('ajax/ajax_cari.php?keyword=' + keyword.value)
    .then((response) => response.text())
    .then((response) => (container.innerHTML = response));
});

//preview image untuk tambah dan ubah Gambar
function previewImage() {
  const gambar = document.querySelector('.gambar');
  const imgPreview = document.querySelector('.img-preview');

  const oFReader = new FileReader();
  oFReader.readAsDataURL(gambar.files[0]);

  oFReader.onload = function(oFREvent) {
    imgPreview.src = oFREvent.target.result;
  };
}