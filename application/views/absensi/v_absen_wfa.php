<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.css">
<style>
    #btn-create {
        position: fixed;
        bottom: 12%;
        right: 10px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: #4A89DC;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 4px;
    }

    #btn-create:hover {
        background-color: #555;
    }

    /*video*/
    canvas {
        position: absolute;

    }

    .video-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #video {
        border-radius: 10px;
        box-shadow: #000;
    }
</style>
<div id="page">
    <?php include APPPATH . '/views/v_nav.php' ?>
    <div class="page-content">
        <div class="content mt-0 mb-3">
            <h3 class="text-center my-3">ABSEN WFA</h3>
            <!-- <div class="search-box shadow-xl border-0 bg-theme rounded-sm bottom-0">
                <form action="" method="get">
                    <i class="fa fa-search"></i>
                    <input type="text" class="border-0" placeholder="Fill in the subject you want to search." id="search" name="search" value="<?= strtolower($this->input->get('search') ?? '') ?>">
                </form>
            </div> -->
        </div>
        <div class="card card-style">
            <div class="content" style="cursor: pointer;">
                <div class="main--content">
                    <div id="messageDiv" class="messageDiv" style="display:none;"> </div>
                    <h5 id="lokasi_sekarang"></h5>
                    <!-- <button class="btn" id="ShowUser" onclick="getLocation()">Tampilkan Posisi</button> -->
                    <!-- <button class="btn" id="ShowUser" onclick="updateTable()">Tampilkan User</button> -->
                    <div class="attendance-button">
                        <button hidden id="startButton" class="add">Launch Facial Recognition</button>
                        <button id="endButton" class="add" style="display:none">End Attendance Process</button>
                        <button hidden id="endAttendance" class="add">END Attendance Taking</button>
                    </div>

                    <div class="video-container">
                        <video id="video" class="video-class" width="320" height="240" autoplay muted></video>
                        <canvas id="overlay"></canvas>
                    </div>

                    <div class="table-container">

                        <div id="studentTableContainer">

                        </div>

                    </div>
                    <p id="location"></p>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <!-- <div class="content">
            <div class="row">
                <div class="col-12 font-15">
                    <nav>
                        <?= $pagination ?>
                    </nav>
                </div>
            </div>
        </div> -->

        <!-- Button Create -->
        <!-- <a href="<?= base_url('app/create_memo') ?>" class="btn" id="btn-create"><i class="fa-solid fa-plus"></i></a> -->
    </div>
</div>
<script defer src="<?= base_url() ?>resources/assets/javascript/face_logics/face-api.min.js"></script>
<script src="<?= base_url() ?>assets/vendor/sweetalert2/js/sweetalert2.all.min.js"></script>

