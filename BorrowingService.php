<?php

class BorrowingService
{
    private PDO $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    public function borrowBook(int $bookId, int $memberId, string $borrowDate): array
    {
        try {
            $db->beginTransaction();




            $stmt = prepare("SELECT * FROM books WHERE id = ? AND available_copies > 0 ;");
            $book = $stmt->execute([$bookId]);
            $book->fetch();


            if ($book) {

                $stmt = prepare("SELECT member_type FROM member WHERE member_id = ?;");
                $typ = $stmt->execute([$memberId]);
                $typ->fetch();
                if ($typ === "student") {
                    getLimits(3);
                }
                else {
                    getLimits(10);

                }

                
            }
            else {
                throw new Error("book is not avaible!");
            }

            $stmt = query("UPDATE books SET available_copies = available_copies-1;");
            
            $db->commit();

        }
        catch (Exception $e){
            $db->rollBack();
        }

    }
    
    public function returnBook(int $borrowingId, string $returnDate): float
    {
        
    }
    
    public function getStatistics(): array
    {
        try {
            $db->beginTransaction();
            
            $borrow = query("SELECT COUNT(*) FROM borrowings;");
            $borrow->fetch();
            $borrow = query("SELECT * FROM borrowings WHERE return_date IS NULL;");
            $borrow->fetchAll();
            $borrow = query("SELECT SUM(late_fee) FROM borrowings;");
            $borrow->fetch();

            $db->commit();

        }
        catch (Exception $e){
            $db->rollBack();
        }
    }
    
    public function findOverdueBorrowings(string $currentDate): array
    {
        $borrow = query("SELECT * FROM borrowings WHERE due_date < now() AND return_date IS NULL;");
        return $borrow->fetchAll();
        
    }
    
    // You may add private helper methods if needed}

    
    private static function getLimits($limit){

        $stmt = prepare("SELECT COUNT(member_id) FROM borrowings WHERE member_id = ?;");
                $borrowCount = $stmt->execute([$memberId]);
                $borrowCount->fetchColumn();


                if ($borrowCount < $limit) {
                    $stmt = prepare("INSERT INTO borrowings(book_id, member_id, borrow_date) VALUES (?, ?, ?); ");
                    $stmt->execute([$book_id, $member_id, $borrow_date]);
                }
                else {
                    throw new Error("limit borrow!");
                }
    }

}