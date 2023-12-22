-- Neel Rajivkumar Patel 8877511
-- Kushal Dharmesh Choksi 8867448
-- Meghrajsinh Chauhan 8867495
-- Gurjeet Singh 8857971
-- Rajat kumar 8870247


-- To generate a receipt for customer with ID 1
DELIMITER //
CREATE PROCEDURE GenerateCustomerReceipt(IN customer_id INT)
BEGIN
SELECT c.customer_id, c.customer_name, c.email, c.phone, c.address,
           o.order_id, o.order_date,
           b.book_id, b.book_title, b.price, o.quantity,
           (b.price * o.quantity) AS total_amount
    FROM Customers c
    INNER JOIN Orders o ON c.customer_id = o.customer_id
    INNER JOIN Books b ON o.book_id = b.book_id
    WHERE c.customer_id = customer_id
END //
DELIMITER ;

-- To get how many customers have ordered book with ID 2
DELIMITER //
CREATE PROCEDURE SpecificBookCustomers(IN book_id INT)
BEGIN
SELECT b.book_id, b.book_title, o.customer_id, c.customer_name, o.quantity
    FROM Books b
    LEFT JOIN Orders o ON b.book_id = o.book_id
    LEFT JOIN Customers c ON o.customer_id = c.customer_id
    WHERE b.book_id = book_id
END //
DELIMITER ;

-- To get a list of all books in the store
DELIMITER //
CREATE PROCEDURE ListBooksInStore()
BEGIN
    SELECT book_id, book_title, author_name, edition, isbn, publication_year, category_name, price, available_quantity, language, publisher
    FROM Books b
    INNER JOIN Authors a ON b.authors_author_id1 = a.author_id  
    INNER JOIN Categories c ON b.category_id = c.category_id
END //
DELIMITER ;