<script>
    let isWithinRange = false;
    let locationName = "";
    const locations = [
        <?php
        if ($lokasi_absensi) {
            foreach ($lokasi_absensi as $l) { ?> {
                    name: "<?= addslashes($l['nama_lokasi']) ?>", // Ensure the name is properly escaped and quoted
                    latitude: <?= $l['latitude'] ?>,
                    longitude: <?= $l['longitude'] ?>,
                    radius: <?= $l['radius'] ?> // Radius in kilometers
                },
            <?php }
        } else { ?> {
                name: "Graha Dirgantara",
                latitude: -6.2559536,
                longitude: 106.8826187,
                radius: 0.5 // Radius in kilometers
            },
            {
                name: "Parkir Bandes",
                latitude: -6.2586284,
                longitude: 106.8820789,
                radius: 0.5 // Radius in kilometers
            },
            {
                name: "Mlejit",
                latitude: -6.2638584,
                longitude: 106.8856266,
                radius: 0.5 // Radius in kilometers
            }
        <?php } ?>
    ];


    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError, {
                enableHighAccuracy: false
            });
        } else {
            Swal.fire('Error', 'Geolocation is not supported by this browser.', 'error');
        }
    }

    function showPosition(position) {
        const userLatitude = position.coords.latitude;
        const userLongitude = position.coords.longitude;


        // Check each location
        for (const location of locations) {
            if (isWithinRadius(userLatitude, userLongitude, location.latitude, location.longitude, location.radius)) {
                isWithinRange = true;
                locationName = location.name;
                break;
            }
        }

        if (isWithinRange) {
            $('#lokasi_sekarang').text('Lokasi Sekarang ' + locationName);
            Swal.fire('Success', `You are within range of ${locationName}. Updating table...`, 'success');
            updateTable();
        } else {
            $('#lokasi_sekarang').text('Lokasi Sekarang Di Luar Jangkauan');
            Swal.fire('Success', `You are not within range. Updating table...`, 'success');
            updateTable();
        }
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                Swal.fire('Error', 'Permission to access location was denied.', 'error');
                break;
            case error.POSITION_UNAVAILABLE:
                Swal.fire('Error', 'Location information is unavailable.', 'error');
                break;
            case error.TIMEOUT:
                Swal.fire('Error', 'The request to get your location timed out.', 'error');
                break;
            case error.UNKNOWN_ERROR:
                Swal.fire('Error', 'An unknown error occurred.', 'error');
                break;
        }
    }

    // Function to calculate distance between two coordinates
    function isWithinRadius(lat1, lon1, lat2, lon2, radiusInKm) {
        const toRadians = (degrees) => degrees * (Math.PI / 180);
        const earthRadiusKm = 6371;

        const dLat = toRadians(lat2 - lat1);
        const dLon = toRadians(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        const distance = earthRadiusKm * c;
        return distance <= radiusInKm;
    }

    function updateTable() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_user", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    students = response.data; // Store the student data
                    labels = students.map(student => student.username);
                    console.log(labels);
                    updateOtherElements();

                    document.getElementById("studentTableContainer").innerHTML = response.html;
                } else if (response.status === "No Picture") {
                    Swal.fire('Alert', 'Picture Not Found, Please take Picture first', 'warning');

                } else {
                    console.error("Error:", response.message);
                }
            }
        };

        xhr.send();
    }

    function markAttendance(detectedFaces) {
        document.querySelectorAll("#studentTableContainer tr").forEach((row) => {

            const username = row.cells[0].innerText.trim();

            if (detectedFaces.includes(username)) {
                if (isWithinRange) {
                    row.cells[3].innerText = "present";
                    row.cells[4].innerText = locationName;
                } else {
                    row.cells[3].innerText = "pending";
                    row.cells[4].innerText = "Di Luar";
                }
                const currentDate = new Date(); // Get the current date

                // Format the date as "YYYY-MM-DD"
                const formattedDate = currentDate.toISOString().split("T")[0];


                row.cells[5].innerText = formattedDate;

                Swal.fire('Success', `Anda Berhasil Melakukan Absensi`, 'success');
                sendAttendanceDataToServer();
                const videoContainer = document.querySelector(".video-container");
                videoContainer.style.display = "none";
                stopWebcam();
            }
        });
    }

    function updateOtherElements() {
        const video = document.getElementById("video");
        const videoContainer = document.querySelector(".video-container");
        const startButton = document.getElementById("startButton");
        let webcamStarted = false;
        let modelsLoaded = false;

        Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri("../../models"),
                faceapi.nets.faceRecognitionNet.loadFromUri("../../models"),
                faceapi.nets.faceLandmark68Net.loadFromUri("../../models"),
            ])
            .then(() => {
                modelsLoaded = true;
                console.log("models loaded successfully");
                videoContainer.style.display = "flex";
                if (!webcamStarted && modelsLoaded) {
                    startWebcam();
                    webcamStarted = true;
                }
            })
            .catch(() => {
                alert("models not loaded, please check your model folder location");
            });
        startButton.addEventListener("click", async () => {
            videoContainer.style.display = "flex";
            if (!webcamStarted && modelsLoaded) {
                startWebcam();
                webcamStarted = true;
            }
        });

        function startWebcam() {
            navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            }).then((stream) => {
                video.srcObject = stream;
                videoStream = stream;
            }).catch((error) => {
                console.error("Error accessing webcam:", error);
                alert("Please allow webcam access.");
            });

        }

        async function getLabeledFaceDescriptions() {
            const labeledDescriptors = [];

            for (const label of labels) {
                console.log(labels);
                const descriptions = [];
                // Find the student matching the username (label)
                const student = students.find(s => s.username === label);

                if (student) {
                    const nama = student.nama; // Get the student's first name
                    const username = student.username; // Get the registration number
                    for (let i = 1; i <= 5; i++) {
                        try {
                            const img = await faceapi.fetchImage(
                                `../resources/labels/${label}/${i}.png`
                            );
                            const detections = await faceapi
                                .detectSingleFace(img)
                                .withFaceLandmarks()
                                .withFaceDescriptor();

                            if (detections) {
                                descriptions.push(detections.descriptor);
                            } else {
                                console.log(`No face detected in ${label}/${i}.png`);
                            }
                        } catch (error) {
                            console.error(`Error processing ${label}/${i}.png:`, error);
                        }
                    }

                    if (descriptions.length > 0) {
                        labeledDescriptors.push(
                            new faceapi.LabeledFaceDescriptors(nama, descriptions) // Use nama here
                        );
                    }
                }
            }

            return labeledDescriptors;
        }

        video.addEventListener("play", async () => {
            const labeledFaceDescriptors = await getLabeledFaceDescriptions();
            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

            const canvas = faceapi.createCanvasFromMedia(video);
            videoContainer.appendChild(canvas);

            const displaySize = {
                width: video.width,
                height: video.height
            };
            faceapi.matchDimensions(canvas, displaySize);

            setInterval(async () => {
                const detections = await faceapi
                    .detectAllFaces(video)
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(detections, displaySize);

                canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

                const results = resizedDetections.map((d) => {
                    return faceMatcher.findBestMatch(d.descriptor);
                });

                // Now map the results to include registration numbers
                detectedFaces = results.map((result) => {
                    // We are returning the registration number instead of nama
                    const student = students.find(s => s.nama === result.label);
                    return student ? student.username : null;
                }).filter(Boolean); // Remove any null values

                console.log(detectedFaces); // Here you'll see the registration numbers
                markAttendance(detectedFaces);

                results.forEach((result, i) => {
                    const box = resizedDetections[i].detection.box;
                    const drawBox = new faceapi.draw.DrawBox(box, {
                        label: result.label, // You can keep nama as label here for visual purposes
                    });
                    drawBox.draw(canvas);
                });
            }, 100);
        });

    }

    function sendAttendanceDataToServer() {
        const attendanceData = [];

        document
            .querySelectorAll("#studentTableContainer tr")
            .forEach((row, index) => {
                if (index === 0) return;
                const username = row.cells[0].innerText.trim();
                const nip = row.cells[1].innerText.trim();
                const nama = row.cells[2].innerText.trim();
                const attendanceStatus = row.cells[3].innerText.trim();
                const lokasiAttendance = row.cells[4].innerText.trim();
                const tanggalAttendance = row.cells[5].innerText.trim();

                attendanceData.push({
                    username,
                    nip,
                    nama,
                    attendanceStatus,
                    lokasiAttendance,
                });
            });

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "recordAttendance", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.status === "success") {
                            showMessage(
                                response.message || "Attendance recorded successfully."
                            );
                        } else {
                            showMessage(
                                response.message ||
                                "An error occurred while recording attendance."
                            );
                        }
                    } catch (e) {
                        showMessage("Error: Failed to parse the response from the server.");
                        console.error(e);
                    }
                } else {
                    showMessage(
                        "Error: Unable to record attendance. HTTP Status: " + xhr.status
                    );
                    console.error("HTTP Error", xhr.status, xhr.statusText);
                }
            }
        };

        xhr.send(JSON.stringify(attendanceData));
    }

    function showMessage(message) {
        var messageDiv = document.getElementById("messageDiv");
        messageDiv.style.display = "block";
        messageDiv.innerHTML = message;
        console.log(message);
        messageDiv.style.opacity = 1;
        setTimeout(function() {
            messageDiv.style.opacity = 0;
        }, 5000);
    }

    function stopWebcam() {
        if (videoStream) {
            const tracks = videoStream.getTracks();

            tracks.forEach((track) => {
                track.stop();
            });

            video.srcObject = null;
            videoStream = null;
        }
    }

    document.getElementById("endAttendance").addEventListener("click", function() {
        sendAttendanceDataToServer();
        const videoContainer = document.querySelector(".video-container");
        videoContainer.style.display = "none";
        stopWebcam();
    });
