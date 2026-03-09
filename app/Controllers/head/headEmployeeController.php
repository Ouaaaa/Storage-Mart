<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../../Models/employee/Employee.php';
require_once __DIR__ . '/../../Models/employee/Ticket.php';
require_once __DIR__ . '/../../Helpers/Session.php';

class HeadEmployeeController extends AuthController
{
    public function tickets()
    {
        // Discard any stray output (PHP notices/warnings) buffered before this point
        if (ob_get_level()) ob_end_clean();
        ob_start();

        header('Content-Type: application/json');

        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (empty($_SESSION['account_id'])) {
                ob_end_clean();
                http_response_code(401);
                echo json_encode(['data' => []]);
                return;
            }

            $employeeId = (int)($_GET['employee_id'] ?? 0);
            if ($employeeId <= 0) {
                ob_end_clean();
                echo json_encode(['data' => []]);
                return;
            }

            $ticketModel = new EmployeeTicket();
            $rows = $ticketModel->fetchAllTicketsByEmployee($employeeId);

            ob_end_clean();
            echo json_encode(['data' => $rows]);

        } catch (Throwable $e) {
            ob_end_clean();
            http_response_code(500);
            echo json_encode([
                'data'  => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function assets()
    {
        // Discard any stray output (PHP notices/warnings) buffered before this point
        if (ob_get_level()) ob_end_clean();
        ob_start();

        header('Content-Type: application/json');

        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (empty($_SESSION['account_id'])) {
                ob_end_clean();
                http_response_code(401);
                echo json_encode(['data' => []]);
                return;
            }

            $employeeId = (int)($_GET['employee_id'] ?? 0);
            if ($employeeId <= 0) {
                ob_end_clean();
                echo json_encode(['data' => []]);
                return;
            }

            $employeeModel = new Employee();
            $rows = $employeeModel->fetchAssetsByEmployeeId($employeeId);

            ob_end_clean();
            echo json_encode(['data' => $rows]);

        } catch (Throwable $e) {
            ob_end_clean();
            http_response_code(500);
            echo json_encode([
                'data'  => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function assetTickets()
    {
        // Discard any stray output (PHP notices/warnings) buffered before this point
        if (ob_get_level()) ob_end_clean();
        ob_start();

        header('Content-Type: application/json');

        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (empty($_SESSION['account_id'])) {
                ob_end_clean();
                http_response_code(401);
                echo json_encode(['data' => []]);
                return;
            }

            $inventoryId = (int)($_GET['inventory_id'] ?? 0);
            if ($inventoryId <= 0) {
                ob_end_clean();
                echo json_encode(['data' => []]);
                return;
            }

            $employeeModel = new Employee();
            $rows = $employeeModel->fetchTicketsByAsset($inventoryId);

            ob_end_clean();
            echo json_encode(['data' => $rows]);

        } catch (Throwable $e) {
            ob_end_clean();
            http_response_code(500);
            echo json_encode([
                'data'  => [],
                'error' => $e->getMessage()
            ]);
        }
    }


}