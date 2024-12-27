<style>
    .theme-dark .body-table tr td {
        color: #fff;
    }

    .theme-light .body-table tr td {
        color: #000;
    }

    /* Hide the table by default */
    /* Default table styles */
    /* Default table styles */
    /* Table styling */
    .table {
        /* Hide table by default */
        width: 100%;
        /* Ensure table spans full width of container */
        table-layout: auto;
        /* Automatically adjust column width based on content */
    }

    /* Show table only on smaller screens */
    @media (max-width: 768px) {
        .table-container {
            display: block;
            /* Make the container block-level */
            overflow-x: auto;
            /* Allow horizontal scrolling if the table exceeds screen width */
        }

        .table {
            /* No need to set display: block here, revert it to default */
            width: 100%;
            /* Ensure table spans the full width of the container */
        }

        /* Ensure each <tr> takes up the full width of the table */
        .table tr {
            width: 100%;
            /* Ensure rows take up the full table width */
        }

        /* Ensure table cells adjust based on content */
        .table td {
            word-wrap: break-word;
            /* Allow text to wrap inside cells */
            white-space: normal;
            /* Allow text to break and not overflow */
        }

        /* Make sure table header adjusts to full width as well */
        .table th {
            width: 100%;
        }
    }
</style>

<div class="table mobile-only">
    <table class="table table-striped bulk_action">
        <tbody id="studentTableContainer" class="body-table">
            <?php
            if ($tipe == NULL) {
                foreach ($users as $user):
                    echo "<td hidden id='username'>" . htmlspecialchars($user["username"]) . "</td>";
                    echo "<td hidden id='tanggalonly'></td>";
                    echo "<tr>";
                    echo "<td>Nip</td>";
                    echo "<td>:</td>";
                    echo "<td id='nip'>" . htmlspecialchars($user["nip"]) . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Nama</td>";
                    echo "<td>:</td>";
                    echo "<td id='nama'>" . htmlspecialchars($user["nama"]) . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Attendance</td>";
                    echo "<td>:</td>";
                    echo "<td id='absent'>Absent</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Tanggal/Waktu</td>";
                    echo "<td>:</td>";
                    echo "<td id='tanggal'></td>"; // No date/time for Absent
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Lokasi</td>";
                    echo "<td>:</td>";
                    echo "<td id='lokasi'></td>"; // No location for Absent
                    echo "</tr>";
                endforeach;
            } else if ($tipe == 'masuk' || $tipe == 'pulang' || $tipe == 'absensi') {
                foreach ($users as $user):
                    echo "<tr>";
                    echo "<td>Nip</td>";
                    echo "<td>:</td>";
                    echo "<td>" . htmlspecialchars($user["nip"]) . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Nama</td>";
                    echo "<td>:</td>";
                    echo "<td>" . htmlspecialchars($user["nama"]) . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Attendance</td>";
                    echo "<td>:</td>";
                    echo "<td>" . htmlspecialchars($user["attendanceStatus"]) . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Tanggal/Waktu</td>";
                    echo "<td>:</td>";
                    echo "<td>" . htmlspecialchars($user["date"]) . " - " . htmlspecialchars($user["waktu"]) . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Lokasi</td>";
                    echo "<td>:</td>";
                    echo "<td>" . htmlspecialchars($user["lokasiAttendance"]) . "</td>";
                    echo "</tr>";

                    echo "<tr>";
                    echo "<td colspan = '2' style='text-align: center;'><img style='display: block;
    margin: 0 auto;' width='200px' src='" . base_url('upload/attendance/' . $user['image']) . "'></td>";
                    echo "</tr>";
                endforeach;
            }
            ?>
        </tbody>
    </table>
</div>