</script>
<?php
if (empty($data_users)) {
?>
    <script>
        const callOnceLocation1 = getLocation();
    </script>
<?php
} else {
    $current_time = new DateTime(); // Current time
    $jam_masuk_plus_two = (new DateTime($data_users->jam_masuk))->modify('+2 hours');
    $jam_keluar_plus_two = (new DateTime($data_users->jam_keluar))->modify('+2 hours');
?>
    <script>
        <?php if ($current_time <= $jam_masuk_plus_two) { ?>
            <?php if (empty($result1)) { ?>
                console.log('ada1');
                const callOnceLocation2 = getLocation(); // Call function
            <?php } else { ?>
                Swal.fire('Alert', 'Anda Sudah Melakukan Absensi Masuk', 'warning');
            <?php } ?>
        <?php } else if ($current_time >= $jam_keluar_plus_two) { ?>
            <?php if (empty($result2)) { ?>
                console.log('ada2');
                const callOnceLocation3 = getLocation(); // Call function
            <?php } else { ?>
                Swal.fire('Alert', 'Anda Sudah Melakukan Absensi Keluar', 'warning');
            <?php } ?>
        <?php } else { ?>
            Swal.fire('Alert', 'Anda Sudah Melakukan Absensi', 'warning');
        <?php } ?>
    </script>
<?php } ?>

<script src='<?= base_url() ?>resources/assets/javascript/active_link.js'></script>
<!-- <script src='<?= base_url() ?>resources/assets/javascript/face_logics/script.js'></script> -->