<?php
    session_start();
    require_once '../database/Database.php';
    require_once '../models/AvailableDate.php';

    $db = new Database();
    $conn = $db->getConnection();
    AvailableDate::setConnection($conn);

    if (!(isset($_SESSION['role']) && ($_SESSION['role'] === 'superadmin' || $_SESSION['role'] === 'admin'))) {
        include '../layout/header.php';
        echo "<script>
            Swal.fire({
                title: 'Warning!',
                text: 'You cannot access this page.',
                icon: 'warning',
                showConfirmButton: true,
            }).then(function() {
                window.location.href = '../index.php';
            });
        </script>";
        exit();
    }

    if (!isset($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $id = $_GET['id'];
    $availableDate = AvailableDate::find($id);
    if (!$availableDate) {
        header('Location: index.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['available_date']) || empty($_POST['available_date'])) {
            $_SESSION['date_error'] = 'Date is required.';
            header('Location: edit.php?id=' . $availableDate->id);
            exit();
        }

        $check_date = AvailableDate::where('date', '=', $_POST['available_date']);
        if (count($check_date) > 0) {
            $_SESSION['date_error'] = 'Date already exists';
            header('Location: edit.php?id=' . $availableDate->id);
            exit();
        }

        $hasAppointment = AvailableDate::hasAppointments($availableDate->id);
        if ($hasAppointment) {
            include '../layout/header.php';
            echo "<script>
                Swal.fire({
                    title: 'Warning!',
                    text: 'You cannot update this date because it has an appointment.',
                    icon: 'warning',
                    showConfirmButton: true,
                }).then(function() {
                    window.location.href = 'index.php';
                });
            </script>";
            exit();
        }

        $data = [
            'date' => $_POST['available_date'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $updateAvailableDate = $availableDate->update($data);

        include '../layout/header.php';

        if ($updateAvailableDate) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Available date has been updated',
                    icon: 'success',
                    showConfirmButton: true,
                }).then(function() {
                    window.location.href = 'index.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update available date',
                    icon: 'error',
                    showConfirmButton: true,
                }).then(function() {
                    window.location.href = 'edit.php?id=$id';
                });
            </script>";
        }

    } else {
        include '../layout/header.php';
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Invalid request method',
                icon: 'error',
                showConfirmButton: true,
            }).then(function() {
                window.location.href = 'edit.php?id=$id';
            });
        </script>";
    }
    include '../layout/footer.php';
?>