<?php

$json_file = 'data.json';
$csv_file = 'books.csv';

// Ielādē grāmatas no JSON faila, ja tas eksistē
if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $books = json_decode($json_data, true); // Konvertē JSON uz asociatīvo masīvu
} else {
    // Sākotnējais grāmatu saraksts
    $books = [
        1 => ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald'],
        2 => ['title' => '1984', 'author' => 'George Orwell'],
        3 => ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen']
    ];
    file_put_contents($json_file, json_encode($books, JSON_PRETTY_PRINT)); // Saglabā sākotnējo JSON failā
}

// Ielādē grāmatas no CSV faila
function loadBooksFromCsv($csv_file) {
    $books = [];
    if (file_exists($csv_file)) {
        $file = fopen($csv_file, 'r');
        while (($line = fgetcsv($file)) !== false) {
            $id = $line[0];
            $books[$id] = ['title' => $line[1], 'author' => $line[2]];
        }
        fclose($file);
    }
    return $books;
}

// Saglabā grāmatas uz CSV failu
function saveBooksToCsv($books, $csv_file) {
    $file = fopen($csv_file, 'w');
    foreach ($books as $id => $book) {
        fputcsv($file, [$id, $book['title'], $book['author']], ',', '\\');
    }
    fclose($file);
}

// Saglabā grāmatas uz JSON failu
function saveBooksToJson($books, $json_file) {
    file_put_contents($json_file, json_encode($books, JSON_PRETTY_PRINT));
}

// Funkcija grāmatu parādīšanai
function showAllBooks($books) {
    foreach ($books as $id => $book) {
        echo ("ID: {$id} // Title: " . $book["title"] . " // Author: " . $book["author"] . "\n");
    }
}

// Funkcija, lai parādītu vienu grāmatu
function showBook($books) {
    $id = readline("Enter book ID: ");
    if (isset($books[$id])) {
        displayBook($id, $books[$id]);
    } else {
        echo "Book with ID {$id} does not exist.\n";
    }
}

// Funkcija grāmatas pievienošanai
function addBook(&$books) {
    $title = readline("Enter title: ");
    $author = readline("Enter author: ");
    $id = max(array_keys($books)) + 1;
    $books[$id] = ['title' => $title, 'author' => $author];
    saveBooksToJson($books, 'data.json');
    saveBooksToCsv($books, 'books.csv');
}

// Funkcija grāmatas dzēšanai
function deleteBook(&$books) {
    $id = readline("Enter book ID you want to delete: ");
    if (isset($books[$id])) {
        unset($books[$id]);
        saveBooksToJson($books, 'data.json');
        saveBooksToCsv($books, 'books.csv');
    } else {
        echo "Book with ID {$id} does not exist.\n";
    }
}

// Funkcija grāmatas rediģēšanai
function editBook(&$books) {
    $id = readline("Enter book ID you want to edit: ");
    if (isset($books[$id])) {
        echo "Current details: \n";
        displayBook($id, $books[$id]);

        $field = readline("Which field do you want to edit? (title/author): ");
        if ($field == "title" || $field == "author") {
            $new_value = readline("Enter new " . $field . ": ");
            $books[$id][$field] = $new_value; // Rediģē grāmatas lauku
            saveBooksToJson($books, 'data.json');
            saveBooksToCsv($books, 'books.csv');
            echo "Book updated successfully!\n";
        } else {
            echo "Invalid field selected. Only 'title' or 'author' can be edited.\n";
        }
    } else {
        echo "Book with ID {$id} does not exist.\n";
    }
}

// Funkcija grāmatas parādīšanai
function displayBook($id, $book) {
    echo "ID: {$id} // Title: " . $book['title'] . " // Author: " . $book['author'] . "\n\n";
}

// Galvenā izvēlne, kuru lietotājs izvēlas vienreiz
echo "\n\nWelcome to the Library\n";
echo "2 - show all books\n";
echo "7 - show a book\n";
echo "3 - add a book\n";
echo "8 - delete a book\n";
echo "5 - edit a book\n";
echo "6 - quit\n\n";
$choice = readline();

switch ($choice) {
    case 7:
        showAllBooks($books);
        break;
    case 3:
        showBook($books);
        break;
    case 2:
        addBook($books);
        break;
    case 4:
        deleteBook($books);
        break;
    case 5:
        editBook($books);
        break;
    case 6:
        echo "Goodbye!\n";
        break;
    default:
        echo "Invalid choice\n";
}

?>
