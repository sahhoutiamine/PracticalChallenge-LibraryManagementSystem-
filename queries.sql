SELECT * FROM borrowings WHERE return_date = NULL;
SELECT m.name, m.email, b.COUNT(member_id) AS number_borrowed FROM members m INNER JOIN borrowings b ON m.id = b.member_id;