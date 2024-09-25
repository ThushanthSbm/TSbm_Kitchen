<?php
// Database connection using PDO
$host = "localhost";
$dbname = "tsbmkitchen_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Fetch form data
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $person = $_POST['person'];
        $reservation_date = $_POST['reservation_date'];  // Note: Changed to underscore (_) to match your form name
        $time = $_POST['time'];
        $message = $_POST['message'];

        // Check if all fields are filled
        if (!empty($name) && !empty($phone) && !empty($person) && !empty($reservation_date) && !empty($time) && !empty($message)) {
            // Prepare SQL statement to insert data into the database
            $sql = "INSERT INTO reservations (name, phone, person, reservation_date, time, message) 
                    VALUES (:name, :phone, :person, :reservation_date, :time, :message)";
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':person', $person);
            $stmt->bindParam(':reservation_date', $reservation_date);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':message', $message);

            // Execute and check if successful
            if ($stmt->execute()) {
                // SweetAlert and page redirection
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Reservation created successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'http://localhost/Modern-Restaurant-UI/';
                        }
                    });
                </script>
                ";
            } else {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to create reservation.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>
                ";
            }
        } else {
            // Alert for missing fields
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'All fields are required.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>
            ";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
