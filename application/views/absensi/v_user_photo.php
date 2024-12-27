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

    .image-box {
        position: relative;
        display: inline-block;
        margin-left: 10%;
        margin-top: 5px;
    }

    .image-box img {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .image-box:hover img {
        filter: blur(0.5px);
        cursor: pointer;
        box-shadow: 0px 0px 10px #5073fb;

    }

    .edit-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
        cursor: pointer;
        color: darkblue;
        font-size: 5rem;

    }

    .image-box:hover .edit-icon {
        display: block;
    }

    .image-box {
        position: relative;
        display: inline-block;
        height: 15rem;
        width: 15rem;
    }

    .image-box img {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .image-box:hover img {
        filter: blur(1.5px);
        cursor: pointer;
        transform: scale(1.1);
        box-shadow: 0px 0px 10px #5073fb;



    }
</style>
<div id="page">
    <?php include APPPATH . '/views/v_nav.php' ?>
    <div class="page-content">
        <div class="content mt-0 mb-3">
            <h3 class="text-center my-3">USER PHOTO</h3>
            <!-- <div class="search-box shadow-xl border-0 bg-theme rounded-sm bottom-0">
                <form action="" method="get">
                    <i class="fa fa-search"></i>
                    <input type="text" class="border-0" placeholder="Fill in the subject you want to search." id="search" name="search" value="<?= strtolower($this->input->get('search') ?? '') ?>">
                </form>
            </div> -->
        </div>
        <div class="card card-style">
            <div class="content" style="cursor: pointer;">
                <font style="font-size:14px;">
                    <?php
                    // Check the URI segments to determine the mode: view, add, or edit
                    $mode = ($this->uri->segment(4) == 'e') ? 'edit' : (($this->uri->segment(3) == true) ? 'view' : 'add');
                    ?>

                    </br>
                    <?= $this->session->flashdata('msg') ?>


                    <!-- Add/Edit mode -->
                    <form action="<?= base_url('absensi/add_photo') ?>" method="POST">
                        <input type="hidden" name="<?= $mode ?>" value="<?= $mode ?>">
                        <input type="hidden" value="<?= $this->session->userdata('nip') ?>" name="nip">
                        <input type="hidden" value="<?= $this->session->userdata('username') ?>" name="username">
                        <table>
                            <tr>
                                <th width="300">Username</th>
                                <td width="300">
                                    <?= $user->username ?>
                                </td>
                            </tr>
                            <tr>
                                <th width="200">Name</th>
                                <td>
                                    <?= $user->nama ?>
                                </td>
                            </tr>
                            <?php if (!empty($user->userImage)) { ?>
                                <tr>
                                    <div id="image-gallery" class="row">
                                        <?php
                                        $images = json_decode($user->userImage, true); // Decode the JSON array
                                        $imagePath = 'resources/labels/' . $user->username . '/';
                                        foreach ($images as $image): ?>
                                            <div class="user-image col-md-2" style="width: 50%;">
                                                <img src="<?= base_url($imagePath . $image) ?>" alt="User Image" style="width: 100px; margin: 5px;">
                                            </div>
                                        <?php endforeach; ?>
                                        <!-- <img src="<?= base_url() ?>resources/images/default.png" alt="Default Image"> -->
                                    </div>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <div>
                                        <div class="form-title-image">
                                            <p>Take Pictures</p>
                                        </div>
                                        <div id="open_camera" class="image-box" onclick="takeMultipleImages()">
                                            <img src="<?= base_url() ?>resources/images/default.png" alt="Default Image">
                                        </div>
                                        <div id="multiple-images"></div>
                                    </div>
                                </tr>
                            <?php } ?>
                            <!-- Delete all images button -->
                            <?php if (!empty($user->userImage)) { ?>
                                <a id="delete-images" class="btn btn-danger mb-5" onclick="deleteUserImages('<?= $user->username ?>')">Delete All Images</a>
                            <?php } ?>
                            <tr>
                                <th>
                                    <div>
                                        <!-- <a href="<?= base_url('app/user') ?>" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> -->
                                        <?php if ($mode != 'view') { ?>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        <?php } ?>
                                    </div>
                                </th>
                            </tr>
                        </table>
                    </form>
                    <br>
                </font>
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
<script>
    function openCamera(buttonId) {
        navigator.mediaDevices
            .getUserMedia({
                video: true
            })
            .then((stream) => {
                const video = document.createElement("video");
                video.srcObject = stream;
                document.body.appendChild(video);

                video.play();

                setTimeout(() => {
                    const capturedImage = captureImage(video);
                    stream.getTracks().forEach((track) => track.stop());
                    document.body.removeChild(video);

                    const imgElement = document.getElementById(
                        buttonId + "-captured-image"
                    );
                    imgElement.src = capturedImage;
                    const hiddenInput = document.getElementById(
                        buttonId + "-captured-image-input"
                    );
                    hiddenInput.value = capturedImage;
                }, 500);
            })
            .catch((error) => {
                console.error("Error accessing webcam:", error);
            });
    }
    const takeMultipleImages = async () => {
        document.getElementById("open_camera").style.display = "none";

        const images = document.getElementById("multiple-images");

        for (let i = 1; i <= 10; i++) {
            // Create the image box element
            const imageBox = document.createElement("div");
            imageBox.classList.add("image-box");

            const imgElement = document.createElement("img");
            imgElement.id = `image_${i}-captured-image`;
            imgElement.class = ``;
            const editIcon = document.createElement("div");
            editIcon.classList.add("edit-icon");

            const icon = document.createElement("i");
            icon.classList.add("fa", "fa-camera");
            icon.setAttribute("onclick", `openCamera("image_"+${i})`);

            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.id = `image_${i}-captured-image-input`;
            hiddenInput.name = `capturedImage${i}`;

            editIcon.appendChild(icon);
            imageBox.appendChild(imgElement);
            imageBox.appendChild(editIcon);
            imageBox.appendChild(hiddenInput);
            images.appendChild(imageBox);
            await captureImageWithDelay(i);
        }
    };

    const captureImageWithDelay = async (i) => {
        try {
            // Get camera stream
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            const video = document.createElement("video");
            video.srcObject = stream;
            document.body.appendChild(video);
            video.play();

            // Wait for 500ms before capturing the image
            await new Promise((resolve) => setTimeout(resolve, 500));

            // Capture the image
            const capturedImage = captureImage(video);

            // Stop the video stream and remove the video element
            stream.getTracks().forEach((track) => track.stop());
            document.body.removeChild(video);

            // Update the image and hidden input
            const imgElement = document.getElementById(`image_${i}-captured-image`);
            imgElement.src = capturedImage;

            const hiddenInput = document.getElementById(
                `image_${i}-captured-image-input`
            );
            hiddenInput.value = capturedImage;
        } catch (err) {
            console.error("Error accessing camera: ", err);
        }
    };

    function captureImage(video) {
        const canvas = document.createElement("canvas");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext("2d");

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        return canvas.toDataURL("image/png");
    }

    function deleteUserImages(username) {
        if (!confirm("Are you sure you want to delete all images for this user?")) {
            return;
        }

        fetch('<?= base_url('app/delete_user_images') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    // location.reload(); // Refresh the page to update the image gallery
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete images. Please try again.');
            });
    }
</script>