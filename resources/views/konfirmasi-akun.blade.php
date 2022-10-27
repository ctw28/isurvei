<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Akun</title>
</head>

<body>
    <p>Konfirmasi Akun</p>
    <ul>
        <li><span id="nomor-jenis"></span> : <span id="nomor"></span></li>
        <li>Nama Lengkap : <span id="nama"></span></li>
    </ul>
    <form role="form" class="text-start" action="{{route('login')}}" method="post">
        @csrf
        <input type="hidden" name="is_first" class="form-control" value="1" required>

        <input type="hidden" name="username" id="username" class="form-control" value="{{$username}}" required>
        <input type="hidden" name="password" id="password" class="form-control" value="{{$password}}" required>
        <div class="text-center">
            <a href="/" class="btn bg-gradient-warning w-100 my-4 mb-2">Batal</a>
            <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2">Akun benar, Lanjut Ke Aplikasi</button>
        </div>
    </form>
    <script>
        getUser()
        async function getUser() {

            let dataSend = new FormData()
            let username = document.querySelector('#username').value
            let password = document.querySelector('#password').value

            dataSend.append('username', username)
            dataSend.append('password', password)

            let send = await fetch("https://sia.iainkendari.ac.id/konseling_api/login", {
                method: "POST",
                body: dataSend
            });
            let response = await send.json()
            console.log(response);
            // return
            if (response.status === true) {
                const jenis = document.querySelector('#nomor-jenis')
                const nomor = document.querySelector('#nomor')
                const nama = document.querySelector('#nama')
                nama.textContent = response.data.nama
                if (response.jenis_akun == "pegawai") {
                    jenis.textContent = "NIP"
                    nomor.textContent = response.data.nip
                } else {
                    jenis.textContent = "NIM"
                    nomor.textContent = response.data.nim
                }
                let dataSend = new FormData()
                dataSend.append('jenis_akun', response.jenis_akun)
                dataSend.append('username', username)
                dataSend.append('password', password)
                dataSend.append('data', JSON.stringify(response.data))
                let sendUser = await fetch("{{route('user.store')}}", {
                    method: "POST",
                    body: dataSend
                });
                let responseUser = await sendUser.json()
                // if ()
                console.log(responseUser);
                //create user, user role, data diri, mahasiswa, user mahasiswa
                // alert("Silahkan login kembali untuk masuk")
                // window.location.href = '/'
                //create user, user role, data diri, pegawai, user pegawai


            } else {
                alert("username dan password tidak sesuai / tidak ditemukan. Mohon Coba Lagi")
                window.location.href = '/'

            }
        }
    </script>
</body>

</html>