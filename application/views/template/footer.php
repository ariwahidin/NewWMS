</div>
<!-- container-fluid -->
</div>
<!-- End Page-content -->

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                PT. Puninar Yusen Logistics Indonesia
            </div>
        </div>
    </div>
</footer>

<!-- JAVASCRIPT -->
<script src="<?= base_url('jar/html/default/') ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('jar/html/default/') ?>assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?= base_url('jar/html/default/') ?>assets/libs/node-waves/waves.min.js"></script>
<script src="<?= base_url('jar/html/default/') ?>assets/libs/feather-icons/feather.min.js"></script>
<script src="<?= base_url('jar/html/default/') ?>assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="<?= base_url('jar/html/default/') ?>assets/js/plugins.js"></script>

<!-- apexcharts -->
<script src="<?= base_url('jar/html/default/') ?>assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Vector map-->
<script src="<?= base_url('jar/html/default/') ?>assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="<?= base_url('jar/html/default/') ?>assets/libs/jsvectormap/maps/world-merc.js"></script>

<!-- projects js -->
<script src="<?= base_url('jar/html/default/') ?>assets/js/pages/dashboard-projects.init.js"></script>
<!-- Dashboard init -->
<script src="<?= base_url('jar/html/default/') ?>assets/js/pages/dashboard-job.init.js"></script>
<!-- App js -->
<script src="<?= base_url('jar/html/default/') ?>assets/js/app.js"></script>




<!-- My JS -->
<script>
    async function logout() {
        try {
            const response = await fetch("<?= base_url('auth/logout') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const responseData = await response.json();
                if (responseData.success === true) {
                    window.location.href = "<?= base_url('auth/login') ?>"; // Ganti dengan URL halaman login yang sesuai
                } else {
                    console.error('Logout gagal:', responseData.message);
                }
            } else {
                console.error('Logout gagal:', response.status);
            }
        } catch (error) {
            console.error('Terjadi kesalahan:', error);
        }
    }
    const logoutLink = document.getElementById('logoutLink');
    logoutLink.addEventListener('click', (event) => {
        event.preventDefault(); // Mencegah navigasi ke "#" (atau URL kosong) jika elemen <a> ditekan
        logout(); // Panggil fungsi logout saat tautan Logout diklik
    });

    setTimeout(stopLoading, 1000);

    function toggleTheme() {
        // Mengecek apakah sessionStorage memiliki data dengan key 'data-bs-theme'
        if (sessionStorage.getItem('data-bs-theme')) {
            // Mengambil nilai yang tersimpan dalam sessionStorage
            var currentTheme = sessionStorage.getItem('data-bs-theme');

            // Mengubah tema dari 'dark' menjadi 'light' atau sebaliknya
            var newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // Memperbarui data pada sessionStorage dengan tema yang baru
            sessionStorage.setItem('data-bs-theme', newTheme);

            // Di sini Anda dapat menambahkan logika lain, misalnya memperbarui tampilan halaman sesuai dengan tema yang baru

            // Contoh: Mengubah warna latar belakang body
            document.body.classList.toggle('dark-theme');
            document.body.classList.toggle('light-theme');
        } else {
            // Jika tidak ada data dengan key 'data-bs-theme' dalam sessionStorage, maka tambahkan data baru dengan tema 'light'
            sessionStorage.setItem('data-bs-theme', 'light');
        }
    }

    // Mengaitkan fungsi toggleTheme() dengan sebuah tombol (misalnya, tombol dengan ID 'theme-toggle')
    document.getElementById('btnTheme').addEventListener('click', toggleTheme);

    function updateClock() {
        var currentDate = new Date();
        var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var monthsOfYear = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        var dayOfWeek = daysOfWeek[currentDate.getDay()];
        var dayOfMonth = currentDate.getDate();
        var monthOfYear = monthsOfYear[currentDate.getMonth()];
        var year = currentDate.getFullYear();
        var hours = currentDate.getHours().toString().padStart(2, '0');
        var minutes = currentDate.getMinutes().toString().padStart(2, '0');
        var seconds = currentDate.getSeconds().toString().padStart(2, '0');

        var dateTimeString = dayOfWeek + ', ' + dayOfMonth + ' ' + monthOfYear + ', ' + year + ' ' + hours + ':' + minutes + ':' + seconds;

        document.getElementById('clock').innerText = dateTimeString;
    }


    function keepAlive() {
        $.post('<?= base_url('Welcome/') ?>' + 'keepAlive', {}, function(response) {
            console.log(response);
        }, 'json');
    }

    // function getDC() {
    //     let dc = location.pathname.split('/')[1];
    //     if (dc == 'ymi' || dc == 'yamvas_dc1') {
    //         dc = 'YAMVAS DC 1';
    //     } else if (dc == 'yamvas_dc2') {
    //         dc = 'YAMVAS DC 2';
    //     } else {
    //         dc = '';
    //     }
    //     document.getElementById('spDC').innerText = dc;
    // }

    // getDC();
    setInterval(keepAlive, 180000);
    setInterval(updateClock, 1000);
    updateClock();
</script>
</body>



</html>