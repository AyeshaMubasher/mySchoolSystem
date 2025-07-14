<?php

session_start();
if(!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!----- DataTable CSS and JS--->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Colorbox CSS and JS -->
    <link rel="stylesheet" href="colorbox/example3/colorbox.css">
    <script src="colorbox/jquery.colorbox-min.js"></script>

</head>
<body style="background: #fff;">
    <div class="navbar">
        <div class="navbar-left">
            Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
        </div>
        <div class="navbar-right">
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <div class="user-table">
        <h2>Registered Users</h2>
        <table id="usersTable" class="display">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Edit</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="pdf-button-container">
    <button class="pdf-download-button" onclick="window.location.href='generate_pdf.php'">Download Users PDF</button>
    </div>

    <div class="pdf-button-container">
    <button class="pdf-download-button" onclick="window.location.href='studentCard_pdf.php'">Download Users card PDF</button>
    </div>

    <div class="pdf-button-container">
    <button class="pdf-download-button" onclick="window.location.href='downloadExcle.php'">Download Excle File</button>
    </div>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "ajax":"get_users.php",
                "columns":[
                    { "data": "name" },
                    { "data": "email" },
                    { "data": "mobileNumber" },
                    {
                      "data": "id",
                      "render": function(data, type, row) {
                        return '<a href="editUser.php?id=' + data + '" class="edit-link">' +
                        '<img src="assets/edit.png" alt="Edit" style="width:20px; height:20px;">' +
                        '</a>';
                       }
                    }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function () {
        // Initialize Colorbox for Edit Links
        $(document).on('click', '.edit-link', function (e) {
            e.preventDefault();
            $.colorbox({
                href: $(this).attr('href'),
                iframe: true,
                width: "60%",  // reduce if too big
                height: "90%", // reduce height
                transition: "fade",
                onClosed: function () {
                    $('#usersTable').DataTable().ajax.reload();
                }
            });
        });
    });
    </script>
</body>
</html>