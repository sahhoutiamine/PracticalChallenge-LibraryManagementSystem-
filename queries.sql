SELECT * FROM borrowings WHERE return_date IS NULL;
SELECT m.name, m.email, COUNT(b.member_id) AS number_borrowed FROM members m INNER JOIN borrowings b ON m.id = b.member_id AND number_borrowed > 3;
SELECT b.title COUNT(br.*) AS most_borrowed FROM books b INNER JOIN borrowings br ON b.id = br.book_id  ORDER BY  most_borrowed DESC LIMIT 1;
