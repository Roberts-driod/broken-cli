<?php

$json_file = 'data.json';


if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $books = json_decode($json_data, true); // Konvertē JSON uz asociatīvo masīvu
} else {
    $books = [
        1 => [
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald'
        ],
        2 => [
            'title' => '1984',
            'author' => 'George Orwell'
        ],
        3 => [
            'title' => 'Pride and Prejudice',
            'author' => 'Jane Austen'
        ]
    ];
    // Saglabā sākotnējo grāmatu sarakstu JSON failā
    file_put_contents($json_file, json_encode($books, JSON_PRETTY_PRINT));
}


function saveBooksToJson($books) {
    global $json_file;
    // Saglabā grāmatu datus JSON formātā
    file_put_contents($json_file, json_encode($books, JSON_PRETTY_PRINT));
}


function showAllBooks($books) {
    foreach ($books as  $book) {
        echo ("title: ".  $book["title"] . " author: " . $book["author"] . "\n");
    }
}

function showBook($books) {
    $id = readline("Enter book id: ");
    displayBook($id, $books[$id]);
}

function addBook(&$books) {
    $title = readline("Enter title: ");
    $author = readline("Enter author: ");
    $id = max(array_keys($books)) + 1;
    $books[$id] = ['title' => $title, 'author' => $author];
    saveBooksToJson($books); 
}

function deleteBook(&$books) {
    $id = readline("Enter book ID you want to delete: ");
    if (isset($books[$id])) {
        unset($books[$id]);
        saveBooksToJson($books); // Saglabā izmaiņas JSON failā
    } else {
        echo "Book with ID {$id} does not exist.\n";
    }
}

function editBook(&$books) {
    $id = readline("Enter book ID you want to edit: ");

        if (isset($books[$id])) {
        echo "Current details: \n";
        displayBook($id, $books[$id]);

        $field = readline("Which field do you want to edit? (title/author): ");

        if ($field == "title" || $field == "author") {
            $new_value = readline("Enter new " . $field . ": ");
            $books[$id][$field] = $new_value; // Rediģē grāmatas lauku
            saveBooksToJson($books); // Saglabā izmaiņas JSON failā
            echo "Book updated successfully!\n";
        } else {
            echo "Invalid field selected. Only 'title' or 'author' can be edited.\n";
        }
    } else {
        echo "Book with ID {$id} does not exist.\n";
    }
}


function displayBook($id, $book) {
    echo "ID: {$id} // Title: ". $book['title'] . " // Author: " . $book['author']. "\n\n";
}


echo "\n\nWelcome to the Library\n";
$continue = true; 
do {
    echo "\n\n";
    echo "1 - show all books\n";
    echo "2 - show a book\n";
    echo "3 - add a book\n";
    echo "4 - delete a book\n";
    echo "6 - EDIT a book\n";
    echo "5 - quit\n\n";
    $choice = readline();

    switch ($choice) {
        case 1:
            showAllBooks($books);
            break;
        case 2:
            showBook($books);
            break;
        case 3:
            addBook($books);
            break;
        case 4:
            deleteBook($books);
            break;
        case 6:
            echo "Goodbye!\n";
            $continue = false;
            break;
        case 5:
            editBook($books);
            break;
        default:
            echo "Invalid choice\n";
    };

} while ($continue == true);
