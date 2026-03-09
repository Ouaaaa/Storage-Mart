<?php
// BUG-18 fix: headModel was empty. Provide explicit delegation to Employee model.
require_once __DIR__ . '/../admin/BaseModel.php';
require_once __DIR__ . '/../employee/Employee.php';

/**
 * HeadModel
 * Currently delegates to Employee since HEADs share the same tblemployee table.
 * Add HEAD-specific methods (e.g., department-wide queries) here.
 */
class HeadModel extends Employee
{
    // Inherits all Employee methods.
    // Add HEAD-specific methods below as needed.
}
