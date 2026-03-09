<?php
// BUG-18 fix: headTicketModel was empty. Explicitly delegate to EmployeeTicket
// so head-specific logic has a clear home and can diverge cleanly if needed.
require_once __DIR__ . '/../admin/BaseModel.php';
require_once __DIR__ . '/../employee/Ticket.php';

/**
 * HeadTicketModel
 * Currently delegates all ticket operations to EmployeeTicket since HEADs
 * share the same ticket tables as employees. Add head-specific overrides here.
 */
class HeadTicketModel extends EmployeeTicket
{
    // Inherits all EmployeeTicket methods.
    // Add HEAD-specific overrides below as needed.
}
