# Website Wisata Batu Kuda

Project ini dijalankan secara lokal menggunakan Laragon dengan HTTPS aktif agar fitur yang membutuhkan `secure context` seperti geolokasi browser bisa bekerja dengan benar.

## Persiapan

Pastikan komponen berikut sudah tersedia:

- Laragon
- PHP 8.5 atau versi yang kompatibel dengan project
- MySQL dari Laragon
- Composer
- Node.js dan npm

## Langkah SSL di Laragon

Lakukan langkah berikut sebelum menjalankan website:

1. Pindahkan project ke folder `C:\laragon\www\` atau `Z:\laragon\www\` sesuai lokasi Laragon Anda.
2. Buka Laragon.
3. Jalankan `Start All` agar Apache dan MySQL aktif.
4. Buka menu `Menu > Preferences > Services & Ports` dan pastikan web server aktif seperti biasa.
5. Aktifkan SSL:
   `Menu > Apache > SSL`
   Pilih opsi enable SSL bila tersedia.
6. Restart Laragon setelah SSL diaktifkan.

## Enable dan Add `Laragon.crt` to Trust Store


1. Buka Laragon 
2. Klik Apache 
3. Pilih SSL
4. Klik Add Laragon.crt to Trust Store.

