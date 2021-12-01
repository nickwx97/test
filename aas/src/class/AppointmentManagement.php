<?php
session_start();
include_once "database.php";

class AppointmentManagement
{
    public $apptTable;
    public $dbObj;
    public $con;

    public function __construct()
    {
        $this->apptTable = "appointment";
        $this->dbObj = new Database();
        $this->con = $this->dbObj->connection();
    }

    public function getmonthyear()
    {
        $date = $this->con->real_escape_string($_GET['date']);
        $sql = $this->con->prepare("select * from appointment where appointment_date_time=?");
        $sql->bind_param("s", $date);
        $bookings = array();
        if ($sql->execute()) {
            $result = $sql->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bookings[] = $row['timeslot'];
                }
                $sql->close();
            }
        }
        return $bookings;
    }

    public function build_calendar($month, $year)
    {
        $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $monthName = $dateComponents['month'];
        $dayOfWeek = $dateComponents['wday'];

        $employee_id = $_GET['employee_id'];

        $dateToday = date('Y-m-d');
        
        $calendar = "<table class='table-responsive'>";
        $calendar .= "<center><h2>$monthName $year</h2>";

        $calendar .= "<a class='btn btn-xs btn-secondary' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1,
            $year)) . "&employee_id=$employee_id&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a>&nbsp;";

        $calendar .= "<a class='btn btn-xs btn-success' href='?month=" . date('m') . "&employee_id=$employee_id&year=" . date('Y') . "'> Current Month</a>&nbsp;";

        $calendar .= "<a class='btn btn-xs btn-info' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1,
            $year)) . "&employee_id=$employee_id&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a></center><br/>";

        $calendar .= "<tr>";

        foreach ($daysOfWeek as $day) {
            $calendar .= "<th class='header'>$day</th>";
        }

        $calendar .= "</tr><tr>";

        if ($dayOfWeek > 0) {
            for ($k = 0; $k < $dayOfWeek; $k++) {
                $calendar .= "<td></td>";
            }
        }

        $currentDay = 1;

        $month = str_pad($month, 2, "0", STR_PAD_LEFT);

        while ($currentDay <= $numberDays) {
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";
            }

            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

            $employee_id = $_GET['employee_id'];

            $date = "$year-$month-$currentDayRel";

            $dayname = strtolower(date("l", strtotime($date)));
            $eventnum = 0;
            $today = $date == date('Y-m-d') ? "today" : "";
            if ($date < date('Y-m-d')) {
                $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N.A.</button>";
            } else {
                $calendar .= "<td class='$today'><h4> $currentDay</h4><a href='guestBooking.php?employee_id=$employee_id&date=" . $date . "' class='btn btn-success btn-xs'>Book</a>";
            }
            {
                $calendar .= "</td>";
            }

            $currentDay++;
            $dayOfWeek++;
        }

        if ($dayOfWeek != 7) {
            $remainingDays = 7 - $dayOfWeek;
            for ($i = 0; $i < $remainingDays; $i++) {
                $calendar .= "<td></td>";
            }
        }

        $calendar .= "</tr>";
        $calendar .= "</table>";

        echo $calendar;
    }

    public function build_calendar_update($month, $year)
    {
        $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $monthName = $dateComponents['month'];
        $dayOfWeek = $dateComponents['wday'];

        $appointment_id = $_GET['appointment_id'];

        $dateToday = date('Y-m-d');

        $calendar = "<table class='table-responsive'>";
        $calendar .= "<center><h2>$monthName $year</h2>";

        $calendar .= "<a class='btn btn-xs btn-secondary' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1,
            $year)) . "&appointment_id=$appointment_id&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a>&nbsp;";

        $calendar .= "<a class='btn btn-xs btn-success' href='?month=" . date('m') . "&appointment_id=$appointment_id&year=" . date('Y') . "'> Current Month</a>&nbsp;";

        $calendar .= "<a class='btn btn-xs btn-info' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1,
            $year)) . "&appointment_id=$appointment_id&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a></center><br/>";

        $calendar .= "<tr>";

        foreach ($daysOfWeek as $day) {
            $calendar .= "<th class='header'>$day</th>";
        }

        $calendar .= "</tr><tr>";

        if ($dayOfWeek > 0) {
            for ($k = 0; $k < $dayOfWeek; $k++) {
                $calendar .= "<td></td>";
            }
        }

        $currentDay = 1;

        $month = str_pad($month, 2, "0", STR_PAD_LEFT);

        while ($currentDay <= $numberDays) {
            if ($dayOfWeek === 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";
            }

            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

            $appointment_id = $_GET['appointment_id'];

            $date = "$year-$month-$currentDayRel";

            $dayname = strtolower(date("l", strtotime($date)));
            $eventnum = 0;
            $today = $date == date('Y-m-d') ? "today" : "";
            if ($date < date('Y-m-d')) {
                $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N.A.</button>";
            } else {
                $calendar .= "<td class='$today'><h4> $currentDay</h4><a href='guestBookingUpdate.php?appointment_id=$appointment_id&date=" . $date . "' class='btn btn-success btn-xs'>Book</a>";
            }
            {
                $calendar .= "</td>";
            }

            $currentDay++;
            $dayOfWeek++;
        }

        if ($dayOfWeek != 7) {
            $remainingDays = 7 - $dayOfWeek;
            for ($i = 0; $i < $remainingDays; $i++) {
                $calendar .= "<td></td>";
            }
        }

        $calendar .= "</tr>";
        $calendar .= "</table>";

        echo $calendar;
    }

    public function timeslots($duration, $cleanup, $start, $end)
    {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = new DateInterval("PT" . $duration . "M");
        $cleanupInterval = new DateInterval("PT" . $cleanup . "M");
        $slots = array();

        for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupInterval)) {
            $endPeriod = clone $intStart;
            $endPeriod->add($interval);
            if ($endPeriod > $end) {
                break;
            }
            $slots[] = $intStart->format("H:iA") . " - " . $endPeriod->format("H:iA");
        }
        return $slots;
    }

    // Insert Appointment
    public function insertApptData($post)
    {
        $employee_id = $this->con->real_escape_string($_GET['employee_id']);
        $user_id = $this->con->real_escape_string($_POST['user_id']);
        $venue = $this->con->real_escape_string($_POST['venue']);
        $date = $this->con->real_escape_string($_GET['date']);
        $timeslot = $this->con->real_escape_string($_POST['timeslot']);

        $sql = $this->con->prepare("INSERT INTO appointment (appointment_date_time, venue, timeslot, user_id, employee_id)
			VALUES(?,?,?,?,?)");
        $sql->bind_param("sssii", $date, $venue, $timeslot, $user_id, $employee_id);
        $sql->execute();
        $result = $sql->get_result();
        $bookings[] = $timeslot;

        if ($sql == true) {
            return true;
        } else {
            return false;
        }
        $sql->close();

    }

    // Update Appointment
    public function updateApptRecord($postData)
    {

        $appointment_id = $this->con->real_escape_string($_GET['appointment_id']);
        $date = $this->con->real_escape_string($_GET['date']);

        $timeslot = $this->con->real_escape_string($_POST['timeslot']);

        if (!empty($appointment_id) && !empty($postData)) {
            $sql = $this->con->prepare("UPDATE appointment SET appointment_date_time=?, timeslot=? WHERE appointment_id=?");
            $sql->bind_param("ssi", $date, $timeslot, $appointment_id);
            $sql->execute();
            $result = $sql->get_result();
            $bookings[] = $timeslot;
            
            if ($sql == true) {
                return true;
            } else {
                return false;
            }
            $sql->close();

        }
    }

    // Delete Appointment
    public function deleteApptRecord($appointment_id)
    {
        $sql = $this->con->prepare("DELETE FROM appointment WHERE appointment_id=?");
        $sql->bind_param("i", $appointment_id);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();

        if ($sql == true) {
            header("Location:myBooking.php?msg3=delete");
        } else {
            echo "Appointment delete failed PLEASE try again!";
        }
        $sql->close();
    }
}

$apptManagementObj = new AppointmentManagement();