<?php


function Word_Token($String) {
    $String = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $String));
    $words = preg_split('/\s+/', $String, -1, PREG_SPLIT_NO_EMPTY);
    return $words;
}

function WordFreq($words) {
    $stopWords = array("a", "an", "and", "are", "as", "at", "be", "but", "by", "for", "if", "in", "into", "is", "it", "no", "not", "of", "on", "or", "such", "that", "the", "their", "then", "there", "these", "they", "this", "to", "was", "will", "with");
    $wordFrequency = array();
    foreach ($words as $word) {
        if (!in_array($word, $stopWords)) {
            if (isset($wordFrequency[$word])) {
                $wordFrequency[$word]++;
            } else {
                $wordFrequency[$word] = 1;
            }
        }
    }
    arsort($wordFrequency);
    return $wordFrequency;
}

function displayWordFrequency($wordFrequency, $sortBy, $limit) {
    $count = 0;
    foreach ($wordFrequency as $word => $frequency) {
        if ($count >= $limit) {
            break;
        }
        echo "$word: $frequency\n";
        $count++;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $String = isset($_POST['text']) ? $_POST['text'] : '';
    $sortBy = isset($_POST['sort_by']) ? $_POST['sort_by'] : 'desc';
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;

    if (!empty($String)) {
        $words = Word_Token($String);
        $wordFrequency = WordFreq($words);
        if ($sortBy === 'asc') {
            ksort($wordFrequency);
        }
        displayWordFrequency($wordFrequency, $sortBy, $limit);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Frequency Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        textarea, select, input[type="number"], input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>Word Frequency Calculator</h1>
    <form method="post">
        <label for="text">Enter text/paragraph:</label><br>
        <textarea id="text" name="text" rows="6" cols="50"><?php echo isset($_POST['text']) ? htmlspecialchars($_POST['text']) : ''; ?></textarea><br>
        <label for="sort_by">Sort by:</label>
        <select id="sort_by" name="sort_by">
            <option value="desc" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'desc') echo 'selected'; ?>>Descending</option>
            <option value="asc" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'asc') echo 'selected'; ?>>Ascending</option>
        </select><br>
        <label for="limit">Number of words to display:</label>
        <input type="number" id="limit" name="limit" min="1" value="<?php echo isset($_POST['limit']) ? $_POST['limit'] : '10'; ?>"><br>
        <input type="submit" value="Calculate">
    </form>
</body>
</html>
