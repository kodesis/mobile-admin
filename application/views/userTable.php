<style>
    .theme-dark .body-table tr td {
        color: #fff;
    }

    .theme-light .body-table tr td {
        color: #000;
    }
</style>

<div class="table mobile-only">
    <table class="table table-striped jambo_table bulk_action">
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
            } else if ($tipe == 'masuk' || $tipe == 'pulang') {
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
                endforeach;
            }
            ?>
        </tbody>
    </table>
</div>

<style>
    /* Hide the table by default */
    .table {
        display: none;
    }

    /* Show the table only on smaller screen sizes */
    @media (max-width: 768px) {
        .table {
            display: block;
            overflow-x: auto;
            /* For horizontal scrolling if the table is too wide */
        }

        table {
            width: 100%;
            /* Ensure table spans the full width of its container */
        }
    }
</style>