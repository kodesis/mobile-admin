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
        <thead>
            <tr>
                <th>Nip</th>
                <th>Name</th>
                <th>Attendance</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Settings</th>
            </tr>
        </thead>
        <tbody id="studentTableContainer" class="body-table">
            <?php foreach ($users as $user):
                echo "<tr>";
                $username = $user["username"];
                echo "<td hidden>" . $username . "</td>";
                echo "<td>" . $user["nip"] . "</td>";
                echo "<td>" . $user["nama"] . "</td>";
                echo "<td>Absent</td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td><span><i class='ri-edit-line edit'></i><i class='ri-delete-bin-line delete'></i></span></td>";
                echo "</tr>";
            endforeach; ?>
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