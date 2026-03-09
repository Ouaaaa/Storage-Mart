<?php
// BUG-10 fix: Extend BaseModel for consistent DB access instead of
// requiring config.php directly with a brittle relative path.
require_once __DIR__ . '/../admin/BaseModel.php';

class TicketRatingModel extends BaseModel
{
    public function hasRated(int $ticketId, int $employeeId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM ticket_ratings
            WHERE ticket_id = ? AND employee_id = ?
        ");
        $stmt->execute([$ticketId, $employeeId]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(int $ticketId, int $employeeId, int $itId, int $rating, string $comment = ''): void
    {
        // BUG-20 fix: validate rating range 1–5
        $rating = max(1, min(5, $rating));

        $stmt = $this->pdo->prepare("
            INSERT INTO ticket_ratings
                (ticket_id, employee_id, it_id, rating, comment, created_at)
            VALUES
                (?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $ticketId,
            $employeeId,
            $itId,
            $rating,
            trim($comment)
        ]);
    }
}
